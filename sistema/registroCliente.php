<?php
session_start();

include_once("../conexion.php");

if(!empty($_POST))
{
    $alert="";
    if(empty($_POST['rut']) ||  empty($_POST['nombre_registro']) || empty($_POST['telefono']) || empty($_POST['direccion']) ){
       
        $alert='<p class="msg_error"> Todos los campos son Requeridos </p>'  ;
    }else{
   
        $rut = $_POST['rut'];
        $nombre = $_POST['nombre_registro'];
        $telefono = $_POST['telefono'];
        $direccion= $_POST['direccion'];
        $usuario_id = $_SESSION['s_iduser'];

        $result = 0;

        if(is_numeric($rut))
        {
            $query1= "SELECT * FROM cliente where rut = $rut ";
            $querySend1 = mysqli_query($conection,$query1);
            $result = mysqli_fetch_array($querySend1);
        }
        if($result > 0)
        {
            $alert='<p class="msg_error"> El numero de Rut ya existen en el sistema </p>'  ;
        }
        else{

            $queryCreate="INSERT INTO cliente (rut,nombre,telefono,direccion,usuario_id) 
                            VALUES ($rut,'$nombre','$telefono','$direccion', $usuario_id)";   
            $queryInsert=mysqli_query($conection,$queryCreate);
           
            if($queryInsert){
                $alert='<p class="msg_save"> Cliente guardado correctamente </p>'  ;
            } else {

                $alert='<p class="msg_error"> Error al guardar el Cliente </p>'  ;
            }
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
	<title>Registro Cliente</title>


</head>
<body>
	<?php
	include("./includes/header.php");
    ?>
    
	<section id="container">

        <div class="formRegistro">
            <h1>
            <i class="fas fa-user-plus"></i>
            Registro Cliente
            </h1>
            <hr>
            <div class="alert">
                  <?php echo isset($alert) ? $alert : "" ?>
                </div>

                <form action="" method="post" >

                     <label for="rut">Rut</label>
                    <input type="number"name="rut" id="rut" placeholder="Ingrese Rut sin guion ni digito verificador" >

                    
                    <label for="nombre_registro">Nombre</label>
                    <input type="text"name="nombre_registro" id="nombre_registro" placeholder="Nombre Completo..." >


                    <label for="telefono">telefono</label>
                    <input type="number"name="telefono" id="telefono" placeholder="Ingrese Telefono" >

                    <label for="direccion">Direccion</label>
                    <input type="text"name="direccion" id="direccion" placeholder="Ingrese su Direccion" >
                    
                    <button type="submit" class="btn_registro"><i class="far fa-save"></i> Guardar Cliente</button>                    

                </form>
                
        </div>

	</section>


	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>