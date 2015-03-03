      <?php
      /* este caso sin encabezado,  se da solo cuando no es electronica, ya que se necesita imprimir el encabezado impreso */ 
        if(get_settings('PRINT_HEAD_FACT') ){ 
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
            echo LineBreak(8, array('class'=>'clr'));
            if($factura->autorizado_sri == 2){ /* 2 cuando es de un punto no electronico*/
                echo tagcontent('span', 'FACTURA No: '.$factura->establecimiento.$factura->puntoemision.'-'.str_pad($factura->secuenciafactventa, 9, '0', STR_PAD_LEFT), array('class'=>'')).'&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;';                
                echo tagcontent('span', 'FECHA: '.$factura->fechaarchivada, array('class'=>''));
            }else{
                echo tagcontent('span', 'PRE-FACTURA No: '.$factura->establecimiento.$factura->puntoemision.'-'.str_pad($factura->secuenciafactventa, 9, '0', STR_PAD_LEFT), array('class'=>'')).'&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;';                
                echo tagcontent('span', 'FECHA: '.$factura->fechaCreacion, array('class'=>''));                
            }
        }
        ?>
        <table class="table table-striped table-condensed" style="font-size:<?= get_settings('FONT_SIZE_FACT') ?>">
            <tr>
                <td>CLIENTE: <?php echo $cliente_data[0]->nombres.' '.$cliente_data[0]->apellidos; ?></td>
                <td>CI/RUC: <?php echo $cliente_data[0]->PersonaComercio_cedulaRuc; ?></td>
            </tr>
        </table>
    <?php
        echo Open('table',array('class'=>'table table-striped table-condensed', 'style'=>'font-family:monospaced;font-size:'.get_settings('FONT_SIZE_FACT')));
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
            echo Open('div',array('class'=>'col-md-8 pull-left', 'style'=>'font-size:'.get_settings('FONT_SIZE_FACT')));
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
            
            echo Open('div',array('class'=>'col-md-4 pull-right'));
                echo Open('table',array('class'=>'table table-condensed','style'=>'font-size:'.get_settings('FONT_SIZE_FACT')));
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
            
            echo LineBreak(1, array('class'=>'clr'));           
        ?>                 
        <div class="col-md-12" >
            <table class="table text-center" style="font-size: <?= get_settings('FONT_SIZE_FACT') ?>">
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
                
        <?php
            if(get_settings('PRINT_FOOTER_FACT')) {
        ?>
            <div class="col-md-12" style="font-size: <?= get_settings('FONT_SIZE_FACT') ?>">
                <?php
                    echo get_info('INVOICE_FOOTER');
                ?>
             </div>                
        <?php
            }
        ?>