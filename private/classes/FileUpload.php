<?php

//klasa za upload fajlova

class FileUpload {

    protected $destination;
    protected $messages = array();
    protected $maxSize = 512000;
    protected $permittedTypes = array (
        'image/jpeg',
        'image/pjpeg',
        'image/gif',
        'image/png',
        'image/webp'
    );
    protected $newName;
    protected $posterName;
    protected $typeCheckingOn = true;
    protected $notTrusted = array('bin', 'cgi', 'exe', 'js', 'pl', 'php', 'py', 'sh');
    protected $suffix = '.upload';
    protected $renameDuplicates;
    protected $idName = false;
    public    $db;
    public    $allTypesCalled = false;

    public function __construct($uploadFolder) {
        if(!is_dir($uploadFolder) || !is_writable($uploadFolder)) {
            throw new Exception('uploadfolder must be valid folder');
        }
        if($uploadFolder[strlen($uploadFolder)-1] != '/') {
            $uploadFolder .= '/';
        }
        $this->destination = $uploadFolder;
        $this->db = Database::getInstance()->getConnection();
    }

    public function setMaxSize($bytes) {

        $serverMax = self::convertToBytes(ini_get('upload_max_filesize'));
        if($bytes > $serverMax) {
            throw new Exception('Maximum size larger than server limit:'.self::convertFromBytes($serverMax));
        }
        if(is_numeric($bytes) && $bytes > 0 ) {
            $this->maxSize = $bytes;
        }
    }

    public static function convertToBytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        if (in_array($last, array('g', 'm', 'k'))){
            // Explicit cast to number
            $val = (float) $val;
            switch ($last) {
                case 'g':
                    $val *= 1024;
                case 'm':
                    $val *= 1024;
                case 'k':
                    $val *= 1024;
            }
        }
        return $val;
    }

    public static function convertFromBytes($bytes) {
        $bytes /= 1024;
        if ($bytes > 1024) {
            return number_format($bytes/1024, 1) . ' MB';
        } else {
            return number_format($bytes, 1) . ' KB';
        }
    }

    public function allowAllTypes($suffix = null) {
        $this->allTypesCalled = true;
        $this->typeCheckingOn = false;
        if(!is_null($suffix)) {
            if(strpos($suffix, '.') ===0 || $suffix == '') {
                $this->suffix = $suffix;
            } else {
                $this->suffix = ".$suffix";
            }
        }
    }

    public function upload($renameDuplicates = true) {
        $this->renameDuplicates = $renameDuplicates;
        $uploaded = current($_FILES);
        
        if(is_array($uploaded['name'])) {
            foreach($uploaded['name'] as $key => $value) {
                $currentFile['name']        = $uploaded['name'][$key];
                $currentFile['type']        = $uploaded['type'][$key];
                $currentFile['tmp_name']    = $uploaded['tmp_name'][$key];
                $currentFile['error']       = $uploaded['error'][$key];
                $currentFile['size']        = $uploaded['size'][$key];
                if($this->checkFile($currentFile)) {
                    $this->moveFile($currentFile);
                }
            }
        } else {
            if($this->checkFile($uploaded)) {
                $this->moveFile($uploaded);
            }
        }

    }

    public function setIdAsFileName() {
        $this->idName = true;
    }

   

    public function setPosterName($name) {
        $this->posterName = $name;
    }

    public function saveAvatarInDb() {
        $sql = "UPDATE users SET picture = :avatar where users_id = :id ";
        $st = $this->db->prepare($sql);
        $id = 5;
        $st->bindParam(':avatar', $this->messages['filename'], PDO::PARAM_STR);
        $st->bindParam(':id', $id, PDO::PARAM_STR);
        $st->execute();
    }

    protected function checkFile($file) {
        if ($file['error'] != 0) {
            $this->getErrorMessage($file);
            return false;
        }
        if(!$this->checkSize($file)) {
            return false;
        }
        if($this->typeCheckingOn) { // proveravamo tip samo ako je typechecking on
            if(!$this->checkType($file)) { // php ne verifikuje filetype reportovan u files superglobalu, koristi rec od brauzera, tako da ovo moze biti spuftovano
                return false;
            }
        }
        $this->checkName($file);
        return true;
    }

    protected function checkType($file) {
        if(in_array($file['type'], $this->permittedTypes)) {
            return true;
        } else {
            $this->messages[] = $file['name'] . 'is not of a supported format';
            return false;
        }
    }

    public function getMessages() {
        return $this->messages; // geteri i seteri samo za stvari koje ce se nekad koristiti van klase
    }

    protected function checkSize($file) {
        if($file['size']==0) {
            $this->messages[] = $file['name'] . 'is empty';
            return false;
        } else if($file['size'] > $this->maxSize) {
            $this->messages[] = $file['name']. 'is too big(max: ' . self::convertFromBytes($this->maxSize).')';
            return false;
        } else {
            return true;
        }
    }

    protected function checkName($file) {
        if($this->idName) {
            $nameParts = pathinfo($file['name']);
            $id = md5($_POST['student_id'] . '-' . $_POST['class_id']);
            $this->newName = $id.".".$nameParts['extension'];
            return;
        } else if($this->posterName) {
                $nameParts = pathinfo($file['name']);
                $this->newName = $this->posterName.".".$nameParts['extension'];
                return;
            }
        $this->newName = null;
        $noSpaces = str_replace(' ', '_', $file['name']);
        if($noSpaces != $file['name']) {
            $this->newName = $noSpaces;
        }
        $nameParts = pathinfo($noSpaces);
        $extension = isset($nameParts['extension']) ? $nameParts['extension'] : '';
        if(!$this->typeCheckingOn && !empty($this->suffix)) {
            if(in_array($extension, $this->notTrusted) || empty($extension)) {
                $this->newName = $noSpaces . $this->suffix;
            }
        }
        if($this->renameDuplicates) {
            $name = isset($this->newName) ? $this->newName : $file['name'];
            $existing = scandir($this->destination);
            if(in_array($name,$existing)) {
                $i = 1;
                do {
                    $this->newName = $nameParts['filename'] . '_' . $i++;
                    if(!empty($extension)) {
                        $this->newName .= '.'.$extension;
                    }
                    if(in_array($extension,$this->notTrusted)) {
                        $this->newName .= $this->suffix;
                    }
                } while(in_array($this->newName, $existing));
            }
        }
    }

    protected function getErrorMessage($file) {
        switch($file['error']) {
            case 1:
            case 2:
                $this->messages[] = 'File'.$file['name'].' is too big: (max: ' . self::convertFromBytes($this->maxSize).')';
                break;
            case 3:
                $this->messages[] = 'File'.$file['name'].' was only partially uploaded';
                break;
            case 4:
                $this->messages[] = 'No file submitted.';
                break;
            default:
                $this->messages[] = 'There was a problem uploading'.$file['name'].'file';
        }
    }

    protected function moveFile($file) {
        $fileName = isset($this->newName) ? $this->newName : $file['name'];
        $success = move_uploaded_file($file['tmp_name'], $this->destination.$fileName);
        if($success) {
            $result = $file['name'] . 'was uploaded successfully';
            if(!is_null($this->newName)) {
                $result .=', and was renamed ' . $this->newName.'.';
            }
            $this->messages[] = $result;
            $this->messages['filename']=$fileName;
        } else {
            $this->messages[] = 'Could not upload' . $file['name'];
        }

    }


}