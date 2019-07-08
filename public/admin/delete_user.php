<?php 
//ob_start();
require ("../../private/initialize.php");
?>
<div id="page-wrapper">

    <div class="container-fluid">

     <!-- Page Heading -->
     <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Delete User</h1>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i> Delete User
                </li>
            </ol>
        </div>
    </div>
    <div class="col-lg-8">
            <form action="delete_user.php" method="POST" autocomplete="off">
                <div class="form-group">
                    <input type="hidden" autocomplete="off"> 
                    <label for="find_user">Search for User by Username:</label><br>
                    <input type="text" name="username" class="form-control" id="username" value="" autocomplete="off">
                    <div id="search_result">
                        <div id="result" class="col-lg-4">
                    </div>
                    </div><br>
                    <input type="submit" name="submit" value="Search" class="btn btn-primary">
                </div>
            </form>
    

<?php

if(isset($_POST['submit'])){
    if(Mapper::checkUser($_POST) === false){
        echo "<p>No such user</p>";
        exit;
    } else {
        $res = Mapper::showUser($_POST);
        $user = [];
        $teacher_subjects = [];

        foreach($res as $users => $user){
            $user = $user;
            if(isset($user->subjects_subjects_id)){
                $teacher_subjects[] = $user->subjects_subjects_id;
            }
        }

        if(empty($teacher_subjects)){
            $teacher_subjects = null;
        }
    }
    
    


?>
</div>
<div class="col-lg-8">
    <form action="delete_user.php" method="get">
        <table class="table">
            <tr>
                <th class=".thead-light">UserID</th>
                <th class=".thead-light">Username</th>
                <th class=".thead-light">Status</th>
                <th class=".thead-light"></th>
            </tr>
            <tr>
                <td><?php echo $user->users_id; ?></td>
                <td><?php echo $user->username; ?></td>
                <td><?php echo $user->status; ?></td>
                <td><a href="<?php echo "delete_user.php?id=" . $user->users_id ?>" class="btn btn-primary">Delete User</a></td>
            </tr>
        </table>
    </form>
</div>

<?php 
}

    if(isset($_GET['id'])){
        $user_id = $_GET['id'];

        echo Mapper::deleteUser($user_id);
    }
?>
   
    </div>
<!-- /.container-fluid -->

</div>
<script>

    $(document).ready(function(){
        
        $('#result').hide(); 
        $('#username').keyup(function(){
            $('#result').show(); 
            var username = $('#username').val();
            $.post("find_user_by_username.php", {
                inputVal : username
            }, function(data, status){
                $('#result').html(data);            
            });
        });

        $(document).on('click', 'p.user', function(){
            var text = $.text(this);
            $('#username').val(text);
            $('#result').hide();            
        });
            
    });

</script>
<?php include("../../private/styles/includes/footer.php"); ?>