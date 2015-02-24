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

    /* Obtiene el stock, restando las reservas */
    public function get_stock_disponible($product_id) {
        $stock = $this->get_stock($product_id);
        $reserva = $this->get_reserva($product_id);
        
        $stock_disp = $stock - $reserva;
        return $stock_disp;
    }    
    
    /* obtenemos el stock total en reserva */
        public function get_reserva($product_id) {
           $tot_reservas = $this->ci->generic_model->sum_table_field( 'billing_stockbodega', 'reserva', array('producto_codigo'=>$product_id) );
           return $tot_reservas;
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
    
    public function get_precio_prod($item_id, $price_tipo) {
        $fields = 'p.codigo, p.stockactual, p.costopromediokardex, p.costoultimokardex, p.ajuste1, p.ajuste2, p.productogrupo_codigo grupo_id, p.descmaxporcent descmaxporcent, gp.utilidad utilidad';
        $join_cluase = array(
            '0' => array('table'=>'bill_grupoprecio gp', 'condition'=>'gp.productogrupo_id = p.productogrupo_codigo AND gp.tiposprecio_id = "'.$price_tipo.'"', 'type'=>'left')
        );
        $where_data = array('p.codigo' => $item_id);
        $p = $this->ci->generic_model->get_join('billing_producto p', $where_data, $join_cluase, $fields, 1, null, null );
        
        $utilidadventaprod = get_settings('DEFAULT_UTILIDAD');
        if(!empty($p->utilidad)){
            $utilidadventaprod = $p->utilidad;
        }
        
        $precioprod = $p->costopromediokardex + $p->ajuste1 + $p->ajuste2 + ( ($p->costopromediokardex + $p->ajuste1 + $p->ajuste2) * $utilidadventaprod )/100;

        $iva_porcent = get_settings('IVA');            
        $precioprod_iva = $precioprod + ( $precioprod * $iva_porcent ) / 100;

            $precio_minimo = $precioprod;
                if($p->descmaxporcent > 0){
                    $val_division_desc = ($p->descmaxporcent / 100) + 1;
                    $desc_prod = $precioprod - ($precioprod / $val_division_desc);                                                        
                    $precio_minimo = $precioprod - $desc_prod;                            
                }                            
        $precios_prod = array('price'=>$precioprod,'price_min'=>$precio_minimo,'price_iva'=>$precioprod_iva);

        return $precios_prod;
    }
    
    
    public function get_product_data($product_id) {
        $fields = 'codigo, costopromediokardex, costoultimokardex, esServicio';
        $product_data = $this->ci->generic_model->get_data( 
                    'billing_producto', 
                    array('codigo'=>$product_id), 
                    $fields, 
                    null, 
                    1
                );
        return $product_data;
    }
    
}