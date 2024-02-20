<?php
class TipoDispositivoController extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_table('tipo_dispositivo');
		$crud->set_subject('Tipo de Dispositivo');
		$crud->set_language('spanish');

		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_clone();

		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();

		$crud->callback_before_insert(array($this,'add_log_create'));
		$crud->callback_before_update(array($this,'add_log_edit'));
		$crud->callback_before_delete(array($this,'add_log_delete'));

		$output = $crud->render();
		$data = (array)$output;
		$data['titulo'] = 'Tipos de Dispositivos';
		$this->load->view('tipo_dispositivo/index', $data);
	}

	public function add_log_create($post_array){
		$this->addLog('Tipo Dispositivo', 'Crear', json_encode($post_array));
		return $post_array;
	}

	public function add_log_edit($post_array){
		$this->addLog('Tipo Dispositivo', 'Editar', json_encode($post_array));
		return $post_array;
	}

	public function add_log_delete($primary_key){
		$this->db->where('id',$primary_key);
    	$entity_row = $this->db->get('tipo_dispositivo')->row();
		$this->addLog('Tipo Dispositivo', 'Eliminar', json_encode($entity_row));
		return true;
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