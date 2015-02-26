<?php
/**
 * Description of contaasientocontable
 *
 * @author estebanch
 */
class Facturaventa_data {

    private $ci;

    function __construct() {
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
            'puntoventaempleado_establecimiento establecimiento',
            'puntoventaempleado_puntoemision puntoemision',
            'secuenciafactventa',
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
        $datos_gen['bodega'] = $bodega[0]->nombre;
        $fields2 = array('nombres', 'apellidos');
        $vendedor = $this->ci->generic_model->get('billing_empleado', array('id' => $idEmp_vend), $fields2, 0);
        $datos_gen['emp_vend'] = $vendedor[0]->nombres . ' ' . $vendedor[0]->apellidos;

        $tecnico = $this->ci->generic_model->get('billing_empleado', array('id' => $idTec), $fields2, null, 1);
        if ($tecnico) {
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

        $res_head['fact_data'] = $f;
        $res_head['cli']=$cliente;
        
        if ($f->puntoventaempleado_tiposcomprobante_cod == '01') {
            if ($f->estado == 1) {
                $this->ci->load->view('ventas/head_fact_pendiente', $res_head);
            } elseif ($f->estado == 2) {
                $this->ci->load->view('ventas/head_fact_archivada', $res_head);
            } elseif ($f->estado < 0) {
                $this->ci->load->view('ventas/head_fact_anulada', $res_head);
            }
        } elseif ($f->puntoventaempleado_tiposcomprobante_cod == '04') {
            if ($f->estado == 1) {
                $this->ci->load->view('ndc_venta/head_ndc_pendiente', $res_head);
            } elseif ($f->estado == 2) {
                $this->ci->load->view('ndc_venta/head_ndc_archivada', $res_head);
            } elseif ($f->estado < 0) {
                $this->ci->load->view('ndc_venta/head_ndc_anulada', $res_head);
            }
        }
        $this->ci->load->view('common/comprobantes/factura_venta', $data);
    }
// Crear PDF de la factura
    public function create_pdf_factVenta($venta_id) {

        $this->ci->load->library('mpdf60/mpdf');

        $f = $this->obtener_datos_factura($venta_id);
        $cliente = $this->obtener_datos_cliente($f->cliente_cedulaRuc);
        $data['factura'] = $f;
        $data['factura_det'] = $this->obtener_detalle_factura($venta_id);
        $data['cliente_data'] = $cliente;
        $data['datos_observ'] = $this->obtener_datos_generales($f->bodega_id, $f->empleado_vendedor, $f->tecnico_id, $f->user_id, $f->tipo_pago);

        $mpdf = new mPDF();
        $mpdf->WriteHTML($this->ci->load->view('common/comprobantes/factura_venta', $data, true));
        $mpdf->Output();
        $nombre_fact = $venta_id.'.pdf';
        $mpdf->Output($nombre_fact, 'F');
 
        $dir_fac = 'C:/xampp/htdocs/billingsof_core/' . $nombre_fact;
        $this->save_drive($nombre_fact, $dir_fac);
    }
//Enviar link de la factura por correo electrónico
    public function send_email($emails_destino, $fact_id) {

        $this->ci->load->library("email");

        $configGmail = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => 'testmasterpc@gmail.com',
            'smtp_pass' => 'mkco5566',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        );

        $this->ci->email->initialize($configGmail);
//      Datos de envio de email
        $this->ci->email->from('testmasterpc@gmail.com', 'Mariuxi Cando');
        $this->ci->email->to($emails_destino);
        $this->ci->email->subject('Factura Electronica');
     
        $url_fact='https://www.googledrive.com/host/0ByqQkg3INrbzfmlmNGQzMFYyT29TRi1HaXdZSVd4SVBocGxhblBiQjcwRDdraXJJejFSUFk/'.$fact_id.'.pdf';
        $this->ci->email->message('Con este mensaje se adjunta un link para la descarga del archivo en pdf que correponde a la factura generada por su compra en Master PC. De un clic en el siguiente enlace '.$url_fact);
        $res = $this->ci->email->send();
        return $res;
    }

    public function save_drive($nombre_fact, $dir_fact) {
        require_once realpath(dirname(__FILE__) . '/google-api-php-cliente/autoload.php');
//      Datos obtenidos en la Consola de Desarrolladores de Google
        $cliente_id = '792070845245-53cs74geuk3r9l467b57mbcu216c6i9c.apps.googleusercontent.com';
        $email_servicio = '792070845245-53cs74geuk3r9l467b57mbcu216c6i9c@developer.gserviceaccount.com';
        $direccion_key = 'C:\xampp\htdocs\billingsof_core\application\modules\common\key_google\Shopping Cart-466439de0a0f.p12';
//      Creación de un objeto tipo Client
        $client = new Google_Client();
        $client->setClientId($cliente_id);
        if (isset($_SESSION['service_token'])) {
            $client->setAccessToken($_SESSION['service_token']);
        }
        $key = file_get_contents($direccion_key);
        $credencial = new Google_Auth_AssertionCredentials(
                $email_servicio, array('https://www.googleapis.com/auth/drive'), $key
        );
        $client->setAssertionCredentials($credencial);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion($credencial);
        }
        $_SESSION['service_token'] = $client->getAccessToken();
//      Selección del tipo de servicio en este caso Drive
        $service_drive = new Google_Service_Drive($client);
        //Creación del archivo para guardarlo en Drive
        $file = new Google_Service_Drive_DriveFile();
        $file->setTitle($nombre_fact);
        $file->setMimeType('application/pdf');
        //Id de la carpeta en Drive
        $carpetaId = '0ByqQkg3INrbzfmlmNGQzMFYyT29TRi1HaXdZSVd4SVBocGxhblBiQjcwRDdraXJJejFSUFk';
        //Establecer la carpeta en donde se guardara en Drive
        if ($carpetaId != null) {
            $carpeta = new Google_Service_Drive_ParentReference();
            $carpeta->setId($carpetaId);
            $file->setParents(array($carpeta));
        }
        try {
            $data = file_get_contents($dir_fact);
            //Inserción del archivo en Drive
            $archivo_creado = $service_drive->files->insert($file, array(
                'data' => $data,
                'uploadType' => 'media'
            ));
            print $archivo_creado->getId();
        } catch (Exception $e) {
            print "Se ha generado el siguiente error: " . $e->getMessage();
        }
    }

}
