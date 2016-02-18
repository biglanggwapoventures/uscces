<?php

class Students extends CES_Controller
{

    protected $tab_title = 'Students';
    protected $active_nav;

    public function __construct()
    {
        parent::__construct();
        $this->active_nav = NAV_ADM_STUDENTS;
        $this->load->model(['Student_model' => 'student']);
    }

    public function index()
    {
        $this->generate_page('students/listing', [
            'students' => $this->student->all(),
        ]);
    }

    public function create()
    {
        $view_data = [];
        if ($this->input->method(TRUE) === 'POST') {
            $this->_perform_validation();
            if ($this->form_validation->run()) {
                $fillable = ['username', 'firstname', 'lastname', 'middlename', 'year_level', 'dob', 'course', 'gender'];
                $created = $this->student->create(elements($fillable, $this->input->post(), NULL));
                if($created){
                    redirect('students');
                }else{
                    //handle error
                }
            }
        }
        $this->generate_page('students/manage', [
            'form_title' => 'Add new student',
            'student' => $this->input->post(),
            'mode' => 'c'
        ]);
    }

    public function edit($student_id = FALSE)
    {
        if(!$student_id || !$this->student->exists($student_id)){
            redirect('students');
        }
        $original_info = $this->student->get($student_id);
        if ($this->input->method(TRUE) === 'POST') {
            $new_info = elements(['firstname', 'lastname', 'middlename', 'year_level', 'dob', 'course', 'gender'], $this->input->post(), NULL);
            $this->_perform_validation('u');
            if ($this->form_validation->run()) {
                $updated = $this->student->update($student_id, $new_info);
                if($updated){
                    redirect('students');
                }
            }else{
                $student = $new_info;
                $student['id'] = $student_id;
                $student['username'] = $original_info['username'];
            }
        }else{
            $student = $original_info;
        }
        
        $this->generate_page('students/manage', [
            'form_title' => 'Update student',
            'student' => $student,
            'mode' => 'u'
        ]);
    }

    public function _perform_validation($mode = 'c')
    {
        if($mode === 'c'){
            $this->form_validation->set_rules('username', 'ID #', 'required|is_unique[users.username]');
        }
        $this->form_validation->set_rules('firstname', 'First name', 'required');
        $this->form_validation->set_rules('lastname', 'Last name', 'required');
        $this->form_validation->set_rules('middlename', 'Middle name', 'required');
        $this->form_validation->set_rules('year_level', 'Year level', 'is_natural|greater_than[0]|less_than[6]|required');
        $this->form_validation->set_rules('gender', 'Gender', 'in_list[f,m]', ['in_list' => 'Gender can only be Male or Female']);
        $this->form_validation->set_rules('dob', 'Date of birth', 'callback__validate_birthdate');
        $this->form_validation->set_rules('course', 'Course', 'callback__validate_course');
        
    }

    public function _validate_birthdate($date)
    {
        if($date){
            $this->load->helper('cesdate');
            $this->form_validation->set_message('_validate_birthdate', 'Please provide a valid birthdate with format: Y-m-d');
            return is_valid_date($date, 'Y-m-d');
        }
        return TRUE;
        
    }

    public function _validate_course($course)
    {
        if($course){
            $this->form_validation->set_message('_validate_course', 'Please select a valid %s from the list.');
            return in_array($course, ['ict', 'it', 'cs']);
        }
        return TRUE;
    }

}
