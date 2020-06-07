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
	<title>Sisteme Ventas</title>
</head>
<body>
	<?php
	include_once("./includes/header.php");
	?>
	<section id="container">
		
		<div class="divContainer">
			<div>
				<h1 class="titlePanelControl">Panel de Control</h1>
			</div>
			<div class="dashboard">
				
				<a href="">
					<i class="fas fa-users"></i>
					<p>
						<strong>Usuarios</strong><br>
						<span>400</span>
					</p>
				</a>
			
		
				<a href="">
					<i class="fas fa-users"></i>
					<p>
						<strong>Clientes</strong><br>
						<span>2000</span>
					</p>
				</a>
			
				<a href="">
					<i class="fas fa-users"></i>
					<p>
						<strong>Proveedores</strong><br>
						<span>200</span>
					</p>
				</a>

				<a href="">
					<i class="fas fa-users"></i>
					<p>
						<strong>Productos</strong><br>
						<span>1500</span>
					</p>
				</a>
				<a href="">
					<i class="fas fa-users"></i>
					<p>
						<strong>Ventas</strong><br>
						<span>500</span>
					</p>
				</a>

			</div>
		</div>

		<div class="divInfoSistem">
			<div>
				<h1 class="titlePanelControl">Configuracion</h1>
			</div>
		</div>



	</section>

	<?php
	include_once("./includes/footer.php");
	?>
	
</body>
</html>