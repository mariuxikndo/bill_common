<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of proveedor_anticipo
 *
 * @author estebanch
 */
class Stockbodega {
    private $ci;
    
    function __construct(){
        $this->ci = & get_instance();
    }    

    /* obtenemos el stock actual en la bodega seleccionada*/
        public function get_stock_bodega($bodega_id, $product_id) {
           $stock_bod = $this->ci->generic_model->get_val_where('billing_stockbodega', array('bodega_id'=>$bodega_id,'producto_codigo'=>$product_id), 'stock',null,0);           
           return $stock_bod;
        }
    
        public function update_stock_bodega($bodega_id, $product_id, $new_stock) {
            $res = false;
            $count_sb = $this->ci->generic_model->count_all_results( 'billing_stockbodega', array('bodega_id'=>$bodega_id,'producto_codigo'=>$product_id) );
            if($count_sb){ /* si ya existen registros de este producto en esta bodega, actualizamos el stock*/
                $res = $this->ci->generic_model->update(
                            'billing_stockbodega', 
                            array('stock'=>$new_stock,'fultimact'=>date('Y-m-d',time())), 
                            array('bodega_id'=>$bodega_id,'producto_codigo'=>$product_id) 
                        );
            }else{ /* caso contrario agregamos el registro con el stock correspondiente */
                $res = $this->ci->generic_model->save(
                        array('bodega_id'=>$bodega_id,'producto_codigo'=>$product_id,'stock'=>$new_stock,'fultimact'=>date('Y-m-d',time())),
                        'billing_stockbodega' 
                );
            }
            return $res;
        }
        
}
