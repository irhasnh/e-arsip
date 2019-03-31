<?php
/**
* 
*/
class Template
{
	private $CI;
	private $_js = array();
	private $_css = array();
	private $_meta = array();
	private $_canonical = array();
	private $_js_view = 'layouts/js_view';
	private $template_data = array();
	private $sidebar_data = array();
	
	public function __construct()
	{
		$this->CI =& get_instance(); 
	}

	public function set($content_area, $value)
	{
		$this->template_data[$content_area] = $value;
	}

	public function section($name='', $view = '',$view_data = array())
	{
		$this->set($name, $this->CI->load->view($view, $view_data, TRUE));
	}

	public function css()
	{
		$css_files = func_get_args();
		foreach ($css_files as $value) {
			$this->_css[] = $value;
		}
		return;
	}

	public function js()
	{
		$js_files = func_get_args();
		foreach ($js_files as $value) {
			$this->_js[] = $value;
		}
		return;
	}

	public function set_meta($name, $content){
		$this->_meta[$name] = $content;
		return true;
	}

	public function set_canonical($url)
    {
       $this->_canonical = $url;
    }

	public function load($template = '', $name='', $view = '',$view_data = array(),$return = FALSE)
	{
		$this->template_data['js'] = $this->_js;
		$this->template_data['css'] = $this->_css;
		$this->template_data['meta'] = $this->_meta;
		$this->set($name, $this->CI->load->view($view, $view_data, TRUE));
		$this->CI->load->view('layouts/'.$template , $this->template_data); 
	}

	// take your custom template setting in here

	public function adminlte($content, $data= array(),$js_view=NULL, $return= FALSE)
	{
		if ($js_view != NULL) {
			$this->_js_view = $js_view;
		}
		$this->section('sidebar','layouts/sidebar', $this->sidebar_data);
		// $this->section('control_sidebar','layouts/control_sidebar');
		$this->section('js_view',$this->_js_view);
		$this->load('default','content',$content, $data);
	}

	private function auth($where,$kd=NULL,$mn=NULL, $ajax = NULL)
	{
		$CI =& get_instance();
		$CI->load->model('M_user');
		$CI->load->model('M_online');
		$CI->load->model('M_nav_menu');
		$CI->load->model('M_permission');
		$create = '';
		$update = '';
		$delete = '';
		$query = $CI->M_user->lihat($where);
		$cek = $query->row();
		if($query->num_rows() != 1){
			redirect(base_url("login"));
		}
		else{
			$data= array(
				'id_user' => $cek->id_user,
				'session' => $CI->session->userdata('session'),
				'date_time' => date('Y-m-d H:i:s')
				);
			$CI->M_online->online($data);
			$cek_permission = '';
			if ($cek->privilages_user != 1 and $kd != NULL) {
				$cek_permissions = $CI->M_permission->check_permission(array('priv_permissions'=>$cek->privilages_user,'nav_permissions'=>$mn));
				if ($cek_permissions->num_rows() != 1) {
					redirect(base_url('error/not_found'));
				}
				else{
					$cek_permissions_row = $cek_permissions->row();
					if ($kd == 'r' and $mn != NULL) {
						if ($cek_permissions_row->read_permissions != 1) {
							redirect(base_url('error/not_found'));
						}
					}
					if ($kd == 'u' and $mn != NULL){
						if ($cek_permissions_row->update_permissions != 1) {
							redirect(base_url('error/not_found'));
						}	
					}
					if ($kd == 'c' and $mn != NULL){
						if ($cek_permissions_row->create_permissions != 1) {
							redirect(base_url('error/not_found'));
						}	
					}
					if ($kd == 'd' and $mn != NULL){
						if ($cek_permissions_row->delete_permissions != 1) {
							redirect(base_url('error/not_found'));
						}	
					}
					$create = $cek_permissions_row->create_permissions;
					$update = $cek_permissions_row->update_permissions;
					$delete = $cek_permissions_row->delete_permissions;
				}
			}
			if ($cek->privilages_user == 1) {
				$create = 1;
				$update = 1;
				$delete = 1;
			}
			if ($ajax == NULL and $ajax != 'Y') {
				# code...
				$menu = $CI->M_nav_menu->show()->result();
				$data_menu = array();
		        foreach ($menu as $k) {
		            $tmp = new stdClass;
		            $tmp->desc_nav = $k->desc_nav;
		            $tmp->fa = $k->fa;
		            if ($cek->privilages_user == 1) {
		            	$tmp->nav_content = $CI->M_nav_menu->nav_content_show(array('fk_id_nav'=>$k->id_nav))->result();
		            }else{
		            	$tmp->nav_content = $CI->M_permission->show_permissions_menu(array('fk_id_nav'=>$k->id_nav,'read_permissions'=> 1,'priv_permissions'=>$cek->privilages_user))->result();
		            }
		            $data_menu[] = $tmp;
		        }
				$this->sidebar_data['menu'] = $data_menu;
			}
			$this->template_data['name'] = $CI->session->userdata('username');
			$this->sidebar_data['priv'] = $cek->privilages_user;
			// $this->template_data['fullname'] = $cek->user_fullname;
			$this->sidebar_data['fullname'] = $cek->user_fullname;
			// $this->template_data['photo'] = base_url().'assets/image/profil/'.$cek->photo;
			$this->sidebar_data['photo'] = base_url().'assets/image/profil/'.$cek->photo;
			$this->sidebar_data['update'] = $update;
			$this->sidebar_data['create'] = $create;
			$this->sidebar_data['delete'] = $delete;
		}
		return;
	}

	public function authlogin($kd=NULL,$mn=NULL,$just_ajax = NULL)
	{
		$CI =& get_instance();
		$where = array(
			'b.session' => $CI->session->userdata('session'),
			'a.flag'=> 0
			);
		$this->auth($where,$kd,$mn,$just_ajax);
	}

	public function if_admin()
	{
		$CI =& get_instance();
		$where = array(
			'b.session' => $CI->session->userdata('session'),
			'a.privilages_user' => 1,
			'a.flag'=> 0
			);
		$this->auth($where);
	}

	public function get_sidebar_data()
	{
		$data = array(
			'priv' => $this->sidebar_data['priv'],
			'delete' => $this->sidebar_data['delete'],
			'update' => $this->sidebar_data['update'],
			'create' => $this->sidebar_data['create'],
			'user' => $this->template_data['name']
			);
		return $data;
	}
}
?>