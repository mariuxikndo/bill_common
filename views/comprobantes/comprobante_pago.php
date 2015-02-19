<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir Comprobante', array('id'=>'printbtn','data-target'=>'comprob_pago_print','class'=>'btn btn-default pull-left'));

if(!empty($anticipo_id)){
    echo Open( 'form',array('action'=>  base_url('cxp/anticipoprov/anular/'.$anticipo_id ),'method'=>'post', 'class'=>'pull-left'));
        echo input(array( 'type'=>'hidden','name'=>'anticipo_id','value'=>$anticipo_id ));
        echo tagcontent('button', 'Anular Anticipo', array('id'=>'ajaxformbtn','data-target'=>'comprob_pago_actions_out','class'=>'btn btn-danger'));
    echo Close('form');    
}

echo tagcontent('div', '', array('id'=>'comprob_pago_actions_out'));
echo lineBreak2(1, array('style'=>'clear:both'));
echo Open('div',array('class'=>'col-md-12','id'=>'comprob_pago_print','style'=>'font-size:16px;font-family:monospaced'));
    echo tagcontent('div', 'Comp. Pago No. '.$comprob_pago->anio.'-'.  str_pad($comprob_pago->id, 6, '0', STR_PAD_LEFT), array('class'=>'col-md-6','style'=>'font-size:18px'));
    echo tagcontent('div', 'Lugar y Fecha: '.$comprob_pago->lugar.', '.$comprob_pago->fecha, array('class'=>'col-md-6','style'=>'font-size:18px'));
    
    echo tagcontent('div', $proveedor->nombres.' '.$proveedor->apellidos, array('class'=>'col-md-6'));
    echo tagcontent('div', 'RUC/CI: '.$proveedor->PersonaComercio_cedulaRuc, array('class'=>'col-md-6'));

    echo tagcontent('div', 'Domicilio: '.$proveedor->direccion, array('class'=>'col-md-6'));
    echo tagcontent('div', 'Telf.: '.$proveedor->telefonos, array('class'=>'col-md-6'));
       
    echo Open('table', array('class'=>'table table-striped'));
        $thead = array('Cta. Cont.','Debito','Credito','Descripcion','No. Anticipo');    
        echo tablethead($thead);
        
        foreach ($data_asiento as $val) {
            echo Open('tr');            
                echo tagcontent('td', $val->cuenta_cont_id.' '.$val->cta_name);
                echo tagcontent('td', $val->debito);
                echo tagcontent('td', $val->credito);
                echo tagcontent('td', $val->detalle);
                echo tagcontent('td', $val->acd_doc_id);            
            echo Close('tr');
        }
    echo Close('table');
echo Close('div');
echo lineBreak2(1, array('style'=>'clear:both'));

