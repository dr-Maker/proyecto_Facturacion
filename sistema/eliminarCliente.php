<?php

session_start();
if($_SESSION['s_rol'] !=1 and $_SESSION['s_rol'] !=2){
    header("location: ../");
}

include_once("../conexion.php");

if(!empty($_POST)){
   
   
    if(empty($_POST['idcliente'])){
        header("location: listaCliente.php ");
        mysqli_close($conection);
    }
   
        $idcliente = $_POST['idcliente'];


    
    $queryDelete ="UPDATE cliente SET status = 0 WHERE idcliente = $idcliente ";

    $queryDeleteSend = mysqli_query($conection,$queryDelete);
    mysqli_close($conection);

    if($queryDeleteSend){
        header("location: listaCliente.php ");
    }else{

            echo "Error al Eliminar el Cliente";
    }

}

if(empty($_REQUEST['id']))
{

    header("location: listaCliente.php ");
}else{

   
    
    $idcliente = $_REQUEST['id'];

    $query="SELECT * 
    FROM cliente
    WHERE idcliente = $idcliente ";

    $querySend= mysqli_query($conection,$query);
    mysqli_close($conection);
    $result = mysqli_num_rows($querySend);

    if($result>0){

        while ($data = mysqli_fetch_array($querySend)){

                $rut = $data['rut'];
                $nombre = $data['nombre'];

         }
    

    }else{
           
        header("location: listaCliente.php ");
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
	<title>Eliminar Cliente</title>
</head>
<body>
	<?php
	include_once("./includes/header.php");
    ?>
    
	<section id="container">

        <div class="data_delete">
            <i class="iconoEliminarUser fas fa-user-times fa-7x" style="color: #ca2b2b"></i>
            <h2>¿Estas seguro de Eliminiar el siguiente Cliente?</h2>

            <p>-. Rut : <span> <?php echo $rut ;?> </span></p>
            <p>-. Nombre : <span> <?php echo $nombre ;?> </span></p>
          
    

            <form action="#" method="post">
                <input type="hidden" name="idcliente" value="<?php echo $idcliente ?>"> 
                <a class="btn-cancelar" href="listaCliente.php"><i class="fas fa-ban"></i> Cancelar</a>
        
                <button class="btn-aceptar" type="submit" ><i class="fas fa-trash-alt"></i> Eliminar</button>


            </form>

        </div>


	</section>

	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>