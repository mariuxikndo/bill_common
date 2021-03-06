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
class Cliente_anticipo {
    private $ci;
    private $tipotransaccion_cod = '18';
    
    function __construct(){
        $this->ci = & get_instance();
    }    

        public function save_anticipo_data($client_id, $anticipo_val, $anticipo_anulado_id = null, $detalle = '', $detalle_anulacion = '') {
            /* Obtenemos el ultimo registro activo de anticipos al proveedor */
            $saldo_client = $this->get_anticipo_saldo($client_id);
            $new_saldo_client = $saldo_client + $anticipo_val;
            
            $user_id_anulacion = null;
            if(!empty($anticipo_anulado_id)){
                $user_id_anulacion = $this->ci->user->id;
            }
            
            /* Registramos el anticipo */
            $anticipo_data = array(
                'anticipo' => $anticipo_val,
                'saldo' => $new_saldo_client,
                'client_id' => $client_id,
                'detalle' => $detalle,
                'empleado_id' => $this->ci->user->id,
                'fecha' => date('Y-m-d',time()),
                'hora' => date('H:i:s',time()),
                'estado' => 1,
                'anticipo_anulado_id' => $anticipo_anulado_id,
                'detalle_anulacion' => $detalle_anulacion,
                'user_id_anulacion' => $user_id_anulacion,
            );
            $anticipo_id = $this->ci->generic_model->save($anticipo_data,'bill_cliente_anticipo');
            
            $upd_saldo_ultimo = $this->update_anticipo_saldos($client_id, $new_saldo_client);
            return $anticipo_id;
            
        }        

        public function update_anticipo_saldos($client_id, $new_saldo) {
            $res = false;
            $count_sb = $this->ci->generic_model->count_all_results( 'bill_cliente_anticipo_saldos', array( 'client_id'=>$client_id ) );
            if($count_sb){ /* si ya existen registros de este producto en esta bodega, actualizamos el stock*/
                $res = $this->ci->generic_model->update( 
                            'bill_cliente_anticipo_saldos', 
                            array('saldo'=>$new_saldo,'fecha'=>date('Y-m-d',time())), 
                            array('client_id'=>$client_id) 
                        );
            }else{ /* caso contrario agregamos el registro con el stock correspondiente */
                $res = $this->ci->generic_model->save(
                        array('saldo'=>$new_saldo, 'client_id'=>$client_id,'fecha'=>date('Y-m-d',time())), 
                        'bill_cliente_anticipo_saldos' 
                );
            }
            return $res;
        }
        
        public function get_anticipo_saldo($client_id) {
           $saldo_client = $this->ci->generic_model->get_val_where( 'bill_cliente_anticipo_saldos', array('client_id'=>$client_id) , 'saldo', '', 0 );        
           return $saldo_client;
        }
        
        public function anular_anticipo($anticipo_id, $detalle_anulacion = 'ANULACION DEL ANTICIPO') {
            $res = false;
            if($anticipo_id > 0){                
                $anticipo_data = $this->ci->generic_model->get_by_id('bill_cliente_anticipo', $anticipo_id);
                
                $tot_anticipo = $this->get_anticipo_saldo($anticipo_data->client_id);

                    /* 
                     * Solo si el total de anticipos disponibles es mayor al anticipo que se va a anular lo permito
                     * caso contrario ya no es posible 
                     */
                    if( $tot_anticipo >= $anticipo_data->anticipo ){
                        $detalle = $detalle_anulacion;
                        $regreso_anticipo = $this->save_anticipo_data($anticipo_data->client_id, $anticipo_data->anticipo * -1, $anticipo_data->id, $detalle, $detalle_anulacion );

                        if( $regreso_anticipo > 0 ){
                            $this->ci->generic_model->update( 'bill_cliente_anticipo', array('estado'=>'-1','detalle_anulacion'=>$detalle_anulacion,'user_id_anulacion'=>$this->ci->user->id), array('id'=>$anticipo_id) );
                            echo info_msg('Se ha anulado el anticipo.');
                            $res = true;
                        }else{
                            echo warning_msg('No fue posible anular el anticipo.');
                            return false;
                        }   
                    }else{
                        echo warning_msg('El cliente dispone de un saldo de anticipos menor('.$tot_anticipo.')  al que intenta anular ('.$anticipo_data->anticipo.').');
                        return false;
                    }
            }
            return $res;
        }        
}
