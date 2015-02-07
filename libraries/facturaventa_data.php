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
class Facturaventa_data {
    
    private $ci;
    
    function __construct(){
        $this->ci = & get_instance();
    }    
 
    public function obtener_datos_factura($codFact) {
        $fields = array(
            'codigofactventa',
            'fechaCreacion',
            'subtotalBruto',
            'subtotalNeto',
            'descuentovalor',
            'iceval',
            'ivaval',
            'totalCompra',
            'empleado_vendedor',
            'bodega_id',
            'tecnico_id',
            'user_id',
            'tipoprecio',
            'tipo_pago',
            'observaciones',
            'puntoventaempleado_tiposcomprobante_cod',
            'estado',
            'autorizado_sri',
            'tarifacerobruto',
            'tarifadocebruto',
            'recargovalor',
            'cliente_cedulaRuc',
        );
        $fact = $this->ci->generic_model->get('billing_facturaventa', array('codigofactventa' => $codFact), $fields, null, 1);
        return $fact;
    }

    public function obtener_datos_cliente($ruc_cliente) {
        $fields = array(
            'PersonaComercio_cedulaRuc',
            'nombres',
            'apellidos',
            'direccion',
            'telefonos',
            'email');
        $cliente = $this->ci->generic_model->get('billing_cliente', array('PersonaComercio_cedulaRuc' => $ruc_cliente), $fields, 0);
        return $cliente;
    }

    public function obtener_detalle_factura($codFact) {
        $fields = array(
            'Producto_codigo',
            'itemcantidad',
            'itempreciobruto',
            'descuentofactvalor',
            'itemprecioxcantidadneto',
            'detalle'
            );
        $factura_detalle = $this->ci->generic_model->get('billing_facturaventadetalle', array('facturaventa_codigofactventa' => $codFact), $fields, 0);
        return $factura_detalle;
    }

    public function obtener_datos_generales($idBod, $idEmp_vend, $idTec, $idUser, $tipopago) {
        $fields1 = array('descripcion', "nombre");
        $bodega = $this->ci->generic_model->get('billing_bodega', array('id' => $idBod), $fields1, 0);
        $datos_gen['bodega'] = $bodega[0]->descripcion;
        $fields2 = array('nombres', 'apellidos');
        $vendedor = $this->ci->generic_model->get('billing_empleado', array('id' => $idEmp_vend), $fields2, 0);
        $datos_gen['emp_vend'] = $vendedor[0]->nombres . " " . $vendedor[0]->apellidos;
        
        $tecnico = $this->ci->generic_model->get('billing_empleado', array('id' => $idTec), $fields2, null ,1);        
        if( $tecnico ){
            $datos_gen['tecnico'] = $tecnico->nombres . ' ' . $tecnico->apellidos;
        }
        
        $usuario = $this->ci->generic_model->get('billing_empleado', array('id' => $idUser), $fields2, 0);
        $datos_gen['user'] = $usuario[0]->nombres . " " . $usuario[0]->apellidos;
        $datos_gen['tip_pag'] = $this->ci->generic_model->get_val_where('bill_venta_tipo', array('id' => $tipopago), 'tipo', null, -1);
        
        return $datos_gen;
    }
    
    /* Presentar la factura en html */
        public function open_fact($venta_id) {
            $f = $this->obtener_datos_factura($venta_id);            
            $cliente = $this->obtener_datos_cliente($f->cliente_cedulaRuc);
            
            $data['factura'] = $f;
            $data['factura_det'] = $this->obtener_detalle_factura($venta_id);
            $data['cliente_data'] = $cliente;
            $data['datos_observ'] = $this->obtener_datos_generales($f->bodega_id, $f->empleado_vendedor, $f->tecnico_id, $f->user_id, $f->tipo_pago);
    //      Creaciónd del objeto mPDF
            //$mpdf = new mPDF();
            
            $res_head['fact_data'] = $f;
            if($f->puntoventaempleado_tiposcomprobante_cod == '01'){
                if($f->estado == 1){
                    $this->ci->load->view('ventas/head_fact_pendiente',$res_head);
                }  elseif ($f->estado == 2) {
                    $this->ci->load->view('ventas/head_fact_archivada',$res_head);
                }  elseif ($f->estado < 0) {
                    $this->ci->load->view('ventas/head_fact_anulada',$res_head);
                }
            }elseif($f->puntoventaempleado_tiposcomprobante_cod == '04'){
                if($f->estado == 1){
                    $this->ci->load->view('nota_credito_venta/head_ndc_pendiente',$res_head);
                }  elseif ($f->estado == 2) {
                    $this->ci->load->view('nota_credito_venta/head_ndc_archivada',$res_head);
                }  elseif ($f->estado < 0) {
                    $this->ci->load->view('nota_credito_venta/head_ndc_anulada',$res_head);
                }
            }
            $this->ci->load->view('comprobantes/factura_venta', $data);
        }     

}
