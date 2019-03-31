<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class login extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_user');
		$this->load->model('M_online');
	}

	public function index()
	{
		$this->load->view('v_login');
	}

	public function doLogin()
	{
		$this->form_validation->set_rules('txtUsername', 'Username', 'required|min_length[3]|max_length[16]');
		$this->form_validation->set_rules('txtPassword', 'Password', 'required',array('required' => 'You must provide a %s.'));
		$username = $this->input->post('txtUsername');
		$password = $this->input->post('txtPassword');
		$password = md5($password);
		$ip = $this->input->ip_address();
		$user_agent = $this->input->user_agent();
		$where = array(
			'username' => $username,
			'password' => $password
			);
		$log = array();
		if ($this->form_validation->run() == FALSE) {
			$log['status'] = 'x';
			$log['keterangan'] = validation_errors();
		} else {
			$query = $this->M_user->lihat2($where);
			$this->load->library('Uuid');
			$cek = $query->num_rows();
			$row = $query->row();
			$sesi = $this->uuid->v4();
			if($cek == 1){
				if ($row->allow_to_login == 0) {
					$data_session = array(
						'id' => $row->id_user,
						'session' => $sesi,
						'username' => $username
						);
					$data= array(
						'id_user' => $row->id_user,
						'session' => $sesi
						// 'date_time' => date('Y-m-d H:i:s')
						);
					$this->M_online->online($data);
					$log = array(
						'id_user' => $row->id_user,
						// 'date_and_time' => date('Y-m-d H:i:s'),
						'user_agents' => $user_agent,
						'ip_address' => $ip,
						'log' => 'login'
						);
					$this->M_online->log($log);
					//echo date('Y-m-d H:i:s');
					$this->session->set_userdata($data_session);
					//echo " login berhasil";
					$log['status']= 'y';
				}
				else{
					$log['status'] = 'x';
					$log['keterangan'] = 'your account is '.$row->desc_user_status.'.<br/> Administrator not allowed '.$row->desc_user_status.' user to Login';
				}
			}else{
				$log['status'] = 'x';
				$log['keterangan'] = 'username or password is wrong';
			}
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($log));
	}

	public function logout()
	{
		$id = $this->session->userdata('id');
		$query = $this->M_online->delete_online($id);
		$ip = $this->input->ip_address();
		$user_agent = $this->input->user_agent();
		$log = array(
			'id_user' => $id,
			'date_and_time' => date('Y-m-d H:i:s'),
			'user_agents' => $user_agent,
			'ip_address' => $ip,
			'log' => 'logout'
			);
		if (!empty($id) or isset($id) or $id != NULL) {
			$this->M_online->log($log);
		}
		$this->session->sess_destroy();
		redirect(base_url("login"));
	}

	function coba()
    {
        $u = '\'u\'';
        echo $u;
        $where = array(
            'b.session' => $this->session->userdata('session'),
            'a.privilages_user' => 1,
            'a.flag'=> 0
            );
        $query = $this->M_user->lihat($where)->result();
        print_r($query);
    }
}

/* End of file Contoh.php */
/* Location: ./application/controllers/Contoh.php */