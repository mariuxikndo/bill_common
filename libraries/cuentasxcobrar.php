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
class Cuentasxcobrar {
    
    private $ci;
    
    function __construct(){
        $this->ci = & get_instance();
    }    

    /* Sumamos una nueva cuenta por cobrar al cliente */
    public function add_cxc( $cxc_val, $client_id, $tipotransaccion_cod, $doc_id, $vence_cuota, $tipopago_cod, $observaciones, $idcuota = 1, $nrocuotas = 1, $vencecadadias = 1 ) {
            $saldo_client = $this->get_client_saldo($client_id);
            $new_saldo_client = $saldo_client + $cxc_val; /* restamos el valor del cheque*/                                          
                                
            /* Registramos la cuenta x cobrar */
                $cxc = array(
                    'doc_id' => $doc_id,
                    'tipotransaccion_cod' => $tipotransaccion_cod,
                    'tipopago_id' => $tipopago_cod,
                    'total_neto' => $cxc_val,
                    'vencecadadias' => $vencecadadias,
                    'nrocuotas' => $nrocuotas,
                    'idcuota' => $idcuota,
                    'cuota_neto' => $cxc_val,
                    'vence_cuota' => $vence_cuota,
                    'balance' => $cxc_val,
                    'observaciones' => $observaciones,
                    'valor_cobrado' => 0,
                    'fecha_cobro' => null,
                    'valor_cobrado_bruto' => 0,
                    'cambio' => 0,
                    'saldototal' => $cxc_val,
                    'fecha' => date('Y-m-d',time()),
                    'hora' => date('H:i:s',time()), 
                    'client_id' => $client_id, 
                    'saldo_client' => $new_saldo_client,
                );
                $cxc_id = $this->ci->generic_model->save($cxc,'bill_cxc');
                $this->update_cxc_saldos($client_id, $new_saldo_client);                
                
                return $cxc_id;
    }
    
    public function update_cxc_saldos($client_id, $new_saldo) {
        $res = false;
        $count_sb = $this->ci->generic_model->count_all_results( 'bill_cxc_saldos', array( 'client_id'=>$client_id ) );
        if($count_sb){ /* si ya existen registros de este producto en esta bodega, actualizamos el stock*/
            $res = $this->ci->generic_model->update( 'bill_cxc_saldos', array('saldo'=>$new_saldo, 'fecha'=>date('Y-m-d',time())), array('client_id'=>$client_id) );
        }else{ /* caso contrario agregamos el registro con el stock correspondiente */
            $res = $this->ci->generic_model->save(array('saldo'=>$new_saldo,'client_id'=>$client_id, 'fecha'=>date('Y-m-d',time())),'bill_cxc_saldos');
        }
        return $res;
    }
    
    public function get_client_saldo($client_id) {
       $saldo_client = $this->ci->generic_model->get_val_where( 'bill_cxc_saldos', array('client_id'=>$client_id) , 'saldo', '', 0 );        
       return $saldo_client;
    }
    
    /*
     * se crean nuevos registros en cuentas x cobrar al momento de anularla, 
     * ademas se cambia el estado de la que se anula
     */
    public function anular_by_id($cxc_id) {
        
        $cxc = $this->ci->generic_model->get( 'bill_cxc', array('id'=>$cxc_id), '', $order_by = null, 1 );
        
        $saldo_client = $this->get_client_saldo($cxc->client_id);
        $new_saldo_client = $saldo_client - $cxc->balance; /* restamos el valor del cheque*/                            

            /* Registramos la cuenta x cobrar */
            $data_cxc = array(
                'doc_id' => $cxc->doc_id,
                'tipotransaccion_cod' => $cxc->tipotransaccion_cod,
                'tipopago_id' => $cxc->tipopago_id,
                'total_neto' => $cxc->total_neto * -1,
                'vencecadadias' => $cxc->vencecadadias,
                'idcuota' => $cxc->idcuota,
                'nrocuotas' => $cxc->nrocuotas,
                'cuota_neto' => $cxc->cuota_neto * -1,
                'vence_cuota' => $cxc->vence_cuota,
                'balance' => $cxc->balance,
                'observaciones' => $cxc->observaciones,
                'valor_cobrado' => $cxc->valor_cobrado,
                'fecha_cobro' => $cxc->fecha_cobro,
                'valor_cobrado_bruto' => $cxc->valor_cobrado_bruto,
                'cambio' => $cxc->cambio,
                'saldototal' => $cxc->saldototal,
                'saldototal' => $cxc->saldototal,
                'fecha' => date('Y-m-d',time()),
                'hora' => date('H:i:s',time()), 
                'client_id' => $cxc->client_id,
                'saldo_client' => $new_saldo_client, 
                'cxc_anulada_id' => $cxc->id 
            );
            $res = $this->ci->generic_model->save($data_cxc,'bill_cxc');  
            if($res <= 0){
                return false;
            }
            $res = $this->update_cxc_saldos($cxc->client_id, $new_saldo_client);
            
            return $res;
    }
    
    
   /* se anula las cuentas x cobrar generadas por un documento */
   public function anular_by_doc($doc_id, $tipotransaccion_cod) {
       $message = '';       
        $cxc_info = $this->ci->generic_model->get('bill_cxc', array('doc_id'=>$doc_id,'tipotransaccion_cod'=>$tipotransaccion_cod,'estado'=>'1'), 'id', $order_by = null, $rows_num = 0 );

        if($cxc_info){
            foreach ($cxc_info as $cxc) {
                $res = $this->anular_by_id($cxc->id);
                if(!$res){
                    return false;
                }else{
                    $message .= info_msg(' Se ha anulado la Cuenta x Cobrar Nro. '.$cxc->id);                            
                }
            }
            
            $res = $this->ci->generic_model->update( 'bill_cxc', array('estado'=>'-1'), array('doc_id'=>$doc_id,'tipotransaccion_cod'=>$tipotransaccion_cod,'cxc_anulada_id'=>null) );
            
            if($res > 0){
                return $message;
            }else{
                return false;
            }
        }else{
            $message .= info_msg(' Este documento no ha generado cuentas x cobrar.'); 
        }        
        return $message;
   }
       
   /* obtener el balance de una cxc */
   public function get_balance($cxc_id) {
       $cxc_data = $this->ci->generic_model->get_data( 
                'bill_cxc', 
                array( 'id'=>$cxc_id ),
                'balance', 
               null,
                1
               );       
       return $cxc_data->balance;
   }
   
}
