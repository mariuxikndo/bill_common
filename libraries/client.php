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
class Client {
    
    private $ci;
    private $obj_cuentasxcobrar;
    
    function __construct(){
        $this->ci = & get_instance();
        $this->ci->load->library('cuentasxcobrar');
        $this->obj_cuentasxcobrar = new Cuentasxcobrar();
    }
 

    public function get_cupo_credito($client_id){
         $cupo_client = $this->ci->generic_model->get(
                    'billing_cliente', 
                    array('PersonaComercio_cedulaRuc'=>$client_id), 
                    'cupocredito,cupo_temporal', 
                    $order_by = null, 
                    1 
                 );
         $cupo_asignado = $cupo_client->cupocredito + $cupo_client->cupo_temporal;
         $tot_ch_custodio = $this->ci->generic_model->sum_table_field( 
                    'bill_chequescustodio', 
                    'valorcheque', 
                    array('estado'=>'1','cliente_cedulaRuc'=>$client_id) 
                 );   
         $tot_cxc = $this->obj_cuentasxcobrar->get_client_saldo($client_id);
         $cupo_disponible = $cupo_asignado - $tot_ch_custodio - $tot_cxc;
         return number_decimal($cupo_disponible);
    }
                
}
