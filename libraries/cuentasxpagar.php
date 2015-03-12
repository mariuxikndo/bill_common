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
class Cuentasxpagar {
    
    private $ci;
    
    function __construct(){
        $this->ci = & get_instance();
    }    

    /* Sumamos una nueva cuenta por cobrar al cliente */
    public function add_cxp( $cxp_val, $proveedor_id, $tipotransaccion_cod, $doc_id, $vence_cuota, $tipopago_cod, $observaciones ) {
            $saldo_proveedor = $this->get_proveedor_saldo($proveedor_id);
            $new_saldo_proveedor = $saldo_proveedor + $cxp_val; /* restamos el valor del cheque*/                                          

                $cxp = array(
                    'compra_id' => $doc_id,
                    'tipopago_id' => $tipopago_cod,
                    'totaldeuda' => $cxp_val,
                    'vencecadadias' => 1,
                    'nrocuotas' => 1,
                    'idcuota' => 1,
                    'valorcuotaindividual' => $cxp_val,
                    'vencecuotaindividual' => $vence_cuota,
                    'balance' => $cxp_val,
                    'observaciones' => $observaciones,
                    'valor_pagado' => 0,
                    'fechapago' => null,
                    'saldototal' => $cxp_val,
                    'fecha' => date('Y-m-d',time()),
                    'hora' => date('H:i:s',time()), 
                    'proveedor_id' => $proveedor_id, 
                    'saldo_proveedor' => $new_saldo_proveedor, 
                    'tipotransaccion_cod' => $tipotransaccion_cod, 
                    'user_id' => $this->ci->user->id, 
                );
                $cxc_id = $this->ci->generic_model->save($cxp,'bill_cxp');
                $this->update_cxp_saldos($proveedor_id, $new_saldo_proveedor);                
                
                return $cxc_id;
    }
    
    public function bajar_cxp($compra_id, $total_recibido_pago, $proveedor_id,$tipotransaccion_cod) {
        $cxp = $this->ci->generic_model->get_by_id('bill_cxp', $compra_id, 'balance,saldototal', 'compra_id');
        $new_balance = $cxp->balance - $total_recibido_pago;
        $new_saldototal = $cxp->saldototal - $total_recibido_pago;

        $data_set = array(
            'balance' => $new_balance,
            'saldototal' => $new_saldototal,
            'valor_pagado' => $total_recibido_pago
        );

        $res = $this->ci->generic_model->update( 'bill_cxp', $data_set, array('compra_id'=>$compra_id) );

        /*****************************************************************/
            /* 
             * Obtenemos el ultimo saldo que tenemos de la deuda con el proveedor, para luego actualizar este valor 
             */
        $saldo_proveedor = $this->get_proveedor_saldo($proveedor_id);        
        $new_saldo_proveedor = $saldo_proveedor - $total_recibido_pago; /* restamos el valor del cheque*/                            

            /* Registramos la cuenta x cobrar */
            $cxp = array(
                'compra_id' => $compra_id,
                'tipopago_id' => null,
                'totaldeuda' => $total_recibido_pago * -1,
                'vencecadadias' => 0,
                'nrocuotas' => 0,
                'idcuota' => 0,
                'valorcuotaindividual' => $total_recibido_pago * -1,
                'vencecuotaindividual' => date('Y-m-d', time()),
                'balance' => 0,
                'observaciones' => 'Se aplica el pago a la CxP',
                'valor_pagado' => 0,
                'fechapago' => date('Y-m-d', time()),
                'saldototal' => $total_recibido_pago * -1,
                'fecha' => date('Y-m-d',time()),
                'hora' => date('H:i:s',time()), 
                'proveedor_id' => $proveedor_id, 
                'saldo_proveedor' => $new_saldo_proveedor, 
                'tipotransaccion_cod' => $tipotransaccion_cod, 
            );
            $cxp_id = $this->ci->generic_model->save($cxp,'bill_cxp');  

            $this->update_cxp_saldos($proveedor_id, $new_saldo_proveedor);              
        /*****************************************************************/ 
            
            return $cxp_id;
    }
    
    public function update_cxp_saldos($proveedor_id, $new_saldo) {
        $res = false;
        $count_sb = $this->ci->generic_model->count_all_results( 'bill_cxp_saldos', array( 'proveedor_id'=>$proveedor_id ) );
        if($count_sb){ /* si ya existen registros de este producto en esta bodega, actualizamos el stock*/
            $res = $this->ci->generic_model->update( 'bill_cxp_saldos', array('saldo'=>$new_saldo, 'fecha'=>date('Y-m-d',time())), array('proveedor_id'=>$proveedor_id) );
        }else{ /* caso contrario agregamos el registro con el stock correspondiente */
            $res = $this->ci->generic_model->save(array('saldo'=>$new_saldo,'proveedor_id'=>$proveedor_id, 'fecha'=>date('Y-m-d',time())),'bill_cxp_saldos');
        }
        return $res;
    }
    
    public function get_proveedor_saldo($proveedor_id) {
       $saldo_proveedor = $this->ci->generic_model->get_val_where( 'bill_cxp_saldos', array('proveedor_id'=>$proveedor_id) , 'saldo', '', 0 );        
       return $saldo_proveedor;
    }
    
    /*
     * se crean nuevos registros en cuentas x cobrar al momento de anularla, 
     * ademas se cambia el estado de la que se anula
     */
//    public function anular_by_id($cxc_id) {
//        
//        $cxc = $this->ci->generic_model->get( 'bill_cxc', array('id'=>$cxc_id), '', $order_by = null, 1 );
//        
//        $saldo_client = $this->get_client_saldo($cxc->client_id);
//        $new_saldo_client = $saldo_client - $cxc->balance; /* restamos el valor del cheque*/                            
//
//            /* Registramos la cuenta x cobrar */
//            $data_cxc = array(
//                'doc_id' => $cxc->doc_id,
//                'tipotransaccion_cod' => $cxc->tipotransaccion_cod,
//                'tipopago_id' => $cxc->tipopago_id,
//                'total_neto' => $cxc->total_neto * -1,
//                'vencecadadias' => $cxc->vencecadadias,
//                'idcuota' => $cxc->idcuota,
//                'nrocuotas' => $cxc->nrocuotas,
//                'cuota_neto' => $cxc->cuota_neto * -1,
//                'vence_cuota' => $cxc->vence_cuota,
//                'balance' => $cxc->balance,
//                'observaciones' => $cxc->observaciones,
//                'valor_cobrado' => $cxc->valor_cobrado,
//                'fecha_cobro' => $cxc->fecha_cobro,
//                'valor_cobrado_bruto' => $cxc->valor_cobrado_bruto,
//                'cambio' => $cxc->cambio,
//                'saldototal' => $cxc->saldototal,
//                'saldototal' => $cxc->saldototal,
//                'fecha' => date('Y-m-d',time()),
//                'hora' => date('H:i:s',time()), 
//                'client_id' => $cxc->client_id,
//                'saldo_client' => $new_saldo_client, 
//                'cxc_anulada_id' => $cxc->id 
//            );
//            $res = $this->ci->generic_model->save($data_cxc,'bill_cxc');  
//            if($res <= 0){
//                return false;
//            }
//            $res = $this->update_cxc_saldos($cxc->client_id, $new_saldo_client);
//            
//            return $res;
//    }
    
    
   /* se anula las cuentas x cobrar generadas por un documento */
//   public function anular_by_doc($doc_id, $tipotransaccion_cod) {
//       $message = '';       
//        $cxc_info = $this->ci->generic_model->get('bill_cxc', array('doc_id'=>$doc_id,'tipotransaccion_cod'=>$tipotransaccion_cod,'estado'=>'1'), 'id', $order_by = null, $rows_num = 0 );
//
//        if($cxc_info){
//            foreach ($cxc_info as $cxc) {
//                $res = $this->anular_by_id($cxc->id);
//                if(!$res){
//                    return false;
//                }else{
//                    $message .= info_msg(' Se ha anulado la Cuenta x Cobrar Nro. '.$cxc->id);                            
//                }
//            }
//            
//            $res = $this->ci->generic_model->update( 'bill_cxc', array('estado'=>'-1'), array('doc_id'=>$doc_id,'tipotransaccion_cod'=>$tipotransaccion_cod,'cxc_anulada_id'=>null) );
//            
//            if($res > 0){
//                return $message;
//            }else{
//                return false;
//            }
//        }else{
//            $message .= info_msg(' Este documento no ha generado cuentas x cobrar.'); 
//        }
//        
//        return $message;
//   }
    
}
