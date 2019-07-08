<?php

class StudentGroupHandler extends Manager {
    protected $table_name   = 'student_group';
    protected $columns      = ['student_group_id','student_group_head_id','group_year','group_number','teaching_group'];
    protected $ai = true;
    protected $joins_id = 0;


    public function getAllGrades() {
        $sql="SELECT g.value,g.subjects_id_fk,g.students_id_fk,g.students_id_fk,g.grade_type,g.semestar,s.name as studentName,s.email,s.lastName as studentLastName,s.group_id,s.parents_parents_id,sub.name as subjectName, sem.name as semestarName
              FROM grades g
              JOIN students s ON g.students_id_fk=s.students_id
              JOIN subjects sub ON sub.subjects_id=g.subjects_id_fk
              JOIN semestar sem ON sem.semestar_id=g.semestar
              WHERE s.group_id=:gid
              ORDER BY s.students_id, g.subjects_id_fk,g.semestar";
        $st = $this->db->prepare($sql);
        $st->bindParam(':gid',$this->student_group_id);
        $st->execute();
        $objectArray = $st->fetchAll();
        return $objectArray;
    }

    public function getFinalGrades() {
        $sql = "SELECT g.value,g.subjects_id_fk,g.students_id_fk,g.students_id_fk,g.grade_type,g.semestar,s.name as studentName,s.email,s.lastName as studentLastName,s.group_id,s.parents_parents_id,sub.name as subjectName, sem.name as semestarName
              FROM grades g
              JOIN students s ON g.students_id_fk=s.students_id
              JOIN subjects sub ON sub.subjects_id=g.subjects_id_fk
              JOIN semestar sem ON sem.semestar_id=g.semestar
              WHERE s.group_id=:gid AND g.grade_type= 2;
              ORDER BY s.students_id, g.subjects_id_fk,g.semestar";
        $st = $this->db->prepare($sql);
        $st->bindParam(':gid',$this->student_group_id);
        $st->execute();
        $objectArray = $st->fetchAll();
        return $objectArray;
    }

    public function getAllStudents() {
        $sql = "SELECT students_id FROM students WHERE group_id = :gid";
        $st = $this->db->prepare($sql);
        $st->bindParam(':gid',$this->student_group_id);
        $st->execute();
        $result = $st->fetchAll(PDO::FETCH_COLUMN, 0);
        return $result;
    }

    public function getAllSubjects() {
        $sql = "SELECT * FROM subjects s JOIN subjects_group sg ON  s.subjects_id=sg.subjects_id_fk JOIN student_group stg ON stg.group_year=sg.group_year WHERE stg.student_group_id = :sgid";
        $st = $this->db->prepare($sql);
        $st->bindParam(':sgid',$this->student_group_id);
        $st->execute();
        $this->allSubjects = $st->fetchAll();
    }

    public function getNewTeachingGroup() {
        $sql = "SELECT teaching_group FROM student_group order by teaching_group DESC LIMIT 1";
        $st = $this->db->prepare($sql);
        $st->execute();
        $res = $st->fetchColumn();
        $this->teaching_group = ++$res;
    }

    public function createGroup() {
        //TODO prvo proveri da li vec postoji
        $this->create();
        if($this->group_year > 4) {
            $sql = "INSERT INTO teaching_group (teaching_group_id, teachers_subjects_id) VALUES (:tg,:tsid)";
            $st = $this->db->prepare($sql);
            $st->bindParam(':tg',$this->teaching_group);
            foreach($this->teachers as $teacher) {
                $st->bindParam(':tsid',$teacher);
                $st->execute();
            }
        } else {
            $sql = "SELECT * FROM subjects_group sg
            JOIN teachers_subjects ts ON sg.subjects_id_fk=ts.subjects_subjects_id
            WHERE group_year = :gy AND ts.teachers_teachers_id=:tid";
            $st = $this->db->prepare($sql);
            $st->bindParam(':gy',$this->group_year);
            $st->bindParam(':tid',$this->student_group_head_id);
            $st->execute();
            $res = $st->fetchAll();

            $sql = "INSERT INTO teaching_group (teaching_group_id, teachers_subjects_id) VALUES (:tg,:tsid)";
            $st = $this->db->prepare($sql);
            $st->bindParam(':tg',$this->teaching_group);
            foreach($res as $r) {
                $st->bindParam(':tsid',$r->teachers_subjects_id);
                $st->execute();
            }
        }
    }

    public function createSchedule() {
        $sql = "INSERT INTO schedule (student_group_id) VALUES (:sgid)";
        $st = $this->db->prepare($sql);
        $st->bindParam(':sgid',$this->last_inserted_id);
        $st->execute();
    }

    public function getOneStudent($studentId) {
        $sql="SELECT g.value,g.subjects_id_fk,g.students_id_fk,g.students_id_fk,g.grade_type,g.semestar,s.name as studentName,s.email,s.lastName as studentLastName,s.group_id,s.parents_parents_id,sub.name as subjectName, sem.name as semestarName
                FROM grades g
                JOIN students s ON g.students_id_fk=s.students_id
                JOIN subjects sub ON sub.subjects_id=g.subjects_id_fk
                JOIN semestar sem ON sem.semestar_id=g.semestar
                WHERE s.students_id=:stid
                ORDER BY s.students_id, g.subjects_id_fk,g.semestar";
        $st = $this->db->prepare($sql);
        $st->bindParam(':stid',$studentId);
        $st->execute();
        $objectArray = $st->fetchAll();
        return $objectArray;
        
    }

    public function getAvailableSubjects() {
        $sql = "SELECT * FROM subjects_group sg JOIN teachers_subjects ts ON ts.subjects_subjects_id=sg.subjects_id_fk WHERE sg.group_year = :gy AND ts.teachers_teachers_id=:tid";
        $st = $this->db->prepare($sql);
        $st->bindParaM(':gy',$this->group_year);
        $st->bindParaM(':tid',$this->student_group_head_id);
        $st->execute();
        $this->allSubjects = $st->fetchAll();
    }

    public function checkifexists() {
        $sql = "SELECT * FROM student_group WHERE group_year=:gy AND group_number=:gn";
        $st=$this->db->prepare($sql);
        $st->bindParam(':gy',$this->group_year);
        $st->bindParam(':gn',$this->group_number);
        $st->execute();
        $res = $st->fetch();
        if(isset($res) && !empty($res)) {
         return true;
        } else return false;
        
    }
}