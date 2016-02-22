<?php

class Profile extends CES_Controller
{

	protected $validation_errors = [];

	public function __construct()
	{
		parent::__construct();
		$this->tabt_title = 'My Status';
		
		$this->load->model(['User_model' => 'user', 'Activity_model' => 'activity']);
	}

	public function index()
	{	
		$this->user->id = $this->input->get('id');
		// determine if user is accessing his own profile or an is trying to access another profile
		if(($this->session->userdata('type') == 's' && $this->session->userdata('id') != $this->user->id) || !$this->user->exists()){
			show_404();
		}
		$user = $this->user->get();
		if($this->user->id != pk()){
			$this->active_nav = $user['type'] === USER_TYPE_STUDENT ? NAV_ADM_STUDENTS : NAV_ADM_FACI;
		}else{
			$this->active_nav = NAV_USR_STATUS;
		}
		$this->import_plugin_script('bootstrap-datepicker/js/bootstrap-datepicker.min.js');
		$this->import_page_script('profile.js');
		$this->generate_page('profile', [
			'user' => $user,
			'proposed_count' => $this->activity->get_proposed_count($this->user->id),
			'attended' => $this->activity->get_attended($this->user->id)
		]);
	}

	public function update($id = FALSE)
	{
		if(!$id || !$this->user->exists($id)){
			$this->json_response(['result' => FALSE, 'messages' => ['Please select a profile.']]);
		}
		$this->_perform_validation();
		if(!empty($this->validation_errors)){
			$this->json_response(['result' => FALSE, 'messages' => $this->validation_errors]);
			return;
		}
		$data = $this->_format_data();
		if($this->user->update($id, $data)){
			$this->json_response(['result' => TRUE]);
			return;
		}
		$this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown. Please try again later.']]);
	}

    public function _perform_validation()
    {
    	if(user_type() === USER_TYPE_STUDENT){
			$this->form_validation->set_rules('dob', 'date of birth', 'required|callback__validate_birthdate');
			$this->form_validation->set_rules('gender', 'gender', 'required|in_list[f,m]', ['in_list' => 'Gender can only be Male or Female']);
			$this->form_validation->set_rules('course', 'course', 'required|in_list[ict,cs,it]', ['in_list' => 'Please select a valid %s.']);
			$this->form_validation->set_rules('mobile', 'mobile', 'required|numeric|exact_length[11]');
			$this->form_validation->set_rules('email', 'email', 'required|valid_email');
    	}else{
    		$this->form_validation->set_rules('firstname', 'first name', 'required');
    		$this->form_validation->set_rules('middlename', 'middlename', 'required');
    		$this->form_validation->set_rules('lastname', 'last name', 'required');
    		$this->form_validation->set_rules('dob', 'date of birth', 'callback__validate_birthdate');
    		$this->form_validation->set_rules('gender', 'gender', 'in_list[f,m]');
    		$this->form_validation->set_rules('course', 'course', 'in_list[ict,cs,it]', ['in_list' => 'Please select a valid %s.']);
    		$this->form_validation->set_rules('mobile', 'mobile', 'numeric|exact_length[11]');
			$this->form_validation->set_rules('email', 'email', 'valid_email');
    	}
    	
    	$this->form_validation->set_rules('password', 'password', 'min_length[4]');
		if($this->input->post('password')){
			$this->form_validation->set_rules('confirm_password', 'password confirmation', 'required|matches[password]');
		}
    	if(!$this->form_validation->run()){
    		$this->validation_errors += array_values($this->form_validation->error_array());
    	}
    }

    public function _format_data()
    {	
    	$input = $this->input->post();
    	$data = elements(['gender', 'talent', 'email', 'mobile'], $this->input->post(), NULL);
    	if(user_type() === USER_TYPE_STUDENT){
    		$data += elements(['course'], $input, NULL);
    	}else{
    		$data += elements(['firstname', 'middlename', 'lastname'], $input, NULL);
    	}
    	if($input['dob']){
    		$data['dob'] = date('Y-m-d', strtotime($input['dob']));
    	}
		if($input['password']){
			$data['password'] = md5($input['password']);
		}
    	return $data;
    }


	public function _validate_birthdate($date)
    {
    	if(!$date){
    		return TRUE;
    	}
        $this->form_validation->set_message('_validate_birthdate', 'Please provide a valid birthdate with format: mm/dd/yyyy');
        return is_valid_date($date, 'm/d/Y');
    }

}