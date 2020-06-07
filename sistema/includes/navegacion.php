<nav>
			<ul>
				<li><a href="index.php"><i class="fas fa-home"></i> Inicio</a></li>


				<?php
					if($_SESSION['s_rol']==1 ){

				?>

				<li class="principal">

			
					<a href="#"><i class="fas fa-users"></i> Usuarios</a>
					<ul>
						<li><a href="registroUser.php"><i class="fas fa-user-plus"></i> Nuevo Usuario</a></li>
						<li><a href="listaUser.php"><i class="fas fa-user-friends"></i> Lista de Usuarios</a></li>
					</ul>
				</li>

				<?php
					}
				?>
				<li class="principal">
					<a href="#"><i class="far fa-address-card"></i> Clientes</a>
					<ul>
						<li><a href="registrocliente.php"><i class="fas fa-plus-circle"></i> Nuevo Cliente</a></li>
						<li><a href="listaCliente.php"><i class="far fa-address-book"></i> Lista de Clientes</a></li>
					</ul>
				</li>

				<?php
					if($_SESSION['s_rol']==1 || $_SESSION['s_rol']==2){

				?>
				<li class="principal">
					<a href="#"><i class="fas fa-truck"></i>  Proveedores</a>
					<ul>
						<li><a href="registroProveedor.php"><i class="fas fa-dolly"></i> Nuevo Proveedor</a></li>
						<li><a href="listaProveedor.php"><i class="fas fa-clipboard-list"></i> Lista de Proveedores</a></li>
					</ul>
				</li>

				
				<?php
					}
				?>

				<li class="principal">
					<a href="#"><i class="fas fa-box-open"></i> Productos</a>
					<ul>
				<?php
				if($_SESSION['s_rol']==1 || $_SESSION['s_rol']==2){
				?>	
						<li><a href="registroProducto.php"><i class="fas fa-shopping-cart"></i> Nuevo Producto</a></li>
				<?php
					}
				?>	
						<li><a href="listaProducto.php"><i class="fas fa-cash-register"></i> Lista de Productos</a></li>
					</ul>
				</li>

				<li class="principal">
					<a href="#"><i class="fas fa-file-invoice"></i> Ventas</a>
					<ul>
						<li><a href="nuevaVenta.php"><i class="fas fa-money-check-alt"></i> Nueva Venta</a></li>
						<li><a href="ventas.php"><i class="far fa-chart-bar"></i> Ventas</a></li>
					</ul>
				</li>
			</ul>
		</nav>