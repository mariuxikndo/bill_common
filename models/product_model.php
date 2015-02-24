<?php

class Product_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
        
        function autosugest_by_name($param) {
            $parambuscaprod = explode('%20', $param);
            $where = '';
            $and = '';            
            foreach ( $parambuscaprod as $val ) {
                $where .= $and.'(UPPER(nombreUnico) LIKE "%'.strtoupper($val).'%" )';
                $and = ' AND ';                 
            }           
            $this -> db -> where($where, null, false); 
            $this -> db -> select( 'p.codigo ci, nombreUnico value');
            $this -> db -> limit(10); 
            $this -> db -> from('billing_producto p'); 
            $query = $this -> db -> get();
            return $query->result();
        }
}