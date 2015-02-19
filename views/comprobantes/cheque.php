<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$cont = 0;
foreach ($cheques as $val) {
    echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir Cheque '.($cont+1), array('id'=>'printbtn','data-target'=>'cheque_print'.$cont,'class'=>'btn btn-default'));
    echo lineBreak2(1, array('style'=>'clear:both'));    
    echo Open('div', array('id'=>'cheque_print'.$cont,'class'=>'col-md-8','style'=>'font-size:16px;font-family:monospaced'));    
        echo tagcontent('div', $val->nombre_beneficiario, array('class'=>'col-md-7'));
        echo tagcontent('div', number_decimal($val->valorcheque), array('class'=>'col-md-5'));
            echo '<br/>';
        echo tagcontent('div', $this->number_letter->convert_to_letter($val->valorcheque), array('class'=>'col-md-12'));
            echo '<br/>';
        echo tagcontent('div', $val->lugar.', '.$val->fecha, array('class'=>'col-md-12'));
    echo Close('div');
    echo lineBreak2(1, array('style'=>'clear:both'));
    $cont ++;    
}
