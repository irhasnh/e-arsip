<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Set_email extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
        $this->template->if_admin();
		$this->template->set('title',"Set Your Email");
        $this->_init();
	}

    function _init()
    {
        $this->template->css('assets/themes/adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
        $this->template->css('assets/themes/adminlte/plugins/iCheck/all.css');
        $this->template->js('assets/themes/adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js');
        $this->template->js('assets/themes/adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js');
        $this->template->js('assets/themes/adminlte/plugins/iCheck/icheck.min.js');
    }

	public function index()
	{
		
		$this->load->model('advance/M_set_advance','set_email');
		$data = array();
		$row = $this->set_email->show(array('kd_advance'=>'set_email'))->row();
		$data['cek'] = $row;
		if (!empty($row->setting) or isset($row->setting)) {
			# code...
			$hasil = explode(',', $row->setting);
			$result = array();
			foreach ($hasil as $k) {
				$u = explode('=>', $k);
				if (count($u) >= 2) {
					# code...
					$result[$u[0]] = $u[1];
				}
			}
			$data['result'] = $result;
		}else{
			$data['result'] = array();
		}
		$this->template->adminlte('advance/email/v_set_email',$data,'advance/email/j_set_email');
	}

	public function set_aksi($value='')
	{
		# code...
		$this->form_validation->set_rules('allow', 'Allow Use', 'trim|min_length[1]|max_length[1]');
		$this->form_validation->set_rules('smtp', 'protocol', 'trim|required|min_length[1]|max_length[32]|alpha_numeric');
		$this->form_validation->set_rules('host', 'fieldlabel', 'trim|required|min_length[5]|max_length[32]');
	}

}

/* End of file Set_email.php */
/* Location: ./application/controllers/advance/Set_email.php */