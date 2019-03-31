<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coba_dua extends CI_Controller {

	public function index()
	{
		//Load email library
		$this->load->library('email');

		//SMTP & mail configuration
		$config = array(
		    'protocol'  => 'smtp',
		    'smtp_host' => 'ssl://smtp.googlemail.com',
		    'smtp_port' => 465,
		    'smtp_user' => 'akun779@gmail.com',
		    'smtp_pass' => 'pejuangmargoyoso',
		    'mailtype'  => 'html',
		    'charset'   => 'utf-8'
		);
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		//Email content
		$htmlContent = '<h1>Sending email via SMTP server</h1>';
		$htmlContent .= '<p>This email has sent via SMTP server from CodeIgniter application.</p>';

		$this->email->to('nurkhafindi@gmail.com');
		$this->email->from('akun779@gmail.com','MyWebsite');
		$this->email->subject('How to send email via SMTP server in CodeIgniter');
		$this->email->message($htmlContent);

		//Send email
		$this->email->send();
	}

}

/* End of file Coba_dua.php */
/* Location: ./application/controllers/Coba_dua.php */