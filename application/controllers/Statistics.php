<?php

class Statistics extends CES_Controller
{

	protected $tab_title = 'Statistics';
    protected $active_nav = NAV_ADM_STATISTICS;

	public function __construct()
	{
		parent::__construct();
		if(!user_type(USER_TYPE_SUPERUSER)) show_404();
	}

	public function index()
	{
		$this->load->model('Activity_model', 'activity');
		$this->generate_page('stats', [
			'area_stats' => $this->activity->area_stats(),
			'nature_stats' => $this->activity->nature_stats(),
			'total_activity_count' => $this->activity->get_total_count(),
			'participants' => $this->activity->get_unique_participants_count(),
			
		]);
	}
}