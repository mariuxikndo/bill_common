<?php

/**
 * Description of ajustesalida
 *
 * @author Mariuxi
 */
class Ajustesalida extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ajustesalida_data');
    }

    function index() {
        $this->open_ajuste();
    }

    function open_ajuste() {
        $this->ajustesalida_data->open_ajuste($this->input->post('ajuste_id'));
//        $this->ajustesalida_data->open_ajuste(41);
    }

}
