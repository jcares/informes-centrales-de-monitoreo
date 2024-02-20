<?php
class LogsController extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Logs', 'modelo');
	}
	
	public function index()
	{
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_table('logs');
		$crud->set_subject('Log');
		$crud->set_language('spanish');

		$crud->unset_clone();
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();

		$crud->set_relation('usuarios_id','usuarios','nombre');
		$crud->display_as('usuarios_id','Usuario');
		$crud->columns(['usuarios_id','entidad', 'fecha_hora', 'accion']);

		$output = $crud->render();
		$data = (array)$output;
		$data['titulo'] = 'Logs';
		$this->load->view('logs/index', $data);
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