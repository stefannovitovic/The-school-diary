<?php

class Sanitizer {
    public static $loginFields         = ['username','password'];
    public static $addGroupFields      = ['group_year','group_number','add_group'];
    public static $editGroupFields     = ['group_id','group_year','group_number','edit_group'];
    public static $deleteGroupFields   = [''];
    public static $addSubjectFields    = [''];
    public static $editSubjectFields   = [''];
    public static $sendMessageFields   = [''];
    public static $updateScheduleFields= [''];
    public static $addUserFields       = ['username','password','ch_password','status','name','lastname','submit'];
    public static $editUserFields      = [''];
    public static $deleteUserFields    = [''];
    public static $addExcuseFields     = [''];
    public static $openDoorsFields     = ['dtime','reason','submit'];
    public static $gradeFields         = ['grade_type','ocene','semestar','value','submit'];



    // da li je zahtev get, vraca true ili false
    public static function ifGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    //da li je zahtev post, vraca true ili false
    public static function ifPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }


    public static function checkFormFields($form) {
        $errors = array();
        foreach(self::$$form as $field) {
            if(!isset($_POST[$field]) || !self::hasPresence($_POST[$field])) {
                    $errors[$field] = "'".$field."' can't be blank";
            }
        }
        if(empty($errors)) {
            return true;
        } else return false;
    }

    public static function hasPresence($value) {
        $trimmed_value = trim($value);
        return isset($trimmed_value) && $trimmed_value !== "";
    }

    public static function hasLength($value, $options=[]) {
        if(isset($options['max']) && (strlen($value) > (int)$options['max'])) {
            return false;
        }
        if(isset($options['min']) && (strlen($value) < (int)$options['min'])) {
            return false;
        }
        if(isset($options['exact']) && (strlen($value) != (int)$options['exact'])) {
            return false;
        }
        return true;
    }

    public static function hasNumber($value, $options=[]) {
        if(!is_numeric($value)) {
            return false;
        }
        if(isset($options['max']) && ($value > (int)$options['max'])) {
            return false;
        }
        if(isset($options['min']) && ($value < (int)$options['min'])) {
            return false;
        }
        return true;
    }

    public static function CSRFToken() {
        return bin2hex(random_bytes(32));
    }

    public static function createCSRFToken() {
        $token = self::CSRFToken();
        $_SESSION['token']      = $token;
        $_SESSION['tokenTime']  = time();
        return $token;
    }

    public static function deleteCSRFToken() {
        $_SESSION['token'] = null;
        $_SESSION['tokenTime'] = null;
        return true;
    }

    public static function CSRFTokenTag() {
        $token = self::createCSRFToken();
        return "<input type='hidden' name='token' value='".$token."'>";
    }

    public static function isCSRFTokenValid() {
        if(self::hasPresence($_POST['token'])) {
            $formToken = $_POST['token'];
            $userToken = $_SESSION['token'];
            return self::checkCSRFTokenTime() && hash_equals($formToken, $userToken);
        }
        else return false;
    }

    public static function checkCSRFTokenTime() {
        $maxTime = 60*60; // 1 sat
        if(self::hasPresence($_SESSION['tokenTime'])) {
            $userTime = $_SESSION['tokenTime'];
            return ($userTime + $maxTime) >= time();
        } else {
            self::deleteCSRFToken();
            return false;
        }
    }

    public static function redirect($target) {
        //uraditi
    }

    //da li je request sa istog servera
    public static function isRequestLocal() {
        if(!isset($_SERVER['HTTP_REFERER'])) {
            return true;
        } else {
            $refererHost = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
            $serverHost = $_SERVER['HTTP_HOST'];
            return ($refererHost == $serverHost) ? true : false;
        }
    }

    //tek treba da se sredi
    public static function sqlEscape($string) {
//        if($db) { //ako postoji konekcija ka bazi, treba srediti
//            return mysqli_real_escape_string($db, $string);
//        }
    }
}