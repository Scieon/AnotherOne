<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 */
class Courses extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * @param null $SEMESTER
     * @param null $COURSECODE
     * @param null $NUMBER
     */
    function sections($SEMESTER = NULL, $COURSECODE = NULL, $NUMBER = NULL)
    {
        //Loading header
        $data['info_bar'] = 'Something';
        $this->load->view('layouts/header.php', $data);

        $this->load->model('section');
        $this->load->model('semester');

        if($SEMESTER == NULL || $COURSECODE == NULL || $NUMBER == NULL){

            //Get form input info, semester, course code, and course number
            if($this->input->post('search', TRUE)){

                $semester = $this->input->post('semester', TRUE);
                $course_code = $this->input->post('course_code', TRUE);
                $course_number = $this->input->post('course_number', TRUE);

                $this->form_validation->set_rules('semester', 'Semester', 'trim|required');
                $this->form_validation->set_rules('course_code', 'Course Code', 'trim|required');
                $this->form_validation->set_rules('course_number', 'Course Number', 'trim|required');

                if($this->form_validation->run() === FALSE)
                {
                    goto search;
                }

                //If the parameters are filled refresh to ;
                redirect("courses/sections/".strtolower($semester)."/$course_code/$course_number", 'refresh');

                return;
            }

        }
        else{
            //if there are no results to the parameters inputted load search.php with error messages
            $semester_name = str_replace('-', ' ', $SEMESTER);

            $results = $this->section->getSectionsBySemesCodeNum($semester_name, $COURSECODE, $NUMBER);

            if($results == FALSE) {
                $data['error_message'] = '<p>No results were found!</p>';
                goto search;
            }
            $data['results'] = $results;
            $this->load->view('course/result.php', $data);

            goto footer;
        }

        search:
        $data['available_semesters'] = $this->semester->getSemesters();
        $this->load->view('course/search.php', $data);

        footer:
        $this->load->view('layouts/footer.php');

        $this->output->enable_profiler(TRUE);
    }

}
?>