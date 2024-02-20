<?php
class Roles extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getRoles(){
        $this->db->select('id, nombre');
        $this->db->from('roles');
        $this->db->order_by('nombre','asc');
        return $this->db->get()->result_array();
    }

    public function getRol($rol_id){
        $this->db->select('id, nombre');
        $this->db->from('roles');
        $this->db->where('id', $rol_id);
        $this->db->limit(1);
        return $this->db->get()->result_array();
    }

    public function addRol($data){
        $this->db->insert('roles', $data);
    }

    public function updateRol($id, $data){
        //query = "update roles set nombre = 'variable' where id = id_variable ";
        $this->db->where('id', $id);
        if($this->db->update('roles', $data))
            return true;
        else
            return false;
    }

    public function deleteRol($id){
        //query = "delete from roles where id = XXX";
        $this->db->where('id', $id);
        if($this->db->delete('roles'))
            return true;
        else
            return false;
    }

    public function getOpciones(){
        $this->db->select('*');
        $this->db->from('opciones');
        $this->db->where('nivel', 3);
        $this->db->order_by('padre ASC, orden ASC');
        return $this->db->get()->result_array();
    }
}