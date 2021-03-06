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
class Ventautilidad {
    
    private $ci;
    
    function __construct(){
        $this->ci = & get_instance();
    }    
 
    /* 
     * Registramos la utilidad generada por la venta 
     * $utilidad = '+' = suma la utlidad     
     * $utilidad = '-' = resta la utlidad     
     */
    public function save_utilidad_venta($doc_id, $costo, $pvp_bienes, $pvp_servicios, $tipotransaccion_cod, $utilidad = '+') {
        $res = false;
        /* No comparamos con $pvp_bienes porque este si podria venir en cero en caso de los ajustes de salida */
        if($costo > 0){
            $utilidad = $pvp_bienes - $costo;
            $utilidad_porcent = ($utilidad * 100)/$costo;
            $data = array(
                'venta_id' => $doc_id,
                'costo' => $costo,
                'pvp' => $pvp_bienes,
                'utilidad' => $utilidad,
                'utilidad_porcent' => $utilidad_porcent,
                'tipotransaccion_cod' => $tipotransaccion_cod,
            );
            $res = $this->ci->generic_model->save($data, 'bill_venta_utilidad');            
        }

        /* La utililidad negativa que por lo general es por ajuste de salida, re registra como servicio en negativo */
        if($pvp_servicios > 0){
            if($utilidad == '-'){
                $pvp_servicios = $pvp_servicios * -1;
                $utilidad_porcent = 0;
            }else{
                /* si no es ajuste de salida, y es venta, la utilidad por un servicio es del 100%*/
                $utilidad_porcent = 100;
            }
        
            $data = array(
                'venta_id' => $doc_id,
                'costo' => 0,
                'pvp' => $pvp_servicios,
                'utilidad' => $pvp_servicios,
                'utilidad_porcent' => $utilidad_porcent,
                'tipotransaccion_cod' => $tipotransaccion_cod,
            );

            $res = $this->ci->generic_model->save($data, 'bill_venta_utilidad');            
        }
        
        return $res;
    }    
    
        /* 
         * Obtiene la utilidad incluyendo los servicios que tiene costo cero por tanto todo antes de impuestos es utilidad 
         */
        public function get_utilidad_total($venta_id, $tipotransaccion_cod = -1) {
            $where_data = array('venta_id'=>$venta_id);            
            if($tipotransaccion_cod != -1){
                $where_data['tipotransaccion_cod'] = $tipotransaccion_cod;
            }
            
            $total_utilidad = $this->ci->generic_model->sum_table_field( 
                        'bill_venta_utilidad', 
                        'utilidad', 
                        $where_data
                    );
            
            return $total_utilidad;
        }
        
        /* Se obtiene el costo total de la venta, restando ajustes de salida */
        public function get_costo_total($venta_id, $tipotransaccion_cod = -1) {
            $where_data = array('venta_id'=>$venta_id);
            if($tipotransaccion_cod != -1){
                $where_data['tipotransaccion_cod'] = $tipotransaccion_cod;
            }
            
            $total_utilidad = $this->ci->generic_model->sum_table_field( 
                        'bill_venta_utilidad', 
                        'costo', 
                        $where_data
                    );
            
            return $total_utilidad;
        }
        
        public function get_pvp_total($venta_id, $tipotransaccion_cod = -1) {
            $where_data = array('venta_id'=>$venta_id);
            if($tipotransaccion_cod != -1){
                $where_data['tipotransaccion_cod'] = $tipotransaccion_cod;
            }
            
            $total_pvp = $this->ci->generic_model->sum_table_field( 
                        'bill_venta_utilidad', 
                        'pvp',
                        $where_data
                    );
            
            return $total_pvp;
        }
    
        /*
         * Se presenta la utilidad en porcentaje, respecto al pvp y costo
         */
//        public function get_utilidad_porcent($venta_id, $tipotransaccion_cod = -1) {
//            $total_utilidad = $this->get_utilidad_total($venta_id,$tipotransaccion_cod);            
//            $costo_total = $this->get_costo_total($venta_id,$tipotransaccion_cod);
//            
//            if($costo_total > 0){
//                $utilidad_porcent = ($total_utilidad * 100)/$costo_total;                
//                return $utilidad_porcent;                            
//            }else{ /* Si no tiene costo, la utilidad es del 100%*/
//                return 100;
//            }
//        }
        
        /*
         * Obtenemos la utilidad en porcentaje con respecto al  pvp
         */
        public function get_utilidad_porcent($venta_id, $tipotransaccion_cod = -1) {
            $total_utilidad = $this->get_utilidad_total($venta_id,$tipotransaccion_cod);            
            $pvp_total = $this->get_pvp_total($venta_id,$tipotransaccion_cod);
            
            if($pvp_total > 0){
                $utilidad_porcent = ($total_utilidad * 100)/$pvp_total;
                return $utilidad_porcent;
            }else{ /* Si no tiene costo, la utilidad es del 100%*/
                return 100;
            }
        }

}
