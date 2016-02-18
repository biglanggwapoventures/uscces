<?php

class Dashboard extends CES_Controller
{

	protected $active_nav = NAV_DASHBOARD;
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->generate_page('dashboard');
	}

	
}