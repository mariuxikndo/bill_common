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
class Comprobanteingreso {
    
    private $ci;
    private $obj_number_letter;
    private $obj_asientocontable;
    
    function __construct(){
        $this->ci = & get_instance();
        $this->ci->load->library('number_letter');
        $this->ci->load->library('asientocontable');
        $this->obj_number_letter = new Number_letter();
        $this->obj_asientocontable = new Asientocontable();
    }
    
    public function save_comprob_ingreso($total_recibo, $tipotransaccion_cod, $doc_id, $client_id, $detalle) {
        $recibo_data = array(
            'anio' => date('Y',time()),
            'client_id' => $client_id,
            'nota' => $detalle,
            'doc_id' => $doc_id,
            'tipotransaccion_cod' => $tipotransaccion_cod,
            'recibo_tipo_id' =>  2, /* 2 = ANTICIPO CLIENTES*/
            'user_empleado_id' => $this->ci->user->id,
            'fecha' => date('Y-m-d',time()),
            'lugar' => get_settings('CIUDAD'),
            'cantidad_number' => $total_recibo,
            'cantidad_letras' => $this->obj_number_letter->convert_to_letter($total_recibo,''),
        );
        $recibo_id = $this->ci->generic_model->save( $recibo_data,'bill_recibo' );
        return $recibo_id;
    }
    
    
    /* Obtenemos el recibo */
    public function print_comprob_ingreso($tipotransaccion_cod, $doc_id){
        /* tipotransaccion_cod y doc_id se podrian usar para la anulacion de la transaccin que genero el cmprobante */
        $res['tipotransaccion_cod'] = $tipotransaccion_cod;
        $res['doc_id'] = $doc_id;
        
        $res['recibo'] = $this->ci->generic_model->get( 'bill_recibo', array('doc_id'=>$doc_id,'tipotransaccion_cod'=>$tipotransaccion_cod), '', null, 1 );
        $res['cliente'] = $this->ci->generic_model->get_by_id('billing_cliente',$res['recibo']->client_id,'','PersonaComercio_cedulaRuc');
        $res['data_asiento'] = $this->ci->asientocontable->get_ac_data($tipotransaccion_cod, $doc_id);
        $this->ci->load->view('comprobantes/comprobante_ingreso',$res);
    }
    
}
