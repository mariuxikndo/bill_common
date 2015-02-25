<?php
               
        $logo = Image(base_url('img/logo1.png'), array('alt'=>'master pc'));
        echo tagcontent('div', $logo, array('class'=>'col-md-6'));
                
        ?>
        <div  class="col-md-6">
            MASTERPC CIA LTDA <br>
            Dirección Matriz: Azuay y Olmedo. <br>
            Dir Sucursal: <br>
            Contribuyente especial Nro: 00290 <br>
            OBLIGADO A LLEVAR CONTABILIDAD: SI <br>
        </div>
        
    <?php
        echo Open('table',array('class'=>'table table-striped table-condensed', 'style'=>'font-family:monospaced;font-size:11px'));    
            $thead = array(
                'Cod.',
                'Cant.',
                'Descrip.',
                'P. Unit.',
            );
            echo tablethead($thead);

                if (sizeof($ajuste_det) > 0){
                    foreach ($ajuste_det as $d) {
                        echo Open('tr');
                            echo tagcontent('td', $d->Producto_codigo);
                            echo tagcontent('td', $d->itemcantidad);
                            echo tagcontent('td', $this->generic_model->get_val_where('billing_producto', array('billing_producto.codigo' => $d->Producto_codigo), 'nombreUnico', null, -1) );                                
                            echo tagcontent('td', number_decimal($d->itemcosto));
                        echo Close('tr');
                    }
                }
            echo Close('table');
            echo lineBreak2(1, array('class'=>'clr'));
            echo Open('div',array('class'=>'col-md-9 pull-left'));            
        ?>
              
                <b>OBSERVACIONES:</b><br>
                <p> <?php echo $ajuste->observaciones; ?></p>

                <?php 
                   echo tagcontent('div', 'Bodega: '.$this->generic_model->get_val_where('billing_bodega', array('id' => $ajuste->bodega_id), 'nombre', null, -1) , array('class'=>'col-md-12'));
               
            echo Close('div');
            
            echo Open('div',array('class'=>'col-md-3 pull-right'));
                echo Open('table',array('class'=>'table table-condensed','style'=>'font-size:11px'));
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">VALOR TOTAL:</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($ajuste->total).'</span>' ));
                echo Close('table');
            echo Close('div');
            
            echo LineBreak(3, array('class'=>'clr'));   
            echo Close('div');
        ?>                 
     

