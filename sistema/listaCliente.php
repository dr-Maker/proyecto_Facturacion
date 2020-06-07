<?php

session_start();
include_once("../conexion.php");

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php
	include_once("./includes/script.php");
	?>
	<title>Lista de Clientes</title>
</head>
<body>
	<?php
	include_once("./includes/header.php");
	?>
	<section id="container">

	<div class="listaUser"
	>
		<h1><i class="far fa-address-card fa-2x"></i> Lista de Clientes</h1>

	<a href="registroCliente.php" class="btn-new"><i class="fas fa-user-plus"></i> Crear Cliente </a>

	<form action="buscarCliente.php" method="get" class="formBusqueda">
		<input type="text" name="busqueda" id="busqueda" placeholder="buscar">
		<button type="submit"  class="btn-busqueda"><i class="fas fa-search"></i></button>
	</form>


			<table>

				<tr>
					<th>ID</th>
                    <th>Rut</th>
					<th>Nombre</th>
					<th>Telefono</th>
					<th>Direccion</th>
					<th>Acciones</th>
				</tr>
<?php

	// paginador **
    $sqlCantidadRegistros = "SELECT COUNT(*) as totalRegistro from cliente where status = 1";
     
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
	from cliente
	WHERE status = 1 
	ORDER BY idcliente ASC
    LIMIT $desde,$cantidadPorPagina ";
 
    $queryselectSend = mysqli_query($conection,$queryselect);
    mysqli_close($conection);
	$result = mysqli_num_rows($queryselectSend);

	if($result>0){

		while($data = mysqli_fetch_array($queryselectSend)){
		
		?>
			<tr>
			<td><?php echo $data['idcliente'] ;?></td>
			<td><?php echo $data['rut']; ?></td>
			<td><?php echo $data['nombre'] ;?></td>
			<td><?php echo $data['telefono']; ?></td>
			<td><?php echo $data['direccion']; ?></td>		
			<td>
				<a class="link_editar" href="editarCliente.php?id=<?php echo $data['idcliente'];?>"><i class="fas fa-user-edit"></i> Editar</a>

				<?php
				if($_SESSION['s_rol'] == 1 || $_SESSION['s_rol'] == 2 ){
				?>

					<a class="link_eliminar" href="eliminarCliente.php?id=<?php echo $data['idcliente'];?>"><i class="fas fa-user-times"></i> Eliminar</a> 
						
				<?php
					}
				?>
			</td>

		</tr>


	<?php
		}

	}

?>

			

			</table>
		</div>
		<div class="paginador">
			<ul>
			<?php
				if($pagina!=1)
				{

				
			?>
				<li><a href="?pagina=<?php echo 1; ?>">|<</a></li>
				<li><a href="?pagina=<?php echo $pagina-1; ?>"><<</a></li>

			<?php
				}
				for ($i= 1; $i <= $totalPaginas ; $i++){

					if ($i == $pagina)
					{
						echo '<li class="pageSelected">'.$i.'</li>';

					}
					else
					{
						echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
					}
					
				}
				if($pagina != $totalPaginas ){
			?>
						
				<li><a href="?pagina=<?php echo $pagina+1; ?>">>></a></li>
				<li><a href="?pagina=<?php echo $totalPaginas; ?>">>|</a></li>

			<?php }?>	
			</ul>
		</div>

	</section>

	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>