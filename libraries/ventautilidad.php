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
 
    /* Registramos la utilidad generada por la venta */
    public function save_utilidad_venta($venta_id, $costo, $pvp_bienes, $pvp_servicios,$tipotransaccion_cod) {
        $res = false;
        if($pvp_bienes > 0){
            $utilidad = $pvp_bienes - $costo;
            $utilidad_porcent = ($utilidad * 100)/$costo;
            $data = array(
                'venta_id' => $venta_id,
                'costo' => $costo,
                'pvp' => $pvp_bienes,
                'utilidad' => $utilidad,
                'utilidad_porcent' => $utilidad_porcent,
                'tipotransaccion_cod' => $tipotransaccion_cod,
            );
            $res = $this->ci->generic_model->save($data, 'bill_venta_utilidad');            
        }
        
        if($pvp_servicios > 0){
            $data = array(
                'venta_id' => $venta_id,
                'costo' => 0,
                'pvp' => $pvp_servicios,
                'utilidad' => $pvp_servicios,
                'utilidad_porcent' => 100,
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
    
        /*
         * Se presenta la utilidad en porcentaje, respecto al pvp y costo
         */
        public function get_utilidad_porcent($venta_id, $tipotransaccion_cod = -1) {
            
            $where_data = array('venta_id'=>$venta_id);            
            if($tipotransaccion_cod != -1){
                $where_data['tipotransaccion_cod'] = $tipotransaccion_cod;
            }
            
            $utilidad_porcent = $this->ci->generic_model->get_data( 
                        'bill_venta_utilidad', 
                        $where_data, 
                        'utilidad_porcent',
                        null, 
                        $rows_num = 0
                    );
            
            if($utilidad_porcent){
                $sum_utilidad = 0;            
                $cont = 0;
                        foreach ($utilidad_porcent as $value) {
                            $sum_utilidad += $value->utilidad_porcent;
                            $cont++;
                        }
                $prom_utilidad = $sum_utilidad / $cont;            
                return $prom_utilidad;                
            }else{
                return 'NN';
            }
        }    

}
