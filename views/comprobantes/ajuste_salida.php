<?php
         
echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('id'=>'printbtn','data-target'=>'ajuste_salida_print_view','class'=>'btn btn-default'));
echo lineBreak2(1, array('class'=>'clr'));

echo Open('div', array('id'=>'ajuste_salida_print_view','class'=>'col-md-12'));
        $logo = Image(base_url('img/logo1.png'), array('alt'=>'master pc'));
        echo tagcontent('div', $logo, array('class'=>'col-md-6'));
        echo tagcontent('div', '<strong>AJUSTE DE SALIDA No. </strong>'.$ajuste->id, array('class'=>'col-md-6'));        
        ?>
        <div class="col-md-6 pull-right">
            FECHA REG.:  <?php echo $ajuste->fecha; ?>
        </div>
    <?php
        echo Open('table',array('class'=>'table table-striped table-condensed', 'style'=>'font-family:monospaced;font-size:11px'));    
            $thead = array(
                'Cod.',
                'Cant.',
                'Descrip.',
                'P. Unit.',
                'P. Total'
            );
            echo tablethead($thead);
            if (sizeof($ajuste_det)>0){
                foreach ($ajuste_det as $a) {
                    echo Open('tr');
                            echo tagcontent('td', $a->Producto_codigo);
                            echo tagcontent('td', $a->itemcantidad);
                            echo tagcontent('td', $a->nombreUnico);                                
                            echo tagcontent('td', number_decimal($a->itemcosto));
                            echo tagcontent('td', number_decimal($a->itemcostoxcantidad));
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
            echo tagcontent('div', 'Bodega: '.$ajuste->nombre);
               
            echo Close('div');
            
            echo Open('div',array('class'=>'col-md-3 pull-right'));
                echo Open('table',array('class'=>'table table-condensed','style'=>'font-size:11px'));
                    echo tagcontent('tr', tagcontent('td', '<span class="pull-right">VALOR TOTAL:</span>').  tagcontent('td', '<span class="pull-right">'.  number_decimal($ajuste->total).'</span>' ));
                echo Close('table');
            echo Close('div');
            
            echo LineBreak(3, array('class'=>'clr'));   
            echo Close('div');               
echo Close('div');

