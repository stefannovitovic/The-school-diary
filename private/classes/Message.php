<?php



class Message{

    protected static $table = "messages";
    public $messages_id;
    public $message;
    public $time;
    public $teacher_id;
    public $parent_id;
    public $sender_name;
    public static $db;

    function __construct($args = [],$status=false){
        $this->status = $status;
        switch($this->status){
            case "teacher":
                
                $this->message = isset($args['message']) ? $args['message'] : '';
                $this->teacher_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';
                $this->parent_id = isset($args['user_id']) ? $args['user_id'] : '';
                $this->sender_name = isset($_SESSION['username']) ? $_SESSION['username'] : '';
            break;
            case "parent":
                
                $this->message = isset($args['message']) ? $args['message'] : '';
                $this->teacher_id = isset($args['user_id']) ? $args['user_id'] : '';
                $this->parent_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';
                $this->sender_name = isset($_SESSION['username']) ? $_SESSION['username'] : '';
            break;
        }
    }
    public static function findAllMessages($id, $name){
        self::$db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM " . self::$table . " WHERE " . $name . " = " . $id;
        $result_set = self::findThisQuery($sql);
        return $result_set;
        
    }

    public static function findAllMessagesByID($teacher_id, $parent_id){
        self::$db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM " . self::$table . " WHERE teacher_id = " . $teacher_id . " AND parent_id = " . $parent_id . " ORDER BY time ASC";
        $result_set = self::findThisQuery($sql);
        return $result_set;
        
    }

    public static function findThisQuery($sql){
    
        self::$db = Database::getInstance()->getConnection();
        $st = self::$db->prepare($sql);
        $st->execute();
        $result = $st->fetchAll(PDO::FETCH_ASSOC);
        $the_object_array = array();
        foreach ($result as $row){
            $the_object_array[] = self::instantation($row);
        }

        return $the_object_array;
     }

     public static function instantation($result){
         $new_object = new self;

         foreach ($result as $attribute => $value){
             if ($new_object->has_attribute($attribute)){
                 $new_object->$attribute = $value;
             }
        }
        return $new_object;
     }

     public static function has_attribute($attribute){
         $test_object = new self;
         $object_properties = get_object_vars($test_object);
         return array_key_exists($attribute, $object_properties);
     }
     public function createMessage(){
        self::$db = Database::getInstance()->getConnection();
        $sql = "INSERT INTO " . self::$table . "(messages_id, message, time, teacher_id, parent_id, sender_name) VALUES (NULL, '" . $this->message . " ' , now() , '" . $this->teacher_id . " ', '" . $this->parent_id . " ' , '" . $this->sender_name . "')";
        $st = self::$db->prepare($sql);
        $result = $st->execute();

        if($result){
            return true;
        }else{
            return false;
        }
     }

     public static function delete($id){
         self::$db = Database::getInstance()->getConnection();
         $sql = "DELETE FROM " . self::$table . " WHERE messages_id = :id ";
         $st = self::$db->prepare($sql);
         $st->bindParam(':id', $id);
         return $result = $st->execute();
     }

     public static function selectTeacherByParentID($id){
         self::$db = Database::getInstance()->getConnection();
         $sql = "SELECT parents.parents_id, teachers.teachers_id FROM parents INNER JOIN students ON parents.parents_id = students.parents_parents_id INNER JOIN student_group ON student_group.student_group_id = students.group_id  INNER JOIN teachers ON student_group.student_group_head_id = teachers.teachers_id WHERE parents.parents_id = :id";
         $st = self::$db->prepare($sql);
         $st->bindParam(':id', $id);
         $st->execute();
         $result = $st->fetchAll();
         return $result;

     }
     public static function selectParentsByTeacherID($id){
         self::$db = Database::getInstance()->getConnection();
         $sql = "SELECT firstName, users.lastName as lN, parents.parents_id FROM `teachers`
                INNER JOIN student_group ON teachers.teachers_id = student_group.student_group_head_id 
                INNER JOIN students on student_group.student_group_id = students.group_id 
                INNER JOIN parents on students.parents_parents_id = parents.parents_id
                INNER JOIN users ON parents.parents_id = users.users_id
                WHERE teachers.teachers_id = :id";
         $st = self::$db->prepare($sql);
         $st->bindParam(':id', $id);
         $st->execute();
         $result = $st->fetchAll();
         return $result;
     }
     

}


