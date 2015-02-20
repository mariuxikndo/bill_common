
    <?php
        echo $head;

        echo Open('div',array('id'=>'factventaprint_view', 'class'=>'container', 'style'=>'font-family:monospaced; font-size:11px'));            
        $logo = Image(base_url('img/logo1.png'), array('alt'=>'master pc'));
        echo tagcontent('div', $logo, array('class'=>'col-md-6'));        
    ?>
        <div class="col-md-6 pull-right">
            R.U.C.: 1191732525001 <br>
            <b>COTIZACIÓN Nro: </b>
            <?php echo $cotizacion[0]->id; ?><br>
            FECHA: 
            <?php echo $cotizacion[0]->fechaCreacion; ?>
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
                <td>CLIENTE: <?php echo $cliente_data[0]->nombres; ?></td>
                <td>CI/RUC: <?php echo $cliente_data[0]->PersonaComercio_cedulaRuc; ?></td>
            </tr>
        </table>
        <br>        

        <br>
        <table class="table table-condensed table-striped" style="font-family:monospaced;font-size:11px">
            <thead>
                <tr>
                    <th>Cod. Principal</th>
                    <th>Cod. Auxiliar</th>
                    <th>Cant.</th>
                    <th>Descripción</th>
                    <th>Detalle Adicional</th>
                    <th>Precio Unitario</th>
                    <th>Descuento</th>
                    <th>Precio Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (sizeof($cotizacion_det) > 0):
                    foreach ($cotizacion_det as $d) {
                        ?>
                        <tr>
                            <td><?php echo $d->Producto_codigo; ?></td>
                            <td></td>
                            <td><?php echo $d->itemcantidad; ?></td>
                            <td><?php echo $this->generic_model->get_val_where('billing_producto', array('billing_producto.codigo' => $d->Producto_codigo), 'nombreUnico', null, -1); ?></td>
                            <td></td>
                            <td><?php echo number_decimal($d->itempreciobruto); ?></td>
                            <td><?php echo number_decimal($d->descuentofactvalor); ?></td>
                            <td><?php echo number_decimal($d->itemprecioxcantidadneto); ?></td>
                        </tr>
                        <?php
                    }
                endif;
                ?>
            </tbody>
        </table>

        <div  class="col-md-9 pull-left">
            Dirección: <?php echo $cliente_data[0]->direccion; ?><br>
            Teléfono: <?php echo $cliente_data[0]->telefonos; ?><br>
            Email: <?php echo $cliente_data[0]->email; ?><br><br>
            <b>OBSERVACIONES:</b><br>
            <p> <?php echo $cotizacion[0]->observaciones; ?></p>
            Tipo Pago: <?php echo $datos_observ['tip_pag']." ".$cotizacion[0]->tipoprecio; ?><br>

            Bodega: <?php echo $datos_observ['bodega']; ?><br>
            Vendedor:<?php echo $datos_observ['emp_vend']; ?><br>
            Técnico: <?php echo $datos_observ['tecnico']; ?><br>
            Usuario: <?php echo $datos_observ['user']; ?><br>
            
        </div>

        <?php
            echo Open('div',array('class'=>'col-md-3 pull-right'));
                echo Open('table',array('class'=>'table table-condensed','style'=>'font-size:11px'));
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">SUBTOTAL 12%</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($cotizacion[0]->tarifacerobruto).'</span>' ));
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">SUBTOTAL 0%:</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($cotizacion[0]->tarifadocebruto).'</span>' ));

                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">Subtotal</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($cotizacion[0]->subtotalBruto).'</span>' ));/* Subotal bruto - antes del descuento */
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">TOTAL Recargo</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($cotizacion[0]->recargovalor).'</span>' ));
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">TOTAL Descuento</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($cotizacion[0]->descuentovalor).'</span>' ));
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">Subtotal Neto</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($cotizacion[0]->subtotalNeto).'</span>' ));/* Subotal bruto - antes del descuento */                    
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">ICE</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($cotizacion[0]->iceval).'</span>' ));
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">IVA 12%</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($cotizacion[0]->ivaval).'</span>' ));

                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">VALOR TOTAL:</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($cotizacion[0]->totalCompra).'</span>' ));
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
                            <?php echo $cliente_data[0]->nombres; ?><br>
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
        NOTA: Estimado cliente, esta cotización tendrá una validez de ocho días, o hasta agotar stock. Para
                revisar sus facturas electrónicas ingrese a www.masterpc.com.ec.
        </div>         
        
    <?php
        echo Close('div');