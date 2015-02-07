<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of contaasientocontable
 *
 * @author estebanch
 */
class Chequecustodio {
    
    private $ci;
    private $obj_contaconfigcuentas;
    
    function __construct(){
        $this->ci = & get_instance();
        $this->ci->load->library('common/contaconfigcuentas');
        $this->obj_contaconfigcuentas = new Contaconfigcuentas();
    }    

    /* Tipo de transaccion y documento de donde salio el cheque */
    public function anular_by_doc($tipotransaccion_cod, $doc_id, $estado_info) {
        $res = $this->ci->generic_model->update( 'bill_chequescustodio', array('estado'=>'-1','estado_info'=>'ANULACION CHEQUE, '.$estado_info), array('tipotransaccion_cod'=>$tipotransaccion_cod,'docid'=>$doc_id) );
        return $res;        
    }
    
    public function print_ch_custodio_by_doc($tipotransaccion_cod, $doc_id) {
        $res['cheques'] = $this->ci->generic_model->get_data( 'bill_chequescustodio', array( 'tipotransaccion_cod'=>$tipotransaccion_cod, 'docid'=>$doc_id ), '', null, 0, null );        
            $this->ci->load->view('comprobantes/cheque', $res);
    }
    
    public function print_ch_cust_by_id($id) {
        $res['cheques'] = $this->ci->generic_model->get_data( 'bill_chequescustodio', array( 'id'=>$id ), '', null, 0, null );        
            $this->ci->load->view('comprobantes/cheque', $res);        
    }
    
    
    /* Cuando recibimos un cheque del cliente, sea por cobro de venta, anticipo, cobro de cxc, ...*/    
    public function cobrar_cheque($cheque_data, $ac_id, $doc_id, $client_id, $tipotransaccion_cod, $detalle) {
        /* Venta con cheque */
        $total_cheques = sum_array($cheque_data);
        if( $total_cheques > 0 ){
            $cheque_nro = $this->ci->input->post('cheque_nro');
            $cheque_nrocuenta = $this->ci->input->post('cheque_nrocuenta');
            $cheque_fcobro = $this->ci->input->post('cheque_fcobro');
            $bancolist_id = $this->ci->input->post('bancolist_id');
            $cta_contable = $this->obj_contaconfigcuentas->get_setting_account('013');
            
            $cont = 0;            
            foreach ( $cheque_data as $val ) {
                if( $val <= 0 ){
                    continue;
                }                                
                /* Ahora registramos los cheques en custodio */
                $cheque_custodio = array(
                    'valorcheque' => $val,
                    'nrocheque' => $cheque_nro[$cont],
                    'nrocuentacheque' => $cheque_nrocuenta[$cont],
                    'fechacobro' => $cheque_fcobro[$cont],
                    'lugar' => get_settings('CIUDAD'),
                    'fecha' => date('Y-m-d',time()),
                    'hora' => date('H:i:s',time()),
                    'cliente_cedulaRuc' => $client_id,
                    'asiento_contable_id' => $ac_id,
                    'tipotransaccion_cod' => $tipotransaccion_cod,
                    'bancolist_id' => $bancolist_id[$cont],
                    'docid' => $doc_id,
                    'observaciones' => $detalle,
                    'empleado_id' => $this->ci->user->id,
                    'nombre_beneficiario' => $this->ci->input->post('beneficiario_cheque'),                       
                );
//                    $this->add_cheques_custodio($cheque_custodio);
                $ch_cust_id = $this->ci->generic_model->save($cheque_custodio,'bill_chequescustodio');
                
                /* 
                 * Cuando cobramos con un cheque registramos el asiento contable
                 * el valor al cheque va al debe ya que es un valor que ingresa
                 */
                $asiento_det_data = array(
                    'asiento_contable_id' => $ac_id,
                    'cuenta_cont_id' => $cta_contable,
                    'debito' => $val,
                    'credito' => 0,
                    'tipotransaccion_cod' => $tipotransaccion_cod,
                    'doc_id' => $doc_id,
                    'detalle' => $detalle.'. No. '.$ch_cust_id,
                    'tipo_pago' => '04',
                    'doc_id_pago' => $ch_cust_id,
                );
                $ac_det_id = $this->ci->generic_model->save($asiento_det_data,'bill_asiento_contable_det');  
                
                $cont ++;
            }
        }
    }
    
}
