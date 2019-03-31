<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_user');
		$this->load->library('template');
        $this->template->if_admin();
		$this->template->set('title',"welcome to adminlte");
        $this->_init();
	}

    function _init()
    {
        $this->template->css('assets/themes/adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
        $this->template->css('assets/themes/adminlte/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css');
        $this->template->js('assets/themes/adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js');
        $this->template->js('assets/themes/adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js');
        $this->template->js('assets/themes/adminlte/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js');
    }

	public function index()
	{
        $data = array();
		$this->template->adminlte('user/user_view',$data,'user/user_view_js');
	}

    public function add_user()
    {
        $this->load->library('Uuid');
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[32]');
        $username = $this->input->post('username');
        $this->form_validation->set_rules('password', 'Password', 'required',
                array('required' => 'You must provide a %s.')
        );
        $this->form_validation->set_rules('status', 'status user', 'trim|required|min_length[1]|max_length[2]|numeric');
        $status = $this->input->post("status");
        $password = $this->input->post('password');
        $password = md5($password);
        $priv = $this->input->post('priv');
        $this->form_validation->set_rules('priv', 'Privilages', 'required|numeric');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status'=> 0,'ket'=> validation_errors()));
        }else{
            $validasi_user = $this->M_user->lihat_by(array('username'=>$username))->num_rows(); 
            if ($validasi_user === 0) {
                # code...
                $data = array(
                    'id_user' => $this->uuid->v4(),
                    'username' => $username,
                    'password' => $password,
                    'privilages_user' => $priv,
                    'flag' => $status
                    // 'create_at' => date('Y-m-d H:i:s')
                    );
                $query = $this->M_user->insert_user($data);
                echo json_encode(array('status'=> 1));
            }
            else{
                echo json_encode(array('status'=> 0,'ket'=> 'username '.$username.' used by other'));   
            }
        }
    }

	public function ajax_list()
    {
        $list = $this->M_user->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $id = "";
        foreach ($list as $u) {
            $no++;
            $row = array();
            $id = "$u->id_user";
            $row[] = $no;
            $online = '';
            if (isset($u->session) or $u->session !='') {
                # code...
                $online = '<span class="label label-success pull-right">Online</span>';
            }
            $row[] = $u->username.' '.$online;
            $row[] = $u->desc_priv;
            $row[] = $u->create_at;
            $row[] = '<span class="label" style="background-color:'.$u->color_user_status.'">'.$u->desc_user_status.'</span>';
            $row[] =    "<button class='btn btn-sm btn-primary btn-flat' data-toggle='tooltip' data-placement='top' title='edit data' onclick=\"edit('".$u->id_user."')\"><i class='fa fa-edit'></i></button> ".
                        "<button class='btn btn-sm btn-danger btn-flat' data-toggle='tooltip' data-placement='top' title='reset password' onclick=\"reset('".$u->id_user."','".$u->username."')\"><i class='fa fa-key'></i></button>";
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->M_user->count_all(),
                        "recordsFiltered" => $this->M_user->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_privilages()
    {
        $data = array(
            'data' => $this->_privilages(),
            'status' => $this->_user_status()
            );
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
        // echo json_encode($data);
    }

    private function _privilages()
    {
        $this->load->model('M_privilages');
        $result = $this->M_privilages->show()->result();
        return $result;
    }

    private function _user_status()
    {
        $this->load->model('M_user');
        $result = $this->M_user->user_status_show()->result();
        return $result;
    }

    public function ajax_edit($id)
    {
        $query = $this->M_user->lihat2(array('a.id_user'=>$id))->row();
        $data = array(
            'result' => $query,
            'data' => $this->_privilages(),
            'status' => $this->_user_status()
            );
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
        // echo json_encode($data);
    }

    public function update()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[32]');
        $username = $this->input->post('username');
        $this->form_validation->set_rules('username2', 'Username', 'required|min_length[3]|max_length[32]');
        $username2 = $this->input->post('username2');
        $this->form_validation->set_rules('priv', 'Privilages', 'required|numeric');
        $priv = $this->input->post('priv');
        $this->form_validation->set_rules('id_user', 'ID User', 'required|max_length[36]');
        $this->form_validation->set_rules('status', 'status user', 'trim|required|min_length[1]|max_length[2]|numeric');
        $status = $this->input->post("status");
        $id_user = $this->input->post('id_user');
        $data = array();
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status'=> 0,'ket'=> validation_errors()));
        }else{
            $validasi_user = $this->M_user->lihat_by("username = '".$username."' and id_user != '".$id_user."'")->num_rows(); 
            if ($validasi_user === 0) {
                # code...
                $data = array(
                    'username' => $username,
                    'privilages_user' => $priv,
                    'flag' => $status
                    );
                $query = $this->M_user->update_user($id_user, $data);
                if (!$query) {
                    # code...
                    $data['status'] = 0;
                    $data['ket'] = $this->db->error();
                }
                else{
                    $data['status'] = "ok";
                    $data['ket'] = "sukses";
                }
            }
            else{
                $data['status'] = 0;
                $data['ket'] = 'username '.$username.' used by other';
                // $data['ket'] = $validasi_user;
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function reset()
    {
        $new = $this->input->post('new');
        $retype = $this->input->post('retype');
        $id_user = $this->input->post('id_user');
        $data = array();
        if ($new != $retype) {
            $data['key'] = 0;
            $data['status'] = "password not same";
        }
        elseif (empty($new) || !isset($new) || empty($retype) || !isset($new) ) {
            $data['key'] = 0;
            $data['status'] = "password cannot null";
        }
        else{
            $update = array(
                'password' => md5($new)
                );
            $query = $this->M_user->update_user($id_user, $update);
            $key['key'] = 1;
            $data['status'] = "ok";
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function status_ajax_list()
    {
        # code...
        $list = $this->M_user->get_datatables_status();
        $data = array();
        $no = $_POST['start'];
        $id = "";
        foreach ($list as $u) {
            $no++;
            $row = array();
            $id = "$u->id_user_status";
            $row[] = $no;
            $row[] = $id;
            $row[] = $u->desc_user_status;
            $row[] = '<span class="label" style="background-color:'.$u->color_user_status.';">'.$u->color_user_status.'</span>';
            $row[]= $u->allow_to_login == 0 ? 'Yes' : 'No';
            $row[] =    "<button class='btn btn-sm btn-primary btn-flat' data-toggle='tooltip' data-placement='top' title='edit data' onclick=\"edit_status('".$id."')\"><i class='fa fa-edit'></i></button> ";
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->M_user->count_all_status(),
                        "recordsFiltered" => $this->M_user->count_filtered_status(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function status_ajax_edit($id)
    {
        $query = $this->M_user->user_status_show(array('id_user_status'=>$id))->row();
        $data = array(
            'result' => $query
            );
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
        // echo json_encode($data);
    }

    public function add_status()
    {
        $this->form_validation->set_rules('desc_status', 'Description', 'trim|required|min_length[1]|max_length[32]|alpha_numeric_spaces');
        $this->form_validation->set_rules('color_status', 'Color', 'trim|required|min_length[5]|max_length[8]');
        $this->form_validation->set_rules('alto', 'allow to login', 'trim|required|min_length[1]|max_length[2]|numeric');
        $desc = $this->input->post("desc_status");
        $color = $this->input->post("color_status");
        $alto = $this->input->post("alto");
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status'=> 0,'ket'=> validation_errors()));
        }else{
            $data = array(
                'desc_user_status' => $desc,
                'color_user_status' => $color,
                'allow_to_login' => $alto
                );
            $query = $this->M_user->insert_status($data);
            echo json_encode(array('status'=> 1));
        }
    }

    public function update_status()
    {
        $this->form_validation->set_rules('id_user_status', 'fieldlabel', 'trim|required|min_length[1]|max_length[3]|numeric');
        $this->form_validation->set_rules('id_status', 'fieldlabel', 'trim|required|min_length[1]|max_length[3]|numeric');
        $this->form_validation->set_rules('desc_status', 'Description', 'trim|required|min_length[1]|max_length[32]|alpha_numeric_spaces');
        $this->form_validation->set_rules('color_status', 'Color', 'trim|required|min_length[5]|max_length[8]');
        $this->form_validation->set_rules('alto', 'allow to login', 'trim|required|min_length[1]|max_length[2]|numeric');
        $id = $this->input->post("id_user_status");
        $id_change = $this->input->post("id_status");
        $desc = $this->input->post("desc_status");
        $color = $this->input->post("color_status");
        $alto = $this->input->post("alto");
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status'=> 0,'ket'=> validation_errors()));
        }else{
            $data = array(
                "id_user_status" => $id_change,
                'desc_user_status' => $desc,
                'color_user_status' => $color,
                'allow_to_login' => $alto
                );
            $query = $this->M_user->update_user_status($id,$data);
            echo json_encode(array('status'=> 1));
        }
    }

}

/* End of file User.php */
/* Location: ./application/controllers/backend/User.php */