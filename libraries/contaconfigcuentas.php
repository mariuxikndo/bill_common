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
class Contaconfigcuentas {
    
    private $ci;
    
    function __construct(){
        $this->ci = & get_instance();
    }    

    /* Obtenemos la cuenta configurada de acuerdo al codigo */
    public function get_setting_account($cod) {
        $res = $this->ci->generic_model->get_val_where('billing_contaconfigcuentas', array('cod'=>$cod),'contacuentasplan_cod', null, -1);
        return $res;
    }    
}
