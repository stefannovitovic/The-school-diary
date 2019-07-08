<?php

class Grade extends GradeHandler {
    public $grade_type;
    public $grade_type_name;
    public $value;
    public $subjects_id_fk;
    public $students_id_fk;
    public $semestar;
    public $data;

    public function __construct($args) {
        $this->db               = Database::getInstance()->getConnection();
        $this->value            = isset($args['value'])         ? $args['value'] : '';
        $this->grade_type       = isset($args['grade_type'])    ? $args['grade_type'] : '';
        $this->subjects_id_fk   = isset($args['subjects_id_fk'])? $args['subjects_id_fk'] : '';
        $this->students_id_fk   = isset($args['students_id_fk'])? $args['students_id_fk'] : '';
        $this->semestar         = isset($args['semestar'])      ? $args['semestar'] : '';
        $this->data             = isset($args['ocene'])         ? $args['ocene'] : '';
        switch($this->grade_type) {
            case 1:
                $this->grade_type_name ="Pismeni zadatak";
                break;
            case 2:
                $this->grade_type_name ="Pismena vezba";
                break;
            case 3:
                $this->grade_type_name ="Usmeni";
                break;
            case 4:
                $this->grade_type_name ="Zakljucna";
            
        }
    }

    public static function parseData($string) {
        $data = explode('|',$string);
        // 1. vrednost je subject_id
        // 2. vrednost je student_id
        // 3. vrednost je grade_type
        // 4. vrednost je semestar_id
        // 5. vrednost je grade
        $args['subjects_id_fk'] = (int) filter_var($data[0], FILTER_SANITIZE_NUMBER_INT);
        $args['students_id_fk'] = (int) filter_var($data[1], FILTER_SANITIZE_NUMBER_INT);
        $args['grade_type'] = (int) filter_var($data[2], FILTER_SANITIZE_NUMBER_INT);
        $args['semestar'] = (int) filter_var($data[3], FILTER_SANITIZE_NUMBER_INT);
        $args['value'] = (int) filter_var($data[4], FILTER_SANITIZE_NUMBER_INT);
        return new self($args);
    }

    public function fillData() {
        $data = explode('|',$this->data);
        $this->subjects_id_fk = (int) filter_var($data[0], FILTER_SANITIZE_NUMBER_INT);
        $this->students_id_fk = (int) filter_var($data[1], FILTER_SANITIZE_NUMBER_INT);
    }


}