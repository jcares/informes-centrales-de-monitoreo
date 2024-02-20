<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InformesController extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		ini_set('date.timezone', 'America/Santiago');
	}

	public function index()
	{
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_table('informes');
		$crud->set_subject('Informes');
		$crud->set_language('spanish');

		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_clone();
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		$crud->unset_columns(['observacion', 'ruta_imagen']);

		$crud->set_relation('organizaciones_id','organizaciones','nombre');

		$crud->callback_before_insert(array($this,'add_log_create'));
		$crud->callback_before_update(array($this,'add_log_edit'));
		$crud->callback_before_delete(array($this,'add_log_delete'));

        $crud->display_as('ruta', 'Enlace');
        $crud->callback_column('ruta', function ($value, $row) {
            $enlace = '<a href="' . $row->ruta . '" download="reporte_'.date('YmdHis').'">Enlace</a>';
            
            return $enlace;
        });

		$crud->add_action('Reenviar Correo', base_url().'assets/img/email.png', 'InformesController/reenviarCorreo', '','', array($this,'get_row_id' ));
		$crud->order_by('fecha','desc');

		$output = $crud->render();
		$data = (array)$output;
		$data['titulo'] = 'Listado Informes';
		$this->load->view('informes/index', $data);
	}

    public function add_log_create($post_array){
		$this->addLog('Informes', 'Crear', json_encode($post_array));
		return $post_array;
	}

	public function add_log_edit($post_array){
		$this->addLog('Informes', 'Editar', json_encode($post_array));
		return $post_array;
	}

	public function add_log_delete($primary_key){
		$this->db->where('id',$primary_key);
    	$entity_row = $this->db->get('capturas')->row();
		$this->addLog('Informes', 'Eliminar', json_encode($entity_row));
		return true;
	}

	public function reenviarCorreo($id){
		echo $id;
	}
}
