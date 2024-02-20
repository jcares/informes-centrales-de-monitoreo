<?php
class Usuarios extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function obtener_usuario_id($id){
        $this->db->select('u.id, u.roles_id, u.email, u.password, u.nombre, u.guarda, u.edita, u.elimina, r.nombre as rol');
        $this->db->from('usuarios u');
        $this->db->join('roles r', 'r.id = u.roles_id');
        $this->db->where('u.id', $id);
        return $this->db->get()->result_array();
    }

    public function login($usuario, $password){
        $this->db->select('usuarios.id as id, usuarios.email as email, usuarios.password as password, usuarios.nombre as nombre, 
                            usuarios.guarda as guarda, usuarios.edita as edita, usuarios.elimina as elimina, usuarios.roles_id as roles_id, 
                            roles.nombre as roles_nombre, usuarios.estado as estado');
        $this->db->from('usuarios');
        $this->db->join('roles', 'roles.id = usuarios.roles_id');
        $this->db->where('email', $usuario);
        $this->db->where('password', $password);
        $this->db->limit(1);
        return $this->db->get()->result_array();    
    }
}