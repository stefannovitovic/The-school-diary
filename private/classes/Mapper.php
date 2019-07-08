<?php

class Mapper {

  static protected $db;
  static protected $table_name = "";
  static protected $columns = [];
  public $errors = [];

  static public function set_database() {
    self::$db = Database::getInstance()->getConnection();
  }

  static public function find_by_sql($sql) {
    $st = self::$db->prepare($sql);
    $result = $st->execute();
    if(!$result) {
      exit("Database query failed.");
    }

    // results into objects
    $object_array = [];
    while($record = $st->fetch()) {
      $object_array[] = $record;
    }
    return $object_array;
  }

  static public function find_all($table) {
    $sql = "SELECT * FROM " . $table;
    return static::find_by_sql($sql);
  }

  static public function count_all($table) {
    $sql = "SELECT COUNT(*) FROM " . $table;
    $st = self::$db->prepare($sql);
    $st->execute();
    $row = $st->fetchColumn();
    return $row;
  }

  static public function checkStudentData() {
    $sql = "SELECT * FROM students WHERE parents_parents_id IS NULL OR group_id IS NULL ";
    $st=self::$db->prepare($sql);
    $result = $st->execute();
    if(!$result) {
      exit("Database query failed.");
    }
    $object_array = [];
    while($record = $st->fetch()) {
      $object_array[] = $record;
    }
    return $object_array;
  }

  static public function checkGroupData() {
    $sql = "SELECT * FROM student_group WHERE teachers_teachers_id IS NULL";
    $st=self::$db->prepare($sql);
    $result = $st->execute();
    if(!$result) {
      exit("Database query failed.");
    }
    $object_array = [];
    while($record = $st->fetch()) {
      $object_array[] = $record;
    }
    return $object_array;
  }
  static public function selectAllSubjects(){
    $db = Database::getInstance()->getConnection();
    $sql = "select * from subjects";
    $st = $db->prepare($sql);
        $st->execute();

    $row = $st->fetchAll();
    return $row;
  }



  static public function setLoginToken($id,$hash) {
    $sql = "UPDATE users SET loginToken = :hash WHERE users_id=:userId LIMIT 1";
    $st=self::$db->prepare($sql);
    $st->bindParam(":userId",$id);
    $st->bindParam(":hash",$hash);
    $result = $st->execute();
    if(!$result) {
      exit("Database query failed.");
    }
  }
  static public function getDbLoginToken($id) {
    $sql = "SELECT loginToken FROM users WHERE users_id=:userId LIMIT 1";
    $st = self::$db->prepare($sql);
    //TODO iz cookieSessName vadi user id
    $st->bindParam(":userId",$id);
    $st->execute();
    if($row = $st->fetch()) {
      return $row->loginToken;
    }
    return false;
  }
  static public function selectSubjectID(){
    
      $db = Database::getInstance()->getConnection();
      $edit_id = $_GET['edit'];
      $sql = "select * from subjects where subjects_id = :id";
      $st = $db->prepare($sql);
      $st->bindParam( ':id', $edit_id);
      $st->execute();
      $row = $st->fetchAll();
      return $row;
  }
  static public function selectAllItems($table_name){
    $db = Database::getInstance()->getConnection();
    $sql = "SELECT * FROM  {$table_name}";
    $st = $db->prepare($sql);
    $st->execute();
    $row = $st->fetchAll();
    return $row;
  }
  static public function selectByID($table_name, $user_id){
      $db = Database::getInstance()->getConnection();
      $sql = "select * from {$table_name} where {$table_name}_id = :id";
      $st = $db->prepare($sql);
      $st->bindParam( ':id', $user_id);
      $st->execute();
      $row = $st->fetchAll();

      return $row;
  }
  static public function deleteSubject(){
    
    $db = Database::getInstance()->getConnection();
    $id_delete = $_GET['delete'];
    $sql = "delete from subjects where subjects_id = :id";
    $st = $db->prepare($sql);
    $st->bindParam( ':id', $id_delete);
    $st->execute();

}
    static public function selectStudentGroupID(){
    
      $db = Database::getInstance()->getConnection();
      $edit_id = $_GET['edit'];
      $sql = "select * from student_group where student_group_id = :id";
      $st = $db->prepare($sql);
      $st->bindParam( ':id', $edit_id);
      $st->execute();
      $row = $st->fetchAll();
      return $row;
  } 
 
   static public function updateStudentGroup(){
      $db = Database::getInstance()->getConnection();
      $group_id = $_POST['group_id'];
      $group_year = $_POST['group_year'];
      $group_number = $_POST['group_number'];
      $sghead       = $_POST['sghead'];
      $sql = "UPDATE student_group SET group_year = :group_year,group_number=:group_number, student_group_head_id=:sghid WHERE student_group_id = :edit_id";
      $st = $db->prepare($sql);
      $st->bindParam( ':group_year', $group_year);
      $st->bindParam( ':group_number', $group_number);
      $st->bindParam( ':edit_id', $group_id);
      $st->bindParam(':sghid',$sghead);
      $st->execute();

      $sql = "SELECT teaching_group FROM student_group WHERE student_group_id = :edit_id";
      $st = $db->prepare($sql);
      $st->bindParam( ':edit_id', $group_id);
      $st->execute();
      $teaching_group = $st->fetchColumn();

      $sql = "DELETE FROM teaching_group WHERE teaching_group_id = :tg";
      $st = $db->prepare($sql);
      $st->bindParam( ':tg', $teaching_group);
      $st->execute();

      $array = array_values(array_filter($_POST, 'is_int', ARRAY_FILTER_USE_KEY));
      $sql = "INSERT INTO teaching_group (teaching_group_id,teachers_subjects_id) VALUES (:tgid,:sid)";
      $st = $db->prepare($sql);
      $st->bindParam(':tgid',$teaching_group);
      foreach($array as $a) {
          $st->bindParam(':sid',$a);
          $st->execute();
      }

  }
  
  static public function deleteItem($id_delete, $page){
    
    $db = Database::getInstance()->getConnection();
    $sql = "delete from subjects where subjects_id = :id";
    $st = $db->prepare($sql);
    $st->bindParam( ':id', $id_delete);
    $result = $st->execute();

    if($result){
      header("Location: $page");
    }   
}
  static public function updateItem($group_name, $edit_id){
    
      $db = Database::getInstance()->getConnection();
      $sql = "UPDATE subjects SET name = :group_name WHERE subjects_id = :edit_id";
      $st = $db->prepare($sql);
      $st->bindParam( ':group_name', $group_name);
      $st->bindParam( ':edit_id', $edit_id);
      $st->execute();
  }
  static public function deleteGroup(){
    
    $db = Database::getInstance()->getConnection();
    $id_delete = $_GET['delete'];

    $sql = "UPDATE students SET group_id = NULL WHERE group_id = :id_delete";
    $st = $db->prepare($sql);
    $st->bindParam( ':id_delete', $id_delete);
    $st->execute();

    
    $sql = "DELETE from student_group WHERE student_group_id = :id_delete";
    $st = $db->prepare($sql);
    $st->bindParam( ':id_delete', $id_delete);
    $st->execute();

    $sql = "SELECT schedule_id from schedule WHERE student_group_id = :id_delete";
    $st = $db->prepare($sql);
    $st->bindParam( ':id_delete', $id_delete);
    $st->execute();
    $schedule = $st->fetchColumn();

    if($schedule) {
        $sql = "DELETE from scheduleblocks WHERE schedule_schedule_id = :sch";
        $st = $db->prepare($sql);
        $st->bindParam( ':sch', $schedule);
        $st->execute();

        $sql = "DELETE from schedule WHERE schedule_id = :sch";
        $st = $db->prepare($sql);
        $st->bindParam( ':sch', $schedule);
        $st->execute();
    }
}

static public function openDoorInvitation(){
  $parents_id=$_SESSION['id'];
  $sql = "SELECT t.teachers_id
  FROM students s
  JOIN parents p ON p.student_JMBG=s.student_JMBG
  JOIN student_group sg ON s.group_id=sg.student_group_id
  JOIN teachers t ON sg.student_group_head_id=t.teachers_id
  WHERE p.parents_id=:id";
  $st = self::$db->prepare($sql);
  $st->bindParam( ':id', $parents_id);
  $st->execute();
  $teacher = $st->fetchColumn();
  return $teacher;
}

static public function openDoorSend($dtime,$reason) {
  $teacher=$_SESSION['teacher_id'];
  $parents_id=$_SESSION['id'];
  $sql = "INSERT INTO opendoors (parents_parents_id, teachers_teachers_id, requesttime, message) VALUES (:p, :tc, :t,:r)";
  $st = self::$db->prepare($sql);
  $st->bindParam(':t',$dtime);
  $st->bindParam(':r',$reason);
  $st->bindParam(':p',$parents_id);
  $st->bindParam(':tc',$teacher);
  $result = $st->execute();
  return $result;
}


static public function getGrades($id, $subject){
  $db = Database::getInstance()->getConnection();
  $sql = "SELECT subjects.name, grades.value  FROM grade_type INNER JOIN grades ON grade_type.grade_type_id = grades.grade_type INNER JOIN subjects ON grades.subjects_id_fk = subjects.subjects_id WHERE grade_type.grade_type_id = :id AND subjects.name = :subject";
  $st = $db->prepare($sql);
  $st->bindParam(':id', $id);
  $st->bindParam(':subject', $subject);
  $st->execute();
  $result = $st->fetchAll();
  $data = array();
  foreach ($result as $row){
    $data[] = $row;
  }
  return $data;

}

static public function getLastID(){
    $db = Database::getInstance()->getConnection();
    $last_id = last_insert_id();

    return $last_id;
}



static public function getParents() {
  $sql = "SELECT p.parents_id
  FROM parents p
  JOIN students s ON s.student_JMBG=p.student_JMBG
  JOIN student_group sg ON sg.student_group_id=s.group_id
  WHERE sg.student_group_head_id=:id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id',$_SESSION['id']);
  $st->execute();
  $result = $st->fetchAll(PDO::FETCH_COLUMN);
  return $result;
}

static public function updateAnnouncements(Announcement $ntf) {
  $sql = "UPDATE announcement SET approved=:decision where announcement_id=:id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':decision',$ntf->approved);
  $st->bindParam(':id',$ntf->announcement_id);
  $st->execute();
}

static public function getAnnouncements() {
  $sql = "SELECT DISTINCT a.announcement_id,ad.sender,u.firstName,u.lastName,a.subject,a.body
          FROM announcement a
          JOIN announcement_data ad ON ad.announcement_id= a.announcement_id
          JOIN users u ON ad.sender=u.users_id
          WHERE a.approved=0";
  $st = self::$db->prepare($sql);
  $result = $st->execute();
  if(!$result) {
    exit("Database query failed.");
  }
  $objectArray = [];
  while($row = $st->fetch()) {
    $objectArray[] = new Announcement($row);
  }
  return $objectArray;
}

static public function getSchedule($schedule_id) {
  $sql = "select *
          from schedule s
          JOIN scheduleblocks sb ON s.schedule_id=sb.schedule_schedule_id
         where schedule_id = :id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id',$schedule_id);
  $st->execute();
  while($row = $st->fetchAll(PDO::FETCH_ASSOC)) {
    $schedule = new Schedule($row);
  }
  if(!isset($schedule)) {
    $args[0]['schedule_id'] = $schedule_id;
    $args[0]['student_group_id'] = self::getGroupBySchedule($schedule_id);
    $args[0]['days_days_id'] = 1;
    $args[0]['blocks_blocks_id'] = 1;
    $args[0]['subjects_subjects_Id'] = 1;
    $schedule = new Schedule($args);
    //unsetuj matu
    $schedule->days[0]->blocks[0]->subject_id = null;
    $schedule->days[0]->blocks[0]->subject_name = null;
  }
   return $schedule;

}

static public function fillBlockData(Block $block) {
  $sql = "SELECT blockstart from blocks where blocks_id=:id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id',$block->block_id);
  $st->execute();
  $block->time = $st->fetchColumn();
  $sql = "SELECT name from subjects where subjects_id=:id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id',$block->subject_id);
  $st->execute();
  $block->subject_name = $st->fetchColumn();
}

static public function fillDayData(Day $day) {
  $sql = "SELECT name from days where days_id=:id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id',$day->day_id);
  $st->execute();
  $day->name = $st->fetchColumn();
}

static public function insertBlock($schedule_id, $block_id,$subject_id,$day_id) {
  $sql = "INSERT INTO scheduleblocks (schedule_schedule_id,days_days_id,blocks_blocks_id,subjects_subjects_Id) 
          VALUES(:scid,:did,:bid,:suid) ";
  $st = self::$db->prepare($sql);
  $st->bindParam(':scid',$schedule_id);
  $st->bindParam(':did',$day_id);
  $st->bindParam(':bid',$block_id);
  $st->bindParam(':suid',$subject_id);
  $st->execute();
}

static public function removeSchedule($schedule_id) {
  $sql = "DELETE FROM scheduleblocks WHERE schedule_schedule_id=:scid";
  $st = self::$db->prepare($sql);
  $st->bindParam(':scid',$schedule_id);
  $st->execute();
}

static public function dummygrades() {
  $sql = "INSERT INTO grades(value,subjects_id_fk, students_id_fk,grade_type,semestar) VALUES (:value,:suid,:stid,:gt,:s)";
  $st=self::$db->prepare($sql);
  for($i = 3;$i<15;$i++) {// for za studente
    for($j=1; $j<12;$j++) { //for za predmete

        for($m = 1; $m<4 ; $m++) {
            $value = mt_rand(1,5);
            $gt= mt_rand(1,4);
            $st->bindParam(':value',$value);
            $st->bindParam(':suid',$j);
            $st->bindParam(':stid',$i);
            $st->bindParam(':gt',$gt);
            $st->bindValue(':s',1);
            $st->execute();
        }
        
    }
}
}

static public function dummysch($scid,$did,$bid,$sid) {
  $sql = "INSERT INTO scheduleblocks (schedule_schedule_id,days_days_id,blocks_blocks_id,subjects_subjects_id) VALUES (:scid,:did,:bid,:sid)";
  $st = self::$db->prepare($sql);
  $st->bindParam(':scid',$scid);
  $st->bindParam(':did',$did);
  $st->bindParam(':bid',$bid);
  $st->bindParam(':sid',$sid);
  $st->execute();
  }
  

static public function checkIfExists($schedule_id) {
  $sql = "SELECT * FROM scheduleblocks WHERE schedule_schedule_id=:scid";
  $st = self::$db->prepare($sql);
  $st->bindParam(':scid',$schedule_id);
  $st->execute();
  if($row = $st->fetch()) {
    return true;
  }
  return false;
}

static public function createSchedule($schedule_id) {
  $sql = "INSERT INTO schedule VALUES (:scid,:gid)";
  $st = self::$db->prepare($sql);
  $st->bindParam(':scid',$schedule_id);
  $st->bindParam(':gid',$schedule_id);
  $st->execute();
}
static public function getScheduleId($group_id) {
  $sql = "SELECT schedule_id from schedule where student_group_id={$group_id}";
  $st = self::$db->prepare($sql);
  $st->execute();
  if($row = $st->fetchColumn()) {
    return $row;
  } 
 
}

static public function getAvailableTeachers() {
    $sql = 'SELECT t1.teachers_id,u.users_id,u.status,u.username,u.firstName,u.lastName
    FROM teachers t1
    LEFT JOIN student_group t2 ON t2.student_group_head_id = t1.teachers_id
    JOIN users u on u.users_id=t1.teachers_id
    WHERE t2.student_group_head_id IS NULL AND t1.teacher_type=1';
    $st = self::$db->prepare($sql);
    $st->execute();
    $row = $st->fetchAll();
    return $row;
}

static public function addStudentGroup() {
    $year = $_POST['group_year'];
    $class = $_POST['group_number'];
    if(isset($_POST['teacher']) && $_POST['teacher']!=0) {
        $teacher = $_POST['teacher'];
        $msql = "INSERT INTO student_group(student_group_id, group_year,group_number,teachers_teachers_id) VALUES (NULL , :groupyear,:group_number,:teacher)";
    } else {
        $msql = "INSERT INTO student_group(student_group_id, group_year,group_number) VALUES (NULL , :groupyear,:group_number)";
    }
    
    $gr = self::$db->prepare($msql);
    $gr->bindParam(':groupyear', $year);
    $gr->bindParam(':group_number', $class);
    if(isset($teacher)) {
        $gr->bindParam(':teacher', $teacher);
    }
    $gr->execute();
    $id = self::$db->lastInsertId();

    $sql = "INSERT INTO schedule (student_group_id) values(:id)";
    $st = self::$db->prepare($sql);
    $st->bindParam(':id',$id);
    $st->execute();


}
    
public static function addUserByStatus($status, array $arr){
  switch($status){
    case 2:
      self::addDirector($arr);
      break;
    case 3:
      Mapper::addTeacher($arr);
      break;
    case 4:
      Mapper::addParent($arr);
      break;
  }
}

public static function addUser(array $arr){
  $password = password_hash($arr['password'], PASSWORD_BCRYPT);
  $sql = "INSERT INTO users (users_id, status, username, password, loginToken, firstName, lastName, picture) VALUES (null, :status, :username, :password, null, :firstName, :lastName, null)";
  $st = self::$db->prepare($sql);
  $st->bindParam(':status', $arr['status']);
  $st->bindParam(':username', $arr['username']);
  $st->bindParam(':password', $password);
  $st->bindParam(':firstName', $arr['name']);
  $st->bindParam(':lastName', $arr['lastname']);
  $result = $st->execute();
  if(!$result) {
    exit("Database query failed.");
  }
  return true; 
} 

public static function addDirector(array $arr){
  isset($arr['users_id']) ? $id = $arr['users_id'] : $id = self::$db->lastInsertId();
  $sql = "INSERT INTO directors (directors_id) VALUES (:id)";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id', $id);
  $result = $st->execute();
  if(!$result){
    exit("Database query failed.");
  }
  return true;
}

public static function addParent(array $arr){
  isset($arr['users_id']) ? $id = $arr['users_id'] : $id = self::$db->lastInsertId();
  $sql = "INSERT INTO parents (parents_id, student_JMBG) VALUES (:id, :student_JMBG)";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id', $id);
  $st->bindParam(':student_JMBG', $arr['student_JMBG']);
  $result = $st->execute();
  if(!$result){
    exit("Database query failed.");
  }
  return true;
}

public static function addTeacher(array $arr){
  isset($arr['users_id']) ? $id = $arr['users_id'] : $id = self::$db->lastInsertId();
  $teacher_type = $arr['teacher_type'];
  
  $sql = "INSERT INTO teachers (teachers_id, teacher_type) VALUES (:id, :teacher_type)";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id', $id);
  $st->bindParam(':teacher_type', $teacher_type);
  $result = $st->execute();

  if($teacher_type == 2){
    $subjects = $arr['subject'];
    foreach($subjects as $subject){
      $sql = "INSERT INTO teachers_subjects (teachers_subjects_id, teachers_teachers_id, subjects_subjects_id) VALUES (null, :id, :subject)";
      $st = self::$db->prepare($sql);
      $st->bindParam(':id', $id);
      $st->bindParam(':subject', $subject);
      $result = $st->execute();
    }
  }

  if(!$result){
    exit("Database query failed.");
  }
  return true;   
}

public static function userInfoByUsername($username){
  $sql = "SELECT * FROM users WHERE username = :username";
  $st = self::$db->prepare($sql);
  $st->bindParam(':username', $username);
  $result = $st->execute();
  return $st->fetch(PDO::FETCH_ASSOC);
}

public static function getTeacherTypeById($id){
  $sql = "SELECT teacher_type FROM teachers WHERE teachers_id = :id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id', $id);
  $result = $st->execute();
  $teacher = $st->fetch(PDO::FETCH_ASSOC);
  return $teacher['teacher_type'];
}

public static function showUser(array $array) {
  $username = $array['username'];
  $user = self::userInfoByUsername($username);
  $id = $user['users_id'];
  $status = $user['status'];

  switch($status){
    case 4:
      $sql = "SELECT users_id, status, username, firstName, lastName, student_JMBG FROM users JOIN parents ON users_id = parents_id WHERE username = :username";
      break;
    case 3:
      $teacher_type = self::getTeacherTypeById($id);
      if($teacher_type == 1){
        $sql = "SELECT users_id, status, username, firstName, lastName, teacher_type FROM users JOIN teachers ON users_id = teachers_id WHERE username = :username";
      } else {
        $sql = "SELECT users_id, status, username, firstName, lastName, teacher_type, teachers_subjects.subjects_subjects_id FROM users 
        JOIN teachers ON users_id = teachers_id 
        JOIN teachers_subjects ON teachers.teachers_id = teachers_subjects.teachers_teachers_id WHERE username = :username;";
      }      
      break;
    default:
      $sql = "SELECT users_id, status, username, firstName, lastName FROM users JOIN directors ON users_id = directors_id WHERE username = :username";
  }

  $st = self::$db->prepare($sql);
  $st->bindParam(':username', $username);
  $result = $st->execute();
  $object_arr = [];
  while($user = $st->fetch()){
    $object_arr[] = $user;
  }

  return $object_arr;
}

public static function updateUserByStatus($status){  
  switch($status){
    case 2:
        Mapper::updateDirector($_POST);
        break;
    case 3:
        Mapper::updateTeacher($_POST);
        break;
    case 4:
        Mapper::updateParent($_POST);
        break;
  }

}

public static function updateDirector(array $arr){
  $sql = "UPDATE users JOIN directors ON users_id = directors_id SET username = :username, firstName = :firstName, lastName = :lastName, status = :status WHERE users_id = :id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id', $arr['users_id']);
  $st->bindParam(':username', $arr['username']);
  $st->bindParam(':firstName', $arr['firstName']);
  $st->bindParam(':lastName', $arr['lastName']);
  $st->bindParam(':status', $arr['status']);
  $result = $st->execute();
  if(!$result) {
    exit("Database query failed.");
  }
  return true; 
}

public static function updateParent(array $arr){
  $sql = "UPDATE users JOIN parents ON users_id = parents_id SET username = :username, firstName = :firstName, lastName = :lastName, student_JMBG = :student_JMBG, status = :status WHERE users_id = :id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id', $arr['users_id']);
  $st->bindParam(':username', $arr['username']);
  $st->bindParam(':firstName', $arr['firstName']);
  $st->bindParam(':lastName', $arr['lastName']);
  $st->bindParam(':student_JMBG', $arr['student_JMBG']);
  $st->bindParam(':status', $arr['status']);
  $result = $st->execute();
  if(!$result) {
    exit("Database query failed.");
  }
  return true; 
}

public static function updateTeacher(array $arr){
  $teacher_type = $arr['teacher_type'];
  $sql = "DELETE FROM teachers_subjects WHERE teachers_teachers_id = :id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id', $arr['users_id']);
  $st->execute();

  $sql1 = "UPDATE users JOIN teachers ON users_id = teachers_id SET username = :username, firstName = :firstName, lastName = :lastName, status = :status, teacher_type = :teacher_type WHERE users_id = :id";
  $st1 = self::$db->prepare($sql1);
  $st1->bindParam(':id', $arr['users_id']);
  $st1->bindParam(':username', $arr['username']);
  $st1->bindParam(':firstName', $arr['firstName']);
  $st1->bindParam(':lastName', $arr['lastName']);
  $st1->bindParam(':status', $arr['status']);
  $st1->bindParam(':teacher_type', $teacher_type);
  $st1->execute();

  if($teacher_type == 2){    
    $subjects = $arr['subject'];
    foreach($subjects as $subject){
      $sql2 = "INSERT INTO teachers_subjects (teachers_subjects_id, teachers_teachers_id, subjects_subjects_id) VALUES (NULL, :id, :subject)";
      $st2 = self::$db->prepare($sql2);
      $st2->bindParam(':id',$arr['users_id']);
      $st2->bindParam(':subject', $subject);
      $st2->execute();
    }
  }     
}


public static function deleteUser($user_id){
  $status = self::get_user_status_by_id($user_id);

  switch($status){
    case 3:
      $result = self::deleteTeacher($user_id);
      break;
    case 4:
      $result = self::deleteParent($user_id);
      break;
    case 2:
      $result = self::deleteDirector($user_id);
      break;
  }

  $result1 = self::deleteUsers($user_id);

  if(!$result || !$result1) {
    exit("Database query failed.");
  } 
  return $message = "<p>User has been deleted form Database.</p>";
}

public static function deleteUsers($id){
  $sql = "DELETE FROM users WHERE users_id = :id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id', $id);
  $result = $st->execute();
  if(!$result) {
    exit("Database query failed.");
  } 
  return true;
}

public static function deleteTeacher($id){
  $teacher_type = self::getTeacherTypeById($id);
  $queries = [];

  switch($teacher_type){
    case 2:
      $queries [] = "UPDATE student_group SET student_group_head_id = NULL WHERE student_group_head_id = :id";      
      $queries [] = "DELETE FROM teachers_subjects WHERE teachers_teachers_id = :id";
      $queries [] = "DELETE FROM teachers WHERE teachers_id = :id";
      break;
    default:
      $queries [] = "UPDATE student_group SET student_group_head_id = NULL WHERE student_group_head_id = :id";
      $queries [] = "UPDATE teachers_subjects SET teachers_teachers_id = NULL WHERE teachers_teachers_id = :id";  
      $queries [] = "DELETE FROM teachers WHERE teachers_id = :id";
  }
  
  for($i=0; $i<count($queries); $i++){
    $sql = $queries[$i];
    $st = self::$db->prepare($sql);
    $st->bindParam(':id', $id);
    $result = $st->execute();
  }
  
  if(!$result) {
    exit("Database query failed.");
  }
  return true; 
}

public static function deleteParent($id){     
  $sql = "DELETE FROM parents WHERE parents_id = :users_id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':users_id', $id);
  $result = $st->execute();
  
  if(!$result) {
    exit("Database query failed.");
  }
  return true; 
}

public static function deleteDirector($id){
  $sql = "DELETE FROM directors WHERE directors_id = :users_id;";
  $st = self::$db->prepare($sql);
  $st->bindParam(':users_id', $id);
  $result = $st->execute();

  if(!$result) {
    exit("Database query failed.");
  }
  return true;
}

public static function get_student_jmbg($id){
  $sql = "SELECT parents.student_JMBG FROM parents JOIN users ON users_id = parents_id WHERE users_id = :userID";
  $st = self::$db->prepare($sql);
  $st->bindParam(':userID', $id);
  $st->execute();
  $parent = $st->fetch(PDO::FETCH_ASSOC);
  return $parent['student_JMBG'];
}
  
public static function checkUser(){
  $username = $_POST['username'];
  $sql = 'SELECT username FROM users WHERE username = :username';
  $st = self::$db->prepare($sql);
  $st->bindParam(':username', $username);
  $st->execute();
  $user = $st->fetch(PDO::FETCH_ASSOC);
  if($username !== $user['username']){
    return false;
  }
}


public static function check_student_group_input(){
  $val = $_POST['group_name'];
  $object_array = self::find_all('student_group');
  $group_name = [];
  foreach($object_array as $obj){
    $students_group = get_object_vars($obj);
    $group_name[] = $students_group['name'];
  }
  return in_array($val, $group_name);
}

public static function get_user_status_by_id($id){
  $sql = "select status from users where users_id = :user_id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':user_id', $id);
  $result = $st->execute();
  if(!$result) {
    exit("Database query failed.");
  }
  $user = $st->fetch(PDO::FETCH_ASSOC);
  return $user['status'];
}

public static function table_name_by_status($status){
  $sql = "select name from status where status_id = :status_id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':status_id', $status);
  $st->execute();
  $row = $st->fetch(PDO::FETCH_ASSOC);
  return $row['name']; 
}

public static function getStudents($group_id) {
  $sql = "SELECT * from students where group_id = :gid";
  $st = self::$db->prepare($sql);
  $st->bindParam(':gid',$group_id);
  $st->execute();
  $objectArray = array();
  while($row = $st->fetch()) {
    $objectArray[] = new Student($row);
  }
  return $objectArray;
}

public static function fillAllGrades($studentId) {
  $sql = "SELECT * FROM grades WHERE students_id_fk = :stid";
  $st = self::$db->prepare($sql);
  $st->bindParam(':stid',$studentId);
  $st->execute();
  $objectArray = array();
  while($row = $st->fetch()) {
    $objectArray[] = $row;
  }
  return $objectArray;
}
public static function findGradesBySub($studentId,$subjectId) {
  $sql = "SELECT * FROM grades WHERE students_id_fk = :stid AND subjects_id_fk=:suid";
  $st = self::$db->prepare($sql);
  $st->bindParam(':stid',$studentId);
  $st->bindParam(':suid',$subjectId);
  $st->execute();
  $objectArray = array();
  while($row = $st->fetch()) {
    $objectArray[] = $row;
  }
  return $objectArray;
}
public static function findGrades($studentId,$subjectId,$semestar) {
  $sql = "SELECT * FROM grades WHERE students_id_fk = :stid AND subjects_id_fk=:suid AND semestar=:sem";
  $st = self::$db->prepare($sql);
  $st->bindParam(':stid',$studentId);
  $st->bindParam(':suid',$subjectId);
  $st->bindParam(':sem',$semestar);
  $st->execute();
  $objectArray = array();
  while($row = $st->fetch()) {
    $objectArray[] = $row;
  }
  return $objectArray;
}


public static function getGSids() {
  $sql = "SELECT * FROM student_group JOIN schedule ON student_group.student_group_id=schedule.student_group_id";
  $st = self::$db->prepare($sql);
  $st->execute();
  $objectArray = $st->fetchAll();
  return $objectArray;
}
public static function getGroupBySchedule($schedule_id) {
  $sql = "SELECT * FROM schedule WHERE schedule_id = '{$schedule_id}'";
	$st = self::$db->prepare($sql);
	$st->execute();
	$row = $st->fetch();
	return $row->student_group_id;
}

public static function proba() {
  $sql = "INSERT INTO hi VALUES(3)";
  $st = self::$db->prepare($sql);
	$st->execute();
}
static public function getGradesByStudentGroup($student_group, $subject, $id){
  $db = Database::getInstance()->getConnection();
  $sql = "SELECT student_group.student_group_id, grades.value, grades.grade_type FROM student_group INNER JOIN students ON student_group.student_group_id =     students.group_id 
         INNER JOIN grades ON students.students_id = grades.students_id_fk INNER JOIN subjects ON grades.subjects_id_fk = subjects.subjects_id 
         WHERE student_group.student_group_id = :student_group AND
        grades.grade_type = :id AND subjects.name= :subject_name";
  $st = $db->prepare($sql);
  $st->bindParam(':student_group', $student_group);
  $st->bindParam(':subject_name', $subject);
  $st->bindParam(':id', $id);
  $st->execute();
  $result = $st->fetchAll();  
  $data = array();
  foreach ($result as $row){
    $data[] = $row;
  }
  return $data;

}

static public function getStudentGroup()
{
  $db = Database::getInstance()->getConnection();
  $sqlQuery = "SELECT CONCAT(student_group.group_number,'/',student_group.group_year) FROM e_dnevnik_.student_group JOIN e_dnevnik_.teachers ON student_group.student_group_head_id = teachers.teachers_id JOIN e_dnevnik_.users ON teachers.teachers_id = users.users_id WHERE users.users_id = '".$_SESSION['id']."'";
  $st = $db->prepare($sqlQuery);
  $st->execute();
  $row = $st->fetchColumn();
}

static public function selectAllSubjectsName()
{
  $sql = "SELECT name FROM e_dnevnik_.subjects";
  $st = self::$db->prepare($sql);
  $st->execute();
  $row = $st->fetchAll(PDO::FETCH_ASSOC);
  $subjects = [];
  for ($i=0; $i < count($row) ; $i++) { 
    foreach ($row[$i] as $key => $value) {
         array_push($subjects,$value);
    }
  
  }
  return $subjects;
}

static public function SGall() {
  $sql = "SELECT * FROM student_group JOIN e_dnevnik_.teachers ON student_group.student_group_head_id = teachers.teachers_id JOIN e_dnevnik_.users ON teachers.teachers_id = users.users_id WHERE users.users_id = '".$_SESSION['id']."'";
  $db = Database::getInstance()->getConnection();
  $st = $db->prepare($sql);
  $st->execute();
  $row = $st->fetch(PDO::FETCH_ASSOC);
  return $row;
}
public static function findSubjectByName($name, $parent_id){
  $db = Database::getInstance()->getConnection();
  $sql = "SELECT * FROM parents INNER JOIN students ";
  $sql.= " ON parents.parents_id = students.parents_parents_id ";
  $sql.= "INNER JOIN grades ON students.students_id = grades.students_id_fk ";
  $sql.= "INNER JOIN subjects ON grades.subjects_id_fk = subjects.subjects_id WHERE subjects.name = :name and parents_id = :parentid;";
  $st = $db->prepare($sql);
  $st->bindParam(':name', $name);
  $st->bindParam(':parentid', $parent_id);
  $st->execute();
  $row = $st->fetchAll();
  return $row;
}
public static function findGroupByTeacher($teachers_id){
  $db = Database::getInstance()->getConnection();
  $sql = "select *  from students s INNER JOIN student_group sg on sg.student_group_id=s.group_id join teachers t ON t.teachers_id=sg.student_group_head_id WHERE t.teachers_id = :teachersId";
  $st = $db->prepare($sql);
  $st->bindParam(':teachersId', $teachers_id);
  $st->execute();
  $result = $st->fetchAll();
  return $result;
}
public static function findAbsenceByTeacherID($teacher_id){
  $db = Database::getInstance()->getConnection();
  $sql = "SELECT absence_info.class_id_fk, absence_info.absence_student_id, absence_info.time_info, students.name as students_name, students.lastName, blocks_blocks_id, subjects.name FROM diary_of_teaching
          INNER JOIN absence_info ON diary_of_teaching.id = absence_info.class_id_fk 
          INNER JOIN scheduleblocks on absence_info.current_block  = scheduleblocks.blocks_blocks_id 
          INNER JOIN subjects ON scheduleblocks.subjects_subjects_id = subjects.subjects_id 
          INNER JOIN students ON absence_info.absence_student_id = students.students_id WHERE absence_status = 'Waiting for approval' AND teacher_id = :teacher_id";
  $st= $db->prepare($sql);
  $st->bindParam(':teacher_id', $teacher_id);
  $st->execute();
  $result = $st->fetchAll();
  return $result;
 }
public static function updateAbsence($student_id, $class_id, $status){
  $db = Database::getInstance()->getConnection();
  $sql = "UPDATE absence_info SET absence_info.absence_status = :status WHERE absence_info.absence_status = 'Waiting for approval' AND absence_info.absence_student_id = :student_id AND class_id_fk = :class_id";
  $st = $db->prepare($sql);
  $st->bindParam(':student_id' , $student_id);
  $st->bindParam(':class_id' , $class_id);
  $st->bindParam(':status', $status);
  $result = $st->execute();
  return $result;

}
public static function selectStudentAbsenceByParentID($parents_id){
  $db = Database::getInstance()->getConnection();
  $sql = "SELECT *, COUNT(*) as count FROM absence_info 
  INNER JOIN students ON absence_info.absence_student_id = students.students_id 
  INNER JOIN parents ON students.parents_parents_id = parents.parents_id 
  INNER JOIN users ON parents.parents_id = users.users_id 
  INNER JOIN student_group ON students.group_id = student_group.student_group_id
  WHERE absence_status = 'Waiting for approval' AND parents_id = :parents_id GROUP BY absence_info.time_info";
  $st = $db->prepare($sql);
  $st->bindParam(':parents_id', $parents_id);
  $st->execute();
  $result = $st->fetchAll();
  return $result;
 }
 public static function insertExcuseRequest($class_id, $parent_id, $student_id, $excuse_text, $picture_name, $teacher_id)
 {
  $db = Database::getInstance()->getConnection();
  $sql = "INSERT INTO excuces_requests(id, parent_id, class_id, student_id, excuse_text, picture_name, teacher_id) VALUES (null, :parent_id, :class_id, :student_id, :excuse_text, :picture_name, :teacher_id)";
  $st = $db->prepare($sql);
  $st->bindParam(':parent_id', $parent_id);
  $st->bindParam(':class_id', $class_id);
  $st->bindParam(':student_id', $student_id);
  $st->bindParam(':excuse_text', $excuse_text);
  $st->bindParam(':picture_name', $picture_name);
  $st->bindParam(':teacher_id', $teacher_id);
  $result = $st->execute();
  return $result;
}
public static function showExcuseRequest($teacher_id)
{
  $db = Database::getInstance()->getConnection($teacher_id);
  $sql = "SELECT excuces_requests.id, absence_info.time_info, students.name, students.lastName as s_last_name, users.firstName, users.lastName, excuces_requests.excuse_text, excuces_requests.picture_name FROM excuces_requests INNER JOIN parents on excuces_requests.parent_id = parents.parents_id 
  INNER JOIN students ON parents.parents_id = students.parents_parents_id 
  INNER JOIN student_group ON students.group_id = student_group.student_group_id 
  INNER JOIN teachers ON student_group.student_group_head_id = teachers.teachers_id 
  INNER JOIN users on parents.parents_id = users.users_id
  INNER JOIN absence_info ON absence_info.absence_student_id = students.students_id WHERE teachers_id = :teacher_id";

  $st = $db->prepare($sql);
  $st->bindParam(':teacher_id', $teacher_id);
  $st->execute();
  $result = $st->fetchAll();
  return $result;
}
public static function updateAbsenceStatus($absence_student_id, $status)
{
  $db = Database::getInstance()->getConnection();
  $sql = "UPDATE absence_info SET absence_status = :absence_status WHERE absence_student_id = :absence_student_id;";
  $st = $db->prepare($sql);
  $st->bindParam(':absence_status', $status);
  $st->bindParam(':absence_student_id', $absence_student_id);
  $result = $st->execute();

  if($result){
    $sql = "DELETE FROM excuces_requests WHERE class_id = :id";
    $st = $db->prepare($sql);
    $st->bindParam(':id', $class_id);
    $result2 = $st->execute();
    return $result2;
  }
  
}

public static function updateGrade($oldGrade,$newGrade) {
  $sql = "UPDATE grades
          SET `value` = :nv, subjects_id_fk = :nsuid, students_id_fk = :nstid , grade_type= :ngt , semestar= :nsem
          WHERE `value` = :ov AND subjects_id_fk = :osuid AND students_id_fk = :ostid AND grade_type= :ogt AND semestar= :osem LIMIT 1";
  $st = self::$db->prepare($sql);
  $st->bindParam(':nv',$newGrade['grade']);
  $st->bindParam(':nsuid',$newGrade['subject_id']);
  $st->bindParam(':nstid',$newGrade['student_id']);
  $st->bindParam(':ngt',$newGrade['grade_type']);
  $st->bindParam(':nsem',$newGrade['semestar_id']);
  $st->bindParam(':ov',$oldGrade['grade']);
  $st->bindParam(':osuid',$oldGrade['subject_id']);
  $st->bindParam(':ostid',$oldGrade['student_id']);
  $st->bindParam(':ogt',$oldGrade['grade_type']);
  $st->bindParam(':osem',$oldGrade['semestar_id']);
  $st->execute();
}



public static function getSD($id) {
  $sql = "SELECT * FROM students where students_id = :sid";
  $st = self::$db->prepare($sql);
  $st->bindParam(':sid',$id);
  $st->execute();
  $result = $st->fetch(PDO::FETCH_ASSOC);
  return $result;
}

public static function selectAllSub() {
  $sql = "SELECT * FROM subjects";
  $st = self::$db->prepare($sql);
  $st->execute();
  while($row = $st->fetch()) {
    $res[] = new Subject($row);
  }
  return $res;
}

public static function getSubs($id) {
  $sql = "SELECT * FROM subjects s JOIN subjects_group sg ON  s.subjects_id=sg.subjects_id_fk JOIN student_group stg ON stg.group_year=sg.group_year WHERE stg.student_group_id = :sgid";
  $st = self::$db->prepare($sql);
  $st->bindParam(':sgid',$id);
  $st->execute();
  $result = $st->fetchAll(PDO::FETCH_COLUMN);
  return $result;
}
public static function getUsernameAndPassword($username,$password)
{
  $sqlQuery = "SELECT us.username,us.users_id,us.password,st.status_id FROM e_dnevnik_.status AS st LEFT OUTER JOIN e_dnevnik_.users AS us ON st.status_id = us.status WHERE us.username = '".$username."' AND us.password = '".$password."'";
  $st = self::$db->prepare($sqlQuery);
  $st->execute();
  $row = $st->fetch();
  return $row;
}
static public function getUserPassword($user) {
  $sql = "SELECT password from users where username= :us";
  $st = self::$db->prepare($sql);
  $st->bindParam(':us',$user);
  $st->execute();
  $row = $st->fetchColumn();
  return $row;
  
}

public static function getTeacherIdBySchedule($schedule_id) {
  $sql = "SELECT sg.teachers_teachers_id FROM student_group sg 
          JOIN schedule sch ON sch.student_group_id=sg.student_group_id
          WHERE sch.schedule_id=:scid";
  $st = self::$db->prepare($sql);
  $st->bindParam(':scid',$schedule_id);
  $st->execute();
  $teacher = $st->fetch(PDO::FETCH_COLUMN);
  return $teacher;
}

public static function addNotification($nt,$id) {
  $sql = "INSERT INTO notifications(users_id,body,status) VALUES (:id,:nt,0)";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id',$id);
  $st->bindParam(':nt',$nt);
  $st->execute();
}

public static function getScheduleByTeacher() {
  $id = $_SESSION['id'];
  $sql = "SELECT schedule_id 
          from schedule
          join student_group ON schedule.student_group_id = student_group.student_group_id
          WHERE student_group_head_id=:tid";
  $st = self::$db->prepare($sql);
  $st->bindParam(':tid',$id);
  $st->execute();
  $res = $st->fetchColumn();
  return $res;
}

public static function getRequests() {
  $teacherId = $_SESSION['id'];
  $sql = "SELECT *
          FROM opendoors od
          JOIN users u on od.parents_parents_id=u.users_id
          WHERE od.teachers_teachers_id = :tid";
  $st = self::$db->prepare($sql);
  $st->bindParam(':tid',$teacherId);
  $st->execute();
  $res = $st->fetchAll();
  return $res;
}
public static function setRequest($id,$status) {
  $sql = "UPDATE opendoors SET status = :sta WHERE opendoors_id = :odid";
  $st = self::$db->prepare($sql);
  $st->bindParam(':sta',$status);
  $st->bindParam(':odid',$id);
  $st->execute();
}

public static function getAnnouncementTargets($id) {
  $sql = "SELECT `target` FROM announcement_data WHERE announcement_id=:id";
  $st = self::$db->prepare($sql);
  $st->bindParam(':id',$id);
  $st->execute();
  $res = $st->fetchAll(PDO::FETCH_COLUMN);
  return $res;
}

public static function getAdditionalData() {
    $sql = "SELECT username from users where users_id = :uid";
    $st = self::$db->prepare($sql);
    $st->bindParam(':uid',$_SESSION['id']);
    $st->execute();
    $_SESSION['username'] = $st->fetchColumn();

  switch($_SESSION['status']) {
    case 3: //teacher
      $sql = "SELECT student_group_id 
              FROM student_group
              WHERE student_group_head_id=:tid";
      $st = self::$db->prepare($sql);
      $st->bindParam(':tid',$_SESSION['id']);
      $st->execute();
      $_SESSION['student_group_id'] = $st->fetchColumn();
  
      $teacher = Manager::createObject('Teacher',$_SESSION['id']);
      $teacher->getSchedule();
      $_SESSION['schedule'] = serialize($teacher->schedule);
      $_SESSION['parents']  = serialize(Mapper::getParents());
      break;
    case 4: //parent
      //teacher_id
      $sql = "SELECT *
              FROM students
              WHERE parents_parents_id=:pid";
      $st = self::$db->prepare($sql);
      $st->bindParam(':pid',$_SESSION['id']);
      $st->execute();
      $k = $st->fetch();
      $_SESSION['student_id'] = $k->students_id;
      $_SESSION['group_id']   = $k->group_id;

      $sql = "SELECT student_group_head_id
              FROM `student_group` 
              JOIN students on students.group_id=student_group.student_group_id
              WHERE students_id =:stid ";
      $st = self::$db->prepare($sql);
      $st->bindParam(':stid',$_SESSION['student_id']);
      $st->execute();
      $_SESSION['teacher_id'] = $st->fetchColumn();
  }
}

public static function blabla() {
  $sql = "SELECT * FROM student_group WHERE group_year > 4";
  $st = self::$db->prepare($sql);
  $st->execute();
  $student_group = $st->fetchAll();

  foreach($student_group as $group) {
    $year = $group->group_year;
    $sql = "SELECT * FROM subjects_group WHERE group_year = {$year}";
    $st = self::$db->prepare($sql);
    $st->execute();
    $group->subjects = $st->fetchAll();
    foreach($group->subjects as $sub) {
      $sql = "SELECT * FROM teachers_subjects JOIN teachers ON teachers.teachers_id = teachers_subjects.teachers_teachers_id  WHERE subjects_subjects_id = {$sub->subjects_id_fk} AND teacher_type=2";
      $st = self::$db->prepare($sql);
      $st->execute();
      $sub->teachers = $st->fetchAll();
    }
  }
  $i=1;
  $all = array();
  foreach($student_group as $group) {
    foreach($group->subjects as $subject) {
      $index = mt_rand(0,count($subject->teachers)-1);
      $teacher = $subject->teachers[$index]->teachers_subjects_id;
      $all[] = $subject->teachers[$index]->teachers_teachers_id;
      $sql  = "INSERT INTO teaching_group (teaching_group_id,teachers_subjects_id) VALUES (:tg,:ts)";
      $st   = self::$db->prepare($sql);
      $st->bindParam(':tg',$i);
      $st->bindParam(':ts',$teacher);
      $st->execute();

    }
    $head = $all[mt_rand(0,count($all)-1)];
    $sql = "UPDATE student_group SET student_group_head_id = :hid, teaching_group=:tg WHERE student_group_id = :id";
    $st = self::$db->prepare($sql);
    $st->bindParam(':hid',$head);
    $st->bindParam(':tg',$i);
    $st->bindParam(':id',$group->student_group_id);
    $st->execute();
    $i++;
  }
}

public static function blah() {
  $sql = "SELECT * FROM student_group WHERE group_year < 5";
  $st = self::$db->prepare($sql);
  $st->execute();
  $student_group = $st->fetchAll();
  $teachers = array();
  for($i=203; $i<216; $i++) {
    $teachers[] = $i;
  }
  $i = 0;
  foreach($student_group as $group) {
    $year = $group->group_year;
    $sql = "SELECT * FROM subjects_group WHERE group_year = {$year}";
    $st = self::$db->prepare($sql);
    $st->execute();
    $group->subjects = $st->fetchAll();
    foreach($group->subjects as $sub) {
      $sql = "SELECT * FROM teachers_subjects JOIN teachers ON teachers.teachers_id = teachers_subjects.teachers_teachers_id  WHERE subjects_subjects_id = {$sub->subjects_id_fk} AND teachers_subjects.teachers_teachers_id=:tid";
      $st = self::$db->prepare($sql);
      $st->bindParam(':tid',$teachers[$i]);
      $st->execute();
      $sub->teachers = $st->fetchAll();
    }
    $i++;
  }
  $i=13;
  foreach($student_group as $group) {
    foreach($group->subjects as $subject) {
      $teacher_subject = $subject->teachers[0]->teachers_subjects_id;
      $teacher = $subject->teachers[0]->teachers_teachers_id;
      $sql  = "INSERT INTO teaching_group (teaching_group_id,teachers_subjects_id) VALUES (:tg,:ts)";
      $st   = self::$db->prepare($sql);
      $st->bindParam(':tg',$i);
      $st->bindParam(':ts',$teacher_subject);
      $st->execute();
      echo "POJ  ";
    }
    $sql = "UPDATE student_group SET student_group_head_id = {$teacher}, teaching_group={$i} WHERE student_group_id = {$group->student_group_id}";
    echo $sql;
    $st = self::$db->prepare($sql);
    $st->execute();
    $i++;
  }
}

public static function blakkkk() {
  for($i = 1; $i<600;$i++) { //id studenta
    $sql = "SELECT group_year FROM `students` join student_group ON students.group_id = student_group.student_group_id WHERE students.students_id={$i}";
    $st = self::$db->prepare($sql);
    $st->execute();
    $year = $st->fetchColumn(); //godina
    $sql = "SELECT * FROM subjects_group WHERE group_year={$year}";
    $st = self::$db->prepare($sql);
    $st->execute();
    $subjects = $st->fetchAll(); // predmeti koje ucenik pohadja
      foreach($subjects as $subject) {  
        for($j=0;$j<5;$j++) { //5 ocena po predmetu
          $value = mt_rand(1,5);
          $semestar = mt_rand(1,2);
          $grade_type = mt_rand(1,4);
          $sql = "INSERT INTO grades(value,subjects_id_fk,students_id_fk,grade_type,semestar) VALUES (:v,:suid,:stid,:gt,:s)";
          $st = self::$db->prepare($sql);
          $st->bindParam(':v',$value);
          $st->bindParam(':suid',$subject->subjects_id_fk);
          $st->bindParam(':stid',$i);
          $st->bindParam(':gt',$grade_type);
          $st->bindParam(':s',$semestar);
          $st->execute();
        }
      }
  }
}
public static function getStudentByParentID($parent_id){
  $db = Database::getInstance()->getConnection();
  $sql = "SELECT students.students_id FROM students WHERE students.parents_parents_id = :parent_id";
  $st = $db->prepare($sql);
  $st->bindParam(':parent_id', $parent_id);
  $st->execute();
  $result = $st->fetchAll();
  return $result;
}
public static function absenceStatusByParentID($student_id)
{
  
  $db = Database::getInstance()->getConnection();
  $sql = "SELECT *, count(current_block) as block FROM absence_info INNER JOIN students ON absence_info.absence_student_id = students.students_id WHERE students.students_id = :student_id GROUP BY time_info;
  ";
  $st = $db->prepare($sql);
  $st->bindParam(':student_id', $student_id);
  $st->execute();
  $result = $st->fetchAll();
  return $result;
}
public static function selectAbsenceFromDB($parent_id)
{
  $db = Database::getInstance()->getConnection();
  $sql = "SELECT absence_status, COUNT(absence_status) as count_absence FROM absence_info INNER JOIN students ON absence_info.absence_student_id = students.students_id
           INNER JOIN parents ON students.parents_parents_Id = parents.parents_id WHERE parents_id = :parent_id  GROUP BY absence_status";
  $st = $db->prepare($sql);
  $st->bindParam(':parent_id', $parent_id);
  $st->execute();
  $result = $st->fetchAll();
  return $result;

}

public static function getSch($id) {
  $db = Database::getInstance()->getConnection();
  $sql = "SELECT *
            FROM (SELECT tg.teaching_group_id,ts.subjects_subjects_id 
                      FROM teaching_group tg 
                      JOIN teachers_subjects ts ON tg.teachers_subjects_id=ts.teachers_subjects_id 
                      WHERE ts.teachers_teachers_id = :tid ) as al
            JOIN student_group sg ON sg.teaching_group=al.teaching_group_id
            JOIN schedule s ON sg.student_group_id=s.student_group_id
            JOIN scheduleblocks sb ON sb.schedule_schedule_id=s.schedule_id AND al.subjects_subjects_id=sb.subjects_subjects_Id
            JOIN subjects sub ON sub.subjects_id = sb.subjects_subjects_Id";
  $st = $db->prepare($sql);
  $st->bindParam(':tid', $id);
  $st->execute();
  $result = $st->fetchAll();
  return $result;
}

public static function insertSch() {
    $db = Database::getInstance()->getConnection();

    $sql = "SELECT * from student_group sg JOIN teaching_group tg ON sg.teaching_group=tg.teaching_group_id JOIN teachers_subjects ts ON ts.teachers_subjects_id=tg.teachers_subjects_id WHERE sg.group_year > 4";
    $st = $db->prepare($sql);
    $st->execute();
    while($row = $st->fetch()) {
        $studentGroup[$row->student_group_id][$row->subjects_subjects_id]=$row->teachers_teachers_id;
    }
    $teacherSchedule = array();
    foreach($studentGroup as $sgid=>$subjectId) {
        $sql = "INSERT INTO scheduleblocks (schedule_schedule_id,days_days_id,blocks_blocks_id,subjects_subjects_id) VALUES (:scid,:did,:bid,:suid)";
        $st = $db->prepare($sql);
        for($i=1;$i<=5;$i++) { //days
            for($j=1;$j<=6;$j++) { // blocks
                $st->bindParam(':scid',$sgid);
                $st->bindParam(':did',$i);
                $st->bindParam(':bid',$j);
                $allSubjects = array_keys($studentGroup[$sgid]);
                $numOfSubjects = count($allSubjects);
                $rand = mt_rand(0,$numOfSubjects-1);
                $subject = $allSubjects[$rand];
                $st->bindParam(':suid',$subject);
                $teacherId = $studentGroup[$sgid][$subject];
                if(isset($teacherSchedule[$teacherId][$i][$j])) {
                    continue;
                } else {
                    $st->execute();

                    $teacherSchedule[$teacherId][$i][$j] = 1;
                }

            }
        }

    }
}
public static function insertSchkids() {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT * from student_group sg JOIN teaching_group tg ON sg.teaching_group=tg.teaching_group_id JOIN teachers_subjects ts ON ts.teachers_subjects_id=tg.teachers_subjects_id WHERE sg.group_year > 4";
        $st = $db->prepare($sql);
        $st->execute();
        while($row = $st->fetch()) {
            $studentGroup[$row->student_group_id][$row->subjects_subjects_id]=$row->teachers_teachers_id;
        }
        die();
        foreach($studentGroup as $sgid=>$subjectId) {
            $numOfSubjects = count($studentGroup[$sgid]);
            $sql = "INSERT INTO scheduleblocks (schedule_schedule_id,days_days_id,blocks_blocks_id,subjects_subjects_id) VALUES (:scid,:did,:bid,:suid)";
            $st = $db->prepare($sql);
            for($i=1;$i<=5;$i++) { //days
                for($j=1;$j<=5;$j++) { // blocks
                    $st->bindParam(':scid',$sgid);
                    $st->bindParam(':did',$i);
                    $st->bindParam(':bid',$j);
                    $rand = mt_rand(0,$numOfSubjects-1);
                    $subject = $studentGroup[$sgid][$rand];
                    $st->bindParam(':suid',$subject);
                    $st->execute();
                }
            }

        }
    }

public static function allTeachers($schedule_id) {
    //kupljenje nastavnika koji predaju tom odeljenju zajedno sa predmetima koje predaju tom odeljenju
    $db = Database::getInstance()->getConnection();
    $sql = "SELECT * FROM teaching_group tg
            JOIN student_group sg ON sg.teaching_group = tg.teaching_group_id
            JOIN schedule s ON s.student_group_id=sg.student_group_id
            JOIN teachers_subjects ts ON ts.teachers_subjects_id=tg.teachers_subjects_id
            WHERE s.schedule_id = :scid";
    $st = $db->prepare($sql);
    $st->bindParam(':scid', $schedule_id);
    $st->execute();
    $result = $st->fetchAll();
    foreach ($result as $res) {
        $teachers[$res->teachers_teachers_id][] = $res->subjects_subjects_id;
    }
    return $teachers;
}

public static function allowedSubjects($teachers) {
    foreach($teachers as $teacherId=>$subjects) {
        $teacher = Manager::createObject('Teacher',$teacherId);
        $teacher->getSchedule();
        //imamo raspored ucitelja/nastavnika, sada treba dodati u $finalSchedule za dan i blok koji predmeti tu ne mogu da se prebace
        // uzmemo subject kao kljuc sto je u sustini i profesor i u taj niz smestimo sva predavanja nastavnika koji tom odeljenju predaje
        foreach($subjects as $subject) {
            foreach($teacher->schedule as $day=>$blocks) {
                foreach($blocks as $blockId=>$block) {
                        $finalSchedule[$subject][] = $day."|".$blockId;
                }
            }
        }
    }
    if(isset($finalSchedule)) {
        return $finalSchedule;
    }
}

public static function getAvailableSubjects($schedule_id) {
    $db = Database::getInstance()->getConnection();
    $sql = "SELECT subj.subjects_id, subj.name
            FROM (
                SELECT s.student_group_id 
                FROM schedule s
                WHERE s.schedule_id=:scid
                ) as sg
            JOIN student_group sgg ON sgg.student_group_id=sg.student_group_id
            JOIN subjects_group subg ON subg.group_year=sgg.group_year
            JOIN subjects subj ON subj.subjects_id=subg.subjects_id_fk";
    $st = $db->prepare($sql);
    $st->bindParam(':scid',$schedule_id);
    $st->execute();
    $res = $st->fetchAll();
    return $res;
}

public static function getAllSubsAndTeachers() {
  $db = Database::getInstance()->getConnection();
  $sql = "SELECT * 
          FROM subjects_group sg
          JOIN teachers_subjects ts ON ts.subjects_subjects_id=sg.subjects_id_fk
          JOIN teachers t ON t.teachers_id=ts.teachers_teachers_id
          WHERE sg.group_year > 4 AND t.teacher_type = 2";

  $st= $db->prepare($sql);
  $st->execute();
  $result = array();
  while($row = $st->fetch()) {
      $result[$row->group_year][$row->subjects_id_fk][] = $row->teachers_id;
  }
  
  foreach($result as $groupYearId=>$groupYear) {
    foreach($groupYear as $subjectsId=>$professors) {
        $k = array_unique($result[$groupYearId][$subjectsId]);
        $res[$groupYearId][$subjectsId] =  array_values($k);
    }
  }

  return $res;
}

public static function getNewTeachingGroup() {
    $sql = "SELECT teaching_group FROM student_group order by teaching_group DESC LIMIT 1";
    $st = self::$db->prepare($sql);
    $st->execute();
    $res = $st->fetchColumn();
    return ++$res;

}


public static function getCurentProf($groupId) {
  $sql = "SELECT sg.student_group_id,sg.student_group_head_id, sg.group_year,sg.group_number,sg.teaching_group,tg.teachers_subjects_id,ts.teachers_teachers_id, ts.subjects_subjects_id, u.firstName, u.lastName
          FROM student_group sg
          JOIN teaching_group tg ON tg.teaching_group_id=sg.teaching_group
          JOIN teachers_subjects ts ON ts.teachers_subjects_id=tg.teachers_subjects_id
          JOIN users u ON u.users_id=ts.teachers_teachers_id
          WHERE sg.student_group_id=:sgid";
  $st = self::$db->prepare($sql);
  $st->bindParam(':sgid',$groupId);
  $st->execute();
  while($row = $st->fetch()) {
      $res[$row->subjects_subjects_id] = $row;
  }
  if(isset($res)) {
      return $res;
  }
}

public static function get123($groupId) {
  $sql = "SELECT *
          FROM student_group sg
          WHERE sg.student_group_id=:sgid";
  $st = self::$db->prepare($sql);
  $st->bindParam(':sgid',$groupId);
  $st->execute();
  $res = $st->fetch();
  return $res->group_year;
}

public static function getEmailFromUser($username)
{
  $sql = "SELECT email FROM users WHERE users.username = :usrname";
  $st = self::$db->prepare($sql);
  $st->bindParam(':usrname',$username);
  $st->execute();
  $res = $st->fetchColumn();
  return $res;
}
public static function updateUserToken($hash, $username){
  $sql ="UPDATE e_dnevnik_.users SET forgotpasstoken=:hash WHERE username=:username;";
  $st = self::$db->prepare($sql);
  $st->bindParam(':username',$username);
  $st->bindParam(':hash',$hash);
  $st->execute();
}
public static function getCurrentSubjects($groupId) {
  $sql = "select * 
          from teaching_group tg
          JOIN teachers_subjects ts ON ts.teachers_subjects_id = tg.teachers_subjects_id
          JOIN student_group sg ON sg.teaching_group=tg.teaching_group_id
          WHERE sg.student_group_id = :sgid AND ts.teachers_teachers_id=:tid";
  $st = self::$db->prepare($sql);
  $st->bindParam(':sgid',$groupId);
  $st->bindParam(':tid',$_SESSION['id']);
  $st->execute();
  while($row = $st->fetch()) {
    $res[] = $row->subjects_subjects_id;
  }
  return $res;
}

public static function getAllGroups() {
    $teacherId = $_SESSION['id'];
    $sql = "SELECT DISTINCT sg.student_group_id, sg.group_year, sg.group_number
            FROM teaching_group tg
            JOIN teachers_subjects ts ON ts.teachers_subjects_id=tg.teachers_subjects_id
            JOIN teachers t ON t.teachers_id=ts.teachers_teachers_id
            JOIN student_group sg ON sg.teaching_group = tg.teaching_group_id 
            WHERE t.teachers_id = :tid";
    $st = self::$db->prepare($sql);
    $st->bindParam(':tid',$teacherId);
    $st->execute();
    $res = $st->fetchAll();
    return $res;
}


public static function getFinalGrades(){
  $db = Database::getInstance()->getConnection();
  $sql = "SELECT students.name as firstName, students.lastName, grades.value, subjects.name FROM parents 
          INNER JOIN students ON parents.parents_id = students.parents_parents_id 
          INNER JOIN grades ON students.students_id = grades.students_id_fk 
          INNER JOIN subjects ON grades.subjects_id_fk = subjects.subjects_id
          WHERE parents_id = :parents_id AND grades.grade_type = 4
";
  $st = $db->prepare($sql);
  $st->bindParam(':parents_id', $_SESSION['id']);
  $st->execute();
  $result = $st->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}
public static function getAverage(){
  $db = Database::getInstance()->getConnection();
  $sql = "SELECT  avg(grades.value) as average FROM parents
  INNER JOIN students ON parents.parents_id = students.parents_parents_id 
  INNER JOIN grades ON students.students_id = grades.students_id_fk 
  INNER JOIN subjects ON grades.subjects_id_fk = subjects.subjects_id 
  WHERE parents_id = :parents_id AND grades.grade_type = 4; 
 ";
  $st = $db->prepare($sql);
  $st->bindParam(':parents_id', $_SESSION['id']);
  $st->execute();
  $result = $st->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}

public static function addNewPassword($newpass,$token)
{
  $sql ="UPDATE users SET password=:newpass WHERE forgotpasstoken = :forgotpasstoken";
  $st = self::$db->prepare($sql);
  $st->bindParam(':forgotpasstoken',$token);
  $st->bindParam(':newpass',$newpass);
  $st->execute();
}
public static function findHash($username)
{
  $sql ="SELECT users.forgotpasstoken FROM users WHERE username = :username";
  $st = self::$db->prepare($sql);
  $st->bindParam(':username',$username);
  $st->execute();
  $result = $st->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}

public static function updateGroup($data) {
    $group_id = $_POST['group_id'];
    $group_year = $_POST['group_year'];
    $group_number = $_POST['group_number'];
    $teacher = $_POST['teacher'];
    $sql = "UPDATE student_group SET group_year=:gy, group_number=:gn, student_group_head_id = :sghid WHERE student_group_id = :sgid";
    $st = self::$db->prepare($sql);
    $st->bindParam(':sgid',$group_id);
    $st->bindParam(':gy',$group_year);
    $st->bindParam(':gn',$group_number);
    $st->bindParam(':sghid',$teacher);
    $st->execute();

    $sql = "SELECT teaching_group FROM student_group WHERE student_group_id = :sgid";
    $st = self::$db->prepare($sql);
    $st->bindParam(':sgid',$group_id);
    $st->execute();
    $res = $st->fetchColumn();
    $teaching_group = $res;

    $sql = "SELECT * FROM subjects_group sg
            JOIN teachers_subjects ts ON sg.subjects_id_fk=ts.subjects_subjects_id
            WHERE group_year = :gy AND ts.teachers_teachers_id=:tid";
    $st = self::$db->prepare($sql);
    $st->bindParam(':gy',$group_year);
    $st->bindParam(':tid',$teacher);
    $st->execute();
    $res = $st->fetchAll();

    $sql = "INSERT INTO teaching_group (teaching_group_id, teachers_subjects_id) VALUES (:tg,:tsid)";
    $st = self::$db->prepare($sql);
    $st->bindParam(':tg',$teaching_group);
    foreach($res as $r) {
        $st->bindParam(':tsid',$r->teachers_subjects_id);
        $st->execute();
    }
}
public static function getSubjectList(){
  $db = Database::getInstance()->getConnection();
  $sql = "SELECT * FROM e_dnevnik_.subjects";
  $st = $db->prepare($sql);
  $st->execute();
  $subjects = $st->fetchAll();
  return $subjects;
}
public static function getStudentsByGroup($group_id){
  $db = Database::getInstance()->getConnection();
  $sql = "SELECT * FROM student_group INNER JOIN students WHERE student_group_id = :group_id;";
  $st = $db->prepare($sql);
  $st->bindParam(':group_id', $group_id);
  $st->execute();
  $result = $st->fetchAll();
  return $result;
}

public static function checkifgroupexists() {
  $sql = "SELECT * FROM student_group WHERE group_year=:gy AND group_number=:gn";
  $st=self::$db->prepare($sql);
  $st->bindParam(':gy',$_POST['group_year']);
  $st->bindParam(':gn',$_POST['group_number']);
  $st->execute();
  $res = $st->fetch();
  if(isset($res) && !empty($res)) {
   return true;
  } else return false;
  
}

}
