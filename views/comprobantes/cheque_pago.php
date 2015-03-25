<?php

$cont = 0;
foreach ($cheques as $val) {
    echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir Cheque ' . ($cont + 1), array('id' => 'printbtn', 'data-target' => 'cheque_print' . ($cont + 1), 'class' => 'btn btn-default'));
    echo lineBreak(1);
    echo Open('div', array('id' => 'cheque_print' . ($cont + 1), 'class' => 'col-md-10', 'style' => 'font-weight:bold;font-size:18px;font-family:monospace'));
    echo lineBreak(2);
        echo '<span class="col-md-12">';
            echo '<span class="col-md-9">';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$val->nombre_beneficiario;
            echo '</span>';
            echo '<span class="col-md-3 pull-left">';
                echo number_decimal($val->valor);
            echo '</span>';
        echo '</span>';
        echo lineBreak(1);
        echo '<span class="col-md-12">';
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$this->number_letter->convert_to_letter($val->valor);
        ?>

<table style="font-weight:bold;font-size:18px;font-family:monospace">
            <tr height="50px">
                <td><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$val->lugar . ', ' . $val->fecha_emision?></td>
           </tr>
        </table>
<?php
        echo '</span>';
    echo Close('div');
    echo lineBreak(1);
    $cont ++;
}

?>

           