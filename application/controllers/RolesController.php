<?php
class RolesController extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Roles', 'modelo');
	}
	
	public function index()
	{
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_table('roles');
		$crud->set_subject('Rol');
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

		$output = $crud->render();
		$data = (array)$output;
		$data['titulo'] = 'Roles';
		$this->load->view('roles/index', $data);
	}

	public function add_log_create($post_array){
		$this->addLog('Roles', 'Crear', json_encode($post_array));
		return $post_array;
	}

	public function add_log_edit($post_array){
		$this->addLog('Roles', 'Editar', json_encode($post_array));
		return $post_array;
	}

	public function add_log_delete($primary_key){
		$this->db->where('id',$primary_key);
    	$entity_row = $this->db->get('roles')->row();
		$this->addLog('Roles', 'Eliminar', json_encode($entity_row));
		return true;
	}

	public function add(){
		$data = [
			'titulo' => 'Agregar Rol'
		];
		$this->load->view('roles/add', $data);
	}

	public function edit(){
		$id = trim($this->input->get('id', TRUE));
		$rol = $this->modelo->getRol($id);
		$data = [
			'id' => $id,
			'titulo' => 'Editar Rol # '.$id,
			'data'	=> $rol
		];
		$this->load->view('roles/edit', $data);
	}

	public function addRol(){
		$rol = $this->input->post('input-rol');
		$data = ['nombre' => $rol];
		$this->modelo->addRol($data);
		$this->index();
	}

	public function editRol(){
		$id = trim($this->input->post('id', TRUE));
		$nombre = trim($this->input->post('nombre', TRUE));
		$data = ['nombre' => $nombre];
		$responseBD = $this->modelo->updateRol($id, $data);
		if($responseBD){
			//true hacer algo
			echo '1';
		}
		else{
			//false hacer algo
			echo '0';
		}
	}

	public function deleteRol(){
		$id = trim($this->input->post('id', TRUE));
		$responseBD = $this->modelo->deleteRol($id);
		if($responseBD){
			//true hacer algo
			echo '1';
		}
		else{
			//false hacer algo
			echo '0';
		}
	}

	public function permisos(){
		$data = [
			'titulo' => 'Permisos',
			'roles' => $this->modelo->getRoles(),
			'opciones' => $this->modelo->getOpciones()
		];
		$this->load->view('roles/permisos', $data);
	}

	public function addPermiso(){
		$opciones = $this->input->post('opciones');
		$rol = $this->input->post('rol');

		if(!empty($rol) && !empty($opciones)){
			$this->db->where('roles_id', $rol);
			$this->db->delete('opcion_rol');
			foreach($opciones as $o){
				$this->db->insert('opcion_rol', ['roles_id' => $rol, 'opciones_id' => $o]);
			}
		}
		echo 1;
	}

	public function obtenerOpcionesRol(){
		$rol = $this->input->post('rol');
		$this->db->where('roles_id', $rol);
		$opciones = $this->db->get('opcion_rol')->result_array();
		echo json_encode($opciones);
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