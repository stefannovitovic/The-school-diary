<?php

class User extends Manager 
{
    protected $table_name   = "users";
    protected $columns      = ['users_id','status','username','password','firstName','lastName'];
    public $joins_id = 0;
    public $users_id;
    public $status;
    public $username;
    public $password;
    public $firstName;
    public $lastName;
    public $fillData;
    public $groupYears = array();
    public $rememberme;

    public function __construct(StdClass $data) {
        $this->db           = Database::getInstance()->getConnection();
        if($this->joins_id != 0) {
            $this->joins        = $this->getJoins();
        }
        $this->users_id     = isset($data->users_id)    ? $data->users_id   : '';
        $this->status       = isset($data->status)      ? $data->status     : '';
        $this->username     = isset($data->username)    ? $data->username   : '';
        $this->password     = isset($data->password)    ? $data->password   : '';
        $this->firstName    = isset($data->firstName)   ? $data->firstName  : '';
        $this->lastName     = isset($data->lastName)    ? $data->lastName   : '';
        $this->rememberme   = isset($data->rememberme)  ? true              : false;
    }

}
