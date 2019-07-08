<?php

class StudentHandler extends Manager {
    protected $table_name   = 'students';
    protected $columns      = ['students_id','name','lastName','email','group_id','student_JMBG'];
    protected $ai = true;
    protected $joins_id = 0;

    public function deleteGrades() {
        $sql = "DELETE FROM grades WHERE students_id_fk = :stid";
        $st = $this->db->prepare($sql);
        $st->bindParam(':stid',$this->students_id);
        $st->execute();
    }


}