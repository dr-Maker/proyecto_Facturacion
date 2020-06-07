<?php

session_start();
include_once("../conexion.php");

$busqueda ='';
$fecha_de ='';
$fecha_a = '';

if(!empty($_REQUEST['busqueda']))
{
    if(!is_numeric($_REQUEST['busqueda']))
    {
        header("location: ventas.php");
    }

    $busqueda = strtolower($_REQUEST['busqueda']);
    $where = "nofactura = $busqueda";
    $buscar = "busqueda = $busqueda";
}

if(!empty($_REQUEST['fecha_de']) && !empty($_REQUEST['fecha_a']))
{
  $fecha_de = $_REQUEST['fecha_de'];
  $fecha_a = $_REQUEST['fecha_a'];

  $buscar = '';

    if($fecha_de > $fecha_a)
    {
        header("location: ventas.php");
    }
    else if($fecha_de == $fecha_a)
    {
        $where ="fecha LIKE '$fecha_de%'";
        $buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
    }
    else
    {
        $f_de = $fecha_de.' 00:00:00'; 
        $f_a = $fecha_a.' 23:59:59';
        $where = "fecha BETWEEN '$f_de' AND '$f_a' ";
        $buscar = " fecha_de$fecha_de&fecha_a=$fecha_a ";
    }

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php
	include_once("./includes/script.php");
	?>
	<title>lista de Ventas </title>
</head>
<body>
	<?php
	include_once("./includes/header.php");
	?>
	<section id="container">

	<div class="listaUser"
	>
		<h1><i class="fas fa-file-invoice"></i> Lista de Ventas</h1>

	<a href="nuevaVenta.php" class="btn-new"><i class="fas fa-shopping-cart"></i> Nueva Venta </a>

	<form action="buscarVenta.php" method="get" class="formBusqueda">
		<input type="text" name="busqueda" id="busqueda" placeholder="Nº Factura">
		<button type="submit"  class="btn-busqueda"><i class="fas fa-search"></i></button>
	</form>

    <div>
        <h5>Buscar por fecha</h5>
        <form action="buscarVenta.php" method="get" class="form_search_date">
            <label>De: </label>
            <input type="date" name="fecha_de" id="fecha_de" required>
            <label> A </label>
            <input type="date" name="fecha_a" id="fecha_a" required>
            <button type="submit" class="btn-view"><i class="fas fa-search"></i></button>
        </form>
    
    
    </div>

			<table>

				<tr>
					<th>Nº</th>
                    <th>Fecha / Hora</th>
					<th>Cliente</th>
					<th>Vendedor</th>
					<th>Estado</th>
                    <th class="textright" >Total Factura</th>
					<th class="textright">Acciones</th>
				</tr>
<?php

	// paginador **
    $sqlCantidadRegistros = "SELECT COUNT(*) as totalRegistro from factura where estatus != 10 ";
     
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

    $queryselect = "SELECT f.nofactura, f.fecha, f.totalfactura, f.codcliente, f.estatus,
                            u.nombre as vendedor,
                            cl.nombre as cliente
                    FROM factura as f
                    INNER JOIN usuario u
                    ON f.usuario = u.idusuario
                    INNER JOIN cliente cl
                    ON f.codcliente = cl.idcliente
                    WHERE f.estatus !=10
                    ORDER BY f.fecha DESC LIMIT $desde,$cantidadPorPagina
                    ";
 
    $queryselectSend = mysqli_query($conection,$queryselect);
  
	$result = mysqli_num_rows($queryselectSend);

    if($result>0)
    {

        while($data = mysqli_fetch_array($queryselectSend))
        {


            if($data = mysqli_fetch_array($queryselectSend))
            {
                if($data["estatus"] == 1)
                {
                    $estado = '<span class="pagada">Pagada</span> ';
                }else
                {
                    $estado = '<span class="anulada">Anulada</span> ';
                }
            }
		
		?>
			<tr id="row_<?php echo $data["nofactura"];?>">
                <td><?php echo $data['nofactura'] ;?></td>
                <td><?php echo $data['fecha']; ?></td>
                <td><?php echo $data['cliente'] ;?></td>
                <td><?php echo $data['vendedor']; ?></td>
                <td class="estado"><?php echo $estado; ?></td>   
                <td class="textright totalfactura"><span>$.</span><?php echo $data['totalfactura']; ?></td>


                <td>
                    <div class="div_acciones">
                            <div>
                                <button class="btn_view view_factura" type="button" cl="<?php echo $data['codcliente'];?>" f="<?php echo $data['nofactura']; ?>"><i class="far fa-eye"></i></button>
                            </div>
                        

                        <?php
                            if($_SESSION["s_rol"] == 1 || $_SESSION["s_rol"] == 2)
                            {
                                if($data["estatus"] == 1)
                                {
                        ?>

                        <div class="div_factura">
                            <button type="button" class="btn_anular anular_factura" fac="<?php echo $data['nofactura']; ?>"><i class="fas fa-ban"></i></button>
                        </div>
                    <?php
                                } 
                                else
                                {
                    ?>

                        <div class="div_factura">
                            <button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>  
                        </div>
                    <?php
                                }
                            }
                    ?>
                    </div>
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