<?php
class OrganizacionesController extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_table('organizaciones');
		$crud->set_subject('Organización');
		$crud->set_language('spanish');

		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_clone();
		$crud->unset_add();
		$crud->unset_edit();
		if($this->session->userdata('usuario_elimina') == 1)
			$crud->add_action('action1', base_url().'assets/grocery_crud/themes/flexigrid/css/images/edit.png', 'OrganizacionesController/edit', '','', array($this,'get_row_id' ));
			
		if($this->session->userdata('usuario_elimina') == 0)
			$crud->unset_delete();

		$crud->callback_before_insert(array($this,'add_log_create'));
		$crud->callback_before_update(array($this,'add_log_edit'));
		$crud->callback_before_delete(array($this,'add_log_delete'));

		$crud->set_relation('tipo_organizacion_id','tipo_organizacion','tipo');
		$crud->display_as('tipo_organizacion_id','Tipo');

		$output = $crud->render();
		$data = (array)$output;
		$data['titulo'] = 'Organizaciones';
		$this->load->view('organizaciones/index', $data);
	}

	public function add_log_create($post_array){
		$this->addLog('Organizaciones', 'Crear', json_encode($post_array));
		return $post_array;
	}

	public function add_log_edit($post_array){
		$this->addLog('Organizaciones', 'Editar', json_encode($post_array));
		return $post_array;
	}

	public function add_log_delete($primary_key){
		$this->db->where('id',$primary_key);
    	$entity_row = $this->db->get('organizaciones')->row();
		$this->addLog('Organizaciones', 'Eliminar', json_encode($entity_row));
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

	public function add(){
		$tipo_organizacion = $this->db->get('tipo_organizacion')->result_array();
		$data = [
			'titulo' => 'Agregar Cliente',
			'tipo_organizacion' => $tipo_organizacion
		];
		$this->load->view('organizaciones/add', $data);
	}

	public function addOrganizacion(){
		$rut = $this->input->post('rut', true);
		$tipo_organizacion = $this->input->post('tipo_organizacion', true);
		$nombre = $this->input->post('nombre', true);
		$direccion = $this->input->post('direccion', true);
		$telefono = $this->input->post('telefono', true);
		$email = $this->input->post('email', true);
		$contacto = $this->input->post('contacto', true);
		$cantidad_dispositivos = $this->input->post('cantidad_dispositivos', true);

		$data_organizacion = 	[
									'nombre' => $nombre,
									'cantidad_dispositivos' => $cantidad_dispositivos,
									'rut' => $rut,
									'creado' => date('Y-m-d H:i:s'),
									'direccion' => $direccion,
									'contacto' => $contacto,
									'telefono' => $telefono,
									'email' => $email,
									'tipo_organizacion_id' => $tipo_organizacion
								];

		$this->db->insert('organizaciones', $data_organizacion);
		$last_id = $this->db->insert_id();
		if($last_id > 0){
			$emails_contactos = $this->input->post('emails_contactos');
			$checks_contactos = $this->input->post('checks_contactos');

			if(count($emails_contactos) > 0){
				$this->db->where('organizaciones_id', $last_id);
				$this->db->delete('organizaciones_contactos');
				$i=0;
				foreach($emails_contactos as $ec){
					$arr_temp = ['organizaciones_id' => $last_id, 'contacto' => $ec, 'estado' => $checks_contactos[$i]];
					$this->db->insert('organizaciones_contactos', $arr_temp);
					$i++;
				}
			}
		}

		echo 'Se ha agregado de manera correcta.';
	}

	public function edit($id){
		$this->db->select('*');
		$this->db->from('organizaciones');
		$this->db->where('id', $id);
		$this->db->limit(1);
		$organizacion = $this->db->get()->result_array();
		
		$this->db->select('contacto, estado');
		$this->db->from('organizaciones_contactos');
		$this->db->where('organizaciones_id', $id);
		$this->db->order_by('contacto ASC');
		$contactos = $this->db->get()->result_array();
		
		$tipo_organizacion = $this->db->get('tipo_organizacion')->result_array();
		$data = [
			'id' => $id,
			'titulo' => 'Editar Cliente #'.$id,
			'tipo_organizacion' => $tipo_organizacion,
			'organizacion' => $organizacion,
			'contactos' => $contactos
		];
		$this->load->view('organizaciones/edit', $data);
	}

	public function editOrganizacion(){
		$id = $this->input->post('id', true);
		$rut = $this->input->post('rut', true);
		$tipo_organizacion = $this->input->post('tipo_organizacion', true);
		$nombre = $this->input->post('nombre', true);
		$direccion = $this->input->post('direccion', true);
		$telefono = $this->input->post('telefono', true);
		$email = $this->input->post('email', true);
		$contacto = $this->input->post('contacto', true);
		$cantidad_dispositivos = $this->input->post('cantidad_dispositivos', true);

		$data_organizacion = 	[
									'nombre' => $nombre,
									'cantidad_dispositivos' => $cantidad_dispositivos,
									'rut' => $rut,
									'direccion' => $direccion,
									'contacto' => $contacto,
									'telefono' => $telefono,
									'email' => $email,
									'tipo_organizacion_id' => $tipo_organizacion
								];
		
		$this->db->where('id', $id);
		$this->db->update('organizaciones', $data_organizacion);
		$emails_contactos = $this->input->post('emails_contactos');
		$checks_contactos = $this->input->post('checks_contactos');
		$i=0;
		if(!empty($emails_contactos) && count($emails_contactos) > 0){
			$this->db->where('organizaciones_id', $id);
			$this->db->delete('organizaciones_contactos');
			foreach($emails_contactos as $ec){
				$arr_temp = ['organizaciones_id' => $id, 'contacto' => $ec, 'estado' => $checks_contactos[$i]];
				$this->db->insert('organizaciones_contactos', $arr_temp);
				$i++;
			}
		}

		echo 'Se ha agregado de manera correcta.';
	}
}