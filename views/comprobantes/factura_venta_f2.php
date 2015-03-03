<?php

echo Open('div', array('id' => 'factventaprint_view', 'class' => 'container', 'style' => 'font-family:monospaced;font-size:11px'));
    $data['empresa'] = $empresa;
    $data['factura'] = $factura;
    $data['factura_det'] = $factura_det;
    $data['cliente_data'] = $cliente_data;
    $data['datos_observ'] = $datos_observ;
    $fact = $this->load->view('factura_venta', $data, TRUE);
    echo Open('div', array('id' => 'fact_venta', 'class' => 'container', 'style' => 'font-family:monospaced;font-size:11px'));
        echo tagcontent('div', $fact, array('id' => 'fact_venta1', 'class' => 'col-md-5 pull-left'));
        echo tagcontent('div', $fact, array('id' => 'fact_venta2', 'class' => 'col-md-5 pull-right'));
    echo Close('div');

echo Close('div');


