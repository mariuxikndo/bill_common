<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$cont = 0;
foreach ($voucher as $val) {
    echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir Voucher '.($cont+1), array('id'=>'printbtn','data-target'=>'cheque_print'.$cont,'class'=>'btn btn-default'));
    echo lineBreak2(1, array('style'=>'clear:both'));    
    echo Open('div', array('id'=>'cheque_print'.$cont,'class'=>'col-md-6','style'=>'font-size:16px;font-family:monospaced'));    
        echo tagcontent('div', $val->nombre_beneficiario, array('class'=>'col-md-9'));
        echo tagcontent('div', number_decimal($val->valor), array('class'=>'col-md-3'));

        echo tagcontent('div', $this->number_letter->convert_to_letter($val->valor), array('class'=>'col-md-12'));

        echo tagcontent('div', $val->lugar.', '.$val->fecha, array('class'=>'col-md-12'));
    echo Close('div');
    echo lineBreak2(1, array('style'=>'clear:both'));
    $cont ++;    
}
