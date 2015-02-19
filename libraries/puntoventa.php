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
class Puntoventa {
    
    private $ci;
    
    function __construct(){
        $this->ci = & get_instance();
    }    
    
    /* Obtenemos el punto de venta que posee el usuario para hacer facturas */
    public function get_punto_venta($user_id, $tipocomprobante_cod) {
        
        $join_cluase = array(
            '0' => array('table'=>'bill_puntoventausers pvu','condition'=>'pv.id = pvu.puntoventa_id'),
        );
        
        $punto_venta = $this->ci->generic_model->get_join(
                'billing_puntoventaempleado pv', 
                array('pv.tiposcomprobante_cod'=>$tipocomprobante_cod,'pvu.empleado_id'=>$user_id), 
                $join_cluase, 
                'pv.*', 
                1 /* Trabajamos unicamente con un punto de venta asignado a cada usuario*/ 
        );
        
        return $punto_venta;
    }
    
    /* incrementa el valor de la secuencia en uno */
    public function incrementar_secuencia($establecimiento, $pemision, $tipocomprobante_cod) {
        $pv = $this->ci->generic_model->get_data( 
                'billing_puntoventaempleado', 
                array('establecimiento'=>$establecimiento,'puntoemision'=>$pemision, 'tiposcomprobante_cod'=>$tipocomprobante_cod), 
                'secuenciaultima', 
                $order_by = null, 
                1 
        );
        
        $new_sec = $pv->secuenciaultima + 1;
        $res = $this->ci->generic_model->update( 
                    'billing_puntoventaempleado', 
                    array('secuenciaultima'=>$new_sec), 
                    array('establecimiento'=>$establecimiento,'puntoemision'=>$pemision, 'tiposcomprobante_cod'=>$tipocomprobante_cod) 
                );
        
        if( $res > 0 ){
            return true;
        }else{
            return false;
        }
    }

}