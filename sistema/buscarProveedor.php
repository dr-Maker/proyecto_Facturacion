<?php

session_start();
if($_SESSION['s_rol'] !=1 and $_SESSION['s_rol'] !=2){
    header("location: ../");
}
include_once("../conexion.php");

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php
	include_once("./includes/script.php");
	?>
	<title>Lista de Proveedores</title>
</head>
<body>
	<?php
	include_once("./includes/header.php");
	?>
	<section id="container">

        <?php
            $busqueda = strtolower($_REQUEST['busqueda']);
            if(empty($busqueda))
            header("location: listaProveedor.php");
           // mysqli_close($conection);
        ?>


	<div class="listaUser"
	>
		<h1><i class="fas fa-clipboard-list fa-2x"></i> Lista de Proveedores</h1>

	<a href="registroProveedor.php" class="btn-new"><i class="fas fa-dolly"></i>+ Crear nuevo Proveedor </a>

	<form action="buscarProveedor.php" method="get" class="formBusqueda">
		<input type="text" name="busqueda" id="busqueda" placeholder="buscar" value="<?php echo $busqueda; ?>">
		<input type="submit" value="buscar" class="btn-busqueda">
	</form>


			<table>

            <tr>
					<th>ID</th>
                    <th>Proveedor</th>
					<th>Contacto</th>
					<th>Telefono</th>
                    <th>Direccion</th>
                    <th>Fecha</th>
					<th>Acciones</th>
				</tr>
<?php

    // paginador **

	$sqlCantidadRegistros = "SELECT COUNT(*) as totalRegistro from proveedor 
    WHERE 
    (
    codproveedor LIKE '%$busqueda%' OR
    proveedor  LIKE '%$busqueda%' OR
    contacto LIKE '%$busqueda%' OR
    telefono LIKE '%$busqueda%' OR
    direccion LIKE '%$busqueda%' 
    ) 
    AND estatus = 1";

	$sqlRegistro = mysqli_query($conection,$sqlCantidadRegistros);
	$resultRegistros = mysqli_fetch_array($sqlRegistro);
	$totalRegistros = $resultRegistros['totalRegistro'];


	$cantidadPorPagina = 4;

		if(empty($_GET['pagina']))
		{
			$pagina = 1;
		}
		else
		{
			$pagina = $_GET['pagina'];
		}

		$desde = ($pagina-1) * $cantidadPorPagina;
		$totalPaginas = ceil($totalRegistros / $cantidadPorPagina); 

	$queryselect = "SELECT *
	from proveedor
	WHERE 
    (
    codproveedor LIKE '%$busqueda%' OR
    proveedor  LIKE '%$busqueda%' OR
    contacto LIKE '%$busqueda%' OR
    telefono LIKE '%$busqueda%' OR
    direccion LIKE '%$busqueda%' 
    )
    AND estatus = 1 
	ORDER BY codproveedor ASC
    LIMIT $desde,$cantidadPorPagina ";
    
	$queryselectSend = mysqli_query($conection,$queryselect);
	$result = mysqli_num_rows($queryselectSend);

	if($result>0){

		while($data = mysqli_fetch_array($queryselectSend)){

            $formato = 'Y-m-d H:i:s';
            $fecha = DateTime::createFromFormat($formato,$data['date_add']);
	
		?>
		 
			<tr>
            <td><?php echo $data['codproveedor'] ;?></td>
            <td><?php echo $data['proveedor']; ?></td>
			<td><?php echo $data['contacto']; ?></td>
			<td><?php echo $data['telefono']; ?></td>
            <td><?php echo $data['direccion']; ?></td>
            <td><?php echo $fecha->format('d-m-Y') ;?></td>		
			<td>
				<a class="link_editar" href="editarProveedor.php?id=<?php echo $data['codproveedor'];?>">Editar</a>




					<a class="link_eliminar" href="eliminarProveedor.php?id=<?php echo $data['codproveedor'];?>">Eliminar</a> 
						


				
			</td>

		</tr>


	<?php
		}

	}

?>

			

			</table>

	<?php 
	
		if($totalRegistros !=0)
	{


	
	?>

		</div>
		<div class="paginador">
			<ul>
			<?php
				if($pagina!=1)
				{

				
			?>
				<li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>">|<</a></li>
				<li><a href="?pagina=<?php echo $pagina-1; ?>&busqueda=<?php echo $busqueda; ?>"><<</a></li>

			<?php
				}
				for ($i= 1; $i <= $totalPaginas ; $i++){

					if ($i == $pagina)
					{
						echo '<li class="pageSelected">'.$i.'</li>';

					}
					else
					{
						echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
					}
					
				}
				if($pagina != $totalPaginas ){
			?>
						
				<li><a href="?pagina=<?php echo $pagina+1; ?>&busqueda=<?php echo $busqueda; ?>">>></a></li>
				<li><a href="?pagina=<?php echo $totalPaginas; ?>&busqueda=<?php echo $busqueda; ?>">>|</a></li>

			<?php }?>	
			</ul>
		</div>
		<?php
	}
		?>

	</section>

	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>