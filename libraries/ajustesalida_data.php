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
    public function get_data_ajusteSalida($id_ajuste){
        $ajuste_s=$this->ci->generic_model->get('bill_ajustesalida', array('id' => $id_ajuste), '', null, 1);
        return $ajuste_s;
    }
    
    public function get_detalle_ajusteSalida($id_ajuste) {
        
        $ajuste_detalle = $this->ci->generic_model->get('bill_ajustesalidadet', array('ajustesalida_id' => $id_ajuste), '', null, 1);
        return $ajuste_detalle;
    }
    public function open_ajuste($id_ajuste){
        $data['ajuste'] = $this->get_data_ajusteSalida($id_ajuste);
        $data['ajuste_det'] = $this->get_detalle_ajusteSalida($id_ajuste);
        $this->ci->load->view('common/comprobantes/ajuste_salida', $data);
    }
    
}
