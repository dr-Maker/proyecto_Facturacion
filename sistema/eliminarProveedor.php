<?php

session_start();
if($_SESSION['s_rol'] !=1 and $_SESSION['s_rol'] !=2){
    header("location: ../");
}

include_once("../conexion.php");

if(!empty($_POST)){
   
   
    if(empty($_POST['idproveedor'])){
        header("location: listaProveedor.php ");
        mysqli_close($conection);
    }
   
        $idproveedor = $_POST['idproveedor'];


    
    $queryDelete ="UPDATE proveedor SET estatus = 0 WHERE codproveedor = $idproveedor ";

    $queryDeleteSend = mysqli_query($conection,$queryDelete);
    mysqli_close($conection);

    if($queryDeleteSend){
        header("location: listaProveedor.php ");
    }else{

            echo "Error al Eliminar el Proveedor";
    }

}

if(empty($_REQUEST['id']))
{
    header("location: listaProveedor.php ");
}else{

   
    
    $idproveedor = $_REQUEST['id'];

    $query="SELECT * 
    FROM proveedor
    WHERE codproveedor = $idproveedor ";

    $querySend= mysqli_query($conection,$query);
    mysqli_close($conection);
    $result = mysqli_num_rows($querySend);

    if($result>0){

        while ($data = mysqli_fetch_array($querySend)){

                $proveedor = $data['proveedor'];
         

         }
    

    }else{
           
        header("location: listaProveedor.php ");
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
	<title>Eliminar Proveedor</title>
</head>
<body>
	<?php
	include_once("./includes/header.php");
    ?>
    
	<section id="container">

        <div class="data_delete">
            <i class="fas fa-truck fa-7x"></i><i class="fas fa-times fa-2x"></i>
          
            
            <h2>¿Estas seguro de Eliminiar el siguiente Proveedor?</h2>

            <p>-. Nombre del Proveedor : <span> <?php echo $proveedor ;?> </span></p>
         
          
    

            <form action="#" method="post">
                <input type="hidden" name="idproveedor" value="<?php echo $idproveedor ?>"> 
                <a class="btn-cancelar" href="listaProveedor.php"><i class="fas fa-ban"></i> Cancelar</a>
        
                <button class="btn-aceptar" type="submit" ><i class="fas fa-trash-alt"></i> Eliminar</button>


            </form>

        </div>


	</section>

	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>