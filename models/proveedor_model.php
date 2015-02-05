<?php

class Proveedor_model extends CI_Model {

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
                $where .= $and.'(UPPER(billing_proveedor.nombres) LIKE "%'.strtoupper($val).'%" OR UPPER(billing_proveedor.apellidos) LIKE "%'.strtoupper($val).'%" )';
                $and = ' AND ';    
            }
            $this -> db -> where($where, null, false);            
                        
            $this -> db -> select( 'id ci, PersonaComercio_cedulaRuc ruc, CONCAT_WS(" ",nombres," ",apellidos) value', FALSE);
            $this -> db -> from('billing_proveedor');  
            $query = $this -> db -> get();
            return $query->result();     
        }
}