<?php
session_start();
if($_SESSION['s_rol'] !=1){
    header("location: ../");
}

include_once("../conexion.php");

if(!empty($_POST))
{
    $alert="";
    if(empty($_POST['nombre_registro']) ||  empty($_POST['correo_registro']) || empty($_POST['usuario_registro']) || empty($_POST['clave_registro']) || empty($_POST['rol_registro'])){
        $alert='<p class="msg_error"> Todos los campos son Requeridos </p>'  ;
    
    }else{
   

        $nombre = $_POST['nombre_registro'];
        $correo = $_POST['correo_registro'];
        $usuario= $_POST['usuario_registro'];
        $pass = md5($_POST['clave_registro']);
        $rol = $_POST['rol_registro'];

        $query= "SELECT * FROM usuario where usuario = '$usuario' or correo = '$correo' ";
        $querySend = mysqli_query($conection,$query);
        $result= mysqli_fetch_array($querySend);

        if($result > 0){
            $alert='<p class="msg_error"> El correo o el Usuario ya existen </p>'  ;
        }
        else{

        $queryCreate="INSERT INTO usuario (nombre,usuario,correo,clave,rol) VALUES ('$nombre','$usuario','$correo','$pass',$rol)";   
            $queryInsert=mysqli_query($conection,$queryCreate);
        
            if($queryInsert){
                $alert='<p class="msg_save"> Usuario creado correctamente </p>'  ;
            } else {

                $alert='<p class="msg_error"> Error al crear el usuario </p>'  ;
            }
          
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
	<title>Registro Usuario</title>


</head>
<body>
	<?php
	include("./includes/header.php");
    ?>
    
	<section id="container">

        <div class="formRegistro">
            <h1> <i class="fas fa-user-plus"></i>
            Registro Usuario
            </h1>
            <hr>
            <div class="alert">
                  <?php echo isset($alert) ? $alert : "" ?>
                </div>

                <form action="" method="post" >
                
                    <label for="nombre_registro">Nombre</label>
                    <input type="text"name="nombre_registro" id="nombre_registro" placeholder="Nombre Completo..." >

                    <label for="correo_registro">Correo Electronico</label>
                    <input type="email"name="correo_registro" id="correo_registro" placeholder="Correo..." >

                    <label for="usuario_registro">Usuario</label>
                    <input type="text"name="usuario_registro" id="usuario_registro" placeholder="Usuario..." >

                    <label for="clave_registro">Contraseña</label>
                    <input type="password"name="clave_registro" id="clave_registro" placeholder="Contraseña de acceso..." >
                    
                    <label for="rol_registro">Tipo Usuario</label>

                    <?php
                    $query_rol="SELECT * FROM rol";
                    $result_roles = mysqli_query($conection,$query_rol); 
    
                    $result_rol = mysqli_num_rows($result_roles);

                    ?>

                    <select name="rol_registro" id="rol_registro">

                        <?php
                        if($result_rol>0){

                            while ($rol = mysqli_fetch_array($result_roles)){
                        ?>
                            <option value="<?php echo $rol['idrol'] ?>"><?php echo $rol['rol'] ?></option>
                        <?php

                            }
                        }
                        ?>

                    </select>
                    
                    <button type="submit" class="btn_registro"><i class="far fa-save"></i> Crear Usuario</button>        

                </form>
                
        </div>

	</section>


	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>