<?php
session_start();
if($_SESSION['s_rol'] !=1 and $_SESSION['s_rol'] !=2){
    header("location: ../");
}

include_once("../conexion.php");

if(!empty($_POST)){


    $alert="";
    if(empty($_POST['proveedor']) ||  empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['id']) || empty($_POST['foto_actual']) || empty($_POST['foto_remove']) ){
       
        $alert='<p class="msg_error"> Todos los campos son Requeridos </p>'  ;
    }
    else{
        $codproducto = $_POST['id'];
        $proveedor = $_POST['proveedor'];
        $producto = $_POST['producto'];
        $precio = $_POST['precio'];
        $imgProducto= $_POST['foto_actual'];
        $imgRemove= $_POST['foto_remove'];
      //  $usuario_id = $_SESSION['s_iduser'];

        $foto = $_FILES['foto'];
        $nombreFoto = $foto['name'];
        $nombreType = $foto['type'];
        $FotoURLTemp = $foto['tmp_name'];

        $upd ='';

        //$imgProducto = 'imgProducto.png';

        if ($nombreFoto != '')
        {
            $destino = 'img/uploads/';
            $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
            $imgProducto = $img_nombre.'.jpg'; 
            $src = $destino.$imgProducto;
      
        }else{

            if($_POST['foto_actual'] != $_POST['foto_remove']){
                $imgProducto = 'imgProducto.png';
            }

        }
        

            $queryUpdate="UPDATE producto SET
                        descripcion = '$producto',
                        proveedor = $proveedor,
                        precio = $precio,
                        foto = '$imgProducto'
                     WHERE codproducto = $codproducto AND status = 1";   
               
           $queryUpdateSend=mysqli_query($conection,$queryUpdate);
           
            if($queryUpdateSend){
                 if(($nombreFoto != '' &&  $_POST['foto_actual']) != 'imgProducto.png' || ($_POST['foto_actual'] != $_POST['foto_remove'])){

                    unlink('img/uploads/'.$_POST['foto_actual']);
                 }   

                if ($nombreFoto != '' ){
                    move_uploaded_file($FotoURLTemp,$src);
                }

                $alert='<p class="msg_save"> Producto Actualizado correctamente </p>'  ;
            } else {

                $alert='<p class="msg_error"> Error al Actualizar el Producto </p>'  ;
            }
        }
 
      
}

//validar Producto
if(empty($_REQUEST['id'])){
    header("location: listaProducto.php");   
}else{
        $id_producto = $_REQUEST['id'];
        if(!is_numeric($id_producto)){
            header("location: listaProducto.php");   
        }

        $queryProducto ="SELECT codproducto, descripcion, precio, foto, codproveedor, proveedor.proveedor FROM producto 
        INNER JOIN proveedor
        ON producto.proveedor = proveedor.codproveedor
        WHERE codproducto = $id_producto AND status = 1 ";
        //echo $queryProducto;
        $queryProductoSend = mysqli_query($conection,$queryProducto);
        $resulProducto = mysqli_num_rows($queryProductoSend);

        $foto = '';
        $ClassRemove = 'notBlock'; 
        if($resulProducto>0){
            $dataProducto = mysqli_fetch_assoc($queryProductoSend);

            if($dataProducto['foto'] != 'imgProducto.png'){
              
                $ClassRemove = '';
                $foto = '<img id="img" src="img/uploads/'.$dataProducto['foto'].'" alt="Producto">';
            }    
           // print_r($dataProducto);
        }

}


?>
<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="UTF-8">

<?php
include("includes/script.php");
	?>
	<title> Actualizar Producto</title>


</head>
<body>
	<?php
	include("./includes/header.php");
    ?>
    
	<section id="container">

        <div class="formRegistro">
            <h1>
            <i class="fas fa-box-open"></i>+
            Actualizar Producto
            </h1>
            <hr>
            <div class="alert">
                  <?php echo isset($alert) ? $alert : "" ?>
                </div>

                <form action="" method="post" enctype="multipart/form-data" >
                    <input type="hidden" name="id" value="<?php echo $dataProducto['codproducto'] ; ?>">
                    <input type="hidden" name="foto_actual" id="foto_actual" value="<?php echo $dataProducto['foto'] ; ?>">
                    <input type="hidden" name="foto_remove" id="foto_remove" value="<?php echo $dataProducto['foto'] ; ?>">

                     <label for="proveedor">Proveedor</label>
                    <?php
                        $queryProveedor = "SELECT codproveedor, proveedor FROM proveedor WHERE estatus = 1 
                        ORDER BY proveedor ASC";
                        $queryProveedorSend =mysqli_query($conection,$queryProveedor);
                        $resultProveedor = mysqli_num_rows($queryProveedorSend);
                   
                    ?>

                    <select name="proveedor" id="proveedor" class="notItemOne">
                    <option value="<?php echo $dataProducto['codproveedor']; ?>" selected><?php echo $dataProducto['proveedor']; ?></option>
                    <?php
                        if($resultProveedor > 0){
                          while($proveedorResult = mysqli_fetch_array($queryProveedorSend)){                
                    ?>

                        <option value="<?php echo $proveedorResult['codproveedor']; ?>"><?php echo $proveedorResult['proveedor']; ?></option>
                     <?php
                            }
                        }
                     ?>

                        
                    </select>
                    <label for="producto">Producto</label>
                    <input type="text"name="producto" id="producto" value="<?php echo $dataProducto['descripcion'];?>" >

                    <label for="precio">Precio</label>
                    <input type="number"name="precio" id="precio" value="<?php echo $dataProducto['precio'];?>" >               


                    <div class="photo">
                        <label for="foto">Foto</label>
                            <div class="prevPhoto">
                            <span class="delPhoto <?php echo $ClassRemove;?>">X</span>
                            <label for="foto"></label>
                            <?php echo $foto; ?>      
                            </div>
                            <div class="upimg">
                            <input type="file" name="foto" id="foto">
                            </div>
                            <div id="form_alert"></div>
                     </div>

                    
                    </div>
                    
                    
                    <button type="submit" class="btn_registro"><i class="far fa-save"></i> Actualizar Producto</button>                    

                </form>
                
        </div>

	</section>


	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>