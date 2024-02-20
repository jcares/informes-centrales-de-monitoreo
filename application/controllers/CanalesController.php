<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CanalesController extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'America/Santiago');
		$this->load->model('Canales', 'modelo');
	}

	public function index()
	{
        $organizaciones = $this->db->get('organizaciones')->result_array();
		$data = [
			'titulo' => 'Canales',
			'organizaciones' => $organizaciones
		];
		$this->load->view('canales/index', $data);
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

    public function obtenerCantidadCanalesDispotivo(){
		$dispositivo = $this->input->post('dispositivo');
		$this->db->select('cantidad_canales');
		$this->db->from('dispositivos');
		$this->db->where('id', $dispositivo);
		$this->db->limit(1);
		$canales = $this->db->get()->result_array();
		$cantidad_canales = !empty($canales[0]['cantidad_canales']) ? $canales[0]['cantidad_canales'] : 0;

        $this->db->select('*');
        $this->db->from('canales');
        $this->db->where('devices_id', $dispositivo);
        $this->db->order_by('canal ASC');
        $canales = $this->db->get()->result_array();
        $tbody = '';
        if(!empty($canales)){
            for($i=0; $i< count($canales); $i++){
                $tbody .= '<tr>';
                $tbody .= '<td>'.$canales[$i]['canal'].'</td>';
                $tbody .= '<td><input class="form-control" name="canales" id="input-canal-'.$canales[$i]['canal'].'" value="'.$canales[$i]['nombre'].'" /></td>';
                $tbody .= '</tr>';
            }
        }
        else{
            for($i=1; $i<= $cantidad_canales; $i++){
                $tbody .= '<tr>';
                $tbody .= '<td>'.$i.'</td>';
                $tbody .= '<td><input class="form-control" name="canales" id="input-canal-'.$i.'" value="Canal '.$i.'" /></td>';
                $tbody .= '</tr>';
            }
        }
        echo $tbody;
	}

    public function addCanales(){
        $dispositivo = $this->input->post('dispositivo');
        $canales = $this->input->post('canales');

        $this->db->where('devices_id', $dispositivo);
        $this->db->delete('canales');

        foreach($canales as $c){
            $data_canales = [
                                'devices_id' => $dispositivo,
                                'canal' => $c['canal'],
                                'nombre' => $c['nombre']
                            ];
            $this->db->insert('canales', $data_canales);
        }

        $this->db->where('id', $dispositivo);
        $this->db->update('dispositivos',['cantidad_canales' => count($canales)]);

        $this->addLog('Canales', 'Crear', json_encode(['dispositivo_id' => $dispositivo, 'canales' => $canales]));
    }

    public function traerNombreCanales(){
        $dispositivo = $this->input->post('dispositivo');
        $this->db->select('marcas_id, ip, puerto, usuario, password');
        $this->db->from('dispositivos');
        $this->db->where('id', $dispositivo);
        $this->db->limit(1);
        $dev = $this->db->get()->result_array();

        $data = [];
        if(!empty($dev)){
            if($dev[0]['marcas_id'] == 1){
                //dahua
                $nombre_canales = $this->obtenerNombreCanalDahua($dev[0]['ip'].':'.$dev[0]['puerto'], $dev[0]['usuario'], $dev[0]['password']);
                $arr_nombres = preg_split("/\r\n|\r|\n/", $nombre_canales);
                $ch = [];
                for($i=0; $i<count($arr_nombres); $i++){
                    if(!empty(explode('.Name=',$arr_nombres[$i])[1])){
                        $ch[] = trim(explode('.Name=',$arr_nombres[$i])[1]);
                    }
                }
                $data = [
                    'codigo' => 1,
                    'mensaje' => 'Existen nombres de canales para este dispositivo.',
                    'data' => $ch
                ];
            }
            else if($dev[0]['marcas_id'] == 2){
                //hikvision
                $data = [
                    'codigo' => 0,
                    'mensaje' => 'No esta habilitada esta operación para Hikvision.',
                    'data' => ''
                ];
            }
            
        }
        else{
            $data = [
                        'codigo' => 0,
                        'mensaje' => 'Dispositivo no registrado.',
                        'data' => ''
                    ];
        }

        echo json_encode($data);
    }

    private function obtenerNombreCanalDahua($ip, $usuario, $contrasena){
		// Configuración
		//http://sgmas:sgmas123@201.236.179.91:81/cgi-bin/configManager.cgi?action=getConfig&name=ChannelTitle&channel=$canal
        /*
		$ip = '201.236.179.91:81';
		$usuario = 'sgmas';
		$contrasena = 'sgmas123';
		$canal = 1; // Número del canal que deseas consultar
        */
		// URL de la API de Dahua para obtener información sobre el canal
		$url = "http://$ip/cgi-bin/configManager.cgi?action=getConfig&name=ChannelTitle";

		// Configuración de la solicitud
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
		curl_setopt($ch, CURLOPT_USERPWD, "$usuario:$contrasena");

		// Realizar la solicitud
		$response = curl_exec($ch);
		//var_dump($response);
		// Verificar si hubo errores
		$return = '';
		if (curl_errno($ch)) {
			$return = 'Error: '.curl_errno($ch);
		} else {
			$return = $response;
		}
		// Cerrar la conexión cURL
		curl_close($ch);
        return $return;
	}

    public function eliminarCanales(){
        $dispositivo = $this->input->post('dispositivo');
        $this->db->where('devices_id', $dispositivo);
        $this->db->delete('canales');
    }

    public function traerNombreCanales2(){

        $marcas_id = $this->input->post('marca', true);
        $ip = $this->input->post('ip', true);
        $puerto = $this->input->post('puerto', true);
        $usuario = $this->input->post('usuario', true);
        $password = $this->input->post('password', true);

        $data = [];
        if($marcas_id == 1){
            //dahua
            $nombre_canales = $this->obtenerNombreCanalDahua($ip.':'.$puerto, $usuario, $password);
            $arr_nombres = preg_split("/\r\n|\r|\n/", $nombre_canales);
            $ch = [];
            for($i=0; $i<count($arr_nombres); $i++){
                if(!empty(explode('.Name=',$arr_nombres[$i])[1])){
                    $ch[] = trim(explode('.Name=',$arr_nombres[$i])[1]);
                }
            }
            $data = [
                'codigo' => 1,
                'mensaje' => 'Existen nombres de canales para este dispositivo.',
                'data' => $ch
            ];
        }
        else if($marcas_id== 2){
            //hikvision
            $data = [
                'codigo' => 0,
                'mensaje' => 'No esta habilitada esta operación para Hikvision.',
                'data' => ''
            ];
        }

        echo json_encode($data);
    }

}
