<?php
class ObservacionesController extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Roles', 'modelo');
	}
	
	public function index()
	{
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_table('observaciones_sugeridas');
		$crud->set_subject('Observación Sugerida');
		$crud->set_language('spanish');

		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_clone();

		if($this->session->userdata('usuario_guarda') == 0)
			$crud->unset_add();
		if($this->session->userdata('usuario_edita') == 0)
			$crud->unset_edit();
		if($this->session->userdata('usuario_elimina') == 0)
			$crud->unset_delete();
		$crud->required_fields('nombre');

		$crud->callback_before_insert(array($this,'add_log_create'));
		$crud->callback_before_update(array($this,'add_log_edit'));
		$crud->callback_before_delete(array($this,'add_log_delete'));
		$crud->columns(['id', 'observacion']);
		$output = $crud->render();
		$data = (array)$output;
		$data['titulo'] = 'Observaciones Sugeridas';
		$this->load->view('observaciones/index', $data);
	}

	public function add_log_create($post_array){
		$this->addLog('Observaciones', 'Crear', json_encode($post_array));
		return $post_array;
	}

	public function add_log_edit($post_array){
		$this->addLog('Observaciones', 'Editar', json_encode($post_array));
		return $post_array;
	}

	public function add_log_delete($primary_key){
		$this->db->where('id',$primary_key);
    	$entity_row = $this->db->get('observaciones_sugeridas')->row();
		$this->addLog('Observaciones', 'Eliminar', json_encode($entity_row));
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