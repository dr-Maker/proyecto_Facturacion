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

        <?php
            $busqueda = strtolower($_REQUEST['busqueda']);
            if(empty($busqueda))
            header("location: listaUser.php");
        ?>


	<div class="listaUser"
	>
		<h1>Lista de Usuarios</h1>

	<a href="registroUser.php" class="btn-new"> Crear Usuario </a>

	<form action="buscarUsuario.php" method="get" class="formBusqueda">
		<input type="text" name="busqueda" id="busqueda" placeholder="buscar" value="<?php echo $busqueda; ?>">
		<input type="submit" value="buscar" class="btn-busqueda">
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
    $rol ='';
    if($busqueda == 'administrador')
    {
        $rol = "OR ROL LIKE '%1%'";
    }
    else if($busqueda == 'supervisor')
    {
        $rol = "OR ROL LIKE '%2%'";
    }
    else if($busqueda == 'vendedor')
    {
        $rol = "OR ROL LIKE '%3%'";
    } 


	$sqlCantidadRegistros = "SELECT COUNT(*) as totalRegistro from usuario 
    WHERE 
    (
    idusuario LIKE '%$busqueda%' OR
    nombre LIKE '%$busqueda%' OR
    correo LIKE '%$busqueda%' OR
    usuario LIKE '%$busqueda%' 
    $rol
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

	$queryselect = "SELECT idusuario,nombre, correo, usuario, rol.rol, rol.idrol 
	from usuario
	inner JOIN rol
	on usuario.rol = rol.idrol
	WHERE 
    (
        usuario.idusuario LIKE '%$busqueda%' OR
        usuario.nombre LIKE '%$busqueda%' OR
        usuario.correo LIKE '%$busqueda%' OR
        usuario.usuario LIKE '%$busqueda%' OR
        rol.rol LIKE '%$busqueda%'
    )
    AND estatus = 1 
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
				<a class="link_editar" href="editarUser.php?id=<?php echo $data['idusuario'];?>">Editar</a>

				<?php
					if($data['idusuario'] != 1 || $data['idrol'] != 1){ ?>


					<a class="link_eliminar" href="eliminarUser.php?id=<?php echo $data['idusuario'];?>">Eliminar</a> 
						

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