<?php

class GradeHandler extends Manager {
    protected $table_name   = 'grades';
    protected $columns      = ['value','subjects_id_fk','students_id_fk','grade_type','semestar'];
    protected $ai = false;
    protected $joins_id = 0;

    public function delete() {
        $sql = "DELETE FROM grades WHERE `value` = :v AND subjects_id_fk = :suid AND students_id_fk = :stid AND grade_type = :gt AND semestar = :s LIMIT 1";
        $st = $this->db->prepare($sql);
        $st->bindParam(':v',$this->value);
        $st->bindParam(':suid',$this->subjects_id_fk);
        $st->bindParam(':stid',$this->students_id_fk);
        $st->bindParam(':gt',$this->grade_type);
        $st->bindParam(':s',$this->semestar);
        $res = $st->execute();
    }
}