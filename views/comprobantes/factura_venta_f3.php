<?php
    /* este caso sin encabezado,  se da solo cuando no es electronica, ya que se necesita imprimir el encabezado impreso */ 
        if(get_settings('PRINT_HEAD_FACT') ){ 
//            $logo = Image(base_url('img/logo1.png'), array('alt'=>'master pc'));
//            echo tagcontent('div', $logo, array('class'=>'col-md-6'));
        
            $doc_label = 'FACT.';
            if($factura->puntoventaempleado_tiposcomprobante_cod == '04'){
                $doc_label = 'N.D.C.';
                echo tagcontent('div', '<strong>APLICADA A FACT. No.</strong>'.$factura->fact_ndc, array('class'=>'col-md-6'));                
            }
            
            if($factura->estado == 1){
                echo tagcontent('div', '<strong>'.$doc_label.' PENDIENT No.</strong>'.$factura->codigofactventa, array('class'=>'col-md-6'));
            }elseif($factura->estado == 2){
                if($factura->autorizado_sri == 2){
                    echo tagcontent('div', '<strong>'.$doc_label.' No.</strong>'.$factura->establecimiento.$factura->puntoemision.'-'.str_pad($factura->secuenciafactventa, 9, '0', STR_PAD_LEFT), array('class'=>'col-md-6'));
                }else{
                    echo tagcontent('div', '<strong>PRE-'.$doc_label.' No.</strong>'.$factura->codigofactventa, array('class'=>'col-md-6'));
                }
            }            
        ?>
            <div class="col-md-6 pull-right">
                R.U.C.: <?php echo $empresa->ruc; ?>   <br>
                <?php 
                if($punto_venta->electronic == 1){
                    echo tagcontent('span', 'Fecha Reg.: '.$factura->fechaCreacion, array('class'=>''));
                }else{
                    echo tagcontent('span', 'Fecha Reg.: '.$factura->fechaarchivada, array('class'=>''));
                }
                ?>
            </div>
            <div  class="col-md-6">
                <?php echo $empresa->nombreComercial; ?> <br>
                Dirección Matriz:  <?php echo $empresa->direccion; ?>  <br>
                Dir Sucursal: <br>
                Contribuyente <?php echo $empresa->clase; ?> Nro: <?php echo $empresa->resolucion; ?> <br>
                OBLIGADO A LLEVAR CONTABILIDAD:  <?php echo $empresa->contabilidad; ?><br>
            </div>
        <?php
        }else{
            if ($factura->autorizado_sri == 2) { /* 2 cuando es de un punto no electronico */
                echo 'FACTURA No: ' . $factura->establecimiento . $factura->puntoemision . '-' . str_pad($factura->secuenciafactventa, 9, '0', STR_PAD_LEFT);
                echo 'FECHA: ' . $factura->fechaarchivada;
            } else { 
                echo '<span style="font-family:monospace">';
                    echo 'PRE-FACTURA No: ' . $factura->establecimiento . $factura->puntoemision . '-' . str_pad($factura->secuenciafactventa, 9, '0', STR_PAD_LEFT);
                    echo LineBreak(1);
                    echo 'FECHA: ' . $factura->fechaCreacion;
                    echo LineBreak(1);
                echo '</span>';
            }
        }
    echo '<span style="font-family:monospace">';
 ?>
-------------------------------<br>
<?PHP  
     
            echo 'CLIENTE: ' .get_short_string($cliente_data[0]->apellidos.' '.$cliente_data[0]->nombres, 28, false) ;
            echo lineBreak(1);
            echo 'CI/RUC: ' . $cliente_data[0]->PersonaComercio_cedulaRuc;
            echo lineBreak(1);
    ?>
-------------------------------<br>
<?PHP
            echo 'Cant.'. '&nbsp;|&nbsp;';
            echo 'Desc.'. '&nbsp;|&nbsp;';
            echo 'P. Unit'. '&nbsp;|&nbsp;';
            echo 'P. Total';
            echo lineBreak(1);
?>
-------------------------------<br>
<?PHP
    echo '</span>';
        if (sizeof($factura_det) > 0) {
            $desc_prod="";
            foreach ($factura_det as $d) {
                /*Detalle de la Factura*/
                echo '<span style="font-family:monospace">';
                    
                    echo $d->itemcantidad. '&nbsp;|';
                    if (!empty($d->detalle)) {
                        $desc_prod=  $d->detalle;
                    } else {
                        $desc_prod=$this->generic_model->get_val_where('billing_producto', array('billing_producto.codigo' => $d->Producto_codigo), 'nombreUnico', null, -1) ;
                        
                    }
                    echo get_short_string($desc_prod, 16, false). '&nbsp;|';
                    echo  number_decimal($d->itempreciobruto) . '&nbsp;|';
                    echo  number_decimal($d->itemprecioxcantidadneto);
                    echo LineBreak(1);
?>
-------------------------------<br>
<?PHP
                echo '</span>';
            }
        }
            echo '<span style="font-family:monospace">';
                echo 'SUBTOTAL 12%: ' . number_decimal($factura->tarifadocebruto);
                echo LineBreak(1);
                echo 'SUBTOTAL 0%: ' . number_decimal($factura->tarifacerobruto);
                echo LineBreak(1);
                echo 'Subtotal: ' . number_decimal($factura->subtotalBruto);
                echo LineBreak(1);
                echo 'TOTAL Recargo: ' . number_decimal($factura->recargovalor);
                echo LineBreak(1);
                echo 'TOTAL Descuento: ' . number_decimal($factura->descuentovalor);
                echo LineBreak(1);
                echo 'Subtotal Neto: ' . number_decimal($factura->subtotalNeto);
                echo LineBreak(1);
                echo 'ICE: ' . number_decimal($factura->iceval);
                echo LineBreak(1);
                echo 'IVA 12%: ' . number_decimal($factura->ivaval);
                echo LineBreak(1);
                echo 'VALOR TOTAL: ' . number_decimal($factura->totalCompra);
                echo LineBreak(1);
?>
-------------------------------<br>
<?PHP                
            /*Datos Cliente*/
                echo 'Dirección:'.$cliente_data[0]->direccion;
                echo LineBreak(1);
                echo 'Teléfono:'.$cliente_data[0]->telefonos; 
                echo LineBreak(1);
                echo 'Email:'.$cliente_data[0]->email;
                echo LineBreak(1);

           /*Datos Venta Master PC*/
                echo 'Tipo Pago: ' . $datos_observ['tip_pag'] . " " . $factura->tipoprecio;
                echo LineBreak(1);
                echo 'Bodega: ' . $datos_observ['bodega'];
                echo LineBreak(1);
                echo 'Vendedor: ' . $datos_observ['emp_vend'];
                echo LineBreak(1);

                if (!empty($datos_observ['tecnico'])) {
                    echo 'Técnico: ' . $datos_observ['tecnico'];
                    echo LineBreak(1);
                }
                echo 'Usuario: ' . $datos_observ['user'];
                echo LineBreak(1);

            echo '</span>';
        ?>                 
        <span style="font-family:monospace">
               <br>
               ------------------------------<br>
                <?php echo $cliente_data[0]->nombres . ' ' . $cliente_data[0]->apellidos; ?><br>
                Firma Cliente<br><br>

                ------------------------------<br>
                <?php echo $datos_observ['emp_vend']; ?><br>
                Firma Vendedor
        </span>
