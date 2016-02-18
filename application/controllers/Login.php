<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller 
{

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('id')){
			redirect('dashboard');
		}
	}

	public function index()
	{
		$this->load->model('User_model', 'user');
		$error_messages = [];
		if($this->input->method(TRUE) === 'POST'){
			$this->form_validation->set_rules('username', 'Username / ID Number', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			if($this->form_validation->run()){
				$user = $this->user->authenticate($this->input->post('username'), $this->input->post('password'));
				if($user){
					$this->session->set_userdata($user);
					if($user['type'] === 's'){
						redirect('student/view_activities');
					}
					redirect('activities');
				}
				$error_messages = ['Invalid username or password'];
			}else{
				$error_messages = array_values($this->form_validation->error_array());
			}
		}
		$this->load->view('login', ['error_messages' => $error_messages]);
	}

}
