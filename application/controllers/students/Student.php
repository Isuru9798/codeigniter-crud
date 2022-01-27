<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Student_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation'));
    }
    public function store()
    {

        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('grade', 'Grade', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[students.email]');

            if ($this->form_validation->run()) {
                $form_data = $this->input->post();
                if ($this->Student_model->insert_entry($form_data)) {
                    $data = array('responce' => 'success', 'message' => 'New Student Registered');
                } else {
                    $data = array('responce' => 'error', 'message' => 'Failed!');
                }
            } else {
                $data = array('responce' => 'error', 'message' => validation_errors());
            }
        } else {
            echo "'No direct script access allowed'";
        }
        echo json_encode($data);
    }
    public function getStudents()
    {
        if ($this->input->is_ajax_request()) {
            $students = $this->Student_model->get_entries();
            echo json_encode($students);
        } else {
            echo "'No direct script access allowed'";
        }
    }
    public function delete()
    {
        if ($this->input->is_ajax_request()) {
            $id  = $this->input->post('del_id');
            if ($this->Student_model->delete_entry($id)) {
                $data = array('response' => 'success');
            } else {
                $data = array('response' => 'error');
            }
            echo json_encode($data);
        } else {
            echo "'No direct script access allowed'";
        }
    }
    public function edit()
    {
        if ($this->input->is_ajax_request()) {
            $this->input->post('edit_id');

            $edit_id = $this->input->post('edit_id');

            if ($studnet = $this->Student_model->single_entry($edit_id)) {
                $data = array('response' => "success", 'post' => $studnet);
            } else {
                $data = array('response' => "error", 'message' => "failed");
            }

            echo json_encode($data);
        } else {
            echo "'No direct script access allowed'";
        }
    }
    public function update()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('edit_first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('edit_last_name', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('edit_grade', 'Grade', 'trim|required');
            $this->form_validation->set_rules('edit_email', 'Email', 'trim|required|valid_email');

            if ($this->form_validation->run() == FALSE) {
                $data = array('response' => "error", 'message' => validation_errors());
            } else {
                $data['id'] = $this->input->post('edit_id');
                $data['first_name'] = $this->input->post('edit_first_name');
                $data['last_name'] = $this->input->post('edit_last_name');
                $data['grade'] = $this->input->post('edit_grade');
                $data['email'] = $this->input->post('edit_email');

                if ($this->Student_model->update_entry($data)) {
                    $data = array('response' => "success", 'message' => "Data update successfully");
                } else {
                    $data = array('response' => "error", 'message' => "failed");
                }
            }

            echo json_encode($data);
        } else {
            echo "'No direct script access allowed'";
        }
    }
}
