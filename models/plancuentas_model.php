<?php

class Plancuentas_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	// --------------------------------------------------------------------             
        
        /*
         * Para buscar el cliente, autosugerencia 
         */
        function get_plan_cuentas_by_name($param) {
            $parambuscaprod = explode('%20', $param);
            $where = '';
            $and = '';
            foreach ( $parambuscaprod as $val ) {
                $where .= $and.'(UPPER(billing_contacuentasplan.nombre) LIKE "%'.strtoupper($val).'%" )';
                $and = ' AND ';    
            }
            $this -> db -> where($where, null, false);            
                        
            $this -> db -> select( 'cod ci, nombre value', FALSE);
            $this -> db -> from('billing_contacuentasplan');  
            $query = $this -> db -> get();
            return $query->result();     
        }
}