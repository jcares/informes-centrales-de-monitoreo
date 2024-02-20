<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		if($this->session->userdata('usuario_id'))
		{
			header('Location: '.base_url().'index.php/UsuariosController/mi_cuenta');
		}
		else
			$this->load->view('login');
	}

	public function Panel()
	{
		$this->load->view('index');
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

	public function enviar_correo() {
		$this->db->select('valor');
        $this->db->from('configuraciones');
        $this->db->where('parametro', 'smtp_user_sender');
        $this->db->limit(1);
        $smtp_user_sender = $this->db->get()->result_array();
        $smtp_user_sender = $smtp_user_sender[0]['valor'];

        $this->db->select('valor');
        $this->db->from('configuraciones');
        $this->db->where('parametro', 'smtp_pass');
        $this->db->limit(1);
        $smtp_pass = $this->db->get()->result_array();
        $smtp_pass = $smtp_pass[0]['valor'];

        $this->db->select('valor');
        $this->db->from('configuraciones');
        $this->db->where('parametro', 'smtp_host_sender');
        $this->db->limit(1);
        $smtp_host_sender = $this->db->get()->result_array();
        $smtp_host_sender = $smtp_host_sender[0]['valor'];

        $this->db->select('valor');
        $this->db->from('configuraciones');
        $this->db->where('parametro', 'smtp_port_sender');
        $this->db->limit(1);
        $smtp_port_sender = $this->db->get()->result_array();
        $smtp_port_sender = $smtp_port_sender[0]['valor'];

        $this->db->select('valor');
        $this->db->from('configuraciones');
        $this->db->where('parametro', 'smtp_crypto');
        $this->db->limit(1);
        $smtp_crypto = $this->db->get()->result_array();
        $smtp_crypto = $smtp_crypto[0]['valor'];

        $this->load->library('phpmailer_lib');

        $para = 'el_mts@hotmail.com';
        $asunto = 'Asunto del correo';
        $mensaje = 'Contenido del mensaje';

        if ($this->phpmailer_lib->enviar_correo($smtp_user_sender, $smtp_pass, $smtp_host_sender, $smtp_port_sender, $smtp_crypto, $para, $asunto, $mensaje)) {
            echo 'El correo se envió correctamente.';
        } else {
            echo 'Error al enviar el correo.';
        }
    }

}
