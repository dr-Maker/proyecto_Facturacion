<?php

session_start();
if($_SESSION['s_rol'] !=1){
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
	<title>Lista de Usuarios</title>
</head>
<body>
	<?php
	include_once("./includes/header.php");
	?>
	<section id="container">

	<div class="listaUser"
	>
		<h1><i class="fas fa-users fa-2x"></i> Lista de Usuarios</h1>

	<a href="registroUser.php" class="btn-new"><i class="fas fa-user-plus"></i> Crear Usuario </a>

	<form action="buscarUsuario.php" method="get" class="formBusqueda">
		<input type="text" name="busqueda" id="busqueda" placeholder="buscar">
		<button type="submit"  class="btn-busqueda"><i class="fas fa-search"></i></button>
	</form>


			<table>

				<tr>
					<th>ID</th>
					<th>Nombre</th>
					<th>Correo</th>
					<th>Usuario</th>
					<th>Rol</th>
					<th>Acciones</th>
				</tr>
<?php

	// paginador **
	$sqlCantidadRegistros = "SELECT COUNT(*) as totalRegistro from usuario where estatus = 1";
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

	$queryselect = "SELECT idusuario,nombre, correo, usuario, rol.rol, rol.idrol 
	from usuario
	inner JOIN rol
	on usuario.rol = rol.idrol
	WHERE estatus = 1 
	ORDER BY usuario.idusuario ASC
	LIMIT $desde,$cantidadPorPagina ";
	$queryselectSend = mysqli_query($conection,$queryselect);
	$result = mysqli_num_rows($queryselectSend);

	if($result>0){

		while($data = mysqli_fetch_array($queryselectSend)){
			$idrol = $data['idrol'];
		?>
			<tr>
			<td><?php echo $data['idusuario'] ;?></td>
			<td><?php echo $data['nombre']; ?></td>
			<td><?php echo $data['correo'] ;?></td>
			<td><?php echo $data['usuario']; ?></td>
			<td><?php echo $data['rol']; ?></td>		
			<td>
				<a class="link_editar" href="editarUser.php?id=<?php echo $data['idusuario'];?>"><i class="fas fa-user-edit"></i> Editar</a>

				<?php
					if($data['idusuario'] != 1 || $data['idrol'] != 1){ ?>


					<a class="link_eliminar" href="eliminarUser.php?id=<?php echo $data['idusuario'];?>"><i class="fas fa-user-times"></i> Eliminar</a> 
						

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
				<li><a href="?pagina=<?php echo 1; ?>"><i class="fas fa-fast-backward"></i></a></li>
				<li><a href="?pagina=<?php echo $pagina-1; ?>"><i class="fas fa-backward"></i></a></li>

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
						
				<li><a href="?pagina=<?php echo $pagina+1; ?>"><i class="fas fa-forward"></i></a></li>
				<li><a href="?pagina=<?php echo $totalPaginas; ?>"><i class="fas fa-fast-forward"></i></a></li>

			<?php }?>	
			</ul>
		</div>

	</section>

	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>