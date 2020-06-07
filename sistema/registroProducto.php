<?php
session_start();
if($_SESSION['s_rol'] !=1 and $_SESSION['s_rol'] !=2){
    header("location: ../");
}

include_once("../conexion.php");

if(!empty($_POST)){


    $alert="";
    if(empty($_POST['proveedor']) ||  empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['cantidad']) ){
       
        $alert='<p class="msg_error"> Todos los campos son Requeridos </p>'  ;
    }
    else{
   
        $proveedor = $_POST['proveedor'];
        $producto = $_POST['producto'];
        $precio = $_POST['precio'];
        $cantidad= $_POST['cantidad'];
        $usuario_id = $_SESSION['s_iduser'];

        $foto = $_FILES['foto'];
        $nombreFoto = $foto['name'];
        $nombreType = $foto['type'];
        $FotoURLTemp = $foto['tmp_name'];

        $imgProducto = 'imgProducto.png';

        if ($nombreFoto != '')
        {
            $destino = 'img/uploads/';
            $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
            $imgProducto = $img_nombre.'.jpg'; 
            $src = $destino.$imgProducto;
      
        }
        

            $queryCreate="INSERT INTO producto (proveedor,descripcion,precio,existencia,usuario_id,foto) 
                            VALUES ('$proveedor','$producto',$precio,$cantidad, $usuario_id, '$imgProducto' )";   
            $queryInsert=mysqli_query($conection,$queryCreate);
           
            if($queryInsert){
                if ($nombreFoto != '' ){
                    move_uploaded_file($FotoURLTemp,$src);
                }

                $alert='<p class="msg_save"> Producto guardado correctamente </p>'  ;
            } else {

                $alert='<p class="msg_error"> Error al guardar el Producto </p>'  ;
            }
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
	<title> Registro Producto</title>


</head>
<body>
	<?php
	include("./includes/header.php");
    ?>
    
	<section id="container">

        <div class="formRegistro">
            <h1>
            <i class="fas fa-box-open"></i>+
            Registro Productos
            </h1>
            <hr>
            <div class="alert">
                  <?php echo isset($alert) ? $alert : "" ?>
                </div>

                <form action="" method="post" enctype="multipart/form-data" >

                     <label for="proveedor">Proveedor</label>
                    <?php
                        $queryProveedor = "SELECT codproveedor, proveedor FROM proveedor WHERE estatus = 1 
                        ORDER BY proveedor ASC";
                        $queryProveedorSend =mysqli_query($conection,$queryProveedor);
                        $resultProveedor = mysqli_num_rows($queryProveedorSend);
                   
                    ?>

                    <select name="proveedor" id="proveedor">
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
                    <input type="text"name="producto" id="producto" placeholder="Ingrese Nombre del Producto..." >

                    <label for="precio">Precio</label>
                    <input type="number"name="precio" id="precio" placeholder="Ingrese Precio ..." >

                    <label for="cantidad">Cantidad</label>
                    <input type="number"name="cantidad" id="cantidad" placeholder="Ingrese la Cantidad" >


                    <div class="photo">
                        <label for="foto">Foto</label>
                            <div class="prevPhoto">
                            <span class="delPhoto notBlock">X</span>
                            <label for="foto"></label>
                            </div>
                            <div class="upimg">
                            <input type="file" name="foto" id="foto">
                            </div>
                            <div id="form_alert"></div>
                     </div>

                    
                    </div>
                    
                    
                    <button type="submit" class="btn_registro"><i class="far fa-save"></i> Guardar Producto</button>                    

                </form>
                
        </div>

	</section>


	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>