<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of autosuggest
 *
 * @author estebanch
 */
class Autosuggest extends MX_Controller{
    
	function __construct()
	{
		parent::__construct();
	}
    
        function get_plan_cuentas_by_name( $param ) {            
            $this->load->model('plancuentas_model');
            $res = $this->plancuentas_model->get_plan_cuentas_by_name($param);
            if(!empty($res)) {
                    echo json_encode($res);
            } else {
                    echo '{"id":"--","name":"No hay resultados para '.$param.'"}';
            }
        }
                
        function get_client_by_name( $param ) {
            $this->load->model('client_model');            
            $res = $this->client_model->autosugest_by_name($param);
            	            
            if(!empty($res)) {
                    echo json_encode($res);
            } else {
                    echo '{"id":"--","name":"No hay resultados para '.$param.'"}';
            }
        }        
        
        function get_proveedor_by_name( $param ) {
            $this->load->model('proveedor_model');            
            $res = $this->proveedor_model->autosugest_by_name($param);
            	            
            if(!empty($res)) {
                    echo json_encode($res);
            } else {
                    echo '{"id":"--","name":"No hay resultados para '.$param.'"}';
            }
        }
        
        function get_proveedor_by_ci( $param ) {
//            $res = $this->generic_model->get('billing_proveedor', array('PersonaComercio_cedulaRuc'=>$param), 'PersonaComercio_cedulaRuc ci, nombres value', null, 15 );            
            $res = $this->generic_model->get('billing_proveedor', array('PersonaComercio_cedulaRuc'=>$param), 'id ci, PersonaComercio_cedulaRuc ruc, CONCAT_WS(" ",nombres," ",apellidos) value', null, 15 );            
            if(!empty($res)) {
                    echo json_encode($res);
            } else {
                    echo '{"id":"--","name":"No hay resultados para '.$param.'"}';
            }
        }
        
        function get_client_by_ci( $param ) {
            $res = $this->generic_model->get('billing_cliente', array('PersonaComercio_cedulaRuc'=>$param), $fields = 'PersonaComercio_cedulaRuc ci, nombres value', null, 15 );            
            if(!empty($res)) {
                    echo json_encode($res);
            } else {
                    echo '{"id":"--","name":"No hay resultados para '.$param.'"}';
            }
        }
        
        
        function get_product_by_name( $param ) {
            $this->load->model('product_model');
            $res = $this->product_model->autosugest_by_name($param);
//            $parambuscaprod = explode('%20', $param);
//            
//            $or_like_data = array();
//            foreach ( $parambuscaprod as $val ) {
//                $and_like_data['nombreUnico'] = $val;
//            }
//
//            $res = $this->generic_model->get('billing_producto', null, $fields = 'codigo ci, nombreUnico value', null, 15, $or_like_data, $and_like_data );
            if(!empty($res)) {
                    echo json_encode($res);
            } else {
                    echo '{"id":"--","name":"No hay resultados para '.$param.'"}';
            }
        }
        
        function get_product_by_cod( $param ) {
            $res = $this->generic_model->get('billing_producto', array('codigo'=>$param), $fields = 'codigo ci, nombreUnico value', null, 15 );
            if(!empty($res)) {
                    echo json_encode($res);
            } else {
                    echo '{"id":"--","name":"No hay resultados para '.$param.'"}';
            }
        }
        
        /* Eliminamos el cliente cargado por autosuggest*/
        public function quit_client() {
            echo tagcontent('script', '$("#client_name").html("Cliente: Todos")');
            echo tagcontent('script', '$("#client_id").val("")');
        }        
    
}
