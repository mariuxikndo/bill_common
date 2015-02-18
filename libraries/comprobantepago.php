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
class Comprobantepago {
    
    private $ci;
    private $obj_number_letter;
    private $obj_asientocontable;
    
    function __construct(){
        $this->ci = & get_instance();
        $this->ci->load->library('number_letter');
        $this->ci->load->library('common/asientocontable');
        $this->obj_number_letter = new Number_letter();
        $this->obj_asientocontable = new Asientocontable();
    }
    
    public function save_comprob_pago($total_recibo, $tipotransaccion_cod, $doc_id, $proveedor_id, $detalle) {        
        $comprobante_new = array(
            'anio' => date('Y',time()),
            'proveedor_id' => $proveedor_id,
            'nota' => $detalle,
            'doc_id' => $doc_id,
            'tipotransaccion_cod' => $tipotransaccion_cod,
            'tipo_id' => '1',
            'user_id' => $this->ci->user->id,
            'fecha' => date('Y-m-d',time()),
            'lugar' => get_settings('CIUDAD'),
            'estado' => 1,
            'cantidad_number' => $total_recibo,
            'cantidad_letras' => $this->obj_number_letter->convert_to_letter($total_recibo,''),            
        );                
        $comprob_id = $this->ci->generic_model->save( $comprobante_new,'bill_comprob_pago' );
        return $comprob_id;
    }
    
    
    /* Obtenemos el recibo */
    public function print_comprob_pago($tipotransaccion_cod, $doc_id){
        /* tipotransaccion_cod y doc_id se podrian usar para la anulacion de la transaccin que genero el cmprobante */
        $res['tipotransaccion_cod'] = $tipotransaccion_cod;
        $res['doc_id'] = $doc_id;
        
//        $res['recibo'] = $this->ci->generic_model->get( 'bill_comprob_pago', array('doc_id'=>$doc_id,'tipotransaccion_cod'=>$tipotransaccion_cod), '', null, 1 );
        $res['comprob_pago'] = $this->ci->generic_model->get('bill_comprob_pago', array('doc_id'=>$doc_id,'estado'=>'1','tipotransaccion_cod'=>$tipotransaccion_cod), '', null, 1 );        
        
//        print_r($res['comprob_pago']);
        $res['proveedor'] = $this->ci->generic_model->get_by_id('billing_proveedor',$res['comprob_pago']->proveedor_id);
        $res['data_asiento'] = $this->obj_asientocontable->get_ac_data($tipotransaccion_cod, $doc_id);
        
        $this->ci->load->view('comprobantes/comprobante_pago',$res);
    }
    
}
