<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php
	include_once("./includes/script.php");
	?>
	<title>Sistema Ventas</title>
</head>
<body>
	<?php
	include_once("./includes/header.php");
	include_once("../conexion.php");

	//Datos Empresa

	$rutEmpresa = '';
	$nombreEmpresa = '';
	$razonSocial = '';
	$fonoEmpresa = '';
	$emailEmpresa = '';
	$dirEmpresa = '';
	$iva = '';

	$queryEmpresa = "SELECT * FROM configuracion";
	$queryEmpresaSend = mysqli_query($conection,$queryEmpresa);
	$resultEmpresa = mysqli_num_rows($queryEmpresaSend);

	if($resultEmpresa>0)
	{
		while($dataEmpresa = mysqli_fetch_assoc($queryEmpresaSend))
		{
			$rutEmpresa = $dataEmpresa['rut'];
			$nombreEmpresa = $dataEmpresa['nombre'];
			$razonSocial = $dataEmpresa['razon_social'];
			$fonoEmpresa = $dataEmpresa['telefono'];
			$emailEmpresa = $dataEmpresa['email'];
			$dirEmpresa = $dataEmpresa['direccion'];
			$iva = $dataEmpresa['iva'];
		}
	}



	//Datos Persona
	$query = "CALL dataDashBoard();";
	$querySend = mysqli_query($conection,$query);
	$result = mysqli_num_rows($querySend);
	if($result>0)
	{
		$data = mysqli_fetch_assoc($querySend);
		mysqli_close($conection);
	}
	
	?>
	<section id="container">
		
		<div class="divContainer">
			<div>
				<h1 class="titlePanelControl">Panel de Control</h1>
			</div>
			<div class="dashboard">

			<?php
				if ($_SESSION['s_rol'] ==1 || $_SESSION['s_rol'] ==2)
			{
			?>
				
				<a href="listaUser.php">
				<i class="fas fa-user-circle"></i>
					<p>
						<strong>Usuarios</strong><br>
						<span><?= $data['usuarios']; ?></span>
					</p>
				</a>
			<?php
			}
			?>
		
				<a href="listaCliente.php">
					<i class="fas fa-users"></i>
					<p>
						<strong>Clientes</strong><br>
						<span><?= $data['clientes']; ?></span>
					</p>
				</a>

			
				
			<?php
				if($_SESSION['s_rol'] ==1 || $_SESSION['s_rol'] == 2)
			{
			?>
			
				<a href="listaProveedor.php">
					<i class="fas fa-truck"></i>
					<p>
						<strong>Proveedores</strong><br>
						<span><?= $data['proveedores']; ?></span>
					</p>
				</a>
			<?php
			}
			?>

				<a href="listaProducto.php">
					<i class="fas fa-box-open"></i>
					<p>
						<strong>Productos</strong><br>
						<span><?= $data['productos']; ?></span>
					</p>
				</a>
				<a href="ventas.php">
					<i class="far fa-money-bill-alt"></i>
					<p>
						<strong>Ventas</strong><br>
						<span><?= $data['ventas']; ?></span>
					</p>
				</a>

			</div>
		</div>

		<div class="divInfoSistema">
			<div>
				<h1 class="titlePanelControl">Configuracion</h1>
			</div>

			<div class="containerPerfil">
				<div class="containerDataUser">
					<div class="logoUser">
						<img src="img/logoUser.png" style="max-width: 250px;">
					</div>
					<div class="divDataUser">
						<h4>Informacion Personal</h4>
				
					</div>

					<div>
						<label>Nombre:</label> <span><?= $_SESSION['s_nombre'] ;?></span>
					</div>
					<div>
						<label>Correo:</label> <span><?= $_SESSION['s_email'] ;?></span>
					</div>

					<h4>Datos Usuario</h4>
					<div>
						<label>Rol:</label> <span><?= $_SESSION['rol_name'] ;?></span>
					</div>
					<div>
						<label>Usuario:</label> <span><span><?= $_SESSION['s_user'] ;?></span>
					</div>

					<h4>Cambiar contraseña</h4>
					<form action="" method="post" name="formChagnePass" id="formChagnePass">
						<div>
							<input type="password" name="txtPassUser" id="txtPassUser" placeholder="Contraseña actual" required>
						</div>
						<div>
							<input Class="newPass" type="password" name="txtNewPassUser" id="txtNewPassUser" placeholder="Nueva contraseña" required>
						</div>
						<div>
							<input Class="newPass" type="password" name="txtNewPassConfirm" id="txtNewPassConfirm" placeholder="Confirmar contraseña" required>
						</div>

						<div class="alertChangePass" style="display:none">

						</div>

						<div>
							<button type="submit" class="btn-new btnChangePass"><i class="fas fa-key"></i> Cambiar contraseña</button>
						</div>

					
					</form>

				</div>
			<?php
			if($_SESSION['s_rol'] == 1)
			{
			?>

				<div class="containerDataEmpresa">
					<div class="logoEmpresa">
						<img src="img/logoEmpresa.png" style="max-width: 250px;">
					</div>
					<h4>Datos de la Empresa</h4>
					<form action="" method="post" name="formEmpresa" id="formEmpresa">
						
						<input type="hidden" name="action" value="updateDataEmpresa">
				
						<div>
							<label>Rut : </label>
							<input type="text" name="txtRut" id="txtRut" placeholder="Rut de la empresa" value="<?= $rutEmpresa ;?>" required>
						</div>
						<div>
							<label>Nombre : </label>
							<input type="text" name="txtNombre" id="txtNombre" placeholder="Nombre de la empresa" value="<?= $nombreEmpresa ;?>" required>
						</div>
						<div>
							<label>Razon Social : </label>
							<input type="text" name="txtSocial" id="txtSocial" placeholder="Razon social" value="<?= $razonSocial ;?>" required>
						</div>
						<div>
							<label>Telefono : </label>
							<input type="text" name="txtFonoEmpresa" id="txtFonoEmpresa" placeholder="Numero de Telefono" value="<?= $fonoEmpresa ;?>" required>
						</div>
						<div>
							<label>Correo electronico : </label>
							<input type="email" name="txtEmaiEmpresa" id="txtEmaiEmpresa" placeholder="Correo electronico" value="<?= $emailEmpresa ;?>" required>
						</div>
						<div>
							<label>Direccion : </label>
							<input type="text" name="txtDirEmpresa" id="txtDirEmpresa" placeholder="Direccion de la empresa" value="<?= $dirEmpresa ;?>" required>
						</div>
						<div>
							<label>IVA (%) </label>
							<input type="text" name="txtIva" id="txtIva" placeholder="Impuesto al valor agregado (IVA)" value="<?= $iva ;?>" required>
						</div>
						<div class="alertFormEmpresa" style="display:none"></div>
						<div>
							<button type="submit" class="btn-new btnChangePass"><i class="far fa-save"></i> Guardar Datos</button>
						</div>
				
					</form>
				</div>

			<?php
			}
			?>	
			</div>
		</div>


	




	</section>

	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>