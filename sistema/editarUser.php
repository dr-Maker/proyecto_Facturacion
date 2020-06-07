<?php

session_start();
if($_SESSION['s_rol'] !=1){
    header("location: ../");
}

include_once("../conexion.php");

if(!empty($_POST))
{
    $alert="";
    if(empty($_POST['nombre_registro']) ||  empty($_POST['correo_registro']) || empty($_POST['usuario_registro']) || empty($_POST['rol_registro'])){
        $alert='<p class="msg_error"> Todos los campos son Requeridos </p>'  ;
    
    }else{
   
        $idusuario= $_POST['idusuario'];
        $nombre = $_POST['nombre_registro'];
        $correo = $_POST['correo_registro'];
        $usuario = $_POST['usuario_registro'];
        $pass = md5($_POST['clave_registro']);
        $rol = $_POST['rol_registro'];

        $query= "SELECT * FROM usuario 
        where (usuario = '$usuario' AND idusuario != $idusuario) OR (correo = '$correo' AND idusuario != $idusuario)";
        echo $query;
        $querySend = mysqli_query($conection,$query);
        $result= mysqli_fetch_array($querySend);

        if($result > 0){
            $alert='<p class="msg_error"> El correo o el Usuario ya existen </p>'  ;
        }
        else{

            if(empty($_POST['clave_registro'])){

                $queryUpdate ="UPDATE usuario SET 
                nombre = '$nombre',
                correo = '$correo',
                usuario = '$usuario',
                rol = $rol 
                WHERE idusuario = $idusuario" ;
                $queryUpdateSend = mysqli_query($conection,$queryUpdate);

            }   else {
                    $queryUpdate ="UPDATE usuario SET 
                    nombre = '$nombre',
                    correo = '$correo',
                    usuario = '$usuario',
                    clave = '$pass',
                    rol = $rol 
                    WHERE idusuario = $idusuario " ;
                    $queryUpdateSend = mysqli_query($conection,$queryUpdate);

            }         
            
                if($queryUpdateSend){
                    $alert='<p class="msg_save"> Usuario Actualizado correctamente </p>'  ;
                } else {

                    $alert='<p class="msg_error"> Error al Actualizar el usuario </p>'  ;
                }
        }

    }
}

if(empty($_GET['id'])){
    header("location: listaUser.php ");
}

$iduser = $_GET['id'];
$query_2= "SELECT idusuario, nombre, correo,usuario, rol.idrol ,rol.rol  
FROM usuario 
INNER JOIN rol 
ON usuario.rol= rol.idrol WHERE idusuario = $iduser AND estatus = 1";
$sqlSend_2 = mysqli_query($conection,$query_2); 
$result_2 = mysqli_num_rows($sqlSend_2);
if($result_2==0){
    header("location: listaUser.php ");
}else{

$option = "";

    while ($data= mysqli_fetch_array($sqlSend_2)){

        $idusuario = $data['idusuario'];
        $name = $data['nombre'];
        $email = $data['correo'];
        $user = $data['usuario'];
        $idrol = $data['idrol'];
        $rol = $data['rol'];
     

        if($idrol==1){
            $option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
        }else if($idrol==2){
            $option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
        }else if($idrol==3){
            $option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
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
	<title>Editar Usuario</title>


</head>
<body>
	<?php
	include("./includes/header.php");
    ?>
    
	<section id="container">

        <div class="formRegistro">
            <h1> <i class="fas fa-user-edit"></i>
            Editar Usuario
            </h1>
            <hr>
            <div class="alert">
                  <?php echo isset($alert) ? $alert : "" ?>
                </div>

                <form action="" method="post" >
                    <input type="hidden"name="idusuario"  value="<?php echo $idusuario ;?>">

                    <label for="nombre_registro">Nombre</label>
                    <input type="text"name="nombre_registro" id="nombre_registro" placeholder="Nombre Completo..." value="<?php echo $name ;?>">

                    <label for="correo_registro">Correo Electronico</label>
                    <input type="email"name="correo_registro" id="correo_registro" placeholder="Correo..." value="<?php echo $email ;?>" >

                    <label for="usuario_registro">Usuario</label>
                    <input type="text"name="usuario_registro" id="usuario_registro" placeholder="Usuario..."  value="<?php echo $user ;?>">

                    
                    <label for="clave_registro">Contraseña</label>
                    <input type="password"name="clave_registro" id="clave_registro" placeholder="Contraseña de acceso..." >
                    

                    <label for="rol_registro">Tipo Usuario</label value="<?php echo $rol ;?>" >

                    <?php
                    $query_rol="SELECT * FROM rol";
                    $result_roles = mysqli_query($conection,$query_rol); 
                    $result_rol = mysqli_num_rows($result_roles);

                    ?>

                    <select name="rol_registro" id="rol_registro" class="notItemOne">
                        
                        <?php

                        echo $option;

                        if($result_rol>0){

                            while ($rol = mysqli_fetch_array($result_roles)){
                        ?>
                            <option value="<?php echo $rol['idrol'] ?>"><?php echo $rol['rol'] ?></option>
                        <?php

                            }
                        }
                        ?>

                    </select>

                    
                    <button type="submit"  class="btn_registro"><i class="far fa-edit"></i> Actualizar Usuario </button>           

                </form>
                
        </div>

	</section>


	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>