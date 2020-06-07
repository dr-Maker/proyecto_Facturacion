
$(document).ready(function(){

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change",function(){
    	var uploadFoto = document.getElementById("foto").value;
        var foto       = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
            if(uploadFoto !='')
            {
                var type = foto[0].type;
                var name = foto[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png')
                {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                    $("#img").remove();
                    $(".delPhoto").addClass('notBlock');
                    $('#foto').val('');
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        $("#img").remove();
                        $(".delPhoto").removeClass('notBlock');
                        var objeto_url = nav.createObjectURL(this.files[0]);
                        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
                        $(".upimg label").remove();
                        
                    }
              }else{
              	alert("No selecciono foto");
                $("#img").remove();
              }              
    });

    $('.delPhoto').click(function(){
    	$('#foto').val('');
    	$(".delPhoto").addClass('notBlock');
        $("#img").remove();
        if($("#foto_actual") && $("#foto_remove")){
            $("#foto_remove").val('imgProducto.png');
        }
        

    });

      //--------------------- Add Producto  ---------------------

   $('.add_product').click(function(e){
       e.preventDefault();
        var producto = $(this).attr('product');
        var action = 'infoProducto';

    $.ajax({
        url:'ajax.php',
        type:'POST',
        async: true,
        data: {action:action,producto:producto},

        success: function(response){
            if(response != 'Error'){

               //var info = JSON.parse(JSON.stringify(response));
               var info = JSON.parse(response);
              
                //$('#producto_id').val(info.codproducto);
                //$('.nameProducto').html(info.descripcion);
                //console.log(info);
               
                $('.bodyModal').html('<form action="" method="post" name="addProduct" id="addProduct" onSubmit="event.preventDefault(); sendDataProduct();">'+
                                    '<h1><i class="fas fa-people-carry" style="font-size:30pt;"></i>+<br>'+
                                    'Agregar Producto</h1>'+
                                    '<h2 class="nameProducto">'+info.descripcion+'</h2>'+
                                    '<br>'+
                                    '<input type="number" name="cantidad" id="txtCantidad" placeholder="Cantdad del Producto" require>'+
                                    '<br>'+
                                    '<input type="number" name="precio" id="txtPrecio" placeholder="Precio del Producto" require>'+
                                    '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'" require>'+
                                    '<input type="hidden" name="action" value="addProduct" require>'+
                                    '<div class="alert alertAddProduct"></div>'+
                                    '<button type="submit" class="btn-new"><i class="fas fa-plus"></i> Agregar</button>'+
                                    '<a href="#" class="btn-aceptar closeModal" onClick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>'+
                                     '</form>');


            }
        },
        error: function(error){
            console.log(error);
        }

    });

        $('.modal').fadeIn();
   });   

   // ------------------------Delete Product-----------------------------

   $('.delete_product').click(function(e){
    e.preventDefault();
     var producto = $(this).attr('product');
     var action = 'infoProducto';

 $.ajax({
     url:'ajax.php',
     type:'POST',
     async: true,
     data: {action:action,producto:producto},

     success: function(response){
         if(response != 'Error'){

            
            var info = JSON.parse(response);
           
             //$('#producto_id').val(info.codproducto);
             //$('.nameProducto').html(info.descripcion);
             //console.log(info);
            
             $('.bodyModal').html('<form action="" method="post" name="formDelProduct" id="formDelProduct" onSubmit="event.preventDefault(); delProduct();">'+
                                 '<h1><i class="fas fa-people-carry" style="font-size:30pt;"></i>+<br>'+
                                 'Eliminar Producto</h1>'+
                                 '<p>¿Estas seguro de Eliminiar el siguiente Producto?</p>'+
                                 '<h2 class="nameProducto">'+info.descripcion+'</h2>'+
                                 '<br>'+
                                 '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'" require>'+
                                 '<input type="hidden" name="action" value="delProduct" require>'+
                                 '<div class="alert alertAddProduct"></div>'+
                                 '<a class="btn-cancelar" onClick="closeModal();" href="#"><i class="fas fa-ban"></i> Cerrar</a>'+
                                 '<button class="btn-aceptar" type="submit" ><i class="fas fa-trash-alt"></i> Eliminar</button>'+
                                  '</form>');

         }
     },
     error: function(error){
         console.log(error);
     }

 });

     $('.modal').fadeIn();
});   

    $('#search_proveedor').change(function(e){
        e.preventDefault();

        var sistema = getUrl();
        location.href = sistema+'buscarProducto.php?proveedor='+$(this).val(); 

    }); 

    $('.btn_new_cliente').click(function(e){
         e.preventDefault();
         $('#nombre_cliente').removeAttr('disabled');
         $('#fono_cliente').removeAttr('disabled');
         $('#dir_cliente').removeAttr('disabled');   

         $('#div_registro_cliente').slideDown();
    });

    // buscar cliente
    $('#rut_cliente').keyup(function(e){
        e.preventDefault();
        var cl = $(this).val();
        var action = 'searchCliente';

        $.ajax({
            url:'ajax.php',
            type:'POST',
            async: true,
            data: {action:action,cliente:cl},
       
            success: function(response){
                
                if(response==0){

                    $('#idCliente').val('');    
                    $('#nombre_cliente').val('');
                    $('#fono_cliente').val('');
                    $('#dir_cliente').val('');

                    //Mostrar el boton Agregar
                    $('.btn_new_cliente').slideDown();
                }else{
                    
                    var data = $.parseJSON(response);
                    $('#idCliente').val(data.idcliente);    
                    $('#nombre_cliente').val(data.nombre);
                    $('#fono_cliente').val(data.telefono);
                    $('#dir_cliente').val(data.direccion);
                    //Ocultar el boton Agregar

                    $('.btn_new_cliente').slideUp();

                    //bloqueo Campos
                    $('#nombre_cliente').attr('disabled','disabled');
                    $('#fono_cliente').attr('disabled','disabled');
                    $('#dir_cliente').attr('disabled','disabled');
                      //Ocultar el boton guardar

                      $('#div_registro_cliente').slideUp();

                }    

            },
            error: function(error){
                console.log(error);
            }
       
        });
       
    });

    //crear Cliente - VENTA

    $('#formNuevoCliente').submit(function(e){
        e.preventDefault();

        $.ajax({
            url:'ajax.php',
            type:'POST',
            async: true,
            data: $('#formNuevoCliente').serialize(),
       
            success: function(response){

                if(response != 'error'){
                    // Agregar el ID al input Hidden
                    $('#idCliente').val(response);

                    //Bloquear los campos 
                    $('#nombre_cliente').attr('disabled','disabled');
                    $('#fono_cliente').attr('disabled','disabled');
                    $('#dir_cliente').attr('disabled','disabled');

                    // bloquear el boton Agregar
                    $('.btn_new_cliente').slideUp();
                    //Oultar el boton Guardar
                    $('#div_registro_cliente').slideUp();

                }
        
            },
            error: function(error){
                console.log(error);
            }
       
        });
    });

     //buscar Producto - VENTA

     $('#txt_cod_producto').keyup(function(e){
            e.preventDefault();

            var producto = $(this).val();
            var action = 'infoProducto';
            if(producto != '' ){
                $.ajax({
                    url:'ajax.php',
                    type:'POST',
                    async: true,
                    data: {action:action,producto:producto},
               
                    success: function(response){
                        if(response !='Error' ){

                            var info = JSON.parse(response);
                            // colocar las variables devueltas del JSON en el Form
                            $('#txt_descripcion').html(info.descripcion);
                            $('#txt_existencia').html(info.existencia);
                            $('#txt_cant_producto').val('1');
                            $('#txt_precio').html(info.precio);
                            $('#txt_precio_total').html(info.precio);

                            //Activar la Cantidad
                            $('#txt_cant_producto').removeAttr('disabled');
                            //Mostar el Boton Agregar
                            $('#add_product_venta').slideDown();
                        


                        }else{
                            //Dejar las variables vacias
                            $('#txt_descripcion').html('-');
                            $('#txt_existencia').html('-');
                            $('#txt_cant_producto').val('0');
                            $('#txt_precio').html('0');
                            $('#txt_precio_total').html('0');
                            //bloquear Cantidad 
                            $('#txt_cant_producto').attr('disabled','disabled');
                            // Ocultar el boton Agregar
                            $('#add_product_venta').slideUp();
                        }
                    },
                    error: function(error){
                        console.log(error);
                    }         
                });
            }
   
     });

     //Validar Cantidad del producto antes de Agregar
      $('#txt_cant_producto').keyup(function(e){
     e.preventDefault();
        
     var precio_total = $(this).val() * $('#txt_precio').html();
     var existencia = parseInt($('#txt_existencia').html()); 
     $('#txt_precio_total').html(precio_total);

     //Ocultar el boton Agregar si la cantidad es menor que 1

     if( ($(this).val() <1 || isNaN($(this).val())) || ($(this).val() > existencia) ){

        $('#add_product_venta').slideUp();
     }else{

        $('#add_product_venta').slideDown();
     }
           
     });
        

     //Agregar Producto al Detalle
     $('#add_product_venta').click(function(e){
        e.preventDefault();

        if($('#txt_cant_producto').val() >0){

            var codproducto = $('#txt_cod_producto').val();
            var cantidad = $('#txt_cant_producto').val();
            var action = 'addProductoDetalle';

       
            $.ajax({
                url:'ajax.php',
                type:'POST',
                async: true,
                data: {action:action,producto:codproducto, cantidad:cantidad},
           
                success: function(response){     
                    if(response != 'error'){
                        var info = JSON.parse(response);
                        $('#detalle_venta').html(info.detalle);
                        $('#detalle_totales').html(info.totales);

                        $('#txt_cod_producto').val('');
                        $('#txt_descripcion').html('-');
                        $('#txt_existencia').html('-');
                        $('#txt_cant_producto').val('0');
                        $('#txt_precio').html('0');
                        $('#txt_precio_total').html('0');
                        $('#txt_cant_producto').attr('disabled','disabled');
                        $('#add_product_venta').slideUp();

                   }else{
                    
                         console.log("Sin Datos");   
                   }  
                   viewProcesar();
                    
                },
                error: function(error){
                    console.log(error);
                }         

            })
        }
     });

     //Anular Venta
     $('#btn_anular_venta').click(function(e)
    {
        e.preventDefault();

        var rows = $('#detalle_venta tr').length;
        if (rows > 0)
        {

            var action = 'anularVenta';
            
            $.ajax
            ({
                url:'ajax.php',
                type:'POST',
                async: true,
                data: {action:action},
           
                success: function(response)
                {     
                    console.log(response);
                    if(response != 'error')
                    {
                        location.reload();
                    }
                    
                },
                error: function(error)
                {
                    console.log(error);
                }         

            });
        }
    });

    //Procesar Factura
    $('#btn_facturar_venta').click(function(e)
    {
        e.preventDefault();
        var rows = $('#detalle_venta tr').length;

        if (rows > 0)
        {

            var action = 'procesarVenta';
            var codcliente = $('#idCliente').val();
            
            $.ajax
            ({
                url:'ajax.php',
                type:'POST',
                async: true,
                data: {action:action, codcliente:codcliente},
           
                success: function(response)
                {     

                    if(response != "error")
                    {
                        var info = JSON.parse(response);
                        generarPDF(info.codcliente,info.nofactura)
                        location.reload();
                    }
                    else
                    {
                        console.log("No data");
                    }
                    

                },
                error: function(error)
                {
                    console.log(error);
                }         

            });
        }
    });

    // Modal ELIMINAR FACTURA
    $('.anular_factura').click(function(e){
        e.preventDefault();
        var nofactura = $(this).attr('fac');
        var action = 'infoFactura';

    $.ajax({
        url:'ajax.php',
        type:'POST',
        async: true,
        data: {action:action,nofactura:nofactura},

        success: function(response){
            if(response != 'error'){
                var info = JSON.parse(response);
            
                
                $('.bodyModal').html('<form action="" method="post" name="form_anular_factura" id="form_anular_factura" onSubmit="event.preventDefault(); anularFactura();">'+
                                    '<h1><i class="far fa-calendar-times" style="font-size:30pt;"></i><br>'+
                            
                                    'Eliminar FACTURA </h1>'+
                                    '<p>¿Realmente desea Anular la Factura ?</p>'+
                                    '<br>'+
                                    '<p><strong>Nº '+info.nofactura+'</strong></p>'+
                                    '<p><strong>Monto $'+info.totalfactura+'</strong></p>'+
                                    '<p><strong>Fecha '+info.fecha+'</strong></p>'+
                                    '<input type="hidden" name="action" value="anularFactura">'+
                                    '<input type="hidden" name="no_factura" id="no_factura" value="'+info.nofactura+'" required>'+
                        
                                    '<div class="alert alertAddProduct"></div>'+
                                    '<button class="btn-aceptar" type="submit" ><i class="fas fa-trash-alt"></i> Anular</button>'+
                                    '<a class="btn-cancelar" onClick="closeModal();" href="#"><i class="fas fa-ban"></i> Cerrar</a>'+
                                    
                                    '</form>');

                                
            }
        },
        error: function(error){
            console.log(error);
        }

    });

        $('.modal').fadeIn();
    });   

    // VER FACTURA
    $('.view_factura').click(function(e){
        e.preventDefault();
        var codcliente = $(this).attr('cl');
        var nofactura = $(this).attr('f');
        generarPDF(codcliente,nofactura);
    });


}); // fin Ready

function getUrl(){
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));

}

function sendDataProduct(){
    $('.alertAddProduct').html('');
    
    $.ajax({
        url:'ajax.php',
        type:'POST',
        async: true,
        data: $('#addProduct').serialize(),

        success: function(response){
           console.log(response);
           if(response == 'error'){
                $(".alertAddProduct").html('<p style="color : red;"> Error al Agregar Producto. </p>');
           }else{

            var info = JSON.parse(response);
            $('.row'+info.producto_id+' .cellPrecio').html(info.nuevo_precio);
            $('.row'+info.producto_id+' .cellExistencia').html(info.nueva_existencia);
            $('#txtCantidad').val('');
            $('#txtPrecio').val('');
            $(".alertAddProduct").html('<p> Producto Agregado Correctamente. </p>');
          
        }
        },
        error: function(error){
            console.log(error);
        }

    });

}

// Eliminar Producto
function delProduct(){
    $('.alertAddProduct').html('');
    var pr = $('#producto_id').val();
    $.ajax({
        url:'ajax.php',
        type:'POST',
        async: true,
        data: $('#formDelProduct ').serialize(),

            
        success: function(response){
            console.log(response);

             
           console.log(response);
           if(response == 'error'){
                $(".alertAddProduct").html('<p style="color : red;"> Error al Eliminar Producto. </p>');
           }else{
            $('.row'+pr).remove();
            $('#formDelProduct .btn-aceptar').remove();
            $(".alertAddProduct").html('<p> Producto Eliminar Correctamente. </p>');
          
        }
        
        },
        error: function(error){
            console.log(error);
        }

    });

}

function closeModal(){
    
    $('.alertAddProduct').html('');
    $('#txtCantidad').val('');
    $('#txtPrecio').val('');
    $('.modal').fadeOut();

}

function searchForDetalle(id){
    var action = 'searchForDetalle';
    var user = id;

    $.ajax({
        url:'ajax.php',
        type:'POST',
        async: true,
        data: {action:action, user:user},
   
        success: function(response){
           

            if (response =='lista_vacia')
            {
           
                $('#detalle_venta').html('');
                $('#detalle_totales').html('');
            }
            
            if((response != 'error') && (response !='lista_vacia'))
            {
               
                
              
                 var info = JSON.parse(response);
                $('#detalle_venta').html(info.detalle);
                $('#detalle_totales').html(info.totales);
                
           }
           else
           {
              
                 console.log("Sin Datos...");   
           }  
        
           viewProcesar();
           
        
        },
        error: function(error){
            console.log(error);
        }           
    })

}

function delProductDetalle(correlativo){

    var action = 'delProductoDetalle';
    var id_detalle = correlativo;

    $.ajax({
        url:'ajax.php',
        type:'POST',
        async: true,
        data: {action:action, id_detalle:id_detalle},
   
        success: function(response)
        { 
            if (response =='lista_vacia')
            {
           
                $('#detalle_venta').html('');
                $('#detalle_totales').html('');
            }
      
    
            
           if((response != 'error') && (response !='lista_vacia'))
           {
           
            var info = JSON.parse(response);
            

            
            $('#detalle_venta').html(info.detalle);
            $('#detalle_totales').html(info.totales);

            $('#txt_cod_producto').val('');
            $('#txt_descripcion').html('-');
            $('#txt_existencia').html('-');
            $('#txt_cant_producto').val('0');
            $('#txt_precio').html('0');
            $('#txt_precio_total').html('0');
            $('#txt_cant_producto').attr('disabled','disabled');
            $('#add_product_venta').slideUp();
            
           }
           else
           {
               $('#detalle_venta').html('');
               $('#detalle_totales').html('');
           }

           viewProcesar();
           
           
           
        },
        error: function(error){
            console.log(error);

        
        }      
             

    })

}

function viewProcesar(){
    if( $('#detalle_venta tr').length > 0)
    {   
        $('#btn_facturar_venta').show();

    }else
    {
        $('#btn_facturar_venta').hide();
    }
}

function generarPDF(cliente,factura){
    var ancho = 1000;
    var alto = 800;

    var x = parseInt((window.screen.width/2) - (ancho/2));
    var y =  parseInt((window.screen.height/2) - (alto/2));
    
    $url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura;
    window.open($url,"Factura","left="+x+",height="+alto+",width"+ancho+",scrolbar=si,location=no,resizable=si,menubar=no")
}

function anularFactura(){
    var nofactura = $('#no_factura').val();
    var action = 'anularFactura';

    $.ajax({
        url:'ajax.php',
        type:'POST',
        async: true,
        data: {action:action, nofactura:nofactura},
   
        success: function(response)
        { 
            if(response == 'error')
            {
                $('.alertAddProduct').html('<p style ="color:red;">Error al anular la Factura. </p>');
            }else
            {
                $('#row_'+nofactura+' .estado').html('<span class="anulada">Anulada</span>');
                $('#form_anular_factura .btn-aceptar').remove();
                $('#row_'+nofactura+' .div_factura').html('<button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>');
                $('.alertAddProduct').html('<p>Factura anulada.</p>');

            }
           
           
        },
        error: function(error){
            console.log(error);
        
        }                   

    })


}
