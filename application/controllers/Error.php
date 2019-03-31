<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		$this->template->authlogin();
	}

	public function not_found()
	{
		$data = array(
			'hi' => "Coba"
			);
		$this->template->set('title',"error bro");
		$this->template->adminlte('errors/adminlte/error_404', $data);
	}

}

/* End of file Error.php */
/* Location: ./application/controllers/Error.php */