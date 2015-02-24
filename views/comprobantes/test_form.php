<?php

echo Open('form', array('action' => base_url('common/facturaventapdf/open_fact'), 'method' => 'post'));
echo input(array('type' => 'text', 'name' => 'venta_id', 'placeholder' => 'Ingrese el numero de la factura'));
echo input(array('type' => 'submit', 'name' => 'btnBuscar', 'value' => 'Ver Factura'));
echo Close('form');

        