<?php
class DahuaController extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	public function obtenerCapturaCanal(){
		// Configuración del NVR Dahua
		$nvrIP = '192.168.10.108:81'; // Cambia esto con la dirección IP de tu NVR
		$usuarioDVR = 'robin';
		$claveDVR = 'robin123';

		// ID de la cámara en el DVR (puede variar según la configuración del DVR)
		$idCamara = 1;

		// URL de la API para obtener una captura de la cámara desde el DVR
		$apiUrl = "http://$nvrIP/cgi-bin/snapshot.cgi?channel=$idCamara";

		// Construir las credenciales para la solicitud
		$credencialesDVR = base64_encode("$usuarioDVR:$claveDVR");

		// Configurar las opciones de la solicitud HTTP con cURL
		$ch = curl_init($apiUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic $credencialesDVR"));
		$imagen = curl_exec($ch);
		curl_close($ch);
		print_r($imagen);
		// Verificar si la captura se obtuvo correctamente
		if ($imagen !== false) {
			// Guardar la imagen en un archivo
			file_put_contents('captura.jpg', $imagen);
			echo 'Captura de imagen exitosa.';
		} else {
			echo 'Error al obtener la imagen.';
		}

	}

	public function obtenerCapturaRTSP(){
		// Ruta del ejecutable de ffmpeg
		$ffmpegPath = '/ruta/a/ffmpeg';

		// URL de la transmisión RTSP
		$rtspUrl = 'rtsp://tu_url_de_transmision_rtsp';

		// Ruta donde se guardará la captura de imagen
		//$imagenSalida = '/ruta/donde/guardar/captura.png';
		//$imagenSalida = '/ruta/donde/guardar/captura.png';
		$imagenSalida = 'captura.png';
		// Comando para capturar un fotograma utilizando ffmpeg
		$comando = "$ffmpegPath -i $rtspUrl -vframes 1 -y $imagenSalida";

		// Ejecutar el comando
		exec($comando);

		// Verificar si la captura se realizó correctamente
		if (file_exists($imagenSalida)) {
			echo 'Captura de imagen exitosa.';
		} else {
			echo 'Error al capturar la imagen.';
		}
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
}