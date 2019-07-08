<?php


class SubjectHandler extends Manager {
    
    protected $table_name = "subjects";
    protected $columns  = ["subjects_id","name"];
    protected $joins_id = 1;
    protected $joins = array();

    public function deleteAndEditSubjectGroup($group_year,$name){
        $db = Database::getInstance()->getConnection();
        $sql = "DELETE FROM subjects_group WHERE subjects_id_fk = :id;";
        $st = $db->prepare($sql);
        $st->bindParam(':id', $this->subjects_id);
        $result = $st->execute();

        $sql = "INSERT INTO subjects_group (group_year, subjects_id_fk) VALUES (:group_year, :id);";
        $st = $db->prepare($sql);
        $st->bindParam(':id',$this->subjects_id);
        foreach($group_year as $group) {
            $st->bindParam(':group_year',$group);
            $st->execute();
        }

        $sql = "UPDATE subjects SET name=:name WHERE subjects_id = :id";
        $st = $db->prepare($sql);
        $st->bindParam(':id', $this->subjects_id);
        $st->bindParam(':name', $name);
        $result = $st->execute();

        return $result;
    }
}



?>