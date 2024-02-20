<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CapturasController extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'America/Santiago');
		$this->load->model('Capturas', 'modelo');
	}

	public function index()
	{
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_table('capturas');
		$crud->set_subject('Captura');
		$crud->set_language('spanish');

		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_clone();
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		$crud->unset_columns(['observacion', 'ruta_imagen']);

		$crud->set_relation('organizaciones_id','organizaciones','nombre');
        $crud->set_relation('dispositivos_id','dispositivos','nombre');
		$crud->set_relation('usuario_id','usuarios','nombre');

		$crud->callback_before_insert(array($this,'add_log_create'));
		$crud->callback_before_update(array($this,'add_log_edit'));
		$crud->callback_before_delete(array($this,'add_log_delete'));

		$crud->display_as('organizaciones_id','Organización')->display_as('dispositivos_id','Dispositivo')
		->display_as('usuario_id','Usuario');
		$crud->field_type('password', 'password');

		$crud->order_by('fecha_hora','desc');

		$output = $crud->render();
		$data = (array)$output;
		$data['titulo'] = 'Capturas Histórico';
		$this->load->view('capturas/index', $data);
	}

	public function add_log_create($post_array){
		$this->addLog('Capturas', 'Crear', json_encode($post_array));
		return $post_array;
	}

	public function add_log_edit($post_array){
		$this->addLog('Capturas', 'Editar', json_encode($post_array));
		return $post_array;
	}

	public function add_log_delete($primary_key){
		$this->db->where('id',$primary_key);
    	$entity_row = $this->db->get('capturas')->row();
		$this->addLog('Capturas', 'Eliminar', json_encode($entity_row));
		return true;
	}

    public function add(){
		$organizaciones = $this->db->get('organizaciones')->result_array();
		$data = [
			'titulo' => 'Imágenes x Clasificar (individual)',
			'organizaciones' => $organizaciones
		];
		$this->load->view('capturas/add', $data);
	}

	public function schema(){
		$organizaciones = $this->db->get('organizaciones')->result_array();
		$data = [
			'titulo' => 'Esquema de Horarios',
			'organizaciones' => $organizaciones
		];
		$this->load->view('capturas/schema', $data);
	}

	public function consolidate(){
		$organizaciones = $this->db->get('organizaciones')->result_array();
		$data = [
			'titulo' => 'Imágenes x Clasificar',
			'organizaciones' => $organizaciones
		];
		$this->load->view('capturas/consolidate', $data);
	}

	public function report(){
		$organizaciones = $this->db->get('organizaciones')->result_array();
		$data = [
			'titulo' => 'Reporte',
			'organizaciones' => $organizaciones
		];
		$this->load->view('capturas/report', $data);
	}

	public function obtenerDispositivosOrganizacion(){
		$organizacion = $this->input->post('organizacion');
		$this->db->where('organizaciones_id', $organizacion);
		$dispositivos = $this->db->get('dispositivos')->result_array();
		echo json_encode($dispositivos);
	}

	public function obtenerCantidadCanalesDispotivo(){
		$dispositivo = $this->input->post('dispositivo');
		$this->db->select('cantidad_canales');
		$this->db->from('dispositivos');
		$this->db->where('id', $dispositivo);
		$this->db->limit(1);
		$canales = $this->db->get()->result_array();
		//echo !empty($canales[0]['cantidad_canales']) ? $canales[0]['cantidad_canales'] : 0;
		$this->db->select('*');
        $this->db->from('canales');
        $this->db->where('devices_id', $dispositivo);
        $this->db->order_by('canal ASC');
        $canales = $this->db->get()->result_array();
        $option = '<option value="" selected>Seleccione</option>';
        if(!empty($canales)){
            for($i=0; $i< count($canales); $i++){
				$option .= '<option value="'.$canales[$i]['canal'].'">'.$canales[$i]['nombre'].'</option>';
            }
        }
		echo $option;
	}

	public function obtenerCantidadCanalesDispotivo2(){
		$dispositivo = $this->input->post('dispositivo');
		$this->db->select('cantidad_canales');
		$this->db->from('dispositivos');
		$this->db->where('id', $dispositivo);
		$this->db->limit(1);
		$canales = $this->db->get()->result_array();
		//echo !empty($canales[0]['cantidad_canales']) ? $canales[0]['cantidad_canales'] : 0;
		$this->db->select('*');
        $this->db->from('canales');
        $this->db->where('devices_id', $dispositivo);
        $this->db->order_by('canal ASC');
        $canales = $this->db->get()->result_array();
        
		echo json_encode($canales);
	}

	public function guardarSchema(){
		$organizacion = $this->input->post('organizacion', true);
		$dispositivo = $this->input->post('dispositivo', true);
		$schema = $this->input->post('schema');

		$this->db->where('dispositivos_id', $dispositivo);
		$this->db->delete('esquemas');

		$data_schema = [];
		foreach($schema as $s){
			foreach($s['horas'] as $h){
				$data_schema = 	[
					'dispositivos_id' => $dispositivo,
					'Lun' => ($s['lun'] == 'true') ? 1 : 0,
					'Mar' => ($s['mar'] == 'true') ? 1 : 0,
					'Mie' => ($s['mie'] == 'true') ? 1 : 0,
					'Jue' => ($s['jue'] == 'true') ? 1 : 0,
					'Vie' => ($s['vie'] == 'true') ? 1 : 0,
					'Sab' => ($s['sab'] == 'true') ? 1 : 0,
					'Dom' => ($s['dom'] == 'true') ? 1 : 0,
					'hora' => $h,
					'canal' => $s['canal']
				];
				$this->db->insert('esquemas', $data_schema);
			}
		}

		$data_log = ['dispositivo' => $dispositivo, 'esquema' => $schema];
		$this->addLog('Capturas', 'Crear Esquema Horarios', json_encode($data_log));
		echo 1;
	}

	public function obtenerSchema(){
		$dispositivo = $this->input->post('dispositivo', true);
		$this->db->select('*');
		$this->db->from('esquemas');
		$this->db->where('dispositivos_id', $dispositivo);
		$this->db->order_by('canal ASC, hora ASC');
		$response = $this->db->get()->result_array();
		echo json_encode($response);
	}

	public function addCaptura(){
		$organizacion = $this->input->post('organizacion', true);
		$dispositivo = $this->input->post('dispositivo', true);
		$canal = $this->input->post('canal', true);
		$date_time = date('Y-m-d H:i:s');
		$ruta_imagen = $this->input->post('ruta_imagen', true);
		$observacion = $this->input->post('observacion', true);
		$usuario_id = !empty($this->session->userdata('usuario_id')) ? $this->session->userdata('usuario_id') : ''; //cambiar por usuario de sesion o sistema
		$consolidado = $this->input->post('consolidado', true);

		$data_captura = 	[
								'organizaciones_id' => $organizacion,
								'dispositivos_id' => $dispositivo,
								'canal' => $canal,
								'fecha_hora' => $date_time,
								'ruta_imagen' => $ruta_imagen,
								'observacion' => $observacion,
								'usuario_id' => $usuario_id,
								'consolidado' => $consolidado
							];
		if($this->db->insert('capturas', $data_captura)){
			$this->addLog('Capturas', 'Crear', json_encode($data_captura));
			echo 1;
		}
		else
			echo 0;
	}

	public function obtenerCapturasConsolidar(){
		$dispositivo = $this->input->post('dispositivo', true);
		$desde = $this->input->post('desde', true);
		$hasta = $this->input->post('hasta', true);

		$this->db->select('*,capturas.canal as numero_canal, usuarios.nombre as usuario, canales.nombre as nombre_canal,capturas.id as id, DATE_FORMAT(fecha_hora, "%d-%m-%Y") as fecha, DATE_FORMAT(fecha_hora, "%H:%i") as hora');
		$this->db->from('capturas');
		$this->db->join('usuarios', 'usuarios.id = capturas.usuario_id', 'left');
		$this->db->join('canales', 'capturas.canal = canales.canal', 'left');
		$this->db->where('dispositivos_id', $dispositivo);
		$this->db->where('DATE(fecha_hora) >=', $desde);
		$this->db->where('DATE(fecha_hora) <=', $hasta);
		$this->db->where('consolidado', 0);
		$this->db->group_by('capturas.id');
		echo json_encode($this->db->get()->result_array());
	}

	public function guardarConsolidacion(){
		$data_consolidar = $this->input->post('data_consolidar');
		foreach($data_consolidar as $dc){
			$data = 	[
							'observacion' => $dc['observacion'],
							'consolidado' => 1
						];
			$this->db->where('id', $dc['id']);
			$this->db->update('capturas', $data);
			$this->addLog('Capturas', 'Crear Consolidado', json_encode(['capturas_id' => $dc['id'], 'data' => $data]));
		}
		echo 1;
	}

	public function obtenerCapturasConsolidado(){
		$dispositivo = $this->input->post('dispositivo', true);
		$desde = $this->input->post('desde', true);
		$hasta = $this->input->post('hasta', true);

		$this->db->select('*, DATE_FORMAT(fecha_hora, "%d-%m-%Y") as fecha, DATE_FORMAT(fecha_hora, "%H:%i") as hora');
		$this->db->from('capturas');
		$this->db->where('dispositivos_id', $dispositivo);
		$this->db->where('DATE(fecha_hora) >=', $desde);
		$this->db->where('DATE(fecha_hora) <=', $hasta);
		$this->db->where('consolidado', 1);
		echo json_encode($this->db->get()->result_array());
	}

	public function cargarPDF()
	{
		ini_set('memory_limit', '-1');
		$org = $this->input->get('org', true);
		$dev = $this->input->get('dev', true);
		$desde = $this->input->get('desde', true);
		$hasta = $this->input->get('hasta', true);

		$this->db->select('capturas.ruta_imagen as imagen, dispositivos.ubicacion as ubicacion, CONCAT(capturas.canal," ",canales.nombre) as canal, capturas.observacion as observacion, DATE_FORMAT(capturas.fecha_hora, "%d-%m-%Y %H:%i") as fecha_hora,
							dispositivos.ip as ip, dispositivos.usuario as usuario, dispositivos.password as password, dispositivos.marcas_id as marcas_id');
		$this->db->from('capturas');
		$this->db->join('dispositivos', 'dispositivos.id = capturas.dispositivos_id');
		$this->db->join('canales', 'capturas.canal = canales.canal', 'left');
		$this->db->where('DATE(capturas.fecha_hora) >=', $desde);
		$this->db->where('DATE(capturas.fecha_hora) <=', $hasta);
		$this->db->where('dispositivos.id', $dev);
		$this->db->where('capturas.consolidado', 1);
		$this->db->group_by('capturas.id');
		$this->db->order_by('capturas.canal ASC, capturas.fecha_hora ASC');
		
		$capturas_consolidadas = $this->db->get()->result_array();

		$fecha_desde = '-';
		$fecha_hasta = '-';

		if(count($capturas_consolidadas) > 0){
			$fecha_desde = $capturas_consolidadas[0]['fecha_hora'];
			$fecha_hasta = $capturas_consolidadas[count($capturas_consolidadas) - 1]['fecha_hora'];
		}
		
		$date = date('d-m-Y');
		$time = date('H:i:s');
		$time2 = date('H:i');

		$nombre_empresa = '';
		$direccion_empresa = '';
		$email_empresa = '';
		$telefono_empresa = '';

		$this->db->select('*');
		$this->db->from('configuraciones');
		$configuraciones = $this->db->get()->result_array();

		foreach($configuraciones as $c){
			if($c['parametro'] == 'nombre_empresa')
				$nombre_empresa = $c['valor'];
			if($c['parametro'] == 'direccion_empresa')
				$direccion_empresa = $c['valor'];
			if($c['parametro'] == 'email_empresa')
				$email_empresa = $c['valor'];
			if($c['parametro'] == 'telefono_empresa')
				$telefono_empresa = $c['valor'];
		}
		$datos_empresa = 	[
									'nombre_empresa' => $nombre_empresa,
									'direccion_empresa' => $direccion_empresa,
									'email_empresa' => $email_empresa,
									'telefono_empresa' => $telefono_empresa
								];



		$this->db->select('*');
		$this->db->from('organizaciones');
		$this->db->where('id', $org);
		$this->db->limit(1);
		$datos_organizacion = $this->db->get()->result_array();

		$data = [
					'title' => 'Informe '.$fecha_desde.' - '.$fecha_hasta, 
					'datos' => $capturas_consolidadas,
					'datos_empresa' => $datos_empresa,
					'datos_organizacion' => $datos_organizacion
				];
		$html = $this->load->view('capturas/consolidado_pdf', $data, true);
		$this->load->library('M_pdf');
		$this->m_pdf->pdf->WriteHTML($html);
		$filename = $org."_".$dev."_".date('YmdHis').".pdf";
		$this->m_pdf->pdf->Output("./assets/reportes/".$filename, "F");
		echo $filename;
	}

	public function Header($pdf, $adicional){  
		$nombre_empresa = '';
		$direccion_empresa = '';
		$email_empresa = '';
		$telefono_empresa = '';

		$this->db->select('*');
		$this->db->from('configuraciones');
		$configuraciones = $this->db->get()->result_array();

		foreach($configuraciones as $c){
			if($c['parametro'] == 'nombre_empresa')
				$nombre_empresa = $c['valor'];
			if($c['parametro'] == 'direccion_empresa')
				$direccion_empresa = $c['valor'];
			if($c['parametro'] == 'email_empresa')
				$email_empresa = $c['valor'];
			if($c['parametro'] == 'telefono_empresa')
				$telefono_empresa = $c['valor'];
		}
		$pdf->SetY(5);
		$pdf->SetX(-170);
		//Display Company Info
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(50,10, utf8_decode($nombre_empresa),30,1);
		$pdf->SetFont('Arial','',12);
		$pdf->SetX(-170);
		$pdf->Cell(50,7,utf8_decode($direccion_empresa),30,1);
		$pdf->SetX(-170);
		$pdf->Cell(50,7,utf8_decode($email_empresa),30,1);
		$pdf->SetX(-170);
		$pdf->Cell(50,7,utf8_decode($telefono_empresa),30,1);
		
		//Display INVOICE text
		$pdf->SetY(40);
		$pdf->SetX(-150);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(50,10,"INFORME ".utf8_decode($adicional),30,1);
		
		//Display Horizontal line
		//$pdf->Line(0,48,210,48);
	}

	public function SubHeader($pdf, $organizacion_id){  
		$this->db->select('*');
		$this->db->from('organizaciones');
		$this->db->where('id', $organizacion_id);
		$this->db->limit(1);
		$organizacion = $this->db->get()->result_array();

		$pdf->Line(10,55,200,55);
		$pdf->SetFont('Times', 'B', 10);
		$pdf->Cell(40, 7, 'Rut: ', '', 0, 'L', false);
		$pdf->SetFont('Times', 'U', 9);
		$pdf->Cell(40, 7, (!empty($organizacion[0]['rut']) ? $organizacion[0]['rut'] : 'N/A'), '', 0, 'L', false);
		$pdf->SetFont('Times', 'B', 10);
		$pdf->Cell(40, 7, 'Contacto: ', '', 0, 'L', false);
		$pdf->SetFont('Times', 'U', 9);
		$pdf->Cell(40, 7, utf8_decode((!empty($organizacion[0]['contacto']) ? $organizacion[0]['contacto'] : 'N/A')), '', 0, 'L', false);

		$pdf->Ln();

		$pdf->SetFont('Times', 'B', 10);
		$pdf->Cell(40, 7, utf8_decode('Razón Social: '), '', 0, 'L', false);
		$pdf->SetFont('Times', 'U', 9);
		$pdf->Cell(120, 7, utf8_decode((!empty($organizacion[0]['nombre']) ? $organizacion[0]['nombre'] : 'N/A')), '', 0, 'L', false);
		
		$pdf->Ln();
		
		$pdf->SetFont('Times', 'B', 10);
		$pdf->Cell(40, 7, utf8_decode('Dirección: '), '', 0, 'L', false);
		$pdf->SetFont('Times', 'U', 9);
		$pdf->Cell(40, 7, utf8_decode((!empty($organizacion[0]['direccion']) ? $organizacion[0]['direccion'] : 'N/A')), '', 0, 'L', false);
		
		$pdf->SetFont('Times', 'B', 10);
		$pdf->Cell(40, 7, utf8_decode('Teléfono Contacto: '), '', 0, 'L', false);
		$pdf->SetFont('Times', 'U', 9);
		$pdf->Cell(40, 7, (!empty($organizacion[0]['telefono']) ? $organizacion[0]['telefono'] : 'N/A'), '', 0, 'L', false);
		
		$pdf->Line(10,76,200,76);
		$pdf->Ln();
	}

	public function addLog($entidad, $accion, $data){
		$usuarios_id = !empty($this->session->userdata('usuario_id')) ? $this->session->userdata('usuario_id') : '';
		$fecha_hora = date('Y-m-d H:i:s');
	
		$data_to_save = 	[
								'usuarios_id' => $usuarios_id,
								'entidad' => $entidad,
								'fecha_hora' => $fecha_hora,
								'accion' => $accion,
								'data' => $data
							];
							
		if($this->db->insert('logs', $data_to_save))
			return true;
		else
			return false;
	}

	public function obtenerCapturaDispositivoCanal(){
		$organizacion = $this->input->post('organizacion', true);
		$dispositivo = $this->input->post('dispositivo', true);
		$canal = $this->input->post('canal', true);

		$this->db->select('*');
		$this->db->from('dispositivos');
		$this->db->where('id', $dispositivo);
		$this->db->limit(1);
		$data = $this->db->get()->result_array();
		if(!empty($data)){
			$imagen = '';
			switch ($data[0]['marcas_id']) {
				case 1:
				  	//dahua
					  $imagen = $this->obtenerCapturaCanalDahua(
						$data[0]['organizaciones_id'], $dispositivo, $data[0]['ip'],
						$data[0]['puerto'], $data[0]['usuario'], $data[0]['password'], $canal);
					if($imagen != false){
						echo $imagen;
					}
					else
						echo 2;
				  	break;
				case 2:
				  	//hikvision
				  	$imagen = $this->obtenerCapturaCanalHikvision(
							$data[0]['organizaciones_id'], $dispositivo, $data[0]['ip'],
							$data[0]['puerto'], $data[0]['usuario'], $data[0]['password'], $canal);
					if($imagen != false){
						echo $imagen;
					}
					else
						echo 2;
				  	break;
				default:
				  	//code block
			}
		}
	}


	public function capturarSnapshots(){
		//esta funcion queda sujeta a un Cron Job en el servidor
		$this->db->select('d.id as dispositivo_id, d.usuario as usuario, d.password as password, d.ip as ip, d.puerto as puerto,
							d.organizaciones_id as organizacion_id, e.canal as canal, d.marcas_id as marcas_id');
		$this->db->from('esquemas as e');
		$this->db->join('dispositivos as d', 'd.id = e.dispositivos_id');
		$this->db->where('hora', date('H:i'));
		$this->db->order_by('e.id ASC');
		$result = $this->db->get()->result_array();
		foreach($result as $r){
			//validar que no exista el mismo dispositivo, en una fecha_hora (YYYY-MM-DD HH:II) determinada 
			$this->db->select('id');
			$this->db->from('capturas');
			$this->db->where('dispositivos_id', $r['dispositivo_id']);
			$this->db->where('canal', $r['canal']);
			$this->db->where('DATE_FORMAT(fecha_hora, "%Y-%m-%d %H:%i") = ', date('Y-m-d H:i'));
			if(empty($this->db->get()->result_array())){
				//[dispositivo_id]
				//[usuario] 
				//[password] 
				//[ip] 
				//[puerto] 
				//[organizacion_id] 
				//[canal]
				//[marcas_id]
				//generar registro en capturas y log pertinente
				switch ($r['marcas_id']) {
					case 1:
						//dahua
						$imagen = $this->obtenerCapturaCanalDahua(
							$r['organizacion_id'], $r['dispositivo_id'], $r['ip'],
							$r['puerto'], $r['usuario'], $r['password'], $r['canal']);
						if($imagen != false){
							//imagen capturada sin problemas

							$data_captura = 	[
													'organizaciones_id' => $r['organizacion_id'],
													'dispositivos_id' => $r['dispositivo_id'],
													'canal' => $r['canal'],
													'fecha_hora' => date('Y-m-d H:i:s'),
													'ruta_imagen' => $imagen,
													'usuario_id' => (!empty($this->session->userdata('usuario_id')) ? !empty($this->session->userdata('usuario_id')) : '')
												];
							if($this->db->insert('capturas', $data_captura)){
								$this->addLog('Capturas', 'Programada', json_encode(['mensaje' => 'Success', 'data' => $data_captura]));
							}
						}
						else{
							//error en la captura de imagen, generar log
							$this->addLog('Capturas', 'Programada', json_encode(['mensaje' => 'No se pudo capturar la imagen', 'data' => $r]));
						}
						break;
					case 2:
						//hikvision
						$imagen = $this->obtenerCapturaCanalHikvision(
								$r['organizacion_id'], $r['dispositivo_id'], $r['ip'],
								$r['puerto'], $r['usuario'], $r['password'], $r['canal']);
						if($imagen != false){
							//imagen capturada sin problemas
							$data_captura = 	[
													'organizaciones_id' => $r['organizacion_id'],
													'dispositivos_id' => $r['dispositivo_id'],
													'canal' => $r['canal'],
													'fecha_hora' => date('Y-m-d H:i:s'),
													'ruta_imagen' => $imagen,
													'usuario_id' => (!empty($this->session->userdata('usuario_id')) ? !empty($this->session->userdata('usuario_id')) : '')
												];
							if($this->db->insert('capturas', $data_captura)){
								$this->addLog('Capturas', 'Programada', json_encode(['mensaje' => 'Success', 'data' => $data_captura]));
							}
						}
						else{
							//error en la captura de imagen, generar log
							$this->addLog('Capturas', 'Programada', json_encode(['mensaje' => 'No se pudo capturar la imagen', 'data' => $r]));
						}
						break;
					default:
				}
			}
		}
	}
	//----------------------
	private function obtenerCapturaCanalHikvision($organizacion_id, $dispositivo_id, $ip, $puerto, $usuario, $clave, $canal){
		ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');
		// Configuración Hikvision
		$ip = $ip.':'.$puerto;
		// ID de la camara en el NVR (puede variar según la configuración del NVR)
		$idCamara = $canal;
		// URL de la API para obtener una captura
		$apiUrl = "http://$ip/ISAPI/Streaming/channels/$idCamara/picture";
		// Construir las credenciales para la solicitud
		/*
		$credenciales = base64_encode("$usuario:$clave");
		// Configurar las opciones de la solicitud HTTP
		$opciones = [
			'http' => [
				'header' => "Authorization: Basic $credenciales"
			]
		];
		// Crear el contexto de la solicitud
		$contexto = stream_context_create($opciones);
		// Hacer la solicitud HTTP y obtener la captura de imagen
		$imagen = '';
		try{
			$imagen = file_get_contents($apiUrl, false, $contexto);
		}
		catch(Exception $ex){
			$this->addLog('Capturas', 'Error', json_encode(['mensaje' => $ex, 
																'data' => 
																[
																	'organizacion_id' => $organizacion_id,
																	'ip' => $ip,
																	'puerto' => $puerto,
																	'usuario' => $usuario,
																	'clave' => $clave,
																	'canal' => $canal 
																]
			]));
		}
		// Verificar si la captura se obtuvo correctamente
		if ($imagen !== false) {
			// Guardar la imagen en un archivo
			$nombre_imagen = 'captura_'.$organizacion_id.'_'.$idCamara.'_'.date('YmdHis').'.jpg';
			file_put_contents('./assets/imagenes_capturadas/'.$nombre_imagen, $imagen);
			$this->addLog('Capturas', 'Success', json_encode(['mensaje' => 'Se ha captura de manera correcta. (Hikvision)', 
																'data' => 
																[
																	'organizacion_id' => $organizacion_id,
																	'ip' => $ip,
																	'puerto' => $puerto,
																	'usuario' => $usuario,
																	'clave' => $clave,
																	'canal' => $canal 
																]
			]));
			return $nombre_imagen;
		} else {
			
			return false;
		}
		*/
		$ch = curl_init($apiUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
		curl_setopt($ch, CURLOPT_USERPWD, "{$usuario}:{$clave}");

		$response = curl_exec($ch);
		// Verificar si la captura se obtuvo correctamente
		if ($response !== false) {
			// Guardar la imagen en un archivo
			$nombre_imagen = 'captura_'.$organizacion_id.'_'.$dispositivo_id.'_'.$idCamara.'_'.date('YmdHis').'.jpg';
			file_put_contents('./assets/imagenes_capturadas/'.$nombre_imagen, $response);

			$this->addLog('Capturas', 'Success', json_encode(['mensaje' => 'Se ha captura de manera correcta. (Hikvision)', 
																'data' => 
																[
																	'organizacion_id' => $organizacion_id,
																	'ip' => $ip,
																	'puerto' => $puerto,
																	'usuario' => $usuario,
																	'clave' => $clave,
																	'canal' => $canal 
																]
			]));
			curl_close($ch);
			return $nombre_imagen;
		} else {
			$this->addLog('Capturas', 'Error', json_encode(['mensaje' => 'Error de conexion', 
																'data' => 
																[
																	'organizacion_id' => $organizacion_id,
																	'ip' => $ip,
																	'puerto' => $puerto,
																	'usuario' => $usuario,
																	'clave' => $clave,
																	'canal' => $canal 
																]
			]));
			curl_close($ch);
			return false;
		}
	}

	private function obtenerCapturaCanalDahua($organizacion_id, $dispositivo_id, $ip, $puerto, $usuario, $clave, $canal){
		ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');
		// Configuración Dahua
		$ip = $ip.':'.$puerto;
		//canal
		$idCamara = $canal;
		// URL de la API para obtener una captura
		$apiUrl = "http://$ip/cgi-bin/snapshot.cgi?channel=$idCamara";
		/*
		// Construir las credenciales para la solicitud
		$credenciales = base64_encode("$usuario:$clave");
		// Configurar las opciones de la solicitud HTTP
		$opciones = [
			'http' => [
				'header' => "Authorization: Basic $credenciales"
			]
		];
		// Crear el contexto de la solicitud
		$contexto = stream_context_create($opciones);
		// Hacer la solicitud HTTP y obtener la captura de imagen
		$imagen = '';
		try{
			$imagen = file_get_contents($apiUrl, false, $contexto);
		}
		catch(Exception $ex){
			$this->addLog('Capturas', 'Error', json_encode(['mensaje' => $ex, 
																'data' => 
																[
																	'organizacion_id' => $organizacion_id,
																	'ip' => $ip,
																	'puerto' => $puerto,
																	'usuario' => $usuario,
																	'clave' => $clave,
																	'canal' => $canal 
																]
			]));
		}
		*/
		// cURL request to capture the snapshot
		$ch = curl_init($apiUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
		curl_setopt($ch, CURLOPT_USERPWD, "{$usuario}:{$clave}");

		$response = curl_exec($ch);
		// Verificar si la captura se obtuvo correctamente
		if ($response !== false) {
			// Guardar la imagen en un archivo
			$nombre_imagen = 'captura_'.$organizacion_id.'_'.$dispositivo_id.'_'.$idCamara.'_'.date('YmdHis').'.jpg';
			file_put_contents('./assets/imagenes_capturadas/'.$nombre_imagen, $response);

			$this->addLog('Capturas', 'Success', json_encode(['mensaje' => 'Se ha captura de manera correcta. (Dahua)', 
																'data' => 
																[
																	'organizacion_id' => $organizacion_id,
																	'ip' => $ip,
																	'puerto' => $puerto,
																	'usuario' => $usuario,
																	'clave' => $clave,
																	'canal' => $canal 
																]
			]));
			curl_close($ch);
			return $nombre_imagen;
		} else {
			$this->addLog('Capturas', 'Error', json_encode(['mensaje' => 'Error de conexion', 
																'data' => 
																[
																	'organizacion_id' => $organizacion_id,
																	'ip' => $ip,
																	'puerto' => $puerto,
																	'usuario' => $usuario,
																	'clave' => $clave,
																	'canal' => $canal 
																]
			]));
			curl_close($ch);
			return false;
		}
		
	}

	private function obtenerNombreCanalHikvision($ip, $usuario, $clave, $canal){
		// Configuración
		$ip = $ip;
		$usuario = $usuario;
		$contrasena = $clave;
		$canal = $canal; // Número del canal que deseas consultar

		// URL de la API ISAPI de Hikvision para obtener información sobre el canal
		$url = "http://$ip/ISAPI/Streaming/channels/$canal";

		// Configuración de la solicitud
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$usuario:$contrasena");

		// Realizar la solicitud
		$response = curl_exec($ch);

		// Verificar si hubo errores
		if (curl_errno($ch)) {
			return false;
		} else {
			// Procesar la respuesta JSON
			$data = json_decode($response, true);

			// Obtener el nombre del canal desde la respuesta
			$channelName = $data['StreamingChannel']['channelName'];
			return $channelName;
		}

		// Cerrar la conexión cURL
		curl_close($ch);
	}

	public function uploadImage(){
		$organizaciones = $this->db->get('organizaciones')->result_array();
		$data = [
			'titulo' => 'Subir imágenes',
			'organizaciones' => $organizaciones
		];
		$this->load->view('capturas/upload_image', $data);
	}

	public function subirImagen(){
		$org = $this->input->post('org', true);
		$dev = $this->input->post('dev', true);
		$canal = $this->input->post('canal', true);

		if ($_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
			$nombreArchivo = $_FILES['imagen']['name'];
			$rutaTemporal = $_FILES['imagen']['tmp_name'];

			// Obtener información sobre el archivo
			$infoArchivo = pathinfo($_FILES["imagen"]["name"]);
			// Obtener la extensión del archivo
			$extension = strtolower($infoArchivo['extension']);
			$nombreImagen = $org.'_'.$dev.'_'.$canal.'_'.date('YmdHis').'.'.$extension;
			$rutaDestino = './assets/imagenes_capturadas/' . $nombreImagen; // Ajusta la ruta según tus necesidades
		
			if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
				echo json_encode(['codigo' => 1, 'imagen' => $nombreImagen]);
			} else {
				echo json_encode(['codigo' => 0, 'imagen' => 'Error al subir']);
			}
		} else {
			echo json_encode(['codigo' => 0, 'imagen' => $_FILES['imagen']['error']]);
		}
	}

	public function guardarInforme(){
		$organizacion = $this->input->post('organizacion', true);
		$fecha = date('Y-m-d H:i:s');
		$url = $this->input->post('url', true);
		$archivo = $this->input->post('archivo', true);

		$nombreArchivo = './assets/reportes/'.$archivo;
		$size = 0;
		$sizeKB = 0;
		if (file_exists($nombreArchivo)) {
			$size = filesize($nombreArchivo);
			$sizeKB = round($size / 1024, 2);
		} 

		$data_informe = [
							'organizaciones_id' => $organizacion,
							'fecha' => $fecha,
							'ruta' => $url,
							'size' => $sizeKB.' KB'
						];
		$this->db->insert('informes', $data_informe);
		echo 'Se ha generado el registro de manera correcta';
	}
}
