<?php
/**
 * Description of cheque
 *
 * @author Mariuxi
 */
class Chequepago {
    private $ci;
  
    function __construct(){
        $this->ci = & get_instance();
        
    }    
   
    public function print_ch_pago_by_doc($tipotransaccion_cod, $doc_id) {
        $res['cheques'] = $this->ci->generic_model->get_data( 'bill_cheque_pago', array( 'tipotransaccion_cod'=>$tipotransaccion_cod, 'docid'=>$doc_id ), '', null, 0, null );        
            $this->ci->load->view('common/comprobantes/cheque_pago', $res);
    }
    
    public function print_ch_pago_by_id($id) {
        $res['cheques'] = $this->ci->generic_model->get_data( 'bill_cheque_pago', array( 'id'=>$id ), '', null, 0, null );        
            $this->ci->load->view('common/comprobantes/cheque_pago', $res);        
    }
    
    
    
    
}
