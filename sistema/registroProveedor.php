<?php
session_start();
if($_SESSION['s_rol'] !=1 and $_SESSION['s_rol'] !=2){
    header("location: ../");
}

include_once("../conexion.php");

if(!empty($_POST)){
    $alert="";
    if(empty($_POST['proveedor']) ||  empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion']) ){
       
        $alert='<p class="msg_error"> Todos los campos son Requeridos </p>'  ;
    }
    else{
   
        $proveedor = $_POST['proveedor'];
        $contacto = $_POST['contacto'];
        $telefono = $_POST['telefono'];
        $direccion= $_POST['direccion'];
        $usuario_id = $_SESSION['s_iduser'];

    

            $queryCreate="INSERT INTO proveedor (proveedor,contacto,telefono,direccion,usuario_id) 
                            VALUES ('$proveedor','$contacto','$telefono','$direccion', $usuario_id)";   
            $queryInsert=mysqli_query($conection,$queryCreate);
           
            if($queryInsert){
                $alert='<p class="msg_save"> Proveedor guardado correctamente </p>'  ;
            } else {

                $alert='<p class="msg_error"> Error al guardar el Proveedor </p>'  ;
            }
        }
     mysqli_close($conection);
      
}

?>


<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="UTF-8">

<?php
include("includes/script.php");
	?>
	<title> Registro Proveedor</title>


</head>
<body>
	<?php
	include("./includes/header.php");
    ?>
    
	<section id="container">

        <div class="formRegistro">
            <h1>
            <i class="fas fa-dolly-flatbed"></i>+
            Registro Proveedor
            </h1>
            <hr>
            <div class="alert">
                  <?php echo isset($alert) ? $alert : "" ?>
                </div>

                <form action="" method="post" >

                     <label for="proveedor">Nombre del Proveedor</label>
                    <input type="text"name="proveedor" id="proveedor" placeholder="Ingrese el nombre del Proveedor" >

                    
                    <label for="contacto">Nombre del Contacto</label>
                    <input type="text"name="contacto" id="contacto" placeholder="Ingrese Nombre del Contacto..." >

                    <label for="telefono">telefono</label>
                    <input type="number"name="telefono" id="telefono" placeholder="Ingrese Telefono" >

                    <label for="direccion">Direccion</label>
                    <input type="text"name="direccion" id="direccion" placeholder="Ingrese su Direccion" >
                    
                    <button type="submit" class="btn_registro"><i class="far fa-save"></i> Guardar Proveedor</button>                    

                </form>
                
        </div>

	</section>


	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>