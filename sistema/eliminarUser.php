<?php

session_start();
if($_SESSION['s_rol'] !=1){
    header("location: ../");
}

include_once("../conexion.php");

if(!empty($_POST)){
   
    if($_POST['idusuario'] == 1){

        header("location: listaUser.php ");
        exit;

    }
   
    $idusuario = $_POST['idusuario'];

    //$queryDelete = "DELETE FROM usuario WHERE idusuario = $idusuario" ;
    
    $queryDelete ="UPDATE usuario SET estatus = 0 WHERE idusuario = $idusuario ";

    $queryDeleteSend = mysqli_query($conection,$queryDelete);

    if($queryDeleteSend){

        header("location: listaUser.php ");
    }else{

            echo "Error al Eliminar Usuario";
    }

}

if(empty($_REQUEST['id']) || $_REQUEST['id'] == 1 )
{

    header("location: listaUser.php ");
}else{

   
    
    $idusuario = $_REQUEST['id'];

    $query="SELECT nombre,usuario, rol.rol 
    FROM usuario
    INNER JOIN rol
    ON usuario.rol = rol.idrol
    WHERE idusuario = $idusuario ";

    $querySend= mysqli_query($conection,$query);

    
    $result = mysqli_num_rows($querySend);

    if($result>0){

        while ($data = mysqli_fetch_array($querySend)){

                $nombre = $data['nombre'];
                $usuario = $data['usuario'];
                $rol = $data['rol'];
         }
    

    }else{
           
        header("location: listaUser.php ");
    }
}

?>



<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php
	include_once("./includes/script.php");
	?>
	<title>Eliminar Usuario</title>
</head>
<body>
	<?php
	include_once("./includes/header.php");
    ?>
    
	<section id="container">

        <div class="data_delete">
            <i class="iconoEliminarUser fas fa-user-times fa-7x" style="color: #ca2b2b"></i>

            <h2> ¿Estas seguro de Eliminiar el siguiente Usuario?</h2>

            <p>-. Nombre : <span> <?php echo $nombre ;?> </span></p>
            <p>-. Usuario : <span> <?php echo $usuario ;?> </span></p>
            <p>-. Tipo de Usuario : <span> <?php echo $rol ;?> </span></p>

            <form action="#" method="post">
                <input type="hidden" name="idusuario" value="<?php echo $idusuario ?>"> 
                <a class="btn-cancelar" href="listaUser.php"><i class="fas fa-ban"></i> Cancelar</a>
                <button class="btn-aceptar" type="submit" ><i class="fas fa-trash-alt"></i> Eliminar</button>

            </form>

        </div>


	</section>

	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>