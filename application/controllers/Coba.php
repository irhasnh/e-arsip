<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coba extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
	}

	public function index()
	{
		$this->template->authlogin('r',1);
		$data = array(
			'hi' => "Coba"
			);
		$this->template->set('title',"Coba Read bro");
		$this->template->adminlte('layouts/home', $data);
	}

	public function update($value='')
	{
		$this->template->authlogin('u',1);
		$data = array(
			'hi' => "Halaman Update"
			);
		$this->template->set('title',"home bro");
		$this->template->adminlte('layouts/home', $data);
	}

	public function create($value='')
	{
		$this->template->authlogin('c',1);
		$data = array(
			'hi' => "Halaman Create"
			);
		$this->template->set('title',"home bro");
		$this->template->adminlte('layouts/home', $data);
	}

	public function delete($value='')
	{
		$this->template->authlogin('d',1);
		$data = array(
			'hi' => "Halaman Delete"
			);
		$this->template->set('title',"home bro");
		$this->template->adminlte('layouts/home', $data);
	}

	public function date($value='')
	{
		# code...
		// echo date('d_m_Y_H_i_s');
		$data = "'a'=>'konfig 1','b'=>'konfig b'";
		$hasil = explode(',',$data);
		$result = array();
		foreach ($hasil as $k) {
			$u = explode('=>', $k);
			$result[$u[0]] = $u[1];
		}
		print_r($result);
	}

}

/* End of file Coba.php */
/* Location: ./application/controllers/Coba.php */