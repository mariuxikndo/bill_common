<?php

/**
 * Description of facturaventapdf
 *
 * @author Mariuxi
 */
class Facturaventapdf extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('facturaventa_data');
    }

    function open_fact() {
        $this->facturaventa_data->open_fact($this->input->post('venta_id'));
    }

    function create_pdf_fact() {
        $this->facturaventa_data->create_pdf_factVenta($this->input->post('venta_id'));
    }
    
    function send_email() {
        $this->facturaventa_data->send_email($this->input->post('txt_email'),$this->input->post('venta_id') );
    }

}
