<?php

function checkStatus() {
  if(isset($_COOKIE['session'])) {
    $status = substr($_COOKIE['session'],-1);
    if(!is_numeric($status) || $status < 1 || $status >4) {
      header('Location:http://localhost/_egradebook/public/login.php');
      die();
    }
  } else {
    header('Location:http://localhost/_egradebook/public/login.php');
    die();
  }

  $currentFile = getCurrentFileName();
  $currentDir = getCurrentDirectoryName();
  // $permissions[1] = ['users.php','groups.php','subjects.php','schedule.php','notifications.php']; //admin
  // $permissions[2] = ['statsgroups.php','statssubjects.php']; // direktor
  // $permissions[3] = ['gradebook.php','messages.php','opendoors.php','schedule.php','group.php','proba.php','teacher.php']; // ucitelj
  // $permissions[4] = ['messages.php','notifications.php','opendoors.php']; //roditelj
  $defaultpage = [  1=> 'admin.php',
                    2=>'director.php',
                    3=>'teacher.php',
                    4=>'parent.php'];
  $dirPerm [1] = 'admin';
  $dirPerm [2] = 'director';
  $dirPerm [3] = 'teacher';
  $dirPerm [4] = 'parent';
  // if(in_array($currentFile,$permissions[$status])) {
  //   return true;
  // } 
  if($dirPerm[$status]===$currentDir) {
    return true;
  }
  else {
    $redirect = 'location:http://localhost/_egradebook/public/'.$dirPerm[$status].'/index.php';
    header($redirect);
    die();
  }

}

function getCurrentFileName() {
  $backtrace = debug_backtrace();
  $backtrace = end($backtrace);
  $string = $backtrace['file'];
  $len = strlen($string) - strrpos($string,"\\")-1;
  $file = substr($string,-$len);
  return $file;
}

function getCurrentDirectoryName() {
  $dir = getcwd();
  $len = strlen($dir) - strrpos($dir,"\\")-1;
  $dir = substr($dir,-$len);
  return $dir;
}
function getCurrentBlock() {
  $hour = date('G')+2;
  $minutes = date('i');
  $time = $hour * 3600 + $minutes*60;
  $blocks = Mapper::selectAllItems('blocks');
  foreach($blocks as $block) {
      if($time > $block->blockstart && $time < $block->blockstart+45*60) {
          $currentBlock = $block->blocks_id;
          break;
      }
  }
  if(isset($currentBlock)) {
      return $currentBlock;
  } else return 0;
}

function getCurrentDay() {
  $days=['Mon','Tue','Wed','Thu','Fri'];
  $day = date('D');
  $currentDay = array_search($day,$days) +1;
  return $currentDay;
}

function uploadAvatar() {
  $max = 500 * 1024;
  $result = array();
  $targetDir = __DIR__ . '/avatars/';
  echo $targetDir;
  try {
      $upload = new FileUpload($targetDir);
      $upload->setMaxSize($max);
      //$upload->allowAllTypes();
      $upload->setIdAsFileName();
      // rename duplicates i setIdAsFileName se medjusobno kose
      $upload->upload();
      $upload->saveAvatarInDb();
      $result = $upload->getMessages();
  } catch (Exception $e) {
      $result[] = $e->getMessage();
  }

  //ovo ispod bi trebalo da je deo aploada, jer ako ne prodje apload, ne postoji slika koja bi trebala da se risajzuje
  if(isset($result['filename']) && !$upload->allTypesCalled) {
      $file = $targetDir . $result['filename'];
      $destination = $targetDir;
      try {
          $resize = new ImageResize($file, true);
          $resize->setOutputSizes(AVATAR_SIZES);
          $result = $resize->outputImage($destination);
          $resize->deleteSimilar();
      } catch (Exception $e) {
          echo $e->getMessage();
      }
  }
}

function picSmall($img) {
  return "../private/posters/".substr_replace($img,'_300',strpos($img,'.'),0);
}

function picBig($img) {
  return "../private/posters/".substr_replace($img,'_400',strpos($img,'.'),0);
}

function urlFor($path) {
  if($path[0] != '/') {
      $path = "/" . $path;
  }
  return WWW_ROOT . $path;
}

function redirectUser($path){
  header("Location:" . $path . ".php");
}

    //za html output
function h($string) {
      return htmlspecialchars($string);
  }

  //za js output
function j($string) {
      return json_encode($string);
  }

  /*string koji se unosi se sprema za url...
  ovo se koristi zbog sledeceg problema:
  ukoliko korisnik unese u polje koje kaci na na link npr string '&joe'
  URL bi ovako izgledao : localhost/index.php?name=&joe
  taj "name=&" deo moze da napravi problem pri procesuiranju forme.
  Zato bi uz urlencode, URL izgledao ovako:
  localhost/index.php?name=%26joe sto ne bi prouzrokovalo probleme
  */
function u($string) {
      return urlencode($string);
  }

  function redirectByStatus() {
    $status = substr($_COOKIE['session'], -1);
    switch ($status) {
      case 1:
        header('Location: http://localhost/_egradebook/public/admin/index.php');
        break;
      case 2:
        header('Location: http://localhost/_egradebook/public/director/pick_page.php');
        break;
      case 3:
        header('Location: http://localhost/_egradebook/public/teacher/index.php');
                break;
      case 4:
        header('Location: http://localhost/_egradebook/public/parent/parent.php');
          break;			
      }
  }

  function showMessage() {
    if(isset($_SESSION['message'])) {
        echo "<div class='row'>
                    <div class='alert alert-success'>
                        {$_SESSION['message']}
                    </div>
               </div>";
        unset($_SESSION['message']);
    }
  }