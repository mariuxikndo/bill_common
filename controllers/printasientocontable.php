<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of autosuggest
 *
 * @author estebanch
 */
class Printasientocontable extends MX_Controller{
    
	function __construct()
	{
		parent::__construct();
                $this->load->library('asientocontable');
	}
    
        public function open_ac($tipo_trans, $doc_id) {
            $res['ac_data'] = $this->asientocontable->get_ac_data($tipo_trans, $doc_id);
            $this->load->view('asiento_contable_doc',$res);
        }

        /* abrir el asiento contable cuando conocemos unicamente alguno de los documentos de pago */
        public function open_by_doc_pago($tipo_pago, $doc_id_pago, $estado = 1) {
            $res['ac_data'] = $this->asientocontable->get_by_doc_pago($tipo_pago, $doc_id_pago, $estado);
            $this->load->view('asiento_contable_doc',$res);
        }
    
}
