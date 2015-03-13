<?php
/**
 * Description of ajustesalida_data
 *
 * @author Mariuxi
 */
class Ajustesalida_data {

    private $ci;

    function __construct() {
        $this->ci = & get_instance();
    }

    public function get_data_ajusteSalida($id_ajuste) {
        $join_cluase = array(
            '0' => array('table' => 'billing_bodega', 'condition' => 'billing_bodega.id=bill_ajustesalida.bodega_id'),
//            '1'=>array('table'=>'billing_proveedor', 'condition'=>'billing_proveedor.id=bill_ajustesalida.proveedor_id')
        );
        $fields = array(
            'bill_ajustesalida.id',
            'bill_ajustesalida.fecha',
            'bill_ajustesalida.total',
            'bill_ajustesalida.observaciones',
            'billing_bodega.nombre',
//            'billing_proveedor.PersonaComercio_cedulaRuc',
//            'billing_proveedor.nombres',
//            'billing_proveedor.apellidos',
//            'billing_proveedor.direccion',
//            'billing_proveedor.telefonos',
//            'billing_proveedor.email'
        );
        $ajuste_s = $this->ci->generic_model->get_join(
                    'bill_ajustesalida', 
                    array('bill_ajustesalida.id' => $id_ajuste),
                    $join_cluase,$fields, 
                    1, 
                    null, 
                    null
                );
        return $ajuste_s;
    }

    public function get_detalle_ajusteSalida($id_ajuste) {
        $join_cluase = array(
            '0' => array('table' => 'billing_producto', 'condition' => 'billing_producto.codigo=bill_ajustesalidadet.Producto_codigo')
        );
        $fields = array(
            'bill_ajustesalidadet.Producto_codigo',
            'bill_ajustesalidadet.itemcantidad',
            'bill_ajustesalidadet.itemcosto',
            'bill_ajustesalidadet.itemcostoxcantidad',
            'billing_producto.nombreUnico'
        );
        $ajuste_detalle = $this->ci->generic_model->get_join('bill_ajustesalidadet', array('ajustesalida_id' => $id_ajuste), $join_cluase,$fields, 0 , null, null);
        return $ajuste_detalle;
    }

    public function open_ajuste($id_ajuste) {
        $data['ajuste'] = $this->get_data_ajusteSalida($id_ajuste);        
        $data['ajuste_det'] = $this->get_detalle_ajusteSalida($id_ajuste);
//        print_r($data['ajuste_det']);        
        $this->ci->load->view('common/comprobantes/ajuste_salida', $data);
    }

}
