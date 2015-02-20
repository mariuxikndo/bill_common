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
class Retencioncompra {
    
    private $ci;
    
    function __construct(){
        $this->ci = & get_instance();
    }    

        /* 
         * Cuando se marca el campo no aplica retencion, se crea entonces automaticamente una retencion 
         * en valor cero
         * codigo 332
         * base_imponible : es el subtotal
         * porcentaje retencion 0%
         */
        public function crear_retencion_cero($compra_id, $base_imponible, $proveedor_id) {
            $cod_impuesto = get_settings('RETENCION_CERO_COD');
            $sri_cod_retencion = $this->ci->generic_model->get_data( 
                        'bill_sri_retencion', 
                        array('cod'=>$cod_impuesto), 
                        $fields = '', 
                        $order_by = null, 
                        1 /* una fila */
                    );
            
                $data_ret = array(
                    'total' => 0,
                    'proveedor_id' => $proveedor_id,
                    'establecimiento' => 'NNN', /* no se requiere estalecimieno ni punto de emision, solo es para sacar el reporte*/
                    'pemision' => 'NNN',
                    'nro' => time(),
                    'empleado_id' => $this->ci->user->id,
                    'estado' => '1', /* 1 = Activa, 2 = anulada*/
                    'doc_id' => $compra_id,
                    'tipo_transaccion' => '02',
                    'fecha' => date( 'Y-m-d', time() ),
                    'hora' => date( 'H:i:s', time() ),
                    'nroAutorizacion' => '000',
                    'vence_autorizacion_sri' => date( 'Y-m-d', time() ),
                    'user_id' => $this->ci->user->id
                );

                $retencion_id = $this->ci->generic_model->save( $data_ret, 'bill_retencion' );

                $data_ret_det = array(
                    'retencion_id' => $retencion_id,
                    'sri_retencion_id' => $sri_cod_retencion->id,
                    'impuesto_renta' => $sri_cod_retencion->impuesto_renta,
                    'base_imponible' => $base_imponible,
                    'cod_impuesto' => $cod_impuesto,
                    'porcent_ret' => 0,
                    'val_retenido' => 0
                );
                $ret_det_id = $this->ci->generic_model->save( $data_ret_det, 'bill_retencion_det' );          
        }
}
