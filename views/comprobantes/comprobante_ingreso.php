<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if($recibo->estado == -1){
    echo error_info_msg(' Este recibo esta anulado.');
}
echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir Comprobante Ingreso', array('id'=>'printbtn','data-target'=>'comprob_pago_print','class'=>'btn btn-default pull-left'));
echo lineBreak2(1, array('style'=>'clear:both'));
echo Open('div',array('class'=>'col-md-12','id'=>'comprob_pago_print','style'=>'font-size:16px;font-family:monospaced'));
    echo tagcontent('strong', get_settings('RAZON_SOCIAL'), array('class'=>'col-md-12','style'=>'font-size:20px'));
    echo tagcontent('div', 'Comp. Ingreso No. '.$recibo->anio.'-'.  str_pad($recibo->id, 6, '0', STR_PAD_LEFT), array('class'=>'col-md-6','style'=>'font-size:18px'));
    echo tagcontent('div', 'Lugar y Fecha: '.$recibo->lugar.', '.$recibo->fecha, array('class'=>'col-md-6','style'=>'font-size:18px'));
    
    echo tagcontent('div', $cliente->nombres.' '.$cliente->apellidos, array('class'=>'col-md-6'));
    echo tagcontent('div', 'RUC/CI: '.$cliente->PersonaComercio_cedulaRuc, array('class'=>'col-md-6'));

    echo tagcontent('div', 'Domicilio: '.$cliente->direccion, array('class'=>'col-md-6'));
    echo tagcontent('div', 'Telf.: '.$cliente->telefonos, array('class'=>'col-md-6'));
    
    echo tagcontent('div', 'Asiento No. '.$data_asiento[0]->anio .'-'. str_pad($data_asiento[0]->id, 11, '0', STR_PAD_LEFT), array('class'=>'col-md-6','style'=>'font-size:18px'));    
    echo Open('table', array('class'=>'table table-striped table-condensed'));
        $thead = array('Cta. Cont.','Debito','Credito','Descripcion','No. Anticipo');    
        echo tablethead($thead);
        
        foreach ($data_asiento as $val) {
            echo Open('tr');
//                echo tagcontent('td', $val->fecha);
                echo tagcontent('td', $val->cuenta_cont_id.' '.$val->cta_name);
                echo tagcontent('td', $val->debito);
                echo tagcontent('td', $val->credito);
                echo tagcontent('td', $val->detalle);
                echo tagcontent('td', $val->acd_doc_id);
            echo Close('tr');
        }        
   
    echo Close('table');
    
    echo tagcontent('div', '<strong>Recibo la cantidad de:</strong> '.$recibo->cantidad_letras, array('class'=>'col-md-12'));
    echo lineBreak2(1, array('class'=>'clr'));
    echo tagcontent('div', 'Usuario: '.$data_asiento[0]->user_nombres.$data_asiento[0]->user_apellidos, array('class'=>'col-md-6'));
    echo tagcontent('div', 'Cliente: '.$cliente->nombres.' '.$cliente->apellidos, array('class'=>'col-md-6'));
    echo tagcontent('div', 'Nota: '.$recibo->nota, array('class'=>'col-md-12'));
echo Close('div');
echo lineBreak2(1, array('class'=>'clr'));

