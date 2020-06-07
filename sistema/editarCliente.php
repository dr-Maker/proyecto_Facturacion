<?php

session_start();
include_once("../conexion.php");

if(!empty($_POST))
{
    $alert="";
    if(empty($_POST['rut']) ||  empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion'])){
        $alert='<p class="msg_error"> Todos los campos son Requeridos </p>'  ;
    
    }else{
   
        $idcliente= $_POST['id'];
        $rut = $_POST['rut'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];

        $result = 0;
        if(is_numeric($rut)){

            $query= "SELECT * FROM cliente 
            where (rut = $rut AND idcliente != $idcliente)";
            $querySend = mysqli_query($conection,$query);
            $result= mysqli_fetch_array($querySend);
            //$result = count($result);
        }

        if($result > 0){
            $alert='<p class="msg_error"> El rut ya existe </p>'  ;
        }
        else{

          //  if(empty($_POST['clave_registro'])){

                $queryUpdate ="UPDATE cliente SET 
                rut = '$rut',
                nombre = '$nombre',
                telefono = '$telefono',
                direccion = '$direccion' 
                WHERE idcliente = $idcliente" ;
                $queryUpdateSend = mysqli_query($conection,$queryUpdate);

           
            
                if($queryUpdateSend){
                    $alert='<p class="msg_save"> Cliente Actualizado correctamente </p>'  ;
                } else {

                    $alert='<p class="msg_error"> Error al Actualizar el Cliente </p>'  ;
                }
        }

    }
}

if(empty($_REQUEST['id'])){
    header("location: listaCliente.php ");
    mysqli_close($conection);
}

$idcliente = $_REQUEST['id'];
$query_2= "SELECT *  
FROM cliente 
 WHERE idcliente = $idcliente AND status = 1";
$sqlSend_2 = mysqli_query($conection,$query_2); 
$result_2 = mysqli_num_rows($sqlSend_2);
if($result_2==0){
    header("location: listaCliente.php ");
}else{



    while ($data= mysqli_fetch_array($sqlSend_2)){

        $idcliente = $data['idcliente'];
        $rut = $data['rut'];
        $nombre = $data['nombre'];
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
	<title>Actualizar Cliente</title>


</head>
<body>
	<?php
	include("./includes/header.php");
    ?>
    
	<section id="container">

        <div class="formRegistro">
            <h1>
            <h1> <i class="fas fa-user-edit"></i>    
            Actualizar Cliente
            </h1>
            <hr>
            <div class="alert">
                  <?php echo isset($alert) ? $alert : "" ?>
                </div>

                <form action="" method="post" >

<label for="rut">Rut</label>

<input type="hidden" name="id" value="<?php echo $idcliente; ?>">

<input type="number"name="rut" id="rut" placeholder="Ingrese Rut sin guion ni digito verificador" value="<?php echo $rut; ?>" >


<label for="nombre">Nombre</label>
<input type="text"name="nombre" id="nombre" placeholder="Nombre Completo..." value="<?php echo $nombre; ?>">


<label for="telefono">telefono</label>
<input type="number"name="telefono" id="telefono" placeholder="Ingrese Telefono" value="<?php echo $telefono; ?>" >

<label for="direccion">Direccion</label>
<input type="text"name="direccion" id="direccion" placeholder="Ingrese su Direccion" value="<?php echo $direccion; ?>">

 
<button type="submit"  class="btn_registro"><i class="far fa-edit"></i> Actualizar Cliente </button>                

</form>
                
        </div>

	</section>


	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>