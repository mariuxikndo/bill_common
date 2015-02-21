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
            '1' => array('table' => 'billing_proveedor', 'condition' => 'billing_proveedor.id=billing_facturacompra.proveedor_id'),
            '2' => array('table' => 'bill_compratipo', 'condition' => 'bill_compratipo.id=billing_facturacompra.tipo')
        );
        $fields = array(
            'billing_facturacompra.codigoFacturaCompra',
            'billing_facturacompra.fechaCreacion',
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
            'billing_facturacompra.pa_sriautorizaciondocs_tiposcomprobante_cod',
            'billing_bodega.nombre',
            'bill_compratipo.tipo',
            'billing_proveedor.PersonaComercio_cedulaRuc',
            'billing_proveedor.nombres',
            'billing_proveedor.apellidos',
            'billing_proveedor.direccion'
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
        $det_factCompra = $this->ci->generic_model->get('billing_detallefacturacompra', array('FacturaCompra_codigo' => $codFact), $fields, 1);
        return $det_factCompra;
    }

    /* Presentar la factura en html */

    public function open_fact($compra_id) {
        $fact = $this->get_data_factura($compra_id);
        $data['factura'] = $fact;
        $data['factura_det'] = $this->get_data_detFact($compra_id);
        
        $res_head['fact_data'] = $fact;
        if ($fact->pa_sriautorizaciondocs_tiposcomprobante_cod == '01') {
            if ($fact->estado == 1) {
                $this->ci->load->view('compras/head_fact_pendiente', $res_head);
            } elseif ($fact->estado == 2) {
                $this->ci->load->view('compras/head_fact_archivada', $res_head);
            } elseif ($fact->estado < 0) {
                $this->ci->load->view('compras/head_fact_anulada', $res_head);
            }
        } elseif ($fact->pa_sriautorizaciondocs_tiposcomprobante_cod == '04') {
            if ($fact->estado == 1) {
                $this->ci->load->view('ndc_compra/head_ndc_pendiente', $res_head);
            } elseif ($fact->estado == 2) {
                $this->ci->load->view('ndc_compra/head_ndc_archivada', $res_head);
            } elseif ($fact->estado < 0) {
                $this->ci->load->view('ndc_compra/head_ndc_anulada', $res_head);
            }
        }
        $this->ci->load->view('common/comprobantes/factura_compra', $data);
    }

}
