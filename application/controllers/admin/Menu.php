<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_fa');
		$this->load->model('M_nav_menu');
		$this->template->if_admin();
        $this->_init();
	}

	public function index()
	{
        $data = array();
        $this->template->set("title","welcome to adminlte");
		$this->template->adminlte('admin/menu2', $data,'admin/menu_js');
	}

	private function _init()
    {
        $this->template->css('assets/themes/adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
        $this->template->js('assets/themes/adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js');
        $this->template->js('assets/themes/adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js');
    }

    public function ajax_nav_list()
    {
        $list = $this->M_nav_menu->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $id = "";
        $x = 1;
        foreach ($list as $u) {
            $row = array();
            $id = $u->id_nav;
            $row[] = $x;
            $x++;
            $row[] = $u->desc_nav;
            $row[] = "<i class='fa ".$u->fa."'></i>";
            $row[] = $u->order_nav;
            $row[] =    "<button class='btn btn-sm btn-primary btn-flat' data-toggle='tooltip' data-placement='top' title='edit data' onclick=\"edit_nav('".$id."')\"><i class='fa fa-edit'></i></button> ";
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->M_nav_menu->count_all(),
                        "recordsFiltered" => $this->M_nav_menu->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function fa($y=null)
    {
    	$data = array();
    	$x = 52;
    	$page = ($y >= 1) ? ($x*$y) : 0;
    	$data['hasil'] = $this->M_fa->show($x,$page)->result();
    	$data['page'] = $page;
        $data['total_page'] = ceil($this->M_fa->count_all()/$x);
    	// print_r($data);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function add_nav()
    {
        $this->form_validation->set_rules('inputDescNav', 'Desc Nav', 'required|max_length[16]');
        $this->form_validation->set_rules('inputFa', 'Icon', 'required|max_length[32]');
        $this->form_validation->set_rules('inputOrderNav', 'Order Nav', 'required|numeric');
        $desc = $this->input->post('inputDescNav');
        $fa = $this->input->post('inputFa');
        $order_nav = $this->input->post('inputOrderNav');
        $result = array();
        if ($this->form_validation->run() === FALSE) {
            $result['status'] = 'warning';
            $result['ket'] = validation_errors();
        }else{
            $data = array(
                'desc_nav' => $desc,
                'fa' => $fa,
                'order_nav' => $order_nav
            );
            $query = $this->M_nav_menu->insert_nav($data);
            if ($query >= 1) {
                $result['status'] = 'success';
                $result['ket'] = "saving success";
            }
            else{
                $result['status'] = 'warning';
                $result['ket'] = "saving error";
            }
        } 
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

     public function ajax_edit_nav($id)
    {
        $query = $this->M_nav_menu->edit($id)->row();
        $data = array(
            'result' => $query
            );
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
        // echo json_encode($data);
    }

    public function update_nav()
    {
        $this->form_validation->set_rules('inputDescNav', 'Desc Nav', 'required|max_length[16]');
        $this->form_validation->set_rules('inputFa', 'Icon', 'required|max_length[32]');
        $this->form_validation->set_rules('inputIdNav', 'Id Nav', 'required|numeric');
        $this->form_validation->set_rules('inputOrderNav', 'Order Nav', 'required|numeric');
        $desc = $this->input->post('inputDescNav');
        $fa = $this->input->post('inputFa');
        $id = $this->input->post('inputIdNav');
        $order_nav = $this->input->post('inputOrderNav');
        $result = array();
        if ($this->form_validation->run() === FALSE) {
            $result['status'] = 'warning';
            $result['ket'] = validation_errors();
        }else{
            $data = array(
                'desc_nav' => $desc,
                'fa' => $fa,
                'order_nav' => $order_nav
            );
            $query = $this->M_nav_menu->update_nav($id, $data);
            if ($query >= 1) {
                $result['status'] = 'success';
                $result['ket'] = "Update Data success";
            }
            else{
                $result['status'] = 'warning';
                $result['ket'] = "Update Data ".$query." success";
            }
        } 
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function ajax_nav_list_content()
    {
        $list = $this->M_nav_menu->get_datatables_content();
        $data = array();
        $no = $_POST['start'];
        $id = "";
        $x = 1;
        foreach ($list as $u) {
            $row = array();
            $id = $u->id_nav_content;
            $row[] = $x;
            $x++;
            $row[] = $id;
            $row[] = $u->desc_nav_content;
            $row[] = $u->desc_nav;
            $row[] = $u->order_nav;
            $row[] = $u->url;
            $row[] = "<button class='btn btn-sm btn-primary btn-flat' data-toggle='tooltip' data-placement='top' title='edit data' onclick=\"edit_nav_content('".$id."')\"><i class='fa fa-edit'></i></button> ";
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->M_nav_menu->count_all_content(),
                        "recordsFiltered" => $this->M_nav_menu->count_filtered_content(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_menu()
    {
        $query = $this->M_nav_menu->show()->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($query));
    }

    public function add_content()
    {
        $this->form_validation->set_rules('inputDescNavContent', 'Name Content', 'required|max_length[16]|alpha_numeric_spaces');
        $this->form_validation->set_rules('inputOrderNavContent', 'Order Content', 'numeric');
        $this->form_validation->set_rules('inputUrlContent', 'url or controller', 'required');
        $this->form_validation->set_rules('inputNavContent', 'nav content', 'required|numeric');
        $name = $this->input->post('inputDescNavContent');
        $order = $this->input->post('inputOrderNavContent');
        $nav_content = $this->input->post('inputNavContent');
        $url = $this->input->post('inputUrlContent');
        $result = array();
        if ($this->form_validation->run() === FALSE) {
            $result['status'] = 'warning';
            $result['ket'] = validation_errors();
        }else{
            $data = array(
                'desc_nav_content' => $name,
                'fk_id_nav' => $nav_content,
                'order_nav' => $order,
                'url' => $url
            );
            $query = $this->M_nav_menu->insert_nav_content($data);
            if ($query >= 1) {
                $result['status'] = 'success';
                $result['ket'] = "saving success";
            }
            else{
                $result['status'] = 'warning';
                $result['ket'] = "saving error";
            }
        } 
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function ajax_edit_nav_content($id)
    {
        $query = $this->M_nav_menu->nav_content_show(array('id_nav_content'=>$id))->row();;
        $data = array(
            'result' => $query,
            'menu' => $this->M_nav_menu->show()->result_array()
            );
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function update_content()
    {
        $this->form_validation->set_rules('inputDescNavContent', 'Name Content', 'required|max_length[16]|alpha_numeric_spaces');
        $this->form_validation->set_rules('inputOrderNavContent', 'Order Content', 'numeric');
        $this->form_validation->set_rules('inputUrlContent', 'url or controller', 'required');
        $this->form_validation->set_rules('inputNavContent', 'nav content', 'required|numeric');
        $this->form_validation->set_rules('inputIdNavContent', 'id nav content', 'required|numeric');
        $name = $this->input->post('inputDescNavContent');
        $order = $this->input->post('inputOrderNavContent');
        $nav_content = $this->input->post('inputNavContent');
        $url = $this->input->post('inputUrlContent');
        $id = $this->input->post('inputIdNavContent');
        $result = array();
        if ($this->form_validation->run() === FALSE) {
            $result['status'] = 'warning';
            $result['ket'] = validation_errors();
        }else{
            $data = array(
                'desc_nav_content' => $name,
                'fk_id_nav' => $nav_content,
                'order_nav' => $order,
                'url' => $url
            );
            $query = $this->M_nav_menu->update_nav_content($id, $data);
            if ($query >= 1) {
                $result['status'] = 'success';
                $result['ket'] = "Update Data success";
            }
            else{
                $result['status'] = 'warning';
                $result['ket'] = "Update Data ".$query." success";
            }
        } 
        $this->output->set_content_type('application/json')->set_output(json_encode($result));

    }

    public function coba()
    {
        // $query = $this->M_nav_menu->show()->result();
        // $data = array();
        // foreach ($query as $k) {
        //     $tmp = new stdClass;
        //     $tmp->desc_nav = $k->desc_nav;
        //     $tmp->fa = $k->fa;
        //     $tmp->nav_content = $this->M_nav_menu->nav_content_show($k->id_nav)->result();
        //     $data[] = $tmp;
        // }
        // foreach ($data as $k) {
        //     echo $k->desc_nav.'</br>';
        //     foreach ($k->nav_content as $u) {
        //         echo $u->desc_nav_content;
        //     }
        // }
        $result = $this->ajax_menu();
        print_r($result);
        // print_r($data);
    }

}

/* End of file Menu.php */
/* Location: ./application/controllers/admin/Menu.php */