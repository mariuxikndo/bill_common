<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Administramos los indices de las empresas
 *
 * @author estebanch
 */
class Empresaindice {
    
    private $ci;
    
    function __construct(){
        $this->ci = & get_instance();
    }    
 
    function get_indice($indice_name){
        $val = $this->ci->generic_model->get_val('bill_empresa_indice', $indice_name, 'valor_indice', 'indice_name', null, 0);
        return $val;
    }
    
    function update_indice($indice_name, $indice_val){
        $res = $this->ci->generic_model->update(
                    'bill_empresa_indice',
                    array('valor_indice'=>$indice_val),
                    array('indice_name'=>$indice_name)
                );        
        if($res > 0){
            return true;
        }else{
            return false;
        }
    }

}