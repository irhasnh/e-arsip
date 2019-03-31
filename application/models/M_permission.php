<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_permission extends CI_Model {
	var $table = 'tbl_nav_content';
	var $table2 = 'tbl_permissions';

	public function show_permissions($value=array())
	{
		$this->db->select('a.id_nav_content, a.desc_nav_content, 
							b.id_permissions, b.priv_permissions, b.nav_permissions, 
							b.read_permissions, b.create_permissions, b.update_permissions, b.delete_permissions');
		$this->db->join($this->table2.' b', 'a.id_nav_content = b.nav_permissions', 'left');
		$this->db->where($value);
		return $this->db->get($this->table.' a');
	}

	public function show_permissions_menu($value=array())
	{
		$this->db->select('a.id_nav_content, a.desc_nav_content,a.url');
		$this->db->join($this->table2.' b', 'a.id_nav_content = b.nav_permissions', 'left');
		$this->db->where($value);
		return $this->db->get($this->table.' a');
	}

	public function check_permission($value)
	{
		$this->db->select('id_permissions,read_permissions, create_permissions, update_permissions, delete_permissions');
		$this->db->where($value);
		return $this->db->get($this->table2,1);
	}

	public function insert_permission($data)
	{
		$this->db->insert($this->table2, $data);
		return $this->db->affected_rows();
	}

	public function update_permission($where, $data)
	{
		$this->db->where('id_permissions', $where);
		$this->db->update($this->table2, $data);
		return $this->db->affected_rows();
	}

}

/* End of file M_permission.php */
/* Location: ./application/models/M_permission.php */