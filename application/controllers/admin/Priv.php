<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Priv extends CI_Controller {

    public $id;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_privilages');
		$this->template->if_admin();
		$this->template->set("title","welcome to adminlte");
        $this->_init();
	}

	public function index()
	{
        $data = array();
		$this->template->adminlte('admin/v_privilages',$data, 'admin/v_privilages_js');
	}

	function _init()
    {
        $this->template->css('assets/themes/adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
        $this->template->css('assets/themes/adminlte/plugins/iCheck/all.css');
        $this->template->js('assets/themes/adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js');
        $this->template->js('assets/themes/adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js');
        $this->template->js('assets/themes/adminlte/plugins/iCheck/icheck.min.js');
    }

    public function ajax_list()
    {
        $list = $this->M_privilages->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $id = "";
        $x = 1;
        foreach ($list as $u) {
            $row = array();
            $id = $u->id_priv;
            $row[] = $x;
            $x++;
            $row[] = $u->desc_priv;
            $row[] = $u->desc_priv_more;
            $row[] =    "<button class='btn btn-xs btn-primary btn-flat' data-toggle='tooltip' data-placement='top' title='edit data' onclick=\"edit('".$id."')\"><i class='fa fa-edit'></i></button> <button class='btn btn-xs btn-warning btn-flat' data-toggle='tooltip' data-placement='top' title='permission menu' onclick=\"setting('".$id."')\"><i class='fa fa-gear'></i></button>";
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->M_privilages->count_all(),
                        "recordsFiltered" => $this->M_privilages->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $query = $this->M_privilages->show(array('id_priv'=>$id))->row();
        $data = array(
            'result' => $query
            );
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
        // echo json_encode($data);
    }

    public function add_priv()
    {
        // $this->input->post('inputIdPriv');
        $this->form_validation->set_rules('inputDescPriv', 'Desc Privilages', 'required|max_length[32]');
        $desc = $this->input->post('inputDescPriv');
        $this->form_validation->set_rules('inputDescPrivMore', 'Desc Privilages More', 'required|max_length[64]');
        $desc_more = $this->input->post('inputDescPrivMore');
        $result = array();
        if ($this->form_validation->run() == FALSE) {
            $result['status'] = 'warning';
            $result['ket'] = validation_errors();
        }else{
            if (!empty($desc) or !empty($desc_more) or isset($desc) or isset($desc_more) or $desc == '' or $desc_more == '' ) {
                $data = array(
                'desc_priv' => $desc,
                'desc_priv_more' => $desc_more
                );
                $query = $this->M_privilages->insert_priv($data);
                if ($query >= 1) {
                    $result['status'] = 'success';
                    $result['ket'] = "saving success";
                }
                else{
                    $result['status'] = 'warning';
                    $result['ket'] = "saving error";
                }
            }
            else{
                $result['status'] = 'warning';
                $result['ket'] = "Data Not Set";
            } 
        } 
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function update()
    {

        $this->form_validation->set_rules('inputIdPriv', 'ID Desc Privilages', 'required|numeric');
        $id = $this->input->post('inputIdPriv');
        $this->form_validation->set_rules('inputDescPriv', 'Desc Privilages', 'required|max_length[32]');
        $desc = $this->input->post('inputDescPriv');
        $this->form_validation->set_rules('inputDescPrivMore', 'Desc Privilages More', 'required|max_length[64]');
        $desc_more = $this->input->post('inputDescPrivMore');
        if ($this->form_validation->run() == FALSE) {
            $result['status'] = 'warning';
            $result['ket'] = validation_errors();
        }else{
            $data = array(
                'desc_priv' => $desc,
                'desc_priv_more' => $desc_more
                );
            $query = $this->M_privilages->update_priv($id , $data);
            $result = array();
            if ($query >= 1) {
                $result['status'] = 'success';
                $result['ket'] = 'Updating '.$query.' Data Success';
            }
            else{
                $result['status'] = 'warning';
                $result['ket'] = 'Updating '.$query.' Data Success';
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function permission($id)
    {
        $this->load->model('M_nav_menu');
        $this->load->model('M_permission');
        $data = array();
        $data_menu = array();
        $menu = $this->M_nav_menu->nav_content_show(array(), 'id_nav_content')->result();
        $permission = $this->M_privilages->show_permissions(array('priv_permissions'=>$id))->result();
        foreach ($menu as $key_menu => $value_menu) {
            $tmp = new stdClass;
            $tmp->id_nav_content = $value_menu->id_nav_content;
            $tmp->desc_nav_content = $value_menu->desc_nav_content;
            $tmp->read_check = '';
            $tmp->create_check = '';
            $tmp->update_check = '';
            $tmp->delete_check = '';
            foreach ($permission as $p) {
                if ($p->nav_permissions == $value_menu->id_nav_content) {
                    if ($p->read_permissions == 1) {
                        $tmp->read_check = 'checked';
                    }
                    if ($p->create_permissions == 1) {
                        $tmp->create_check = 'checked';
                    }
                    if ($p->update_permissions == 1) {
                        $tmp->update_check = 'checked';
                    }
                    if ($p->delete_permissions == 1) {
                        $tmp->delete_check = 'checked';
                    }
                }
                // $tmp->read_check = $p->nav_permissions === $value_menu->id_nav_content and $p->read_permissions === 1 ?  'checked' : '';
                // $tmp->create_check = $p->nav_permissions === $value_menu->id_nav_content and $p->create_permissions === 1 ?  'checked' : '';
                // $tmp->update_check = $p->nav_permissions === $value_menu->id_nav_content and $p->update_permissions === 1 ?  'checked' : '';
                // $tmp->delete_check = $p->nav_permissions === $value_menu->id_nav_content and $p->delete_permissions === 1 ?  'checked' : '';
            }
            $data_menu[] = $tmp;
        }
        $data['menu'] = $data_menu;
        $data['priv'] = $id;
        // print_r($menu);
        $this->template->adminlte('admin/permission',$data,'admin/permission_js');
    }

    public function permission_aksi()
    {
        $this->form_validation->set_rules('read', 'read', 'numeric');
        $this->form_validation->set_rules('create', 'create', 'numeric');
        $this->form_validation->set_rules('update', 'update', 'numeric');
        $this->form_validation->set_rules('delete', 'delete', 'numeric');
        $this->form_validation->set_rules('menu_content', 'menu_content', 'numeric');
        $this->form_validation->set_rules('id', 'id priv', 'numeric');
        $read = $this->input->post('read');
        $create = $this->input->post('create');
        $update = $this->input->post('update');
        $delete = $this->input->post('delete');
        $menu_content = $this->input->post('menu_content');
        $id = $this->input->post('id');
        $result = array();
        $test = array();
        $this->load->model('M_permission');
        if ($this->form_validation->run() == FALSE) {
            $result['status'] = 'warning';
            $result['ket'] = validation_errors();
        }else{
            $data_query = array();
            foreach ($menu_content as $mc) {
                $v_read = isset($read[$mc]) ? $read[$mc]:0;
                $v_create = isset($create[$mc]) ? $create[$mc]:0;
                $v_update = isset($update[$mc]) ? $update[$mc]:0;
                $v_delete = isset($delete[$mc]) ? $delete[$mc]:0;
                $data_query = array(
                    'priv_permissions'=>$id,
                    'nav_permissions'=>$mc,
                    'read_permissions' => $v_read,
                    'create_permissions' => $v_create,
                    'update_permissions' => $v_update,
                    'delete_permissions' => $v_delete
                    );
                $cek = $this->M_permission->check_permission(array('priv_permissions'=>$id,'nav_permissions'=>$mc));
                $cek_result = $cek->row();
                if ($cek->num_rows() >= 1) {
                    $query_update = $this->M_permission->update_permission($cek_result->id_permissions, $data_query);
                    if ($query_update >= 1) {
                    }
                }
                else{
                    $query_add = $this->M_permission->insert_permission($data_query);
                    if ($query_add >= 1) {
                    }
                }
                // $test .= isset($read[$mc])?$read[$mc]:0;
                // $test .= ' read '.$mc.' content ';
            }
        }
        $result['status'] = 'success';
        $result['ket'] = 'update successfully';
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

}

/* End of file Priv.php */
/* Location: ./application/controllers/admin/Priv.php */