<?php

class Students extends CES_Controller
{

    protected $tab_title = 'Students';
    protected $active_nav = NAV_ADM_STUDENTS;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Student_model', 'student');
    }

    public function index()
    {
        $search = $this->_get_search_params();
        $this->import_page_script('student-listing.js');
        $data = FALSE;
        if($this->input->get()){
            $data['items'] = $this->student->all($search['params'], $search['wildcards']);
        }
        $this->generate_page('students/listing', $data);
    }

    public function _get_search_params()
    {
        $params = [];
        $wildcards = [];
        $search = elements(['year_level', 'course', 'status', 'id_number', 'firstname', 'lastname', 'middlename'], $this->input->get());
        if($search['year_level']){
            $params['year_level'] = $search['year_level'];
        }
        if($search['course']){
            $params['course'] = $search['course'];
        }
        if($search['status']){
            $params['status'] = $search['status'];
        }
        if($search['id_number']){
            $params['username'] = $search['id_number'];
        }
        if($search['firstname']){
            $wildcards['firstname'] = $search['firstname'];
        }
        if($search['lastname']){
            $wildcards['lastname'] = $search['lastname'];
        }
        if($search['middlename']){
            $wildcards['middlename'] = $search['middlename'];
        }
        return compact(['params', 'wildcards']);
    }

    public function create()
    {
        $this->import_plugin_script('bootstrap-datepicker/js/bootstrap-datepicker.min.js');
        $this->import_page_script('manage-students.js');
        $this->generate_page('students/manage', [
            'form_title' => 'Create new student',
            'action' => ACTION_CREATE,
            'data' => []
        ]);
    }

    public function edit($id = FALSE)
    {
        if(!$id || !$student = $this->student->get($id)){
            show_404();
        }
        $this->import_plugin_script('bootstrap-datepicker/js/bootstrap-datepicker.min.js');
        $this->import_page_script('manage-students.js');
        $this->generate_page('students/manage', [
            'form_title' => "Update student: {$student['username']}",
            'action' => ACTION_UPDATE,
            'data' => $student
        ]);

    }

    public function store()
    {
        $this->_perform_validation(ACTION_CREATE);
        if(!$this->form_validation->run()){
            $this->json_response(['result' => FALSE, 'messages' => array_values($this->form_validation->error_array())]);
            return;
        }
        $input = $this->_format_data();
        $created = $this->student->create($input);
        if($created){
            $this->json_response(['result' => TRUE]);
            return;
        }
        $this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown. Please try again later.']]);
    }

    public function reset_password()
    {
        $id = $this->input->post('id');
        if(!$id || !$this->student->exists($id)){
            $this->json_response(['result' => FALSE, 'messages' => ['Student not found!']]);
            return;
        }
        if($this->student->reset_password($id)){
            $this->json_response(['result' => TRUE]);
            return;
        }
        $this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown error. Please try again later.']]);
    }


    public function update($id = FALSE)
    {
        if(!$id || !$student = $this->student->get($id)){
            $this->json_response(['result' => FALSE, 'messages' => ['Student not found!']]);
            return;
        }
        $this->id = $id;
        $this->_perform_validation(ACTION_UPDATE);
        if(!$this->form_validation->run()){
            $this->json_response(['result' => FALSE, 'messages' => array_values($this->form_validation->error_array())]);
            return;
        }
        $input = $this->_format_data(ACTION_UPDATE);
        $updated = $this->student->update($id, $input);
        if($updated){
            $this->json_response(['result' => TRUE]);
            return;
        }
        $this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown. Please try again later.']]);
    }

    public function print_master_list()
    {
        $this->load->view('students/master-list-print', [
            'list' => $this->student->all()
        ]);
    }

    public function _perform_validation($action = ACTION_CREATE)
    {
        if($action === ACTION_CREATE){
            $this->form_validation->set_rules('username', 'id number', 'required|is_unique[users.username]');
        }else{
            $this->form_validation->set_rules('username', 'id number', 'required|callback__validate_username');
        }
        $this->form_validation->set_rules('firstname', 'first name', 'required');
        $this->form_validation->set_rules('middlename', 'middle name', 'required');
        $this->form_validation->set_rules('lastname', 'last name', 'required');
        $this->form_validation->set_rules('year_level', 'year level', 'is_natural|greater_than[0]|less_than[6]|required');
        $this->form_validation->set_rules('gender', 'gender', 'in_list[f,m]', ['in_list' => 'Gender can only be Male or Female']);
        $this->form_validation->set_rules('dob', 'date of birth', 'callback__validate_birthdate');
        $this->form_validation->set_rules('course', 'course', 'in_list[ict,cs,it]', ['in_list' => 'Please select a valid %s.']);
        $this->form_validation->set_rules('email', 'email', 'valid_email');
        $this->form_validation->set_rules('mobile', 'mobile', 'numeric|exact_length[11]');
    }

    public function _format_data($action = ACTION_CREATE)
    {
        $data = elements(['username', 'firstname', 'middlename', 'lastname', 'year_level', 'gender', 'course', 'email', 'mobile'], $this->input->post(), NULL);
        $data['dob'] = $this->input->post('dob') ? date('Y-m-d', strtotime($this->input->post('dob'))) : NULL;
        if($action === ACTION_UPDATE){
            $data['is_locked'] = $this->input->post('is_locked') ? 1 : 0;
        }
        return $data;
    }

    public function _validate_username($username)
    {
        $this->form_validation->set_message('_validate_username', 'The %s is already been taken.');
        return $this->student->has_unique_username($username, $this->id);
    }

    public function _validate_birthdate($date)
    {
        if($date){
            $this->load->helper('cesdate');
            $this->form_validation->set_message('_validate_birthdate', 'Please provide a valid birthdate with format: m/dd/yyyy');
            return is_valid_date($date, 'm/d/Y');
        }
        return TRUE;
        
    }

}
