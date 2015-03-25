<?php
/**
 * Description of imprcheque
 *
 * @author Mariuxi
 */
class Imprcheque extends MX_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->library('chequepago');
        $this->load->library('number_letter');
    }

    public function index() {
//        $this->load->view('comprobantes/index');
        $this->print_ch_pago_by_id(16);
    }

    function print_ch_pago_by_doc($tipotransaccion_cod, $doc_id) {
        $this->chequepago->print_ch_pago_by_doc($tipotransaccion_cod, $doc_id);
    }

    function print_ch_pago_by_id($doc_id) {
        $this->chequepago->print_ch_pago_by_id($doc_id);
    }

}
