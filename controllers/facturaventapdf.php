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
        $this->facturaventa_data->create_pdf_factVenta($this->input->get('venta_id'));
    }
    
    function send_email() {
        $res = $this->facturaventa_data->send_email($this->input->post('txt_email'),$this->input->post('fact_id') );
        
        if($res){
            echo info_msg(' E-mail enviado correctamente');
        }else{
            echo warning_msg(' No fue posible enviar la notificacion por e-mail');
        }
    }

}
