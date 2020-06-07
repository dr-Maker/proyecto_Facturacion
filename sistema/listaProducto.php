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
	<title>Lista de Producto</title>
</head>
<body>
	<?php
	include_once("./includes/header.php");
	?>
	<section id="container">

	<div class="listaUser"
	>
		<h1><i class="fas fa-boxes fa-2x"></i></i> Lista de Productos</h1>

	<a href="registroProducto.php" class="btn-new"><i class="fas fa-shopping-cart"></i>+ Crear Producto </a>

	<form action="buscarProducto.php" method="get" class="formBusqueda">
		<input type="text" name="busqueda" id="busqueda" placeholder="buscar">
		<button type="submit"  class="btn-busqueda"><i class="fas fa-search"></i></button>
	</form>


			<table>

				<tr>
					<th>Codigo</th>
                    <th>Descripcion</th>
					<th>Precio</th>
					<th>Cantidad</th>
					<th>
					<?php
                        $queryProveedor = "SELECT codproveedor, proveedor FROM proveedor WHERE estatus = 1 
                        ORDER BY proveedor ASC";
                        $queryProveedorSend =mysqli_query($conection,$queryProveedor);
                        $resultProveedor = mysqli_num_rows($queryProveedorSend);    
                    ?>

                    <select name="proveedor" id="search_proveedor">
					<option value="" selected>PROVEEDOR</option>
                    <?php
                        if($resultProveedor > 0){
                          while($proveedorResult = mysqli_fetch_array($queryProveedorSend)){                
                    ?>

                        <option value="<?php echo $proveedorResult['codproveedor']; ?>"><?php echo $proveedorResult['proveedor']; ?></option>
                     <?php
                            }
                        }
                     ?>

                        
                    </select>

					</th>
					<th>Foto</th>
                    <th>Acciones</th>
				</tr>
<?php

	// paginador **
    $sqlCantidadRegistros = "SELECT COUNT(*) as totalRegistro from producto where status = 1";
     
    $sqlRegistro = mysqli_query($conection,$sqlCantidadRegistros);
   
    $resultRegistros = mysqli_fetch_array($sqlRegistro);

	$totalRegistros = $resultRegistros['totalRegistro'];


	$cantidadPorPagina = 8;

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

	$queryselect = "SELECT producto.codproducto, descripcion, precio, existencia, proveedor.proveedor as nameprov, foto
	FROM producto
    INNER JOIN proveedor
    ON producto.proveedor = proveedor.codproveedor
	WHERE status = 1 
	ORDER BY codproducto DESC
    LIMIT $desde,$cantidadPorPagina ";
 
    $queryselectSend = mysqli_query($conection,$queryselect);
    mysqli_close($conection);
	$result = mysqli_num_rows($queryselectSend);

	if($result>0){

		while($data = mysqli_fetch_array($queryselectSend)){
            if($data['foto'] !='imgProducto.png')
            {
                $foto = 'img/uploads/'.$data['foto'];
            }
            else
            {
                $foto = 'img/'.$data['foto'];
            }
		?>
			<tr class="row<?php echo $data['codproducto'] ;?>">
			<td><?php echo $data['codproducto'] ;?></td>
			<td><?php echo $data['descripcion']; ?></td>
			<td class="cellPrecio"><?php echo $data['precio'] ;?></td>
			<td class="cellExistencia" ><?php echo $data['existencia']; ?></td>
			<td><?php echo $data['nameprov']; ?></td>	
            <td class="ImgProducto"><img src="<?php echo $foto; ?>" alt="<?php echo $data['descripcion']; ?>"></td>		
			<td>
                
            <?php
				if($_SESSION['s_rol'] == 1 || $_SESSION['s_rol'] == 2 ){
				?>
                <a class="link_editar add_product" product="<?php echo $data['codproducto'];?>" href="#"><i class="fas fa-plus"></i> Agregar</a>
				
                <a class="link_editar" href="editarProducto.php?id=<?php echo $data['codproducto'];?>"><i class="far fa-edit"></i> Editar</a>

				<a class="link_eliminar delete_product" href="#" product="<?php echo $data['codproducto'];?>" ><i class="fas fa-trash-alt"></i> Eliminar</a> 
					
			</td>

            <?php
					}
				?>

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
