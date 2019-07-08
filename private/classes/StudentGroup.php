<?php

class StudentGroup extends StudentGroupHandler {
    public $student_group_id;
    public $student_group_head_id;
    public $group_year;
    public $group_number;
    public $teaching_group;
    public $allSubjects = array();
    public $students = array();
    public $teachers= array();

    public function __construct(stdClass $args) {
        $this->db = Database::getInstance()->getConnection();
        $this->student_group_id = isset($args->student_group_id)  ? $args->student_group_id : '';
        $this->group_year       = isset($args->group_year)        ? $args->group_year     : '';
        $this->group_number     = isset($args->group_number)      ? $args->group_number   : '';
        $this->teachers         = isset($args->teachers)          ? $args->teachers   : '';
        $this->student_group_head_id = isset($args->student_group_head_id)      ? $args->student_group_head_id   : '';
        if(isset($args->sghead)) {
            $this->student_group_head_id = $args->sghead;
        }
    }

    public function fillStudents($gradetype,$id = null) {
        if($gradetype == 'all') {
            $data = $this->getAllGrades();
        } else if($gradetype=="one") {
           $data = $this->getOneStudent($id);
        } else {
            $data = $this->getFinalGrades();
        }

        //odradi array walk ili PDO::FETCH_GROUP u handler metodama

        foreach($data as $row) {
            if(!isset($grades[$row->students_id_fk]['name'])) {
              $grades[$row->students_id_fk]['name']=$row->studentName;
              $grades[$row->students_id_fk]['lastName']=$row->studentLastName;
              $grades[$row->students_id_fk]['email']=$row->email;
              $grades[$row->students_id_fk]['group_id']=$row->group_id;
              $grades[$row->students_id_fk]['parent']=$row->parents_parents_id;
              $grades[$row->students_id_fk]['students_id']=$row->students_id_fk;
            }
            if(!isset($grades[$row->students_id_fk]['subjects'][$row->subjects_id_fk]['name'])) {
              $grades[$row->students_id_fk]['subjects'][$row->subjects_id_fk]['name'] = $row->subjectName;
              $grades[$row->students_id_fk]['subjects'][$row->subjects_id_fk]['subjects_id'] = $row->subjects_id_fk;
            }
            if(!isset($grades[$row->students_id_fk]['subjects'][$row->subjects_id_fk]['semester'][$row->semestar]['name'])){
                $grades[$row->students_id_fk]['subjects'][$row->subjects_id_fk]['semester'][$row->semestar]['name'] = $row->semestarName;
                $grades[$row->students_id_fk]['subjects'][$row->subjects_id_fk]['semester'][$row->semestar]['semesterId'] = $row->semestar;
            }
            $grades[$row->students_id_fk]['subjects'][$row->subjects_id_fk]['semester'][$row->semestar]['grades'][$row->grade_type][]=$row->value;
        }
        
        $students = $grades;
        foreach($students as $student) {
            $s = new Student($student);
            $s->setGrades();
			$this->students[] = $s;
        }
        if($gradetype!="one") {
        $this->checkStudents();
        }
    }

    public function fillStudentsGrades() {
        if(empty($this->students)) {
            echo "no students";
        } else {
            foreach($this->students as $student) {
                $student->fillAllGrades();
            }    
        }

    }

    protected function checkStudents() {
         $tempStudents = $this->getAllStudents();
         $currStudents = array();
         foreach($this->students as $student) {
             $currStudents[] = $student->student_id;
         }
         $missingStudents = array_diff($tempStudents,$currStudents);
         foreach($missingStudents as $student) {
             $args['students_id'] = $student;
             $s = new Student($args);
             $s->fillEmptyData();
             $this->students[] = $s;
         }
    }
}