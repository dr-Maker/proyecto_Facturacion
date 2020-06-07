<?php

session_start();
if($_SESSION['s_rol'] !=1 and $_SESSION['s_rol'] !=2){
    header("location: ../");
}

include_once("../conexion.php");

if(!empty($_POST))
{
    $alert="";
    if(empty($_POST['proveedor']) ||  empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion'])){
        $alert='<p class="msg_error"> Todos los campos son Requeridos </p>'  ;
    
    }else{
   
        $idproveedor= $_POST['id'];
        $proveedor = $_POST['proveedor'];
        $contacto = $_POST['contacto'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];

      

          //  if(empty($_POST['clave_registro'])){

                $queryUpdate ="UPDATE proveedor SET 
                proveedor = '$proveedor',
                contacto = '$contacto',
                telefono = '$telefono',
                direccion = '$direccion' 
                WHERE codproveedor = $idproveedor" ;
                $queryUpdateSend = mysqli_query($conection,$queryUpdate);

           
            
                if($queryUpdateSend){
                    $alert='<p class="msg_save"> Proveedor Actualizado correctamente.</p>'  ;
                } else {

                    $alert='<p class="msg_error"> Error al Actualizar el Proveedor. </p>'  ;
                }
        

    }
}

if(empty($_REQUEST['id'])){
    header("location: listaProveedor.php ");
    mysqli_close($conection);
}

$idproveedor = $_REQUEST['id'];
$query_2= "SELECT *  
FROM proveedor 
 WHERE codproveedor = $idproveedor AND estatus = 1";
$sqlSend_2 = mysqli_query($conection,$query_2); 
$result_2 = mysqli_num_rows($sqlSend_2);
if($result_2==0){
    header("location: listaProveedor.php ");
}else{



    while ($data= mysqli_fetch_array($sqlSend_2)){

        $idproveedor = $data['codproveedor'];
        $proveedor = $data['proveedor'];
        $contacto = $data['contacto'];
        $telefono = $data['telefono'];
        $direccion = $data['direccion'];
        
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
	<title>Actualizar Proveedor</title>


</head>
<body>
	<?php
	include("./includes/header.php");
    ?>
    
	<section id="container">

        <div class="formRegistro">
            <h1>
            <h1> <i class="fas fa-truck"></i><i class="far fa-edit fa-sm"></i>   
            Actualizar Proveedor
            </h1>
            <hr>
            <div class="alert">
                  <?php echo isset($alert) ? $alert : "" ?>
                </div>

                <form action="" method="post" >

                    <input type="hidden" name="id" value="<?php echo $idproveedor; ?>" >

                     <label for="proveedor">Nombre del Proveedor</label>
                    <input type="text"name="proveedor" id="proveedor" placeholder="Ingrese el nombre del Proveedor" value="<?php echo $proveedor; ?>" >

                    
                    <label for="contacto">Nombre del Contacto</label>
                    <input type="text"name="contacto" id="contacto" placeholder="Ingrese Nombre del Contacto..." value="<?php echo $contacto; ?>" >

                    <label for="telefono">telefono</label>
                    <input type="number"name="telefono" id="telefono" placeholder="Ingrese Telefono" value="<?php echo $telefono; ?>" >

                    <label for="direccion">Direccion</label>
                    <input type="text"name="direccion" id="direccion" placeholder="Ingrese su Direccion" value="<?php echo $direccion; ?>" >
                    
                    <button type="submit" class="btn_registro"><i class="far fa-save"></i> Actualizar Proveedor</button>                    

                </form>
                
        </div>

	</section>


	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>