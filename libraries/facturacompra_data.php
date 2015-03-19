<?php

/**
 * Description of facturacompra_data
 *
 * @author Mariuxi
 */
class facturacompra_data {

    private $ci;

    function __construct() {
        $this->ci = & get_instance();
    }

    public function get_data_factura($codFact) {
        $join_cluase = array(
            '0' => array('table' => 'billing_bodega', 'condition' => 'billing_bodega.id=billing_facturacompra.bodega_id'),
            '1' => array('table' => 'billing_proveedor prov', 'condition' => 'prov.id=billing_facturacompra.proveedor_id'),
            '2' => array('table' => 'bill_compratipo', 'condition' => 'bill_compratipo.id=billing_facturacompra.tipo')
        );
        $fields = array(
            'billing_facturacompra.codigoFacturaCompra',
            'billing_facturacompra.pa_sriautorizaciondocs_establecimiento establecimiento',
            'billing_facturacompra.pa_sriautorizaciondocs_puntoemision puntoemision',
            'billing_facturacompra.noFacturaCompra secuencia',
            'billing_facturacompra.fechaCreacion',
            'billing_facturacompra.fechaemisionfactura',
            'billing_facturacompra.subtotalBruto',
            'billing_facturacompra.subtotalNeto',
            'billing_facturacompra.descuentovalor',
            'billing_facturacompra.iceval',
            'billing_facturacompra.iva',
            'billing_facturacompra.totalCompra',
            'billing_facturacompra.observaciones',
            'billing_facturacompra.estado',
            'billing_facturacompra.autorizado_sri',
            'billing_facturacompra.tarifacerobruto',
            'billing_facturacompra.tarifadocebruto',
            'billing_facturacompra.recargovalor',
            'billing_facturacompra.pa_sriautorizaciondocs_tiposcomprobante_cod tipocomprobante_cod',
            'billing_facturacompra.compra_id',
            'billing_bodega.nombre bodega_name',
            'bill_compratipo.tipo tipo_compra',
            'prov.PersonaComercio_cedulaRuc',
            'prov.nombres',
            'prov.apellidos',
            'prov.direccion',
            'prov.telefonos',
            'prov.email',
        );
        $fact = $this->ci->generic_model->get_join('billing_facturacompra', array('codigoFacturaCompra' => $codFact), $join_cluase, $fields, 1, null, null);
        return $fact;
    }

    public function get_data_detFact($codFact) {
        $fields = array(
            'Producto_codigo',
            'itemcantidad',
            'itemcostobruto',
            'descuentoglobalvalor',
            'itemcostoxcantidadneto',
            'detalle'
        );
//        $det_factCompra = $this->ci->generic_model->get('billing_detallefacturacompra', array('FacturaCompra_codigo' => $codFact), $fields, 1);
        $det_factCompra = $this->ci->generic_model->get_data( 'billing_detallefacturacompra', array('FacturaCompra_codigo' => $codFact), $fields, null, 0 );
        return $det_factCompra;
    }

    /* Presentar la factura en html */

    public function open_fact($compra_id) {
        $this->ci->load->library('common/empresa');
        $fact = $this->get_data_factura($compra_id);
        $data['factura'] = $fact;
        $data['ruc_empresa'] = $this->ci->empresa->get_ruc();
        $data['factura_det'] = $this->get_data_detFact($compra_id);
        $data['empresa'] = $this->ci->generic_model->get_data( 'billing_empresa', $where_data = null, $fields = '', null, 1);
        
        $res_head['fact_data'] = $fact;
            if( $fact->tipocomprobante_cod == '01' ){
                echo $this->ci->load->view('compras/comprahead',$res_head, TRUE);
            }elseif( $fact->tipocomprobante_cod == '16' ){
                echo $this->ci->load->view('compras/form_exportacion_head',$res_head,TRUE);                
            }elseif( $fact->tipocomprobante_cod == '04' ){
                echo $this->ci->load->view('compras/ndc_head',$res_head,TRUE);
            }        
        
        $this->ci->load->view('common/comprobantes/factura_compra', $data);
    }

}
