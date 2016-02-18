<?php

class Logout extends CES_Controller
{
	public function index()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}