<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_set_advance extends CI_Model {
	var $tbl = 'tbl_advance';

	public function show($value=array())
	{
		# code...
		$this->db->select('kd_advance, allow_use, setting');
		$this->db->from($this->tbl);
		$this->db->where($value);
		return $this->db->get();
	}

	public function update($where, $data)
	{
		$this->db->where('kd_advance', $where);
		$this->db->update($this->tbl, $data);
		return $this->db->affected_rows();
	}

}

/* End of file M_set_advance.php */
/* Location: ./application/models/advance/M_set_advance.php */