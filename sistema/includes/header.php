
<?php

if(empty($_SESSION['s_active'])){

    header("location: ../");
}

?>


<header>
		<div class="header">
			
			<h1>Sistema Facturación</h1>
			<div class="optionsBar">
				<p>Santiago, <?php echo fechaC(); ?> </p>
				<span>|</span>
				<span class="user"><?php echo $_SESSION['s_user'].' -'.$_SESSION['s_rol'] .' - ' .$_SESSION['s_email']; ?></span>
				<img class="photouser" src="img/user.png" alt="Usuario">
				<a href="salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
			</div>
		</div>
	
    <?php
    include_once("navegacion.php");
    ?>
	</header>

<div class="modal">
	<div class="bodyModal">
		
	</div>
</div>
