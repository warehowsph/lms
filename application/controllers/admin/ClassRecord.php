<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Classrecord extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('classrecord_model');
    }

    function index()
    {
        //--
        // echo 'me';
    }

    function quarter()
    {
        if (!$this->rbac->hasPrivilege('quarter', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Class Record');
        $this->session->set_userdata('sub_menu', 'quarter/index');
        $data['title'] = 'Add Quarter';
        $data['title_list'] = 'Quarter List';
        $result_list = $this->classrecord_model->get('quarter');
        $data['quarterlist'] = $result_list;
        $this->form_validation->set_rules('name', $this->lang->line('quarter_name'), 'trim|required|xss_clean|callback__check_quarter_name_exists');
        $this->form_validation->set_rules('code', $this->lang->line('quarter_code'), 'trim|required|xss_clean|callback__check_quarter_code_exists');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/classrecord/quarter', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code')
            );
            $this->classrecord_model->add('quarter', $data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/classrecord/quarter');
        }
    }

    function _check_quarter_name_exists()
    {
        $data['name'] = $this->security->xss_clean($this->input->post('quarter_name'));

        if ($this->classrecord_model->check_data_exists($data, 'quarter', 'name')) {
            $this->form_validation->set_message('_check_name_exists', $this->lang->line('name_already_exists'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function _check_quarter_code_exists()
    {
        $data['code'] = $this->security->xss_clean($this->input->post('quarter_code'));

        if ($this->classrecord_model->check_data_exists($data, 'quarter', 'code')) {
            $this->form_validation->set_message('_check_code_exists', $this->lang->line('code_already_exists'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function quarter_edit($id)
    {
        if (!$this->rbac->hasPrivilege('quarter', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Class Record');
        $this->session->set_userdata('sub_menu', 'quarter/index');
        $data['title'] = 'Edit Quarter';
        $data['title_list'] = 'Quarter List';
        $data['id'] = $id;

        $result_list = $this->classrecord_model->get('quarter');
        $data['quarterlist'] = $result_list;
        $result = $this->classrecord_model->get('quarter', $id);
        $data['quarter'] = $result;

        $this->form_validation->set_rules('name', $this->lang->line('quarter_name'), 'trim|required|xss_clean|callback__check_quarter_name_exists');
        $this->form_validation->set_rules('code', $this->lang->line('quarter_code'), 'trim|required|xss_clean|callback__check_quarter_code_exists');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/classrecord/quarter_edit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data_quarter = array(
                'id' => $id,
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code')
            );
            $this->classrecord_model->add('quarter', $data_quarter);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/classrecord/quarter');
        }
    }

    function quarter_delete($id)
    {
        if (!$this->rbac->hasPrivilege('quarter', 'can_delete'))
            access_denied();

        $data['title'] = 'Quarter List';
        $this->classrecord_model->remove('quarter', $id);
        redirect('admin/classrecord/quarter');
    }

    function components()
    {
        if (!$this->rbac->hasPrivilege('components', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Class Record');
        $this->session->set_userdata('sub_menu', 'components/index');
        $data['title'] = 'Add Component';
        $data['title_list'] = 'Component List';
        $result_list = $this->classrecord_model->get('components');
        $data['componentlist'] = $result_list;
        $this->form_validation->set_rules('name', $this->lang->line('component_name'), 'trim|required|xss_clean|callback__check_component_name_exists');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/classrecord/components', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name')
            );
            $this->classrecord_model->add('components', $data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/classrecord/components');
        }
    }

    function _check_component_name_exists()
    {
        $data['name'] = $this->security->xss_clean($this->input->post('component_name'));

        if ($this->classrecord_model->check_data_exists($data, 'components', 'name')) {
            $this->form_validation->set_message('_check_name_exists', $this->lang->line('name_already_exists'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function component_edit($id)
    {
        if (!$this->rbac->hasPrivilege('components', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Class Record');
        $this->session->set_userdata('sub_menu', 'component/index');
        $data['title'] = 'Edit Component';
        $data['title_list'] = 'Component List';
        $data['id'] = $id;

        $result_list = $this->classrecord_model->get('components');
        $data['componentlist'] = $result_list;
        $result = $this->classrecord_model->get('components', $id);
        $data['component'] = $result;

        $this->form_validation->set_rules('name', $this->lang->line('component_name'), 'trim|required|xss_clean|callback__check_component_name_exists');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/classrecord/components_edit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data_component = array(
                'id' => $id,
                'name' => $this->input->post('name'),
            );
            $this->classrecord_model->add('components', $data_component);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/classrecord/components');
        }
    }

    function component_delete($id)
    {
        if (!$this->rbac->hasPrivilege('components', 'can_delete'))
            access_denied();

        $data['title'] = 'Component List';
        $this->classrecord_model->remove('components', $id);
        redirect('admin/classrecord/components');
    }

    function transmutedgrades()
    {
        if (!$this->rbac->hasPrivilege('transmuted_grades', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Class Record');
        $this->session->set_userdata('sub_menu', 'transmutedgrades/index');
        $data['title'] = 'Add Transmuted Grade';
        $data['title_list'] = 'Transmuted Grade List';
        $result_list = $this->classrecord_model->get('transmuted_grades');
        $data['transmutedgradelist'] = $result_list;

        $this->form_validation->set_rules('grade', $this->lang->line('grade'), 'trim|required|xss_clean|callback__check_grade_exists');
        $this->form_validation->set_rules('transmute_from', $this->lang->line('transmute_from'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('transmute_to', $this->lang->line('transmute_to'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('remarks', $this->lang->line('remarks'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/classrecord/transmutedgrades', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'grade' => $this->input->post('grade'),
                'transmute_from' => $this->input->post('transmute_from'),
                'transmute_to' => $this->input->post('transmute_to'),
                'remarks' => $this->input->post('remarks')
            );
            $this->classrecord_model->add('transmuted_grades', $data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/classrecord/transmutedgrades');
        }
    }

    function _check_grade_exists()
    {
        $data['grade'] = $this->security->xss_clean($this->input->post('grade'));

        if ($this->classrecord_model->check_data_exists($data, 'transmuted_grades', 'grade')) {
            $this->form_validation->set_message('_check_grade_exists', $this->lang->line('grade_already_exists'));
            return false;
        } else
            return true;
    }

    function transmutedgrades_edit($id)
    {
        if (!$this->rbac->hasPrivilege('transmuted_grades', 'can_view'))
            access_denied();

        $this->session->set_userdata('top_menu', 'Class Record');
        $this->session->set_userdata('sub_menu', 'transmutedgrades/index');
        $data['title'] = 'Edit Transmuted Grade';
        $data['title_list'] = 'Transmuted Grades List';
        $data['id'] = $id;

        $result_list = $this->classrecord_model->get('transmuted_grades');
        $data['transmutedgradelist'] = $result_list;
        $result = $this->classrecord_model->get('transmuted_grades', $id);
        $data['transmutedgrade'] = $result;

        $this->form_validation->set_rules('grade', $this->lang->line('grade'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('transmute_from', $this->lang->line('transmute_from'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('transmute_to', $this->lang->line('transmute_to'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('remarks', $this->lang->line('remarks'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            // var_dump($data);die;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/classrecord/transmutedgrades_edit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data_trans_grade = array(
                'id' => $id,
                'grade' => $this->input->post('grade'),
                'transmute_from' => $this->input->post('transmute_from'),
                'transmute_to' => $this->input->post('transmute_to'),
                'remarks' => $this->input->post('remarks')
            );

            $this->classrecord_model->add('transmuted_grades', $data_trans_grade);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/classrecord/transmutedgrades');
        }
    }

    function transmutedgrades_delete($id)
    {
        if (!$this->rbac->hasPrivilege('transmuted_grades', 'can_delete'))
            access_denied();

        $data['title'] = 'Component List';
        $this->classrecord_model->remove('transmuted_grades', $id);
        redirect('admin/classrecord/transmutedgrades');
    }
}
