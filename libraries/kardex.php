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
class Kardex {
    
    private $ci;
    
    function __construct(){
        $this->ci = & get_instance();
    }    
    
    /* El stock total del producto se encuentra directamente en la tabla billing_producto */
    public function get_costo_prom_total($product_id, $tipotransaccion_cod, $doc_id) {        
        $res = $this->ci->generic_model->get_data( 
                   'bill_kardex', 
                    array('producto_id'=>$product_id, 'transaccion_cod'=>$tipotransaccion_cod, 'docid'=>$doc_id),
                    $fields = 'costo_prom_total', 
                    null, 
                    1
                );
        return $res->costo_prom_total;        
    }
    
}