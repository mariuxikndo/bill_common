        <?php
               
        echo Open('div', array('id'=>'factventaprint_view','class'=>'container', 'style'=>'font-family:monospaced;font-size:11px'));                
        $logo = Image(base_url('img/logo1.png'), array('alt'=>'master pc'));
        echo tagcontent('div', $logo, array('class'=>'col-md-6'));
        
            if($factura->estado == 1){
                echo tagcontent('div', '<strong>FACT. PENDIENT No.</strong>'.$factura->codigofactventa, array('class'=>'col-md-6'));
            }elseif($factura->estado == 2){
                if($factura->autorizado_sri == 2){
                    echo tagcontent('div', '<strong>FACTURA No.</strong>'.$factura->establecimiento.$factura->puntoemision.'-'.str_pad($factura->secuenciafactventa, 9, '0', STR_PAD_LEFT), array('class'=>'col-md-6'));                    
                }else{
                    echo tagcontent('div', '<strong>PRE-FACTURA No.</strong>'.$factura->codigofactventa, array('class'=>'col-md-6'));
                }
            }
        ?>
        
        <div class="col-md-6 pull-right">
            R.U.C.: 1191732525001 <br>
            FECHA REG.: 
            <?php echo $factura->fechaCreacion; ?>
        </div>
        <div  class="col-md-6">
            MASTERPC CIA LTDA <br>
            Dirección Matriz: Azuay y Olmedo. <br>
            Dir Sucursal: <br>
            Contribuyente especial Nro: 00290 <br>
            OBLIGADO A LLEVAR CONTABILIDAD: SI <br>
        </div>
        <br>
        <table class="table table-striped table-condensed" style="font-size:11px">
            <tr>
                <td>CLIENTE: <?php echo $cliente_data[0]->nombres.' '.$cliente_data[0]->apellidos; ?></td>
                <td>CI/RUC: <?php echo $cliente_data[0]->PersonaComercio_cedulaRuc; ?></td>
            </tr>
        </table>
        <br>
    <?php
        echo Open('table',array('class'=>'table table-striped table-condensed', 'style'=>'font-family:monospaced;font-size:11px'));    
            $thead = array(
                'Cod.',
                'Cant.',
                'Desc.',
                'P. Unit.',
                'Desc.',
                'P. Total'
            );
            echo tablethead($thead);

                if (sizeof($factura_det) > 0){
                    foreach ($factura_det as $d) {
                        echo Open('tr');
                            echo tagcontent('td', $d->Producto_codigo);
                            echo tagcontent('td', $d->itemcantidad);
                            
                            if(!empty($d->detalle)){
                                echo tagcontent( 'td', $d->detalle );                                
                            }else{
                                echo tagcontent( 'td', $this->generic_model->get_val_where('billing_producto', array('billing_producto.codigo' => $d->Producto_codigo), 'nombreUnico', null, -1) );                                
                            }
                            
                            echo tagcontent('td', number_decimal($d->itempreciobruto));
                            echo tagcontent('td', number_decimal($d->descuentofactvalor));
                            echo tagcontent('td', number_decimal($d->itemprecioxcantidadneto));
                        echo Close('tr');
                    }
                }
            echo Close('table');
            echo lineBreak2(1, array('class'=>'clr'));
            echo Open('div',array('class'=>'col-md-9 pull-left'));            
        ?>
                Dirección: <?php echo $cliente_data[0]->direccion; ?><br>
                Teléfono: <?php echo $cliente_data[0]->telefonos; ?><br>
                Email: <?php echo $cliente_data[0]->email; ?><br><br>
                <b>OBSERVACIONES:</b><br>
                <p> <?php echo $factura->observaciones; ?></p>

                <?php 
                    echo tagcontent('div', 'Tipo Pago: '.$datos_observ['tip_pag']." ".$factura->tipoprecio, array('class'=>'col-md-12'));
                    echo tagcontent('div', 'Bodega: '.$datos_observ['bodega'], array('class'=>'col-md-12'));
                    echo tagcontent('div', 'Vendedor: '.$datos_observ['emp_vend'], array('class'=>'col-md-12'));                    
                    
                    if(!empty($datos_observ['tecnico'])){
                        echo tagcontent('div', 'Técnico: '.$datos_observ['tecnico'], array('class'=>'col-md-12'));                        
                    }
                    echo tagcontent('div', 'Usuario: '.$datos_observ['user'], array('class'=>'col-md-12'));
            echo Close('div');
            
            echo Open('div',array('class'=>'col-md-3 pull-right'));
                echo Open('table',array('class'=>'table table-condensed','style'=>'font-size:11px'));
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">SUBTOTAL 12%</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($factura->tarifadocebruto).'</span>' ));
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">SUBTOTAL 0%:</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($factura->tarifacerobruto).'</span>' ));

                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">Subtotal</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($factura->subtotalBruto).'</span>' ));/* Subotal bruto - antes del descuento */
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">TOTAL Recargo</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($factura->recargovalor).'</span>' ));
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">TOTAL Descuento</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($factura->descuentovalor).'</span>' ));
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">Subtotal Neto</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($factura->subtotalNeto).'</span>' ));/* Subotal bruto - antes del descuento */                    
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">ICE</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($factura->iceval).'</span>' ));
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">IVA 12%</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($factura->ivaval).'</span>' ));

                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">VALOR TOTAL:</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($factura->totalCompra).'</span>' ));
                echo Close('table');
            echo Close('div');
            
            echo LineBreak(3, array('class'=>'clr'));           
        ?>                 
        <div class="col-md-12">
            <table class="table text-center">
                <tr>
                    <td>
                        <div>
                            <hr>
                            <?php echo $cliente_data[0]->nombres.' '.$cliente_data[0]->apellidos; ?><br>
                            <b>Firma Cliente</b><br>
                        </div> 
                    </td>
                    <td>
                        <div>
                            <hr>
                            <?php echo $datos_observ['emp_vend']; ?><br>
                            <b>Firma Vendedor</b><br>
                        </div> 
                    </td>
                </tr>
            </table>
        </div>
       <div class="col-md-12">
            NOTA: Este documento no es válido para crédito tributario, no es una factura,
                master pc emite factura electrónica, revise su factura autorizada en su correo electrónico,
                o descarguelo de nuestro sitio web www.masterpc.com.ec. También puede solicitar su documento 
                en el servicio de rentas internas SRI.
        </div>                
        <?php
            echo Close('div');


