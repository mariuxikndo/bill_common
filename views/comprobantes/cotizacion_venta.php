<html>
    <head>
        <meta charset="utf-8">
        <style>
            body {font-family: sans-serif;
                  font-size: 10pt;
            }
            p{
                text-align: justify;
                font-size: 11px
            }
            h1{ color: #000088}
            td { vertical-align: top; }
            table{ width: 100%;}   
            div{vertical-align: top;}
            table thead td { background-color: #EEEEEE;
                             text-align: center;
                             border: 0.1mm solid #000000;
            }
            #firmas td { 
                text-align: center;
                width: 40%
            }
            #secundaria {
                border: 1px solid
            }
            #secundaria td { 
                width: 50%;
            }
            #terciaria td { 
                width: 50%;
            }
            .imagen {
                float: left;
                margin-bottom: 0pt;
                width:140px;
                height: 30px;
                margin: 2px;
                vertical-align: top;
            }
            .ruc_reg{
                float: right;
                margin-bottom: 0pt;
                width:50%;
            }
            .mastercialtda{
                clear: both;
            }
        </style>
    </head>       
    <body>
<!--        <div class="imagen"><img src="imgs/logomaster.png"></div>-->
        <div class="imagen"><h1>master pc</h1></div>
        <div class="ruc_reg">
            R.U.C.: 1191732525001 <br>
            <b>COTIZACIÓN Nro: </b>
            <?php echo $cotizacion[0]->id; ?><br>
            FECHA DE CREACIÓN: <br>
            <?php echo $cotizacion[0]->fechaCreacion; ?>
        </div>
        <div  class="mastercialtda">
            MASTERPC CIA LTDA <br>
            Dirección Matriz: Azuay y Olmedo. <br>
            Dir Sucursal: <br>
            Contribuyente especial Nro: 00290 <br>
            OBLIGADO A LLEVAR CONTABILIDAD: SI <br>
        </div>
        <br>
        <table id="secundaria">
            <tr>
                <td>Razón Social/Nombres y Apellidos: <?php echo $cliente_data[0]->nombres; ?></td>
                <td>Identificación: <?php echo $cliente_data[0]->PersonaComercio_cedulaRuc; ?></td>
            </tr>
            <tr>
                <td>Fecha Emisión: <?php echo $cotizacion[0]->fechaCreacion; ?> </td>
                <td>Guía Remisión: </td>
            </tr>
        </table>
        <br>
        <table border="1">
            <thead>
                <tr>
                    <th>Cod. Principal</th>
                    <th>Cod. Auxiliar</th>
                    <th>Cant.</th>
                    <th>Descripción</th>
                    <th>Detalle Adicional</th>
                    <th>Detalle Adicional</th>
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
                            <td></td>
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

        <div  style="float: left; width:60% ; margin-bottom: 0pt; ">
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
            
            <p><b>NOTA: </b>Estimado cliente, esta cotización tendrá una validez de ocho días, o hasta agotar stock. Para
                revisar sus facturas electrónicas ingrese a www.masterpc.com.ec. </p>
        </div>
        <div style="float: right; width: 40%; margin-bottom: 0pt; ">
            <table border="1" id="terciaria">
                <tr> 
                    <td>SUBTOTAL 12%</td>
                    <td><?php echo number_decimal($cotizacion[0]->subtotalBruto); ?></td>
                </tr>
                <tr>
                    <td>SUBTOTAL 0%</td>
                    <td></td>
                </tr>
                <tr>
                    <td>SUBTOTAL NO objeto de IVA</td>
                    <td></td>
                </tr>
                <tr>
                    <td>SUBTOTAL exento de IVA</td>
                    <td></td>
                </tr>
                <tr>
                    <td>SUBTOTAL SIN IMPUESTOS</td>
                    <td></td>
                </tr>
                <tr>
                    <td>TOTAL Descuento</td>
                    <td><?php echo number_decimal($cotizacion[0]->descuentovalor); ?></td>
                </tr>
                <tr>
                    <td>ICE</td>
                    <td><?php echo number_decimal($cotizacion[0]->iceval); ?></td>
                </tr>
                <tr>
                    <td>IVA 12%</td>
                    <td><?php echo number_decimal($cotizacion[0]->ivaval); ?></td>
                </tr>
                <tr>
                    <td>IRBPNR</td>
                    <td></td>
                </tr>
                <tr>
                    <td>PROPINA</td>
                    <td></td>
                </tr>
                <tr>
                    <td>VALOR TOTAL</td>
                    <td><?php echo number_decimal($cotizacion[0]->totalCompra); ?></td>
                </tr>
            </table>
        </div>
        <br><br><br><br><br><br>
        <div style="clear: both; ">
            <table id="firmas">
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
    </body>
</html>



