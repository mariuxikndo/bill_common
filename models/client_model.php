<?php

class Client_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	// --------------------------------------------------------------------             
        
        /*
         * Para buscar el cliente, autosugerencia 
         */
        function autosugest_by_name($param) {
            $parambuscaprod = explode('%20', $param);
            $where = '';
            $and = '';
            foreach ( $parambuscaprod as $val ) {
                $where .= $and.'(UPPER(billing_cliente.nombres) LIKE "%'.strtoupper($val).'%" OR UPPER(billing_cliente.apellidos) LIKE "%'.strtoupper($val).'%" )';
                $and = ' AND ';    
            }
            $this -> db -> where($where, null, false);            
                        
            $this -> db -> select( 'PersonaComercio_cedulaRuc ci, CONCAT_WS(" ",nombres," ",apellidos) value, apellidos, razonsocial razon_social', FALSE);
            $this -> db -> from('billing_cliente');  
            $query = $this -> db -> get();
            return $query->result();     
        }
}