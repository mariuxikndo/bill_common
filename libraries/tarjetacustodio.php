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
class Tarjetacustodio {
    
    private $ci;
    private $obj_contaconfigcuentas;
    
    function __construct(){
        $this->ci = & get_instance();
        $this->ci->load->library('common/contaconfigcuentas');
        $this->obj_contaconfigcuentas = new Contaconfigcuentas();        
    }    

    /* Tipo de transaccion y documento de donde salio el cheque */
    public function anular_by_doc($tipotransaccion_cod, $doc_id, $estado_info) {
            $res = $this->ci->generic_model->update( 'bill_tarjetascustodio', array('estado'=>'-1','estado_info'=>$estado_info), array('tipotransaccion_cod'=>$tipotransaccion_cod,'docid'=>$doc_id) );
            return $res;      
    }
    
    public function print_voucher_custodio_by_doc($tipotransaccion_cod, $doc_id) {
        $res['voucher'] = $this->ci->generic_model->get_data( 'bill_tarjetascustodio', array( 'tipotransaccion_cod'=>$tipotransaccion_cod, 'docid'=>$doc_id ), '', null, 0, null );        
            $this->ci->load->view('common/comprobantes/voucher', $res);
    }
    
    public function print_voucher_cust_by_id($id) {
        $res['voucher'] = $this->ci->generic_model->get_data( 'bill_tarjetascustodio', array( 'id'=>$id ), '', null, 0, null );        
            $this->ci->load->view('common/comprobantes/voucher', $res);        
    }    
    
    /* cobrar con tarjeta de credito/voucher */
    public function cobrar_con_tarjeta($tarjeta_data, $ac_id, $venta_id, $client_id, $tipotransaccion_cod, $detalle) {
        /* Venta con tarjeta de credito */
        $total = sum_array($tarjeta_data);        
        if( $total > 0 ){
//            $this->pago_efectivo = $this->pago_efectivo AND TRUE;
            $tarjeta_nro = $this->ci->input->post('tarjeta_nro');
            $tarjeta_caduca = $this->ci->input->post('tarjeta_caduca');
            $tarjeta_cod = $this->ci->input->post('tarjeta_cod');
            $tarjeta_id = $this->ci->input->post('tarjeta_id');
            $cta_contable = $this->obj_contaconfigcuentas->get_setting_account('015');
            $cont = 0;
            foreach ($tarjeta_data as $val) {
                if( $val <= 0 ){
                    continue;
                }
                /* Registramos las tarjetas en custodio */
                    $tarjeta_custodio = array(
                        'valor' => $val,
                        'nrotarjeta' => $tarjeta_nro[$cont],
                        'codigo' => $tarjeta_cod[$cont],
                        'fechacaducidad' => $tarjeta_caduca[$cont],
                        'depositado' => 0,
                        'fecha' => date('Y-m-d',time()),
                        'hora' => date('H:i:s',time()),
                        'cliente_cedulaRuc' => $client_id,
                        'asiento_contable_id' => $ac_id,
                        'tipotransaccion_cod' => $tipotransaccion_cod,
                        'tarjeta_id' => $tarjeta_id[$cont],
                        'docid' => $venta_id,
                        'observaciones' => $detalle,
                        'empleado_id' => $this->ci->user->id,
                        'nombre_beneficiario' => $this->ci->input->post('beneficiario_cheque'),                        
                    );
                    $tar_cust_id = $this->ci->generic_model->save($tarjeta_custodio,'bill_tarjetascustodio');
                    
                    $asiento_det_data = array(
                        'asiento_contable_id' => $ac_id,
                        'cuenta_cont_id' => $cta_contable,
                        'debito' => $val,
                        'credito' => 0,
                        'tipotransaccion_cod' => $tipotransaccion_cod,
                        'doc_id' => $venta_id,
                        'detalle' => $detalle,
                        'tipo_pago' => '03',
                        'doc_id_pago' => $tar_cust_id,
                    );
                    $ac_det_id = $this->ci->generic_model->save($asiento_det_data,'bill_asiento_contable_det');   
                
                $cont ++;
            }
        }
    }    
}
