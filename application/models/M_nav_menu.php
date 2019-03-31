<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_nav_menu extends CI_Model {
	var $table = 'tbl_nav';
	var $column_order = array(null, 'id_nav','desc_nav','order_nav'); //set column field database for datatable orderable
	var $column_search = array('id_nav','desc_nav'); //set column field database for datatable searchable 
	var $order = array('id_nav' => 'asc'); // default order 
	var $table2 = 'tbl_nav_content';
	var $column_order2 = array(null, 'id_nav_content','desc_nav_content','order_nav','url'); //set column field database for datatable orderable
	var $column_search2 = array('id_nav_content','desc_nav_content','order_nav','url'); //set column field database for datatable searchable 
	var $order2 = array('id_nav_content' => 'asc'); // default order 
	

	public function show()
	{
		$this->db->select('id_nav,desc_nav, fa');
		$this->db->order_by('order_nav', 'asc');
		return $this->db->get($this->table);
	}
	
	private function _get_datatables_query()
	{
		$this->db->from($this->table);
		$i = 0;
	
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function insert_nav($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->affected_rows();
	}
	
	public function edit($id)
	{
		$this->db->where('id_nav', $id);
		$query = $this->db->get($this->table,1);
		return $query;
	}

	public function update_nav($where, $data)
	{
		$this->db->where('id_nav', $where);
		$this->db->update($this->table, $data);
		return $this->db->affected_rows();
	}

	//function query tbl_nav_content
	public function nav_content_show($data, $order=null)
	{
		$this->db->select('*');
		$this->db->where($data);
		if ($order === null) {
			$this->db->order_by('order_nav', 'asc');
		}
		else{
			$this->db->order_by($order, 'asc');
		}
		return $this->db->get($this->table2);
	}

	private function _get_datatables_query_content()
	{
		$this->db->select('a.id_nav_content,a.desc_nav_content,a.order_nav,a.url,b.desc_nav');
		$this->db->from($this->table2.' a');
		$this->db->join($this->table.' b', 'a.fk_id_nav = b.id_nav', 'left');
		$i = 0;
	
		foreach ($this->column_search2 as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search2) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order2[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order2))
		{
			$order = $this->order2;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables_content()
	{
		$this->_get_datatables_query_content();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered_content()
	{
		$this->_get_datatables_query_content();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_content()
	{
		$this->db->from($this->table2);
		return $this->db->count_all_results();
	}

	public function insert_nav_content($data)
	{
		$this->db->insert($this->table2, $data);
		return $this->db->affected_rows();
	}

	public function update_nav_content($where, $data)
	{
		$this->db->where('id_nav_content', $where);
		$this->db->update($this->table2, $data);
		return $this->db->affected_rows();
	}
}

/* End of file M_nav_menu.php */
/* Location: ./application/models/M_nav_menu.php */