<?php

/**
 * Description of ajustesalida
 *
 * @author Mariuxi
 */
class Ajustesalida extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('common/ajustesalida_data');
    }

    function index() {
        $this->open_ajuste();
    }

    function open_ajuste($id) { 
        $this->ajustesalida_data->open_ajuste($id);
//        $this->ajustesalida_data->open_ajuste(41);
    }

}
