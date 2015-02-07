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
class Product {
    
    private $ci;
    
    function __construct(){
        $this->ci = & get_instance();
    }    

    /* El stock total del producto se encuentra directamente en la tabla billing_producto */
    public function get_stock($product_id) {
        $res = $this->ci->generic_model->get_val_where('billing_producto', array('codigo'=>$product_id),'stockactual', null, 0);
        return $res;        
    }
    
    public function update_stock($product_id, $new_stock) {
//        echo 'codigo:'.$product_id, ' new_stock: '.$new_stock ;
        $res = $this->ci->generic_model->update( 
                    'billing_producto', 
                    array('stockactual'=>$new_stock, 'fechaultactualizacion'=>date('Y-m-d',time())),
                    array('codigo'=>$product_id)
                );
        return $res;
    }    
    
    
    /* Obtenemos los costos : promedio */
    public function get_costo_promedio($product_id) {
        $res = $this->ci->generic_model->get_val_where('billing_producto', array('codigo'=>$product_id),'costopromediokardex', null, 0);
        return $res;        
    }    
    /* Obtenemos los costos : ultimo */
    public function get_costo_ultimo($product_id) {
        $res = $this->ci->generic_model->get_val_where('billing_producto', array('codigo'=>$product_id),'costoultimokardex', null, 0);
        return $res;        
    }    
    
    /*
     *      Actualizamos los costos a partir de los cuales se calcula el pvp
    */
    public function update_costos( $product_id, $costo_prom, $costo_ult ) {
        $res = $this->ci->generic_model->update( 
                    'billing_producto', 
                    array(
                        'costopromediokardex'=>$costo_prom, 
                        'costoultimokardex'=>$costo_ult,
                        'fechaultactualizacion'=>date('Y-m-d',time())
                    ),
                    array('codigo'=>$product_id)                
                );            
        return $res;
    }
    
    /* Obtenemos el costo de inventario por producto */
    public function get_costo_inventario($product_id) {
        $res = $this->ci->generic_model->get_val_where('billing_producto', array('codigo'=>$product_id),'costo_inventario', null, 0);
        return $res;        
    }    
    
    /* El total del costo de lo que tenemos invertido por producto */
    public function update_costo_inventario( $product_id, $new_costo_inventario ) {
        $res = $this->ci->generic_model->update( 
                    'billing_producto', 
                    array(
                        'costo_inventario'=>$new_costo_inventario, 
                        'fechaultactualizacion'=>date('Y-m-d',time())
                    ),
                    array('codigo'=>$product_id)                
                );            
        return $res;
    }
}