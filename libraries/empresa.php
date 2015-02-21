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
class Empresa {
    
    private $ci;
    
    function __construct(){
        $this->ci = & get_instance();
    }    

    public function get_data() {
        $fields = '';
        $res = $this->ci->generic_model->get_data( 
                    'billing_empresa', 
                    array('id >'=>0), 
                    $fields, 
                    null,
                    1
                );
        return $res;          
    }

    /* El stock total del producto se encuentra directamente en la tabla billing_producto */
    public function get_ruc() {
        $this->ci->load->library('encript');
        $encrypted_ruc = $this->ci->generic_model->get_val_where('billing_empresa', array('id >'=>0),'ruc', null, 0);
        $ruc = $this->ci->encript->decryptbase64($encrypted_ruc, get_settings('PASSWORDSALTMAIN'));
        return $ruc;        
    }    
}