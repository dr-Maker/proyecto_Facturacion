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

	<div class="listaUser"
	>
		<h1><i class="fas fa-clipboard-list fa-2x"></i> Lista de Proveedores</h1>

	<a href="registroProveedor.php" class="btn-new"><i class="fas fa-dolly"></i>+ Crear Nuevo Proveedor </a>

	<form action="buscarProveedor.php" method="get" class="formBusqueda">
		<input type="text" name="busqueda" id="busqueda" placeholder="buscar">
		<button type="submit"  class="btn-busqueda"><i class="fas fa-search"></i></button>
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
    $sqlCantidadRegistros = "SELECT COUNT(*) as totalRegistro from proveedor where estatus = 1";
     
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
	WHERE estatus = 1 
	ORDER BY codproveedor ASC
    LIMIT $desde,$cantidadPorPagina ";
 
    $queryselectSend = mysqli_query($conection,$queryselect);
    mysqli_close($conection);
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
				<a class="link_editar" href="editarProveedor.php?id=<?php echo $data['codproveedor'];?>"><i class="far fa-edit"></i> Editar</a>


					<a class="link_eliminar" href="eliminarProveedor.php?id=<?php echo $data['codproveedor'];?>"><i class="fas fa-trash"></i> Eliminar</a> 
						

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