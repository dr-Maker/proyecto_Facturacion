<?php
    session_start();
    include_once("../conexion.php");
    //echo md5($_SESSION['s_iduser']);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php
    include_once("./includes/script.php");
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Venta</title>
</head>
<body>
    <?php
	    include_once("./includes/header.php");
    ?>
    <section id="container">
        <div class="title_page">
            <h1> <i class="far fa-credit-card fa-2x"></i> Nueva Venta</h1>
        </div>
            <div class="datos_cliente">
                <div class="action_cliente">
                <h4>Datos del Cliente</h4>
                <a href="#" class="btn-new btn_new_cliente"><i class="fas fa-plus-circle"></i> Nuevo Cliente</a>      
            </div>

            <form name="formNuevoCliente" id="formNuevoCliente" class="datos" >
        
                <input type="hidden" name="action" value="addCliente">
                <input type="hidden" name="idCliente" id="idCliente" value="" require>

                <div class="wd30">
                    <label for="rut_cliente">Rut</label>
                    <input type="text" name="rut_cliente" id="rut_cliente" >       
                </div>
                <div class="wd30">
                    <label for="nombre_cliente">Nombre</label>
                    <input type="text" name="nombre_cliente" id="nombre_cliente" disabled required>       
                </div>
                <div class="wd30">
                    <label for="fono_cliente">Telefono</label>
                    <input type="text" name="fono_cliente" id="fono_cliente" disabled required>       
                </div>
                <div class="wd100">
                    <label for="dir_cliente">Direccion</label>
                    <input type="text" name="dir_cliente" id="dir_cliente" disabled required>       
                </div>
                <div class="w100" id="div_registro_cliente">
                    <button type="submit" class="btn_registro"><i class="far fa-save"></i> Guardar</button>
                </div>
            </form>

        </div>
        
      <div class="datos_venta">
            <h4>Datos de Venta</h4>
            <div class="datos">
                <div class="wd50">
                    <label for="">Vendedor</label>
                    <p><?php echo $_SESSION['s_nombre']; ?></p>
                </div>
                <div class="wd50">
                    <label for="">Acciones</label>
                    <div class="acciones_venta">
                        <a href="#" class="btn-aceptar textcenter" id="btn_anular_venta"><i class="fas fa-ban"></i> Anular</a>
                        <a href="#" class="btn-new textcenter" id="btn_facturar_venta" style="display:none;" ><i class="fas fa-save"></i> Procesar</a>
                    </div>
                </div>
            </div>
      </div>

      <table class="tbl_venta">
          <thead>
                <tr>
                    <th width="100px">Codigo</th>
                    <th>Descripcion</th>
                    <th>Existencia</th>
                    <th width="100px">Cantidad</th>
                    <th class="textrigh">Precio</th>
                    <th class="textrigh">Precio Total</th>
                    <th>Accion</th>   
                </tr>
                <tr>
                    <td><input type="text" name="txt_cod_producto" id="txt_cod_producto"></td>
                    <td id="txt_descripcion">-</td>
                    <td id="txt_existencia">-</td>
                    <td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
                    <td class="textright" id="txt_precio">0</td>
                    <td class="textright" id="txt_precio_total">0</td>
                    <td><a href="#" id="add_product_venta" class="link_add"><i class="fas fa-plus"></i> Agregar</a></td>
                </tr>
                <tr>
                    <th>Codigo</th>
                    <th colspan="2">Descripcion</th>
                    <th>Cantidad</th>
                    <th class="textright">Precio</th>
                    <th class="textright">Precio Total</th>
                    <th>Accion</th>
                </tr>
          </thead>
          <tbody id="detalle_venta">

          <!-- contenido Ajax -->


          </tbody>
          <tfoot id="detalle_totales">
          
          <!-- contenido Ajax -->
          
          </tfoot>
      </table>

    </section>

    <?php
    include_once("./includes/footer.php");
    ?>
    
    <script type="text/javascript">
    $(document).ready(function(){
        var usuarioId=<?php echo $_SESSION['s_iduser']; ?>;
        searchForDetalle(usuarioId);
    });
</script>

</body>
</html>