<?php

class Session {
    public $id = "";
    public $loginToken = "";
    private $cookieSessName = "session";
   // private $cookieIdName   = "id";
    private $cookieTimeName = "timestamp";
    private $renewalTime    = 60; // 1 minut
    private $expirationTime = 60*60*24*30; // 1 mesec
    private $expireOnClose  = TRUE;
    private $userId         = FALSE;
    private $login          = FALSE;
    private $ipFrag         = FALSE;
    private $userAgent      = FALSE;
    private $secureCookie   = FALSE;
    private $data           = array();
    private $hash           = FALSE;

    public function __construct($args = []) {
        session_start();
        Mapper::set_database();
        if(isset($args['id'])) { // proverava da li postoji kljuc id u nizu, on postoji samo u slucaju logina, tj. tada se salje
            $this->deleteCookie();
            $this->login        = true;
            $this->id           = $args['id'];
            $this->userStatus   = $args['userStatus'];
            $this->inputSessionData();
        } else {
            //vadimo user id i status iz vrednosti kolacica
            $this->getUserDataFromCookie();
        }
    }
    public function startSession() {
        if($this->login) {
            if (isset($_POST['rememberme'])) {
                $this->expireOnClose = FALSE;
            }
            $this->deleteCookie();
            $this->setCookie();
        } else {
            if ($this->readCookie()) {
                $_SESSION['id'] = $this->id;
                $_SESSION['status'] = $this->userStatus;
                $this->regenerateCookie();
                Mapper::getAdditionalData();
            } else {
                $this->deleteCookie();
                header("Location:http://localhost/_egradebook/public/login.php");
                die();
            }
        }
    }

    protected function getUserDataFromCookie() {
        //ukoliko ne postoje kolacici, redirektuj na login
        if(isset($_COOKIE[$this->cookieSessName])) {
            $this->id = substr($_COOKIE[$this->cookieSessName],0,strpos($_COOKIE[$this->cookieSessName],'b'));
            $this->userStatus = substr($_COOKIE[$this->cookieSessName], -1);
        }
        else {
            $this->deleteCookie();
            header("Location:http://localhost/_egradebook/public/login.php");
            die();
        }
    }

    protected function readCookie() {
        if(isset($_COOKIE[$this->cookieSessName], $_COOKIE[$this->cookieTimeName])) {
            if($dbtoken = Mapper::getDbLoginToken($this->id)) {
                if(strcmp($dbtoken,$_COOKIE[$this->cookieSessName]) ===0) {
                    return true;
                }
            }
        }
        return false;
    }
    protected function regenerateCookie() {
        if(time() - $_COOKIE[$this->cookieTimeName] > $this->renewalTime) {
            $this->getUserDataFromCookie();
            $this->deleteCookie(true);
            $this->setCookie();
        }
    }
    protected function setCookie() {
        //metoda za postavljanje kolacica korisniku nakon sto prodje sve provere u 
        $this->cookieHash();
        setcookie(
            $this->cookieSessName,
            $this->hash,
            ($this->expireOnClose) ? 0 : time() + $this->expirationTime,
            NULL,
            NULL,
            $this->secureCookie, // samo https ako hoces
            TRUE
        );

        setcookie(
            $this->cookieTimeName,
            time(),
            ($this->expireOnClose) ? 0 : time() + $this->expirationTime,
            NULL,
            NULL,
            $this->secureCookie,
            TRUE
        );
        //$this->loginToken = $this->hash;
        Mapper::setLoginToken($this->id,$this->hash);
    }
    protected function cookieHash() {
        // kreiranje hash-a za kolacic, taj hes se takodje unosi i u bazu
        $salt = "54b874a965ded62";
        $string     =   random_bytes(30).
                        memory_get_usage().
                        $this->getUserIp().
                        bin2hex(openssl_random_pseudo_bytes(10)).
                        lcg_value().
                        getmypid().
                        time().
                        $salt.
                        mt_rand(0,mt_getrandmax()).
                        microtime().
                        serialize($_ENV).
                        bin2hex(openssl_random_pseudo_bytes(10));
        $this->hash = $this->id."b".hash('SHA512',uniqid($string,true)).$this->userStatus;
    }
    protected function deleteCookie($regenerate = false) {
        //za logout - brisanje kolacica
        setcookie(
            $this->cookieSessName,
            "",
            time()-3600
        );
        setcookie(
            $this->cookieTimeName,
            "",
            time()-3600
        );
        if(!$regenerate) {
            session_destroy();
        }
    }
    protected function getUserIp() {
        //koristi se deo ip adrese za kreiranje hash-a
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return substr($ip, 0, 5);
    }

    protected function inputSessionData() {
        $_SESSION['status'] = $this->userStatus;
        $_SESSION['id']     = $this->id;
        $_SESSION['username'] = $args['username'];
        Mapper::getAdditionalData();
    }
}