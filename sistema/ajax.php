<?php

include_once("../conexion.php");
session_start();


      
if(!empty($_POST)){
    //extraer datos del producto
    
    if($_POST['action'] == 'infoProducto'){
  
       $producto_id = $_POST['producto'];
       $query="SELECT codproducto, descripcion, existencia, precio FROM producto WHERE codproducto = $producto_id AND status = 1 ";
       $querySend = mysqli_query($conection,$query);
       mysqli_close($conection);
       $result = mysqli_num_rows($querySend);
        if($result>0){
            $data= mysqli_fetch_assoc($querySend);
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'Error';
        }


     //extraer producto a entrada
    if($_POST['action'] == 'addProduct'){

        if(!empty($_POST['cantidad']) || !empty($_POST['precio']) || !empty($_POST['producto_id']) || !empty($_POST['producto_id'])){

            $cantidad= $_POST['cantidad'];
            $precio = $_POST['precio'];
            $producto_id =$_POST['producto_id'];
            $usuario_id = $_SESSION['s_iduser'];

            $queryInsert=" INSERT INTO entradas (codproducto, cantidad, precio, usuario_id) 
            VALUES ($producto_id,$cantidad,$precio, $usuario_id)";
            $querySendInsert = mysqli_query($conection,$queryInsert);
            if($querySendInsert){
                //ejecutar Procedimiento Almacenado
                $queryUpd = mysqli_query($conection,"CALL actualizar_precio_producto($cantidad,$precio,$producto_id)");
                $resultPro = mysqli_num_rows($queryUpd);
                if($resultPro>0){
                    $data = mysqli_fetch_assoc($queryUpd);
                    $data['producto_id']=$producto_id;
                    echo json_encode($data,JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }else{
                echo 'error';
            }
                     
          
        } else{

            echo 'error';
        }

        exit;   
    }


    //Eliminar producto
    if($_POST['action'] == 'delProduct'){
       
        if(empty($_POST['producto_id']) || !is_numeric($_POST['producto_id'])){
            echo 'error';
            }else{
             
            $idproducto = $_POST['producto_id'];
            $queryDelete = "UPDATE producto SET status = 0 WHERE codproducto = $idproducto ";
            $queryDeleteSend = mysqli_query($conection,$queryDelete);
            mysqli_close($conection);
        
            if($queryDeleteSend){
                echo 'ok';
            }else{
        
             echo "error";
            }

            echo "error"; 
            exit;
        }
    
    }


    //Buscar Cliente
    if($_POST['action']=='searchCliente'){
  
        if(!empty($_POST['cliente'])){
            $rut= $_POST['cliente'];
            
            $querySerCli = "SELECT * FROM cliente WHERE rut LIKE '$rut' AND status =1";
            $querySerCliSend = mysqli_query($conection,$querySerCli);
            mysqli_close($conection);
            $result = mysqli_num_rows($querySerCliSend);

            $data='';
            if($result>0){
                $data = mysqli_fetch_assoc($querySerCliSend);
            }else{
                $data=0;
            }
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    //Registrar Cliente - VENTA
    if($_POST['action']=='addCliente'){


        $rut = $_POST['rut_cliente'];
        $nombre = $_POST['nombre_cliente'];
        $telefono = $_POST['fono_cliente'];
        $direccion = $_POST['dir_cliente'];
        $usuario_id = $_SESSION['s_iduser'];

        $queryCreCl ="INSERT INTO cliente (rut, nombre, telefono, direccion,usuario_id)
                      VALUES ('$rut','$nombre',$telefono,'$direccion',$usuario_id) ";
        $queryCreClSend = mysqli_query($conection,$queryCreCl);
        
        if($queryCreClSend){
            $codCliente = mysqli_insert_id($conection);
            $msg = $codCliente;
        } else {
            
            $msg = 'error';
        }
        mysqli_close($queryCreClSend);
        echo $msg;
        exit;

    }

    //Agregar Productos a la Venta - Venta
    if($_POST['action'] == 'addProductoDetalle'){
        if(empty($_POST['producto']) || empty($_POST['cantidad']) || !is_numeric($_POST['producto']) ){
            echo 'error';
        }else{
            
            $codproducto = $_POST['producto'];
            $cantidad = $_POST['cantidad'];
            $token =  md5($_SESSION['s_iduser']);    


            $queryIva = "SELECT iva FROM configuracion";
            $queryIvaSend = mysqli_query($conection,$queryIva);
         
            $resultIva = mysqli_num_rows($queryIvaSend);
            $queryInsTem ="CALL add_detalle_temp($codproducto,$cantidad,'$token')";
            $queryInsTemSend = mysqli_query($conection,$queryInsTem);   
            $result = mysqli_num_rows($queryInsTemSend);

            $detalleTabla = '';
            $subTotal = 0;
            $iva = 0;
            $total = 0;
            $arrayData = array();

            if($result > 0){
                if($resultIva > 0){

                $infoIva = mysqli_fetch_assoc($queryIvaSend);
                $iva = $infoIva['iva'];    
                }
                while ($data = mysqli_fetch_assoc($queryInsTemSend)){
                    $precioTotal = ($data['cantidad'] * $data['precio_venta']);
                    $subTotal = ($subTotal + $precioTotal);
                    $total = ($total + $precioTotal);
 
                    $detalleTabla .= '
                                <tr>
                                    <td>'.$data['codproducto'] .'</td>
                                    <td colspan="2">'.$data['descripcion'].'</td>
                                    <td class="textcenter">'.$data['cantidad'].'</td>
                                    <td class="textright">'.$data['precio_venta'].'</td>
                                    <td class="textright">'.$precioTotal.'</td>
                                    <td class="">
                                        <a class="link_delete" href="#" onclick="event.preventDefault(); 
                                        delProductDetalle('.$data['correlativo'].');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr> 
                                    ';
                }

                $impuesto = round(($subTotal * ($iva)/100),0);
                $tSinIva =  ($subTotal - $impuesto);
                $total = ($tSinIva + $impuesto);

                $detalleTotales = '
                                    <tr>
                                        <td colspan="5" class="textright"> SUBTOTAL $. </td>
                                        <td class="textright"> '.$tSinIva.' </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="textright"> IVA ('.$iva.'%) </td>
                                        <td class="textright"> '.$impuesto.' </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="textright"> TOTAL $. </td>
                                        <td class="textright"> '.$total.' </td>
                                    </tr>
                                ';

                $arrayData['detalle']=$detalleTabla;
                $arrayData['totales']=$detalleTotales;

                echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
            }else{

            echo'error';
            }
            mysqli_close($conection);
        }
        exit;
    }

    // funcion Search For Detalle
    if($_POST['action'] == 'searchForDetalle' )
    {
       
        if(empty($_POST['user']))
        {
            echo 'error';
        }
        else
        {

            $token =  md5($_SESSION['s_iduser']); 
            $query = "SELECT tmp.correlativo, tmp.token_user, tmp.cantidad, tmp.precio_venta, p.codproducto, p.descripcion
            FROM detalle_temp tmp
            INNER JOIN producto p
            ON tmp.codproducto = p.codproducto
            WHERE token_user = '$token' ";
            $querySend = mysqli_query($conection,$query);
            $result = mysqli_num_rows($querySend);

            $queryIva = "SELECT iva FROM configuracion";
            $queryIvaSend = mysqli_query($conection,$queryIva);
            $resultIva = mysqli_num_rows($queryIvaSend);
         
            $detalleTabla = '';
            $subTotal = 0;
            $iva = 0;
            $total = 0;
            $arrayData = array();

            if($result > 0)
            {
                //si hay datos 
                if($resultIva > 0)
                {
                $infoIva = mysqli_fetch_assoc($queryIvaSend);
                $iva = $infoIva['iva'];    
                while ($data = mysqli_fetch_assoc($querySend))
                    {
                    $precioTotal = ($data['cantidad'] * $data['precio_venta']);
                    $subTotal = ($subTotal + $precioTotal);
                    $total = ($total + $precioTotal);
 
                    $detalleTabla .= '
                                <tr>
                                    <td>'.$data['codproducto'] .'</td>
                                    <td colspan="2">'.$data['descripcion'].'</td>
                                    <td class="textcenter">'.$data['cantidad'].'</td>
                                    <td class="textright">'.$data['precio_venta'].'</td>
                                    <td class="textright">'.$precioTotal.'</td>
                                    <td class="">
                                        <a class="link_delete" href="#" onclick="event.preventDefault(); 
                                        delProductDetalle('.$data['correlativo'].');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr> 
                                    ';
                    }

                        $impuesto = round(($subTotal * ($iva)/100),0);
                        $tSinIva =  ($subTotal - $impuesto);
                        $total = ($tSinIva + $impuesto);

                        $detalleTotales = '
                                    <tr>
                                        <td colspan="5" class="textright"> SUBTOTAL $. </td>
                                        <td class="textright"> '.$tSinIva.' </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="textright"> IVA ('.$iva.'%) </td>
                                        <td class="textright"> '.$impuesto.' </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="textright"> TOTAL $. </td>
                                        <td class="textright"> '.$total.' </td>
                                    </tr>
                                ';

                        $arrayData['detalle']=$detalleTabla;
                        $arrayData['totales']=$detalleTotales;

                        echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);  
                }
            }

            else 
            {
                echo "lista_vacia";   
            }
        }
    }
    //funcion elimina prod de la lista de compra 

    if($_POST['action'] == 'delProductoDetalle')
    {
        if(empty($_POST['id_detalle']))
        {
            echo 'error';
        }
        else
        {
            $id_detalle = $_POST['id_detalle']; 
            $token =  md5($_SESSION['s_iduser']); 

            $queryIva = "SELECT iva FROM configuracion";
            $queryIvaSend = mysqli_query($conection,$queryIva);
            $resultIva = mysqli_num_rows($queryIvaSend);


            $queryDetalleTemp = "CALL del_detalle_temp($id_detalle,'$token')";
            $queryDetalleTempSend =  mysqli_query($conection,$queryDetalleTemp);
            $result = mysqli_num_rows($queryDetalleTempSend);

         
            $detalleTabla = '';
            $subTotal = 0;
            $iva = 0;
            $total = 0;
            $arrayData = array();

            if($result > 0)
            {
                if($resultIva > 0)
                {
                $infoIva = mysqli_fetch_assoc($queryIvaSend);
                $iva = $infoIva['iva'];    
                }
                while ($data = mysqli_fetch_assoc($queryDetalleTempSend))
                    {
                          
                    $precioTotal = ($data['cantidad'] * $data['precio_venta']);
                    $subTotal = ($subTotal + $precioTotal);
                    $total = ($total + $precioTotal);
 
                    $detalleTabla .= '
                                <tr>
                                    <td>'.$data['codproducto'] .'</td>
                                    <td colspan="2">'.$data['descripcion'].'</td>
                                    <td class="textcenter">'.$data['cantidad'].'</td>
                                    <td class="textright">'.$data['precio_venta'].'</td>
                                    <td class="textright">'.$precioTotal.'</td>
                                    <td class="">
                                        <a class="link_delete" href="#" onclick="event.preventDefault(); 
                                        delProductDetalle('.$data['correlativo'].');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr> 
                                    ';
                    }

                $impuesto = round(($subTotal * ($iva)/100),0);
                $tSinIva =  ($subTotal - $impuesto);
                $total = ($tSinIva + $impuesto);

                $detalleTotales = '
                                    <tr>
                                        <td colspan="5" class="textright"> SUBTOTAL $. </td>
                                        <td class="textright"> '.$tSinIva.' </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="textright"> IVA ('.$iva.'%) </td>
                                        <td class="textright"> '.$impuesto.' </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="textright"> TOTAL $. </td>
                                        <td class="textright"> '.$total.' </td>
                                    </tr>
                                ';

                $arrayData['detalle']=$detalleTabla;
                $arrayData['totales']=$detalleTotales;

                echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
            }else{

                echo "lista_vacia";
            }

        
        }

    }

    //Anular Venta
    if($_POST['action'] == 'anularVenta')
    {
        $token =  md5($_SESSION['s_iduser']); 
        $queryDel = "DELETE FROM detalle_temp WHERE token_user = '$token' ";
        $queryDelSend = mysqli_query($conection,$queryDel);
        mysqli_close($conection);
        if($queryDelSend)
        {
            echo 'ok';
        }else
        {
            echo 'error';
        }
    }
 
    //Procesar Venta
    if($_POST['action'] == 'procesarVenta')
    {    
        if(empty($_POST['codcliente']))
        {
            echo 'error';
        }
        else
        {

            $codcliente = $_POST['codcliente'];
            $token =  md5($_SESSION['s_iduser']);  
            $usuario_id = $_SESSION['s_iduser'];
            

            $query = "SELECT * FROM detalle_temp WHERE token_user = '$token' ";
            $querySend = mysqli_query($conection,$query);
            $result = mysqli_num_rows($querySend);

            if($result > 0)
            {
                $queryDetalle = "CALL procesar_venta($usuario_id,$codcliente,'$token')";
                $queryDetalleSend = mysqli_query($conection,$queryDetalle);    
                $resultDetalle = mysqli_num_rows($queryDetalleSend);
                if($resultDetalle > 0)
                {
                    $data = mysqli_fetch_assoc($queryDetalleSend);
                    echo json_encode($data,JSON_UNESCAPED_UNICODE);
                }
                else
                {
                    echo "error";
                }
            }
    
        mysqli_close($conection);
        exit;         
        }
    }

    //Modal Eliminar Factura
    if($_POST['action'] == 'infoFactura')
    {
        if(!empty($_POST['nofactura']))
        {
            $nofactura = $_POST['nofactura'];
            $query = "SELECT * FROM factura WHERE nofactura = '$nofactura' AND estatus = 1 ";
            $querySend = mysqli_query($conection, $query);
            mysqli_close($conection);

            $result = mysqli_num_rows($querySend);

            if($result > 0)
            {
                $data = mysqli_fetch_assoc($querySend); 
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }

        echo "error";
        exit;
    }

    // Conuslta Eliina Factura
    if($_POST['action'] == 'anularFactura')
    {
        if(!empty($_POST['nofactura']))
        {
            $noFactura = $_POST['nofactura'];
            $query = "CALL anular_factura($noFactura)";
            $querySend = mysqli_query($conection,$query);
            mysqli_close($conection);
            $result = mysqli_num_rows($querySend);
            if($result > 0)
            {
                $data = mysqli_fetch_assoc($querySend);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        echo "error";
        exit;
    }
}
 
           


