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
                Update User</h1>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i> Update User
                </li>
            </ol>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="form-group">
            <form action="update_user.php" method="POST" autocomplete="off">
                <input type="hidden" autocomplete="off"> 
                <label for="find_user">Search for User by Username:</label><br>
                <input type="text" id="sr_username" name="username" class="form-control" value="">
                <div id="search_result">
                    <div id="result" class="col-lg-4">
                    </div>
                </div><br>
                <input type="submit" name="submit" value="Search" class="btn btn-primary">
            </form>
        </div>
        <?php

        if(isset($_POST['submit'])){
            Mapper::set_database();
            if(Mapper::checkUser($_POST) === false){
                echo "No such user";
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
            <form action="update_user.php" method="POST" id="update_user">
                <table class="table">
                    <tr>
                        <th style="width:400px;">UserID</th>
                        <td><input type="text" name="users_id" id="users_id" value="<?php echo $user->users_id; ?>" class="form-control"></td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td><input type="text" name="username" id="username" value="<?php echo $user->username; ?>" class="form-control"></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td><input type="text" name="firstName" id="firstName" value="<?php echo $user->firstName; ?>" class="form-control"></td>
                    </tr>
                    <tr>
                        <th>Lastname</th>
                        <td><input type="text" name="lastName" id="lastName" value="<?php echo $user->lastName; ?>" class="form-control"></td>
                    </tr>                   
                    <tr id="st_JMBG">
                        <th>Students JMBG</th>
                        <td><input type="text" name="student_JMBG" id="student_JMBG" value="<?php echo (isset($user->student_JMBG)) ? $user->student_JMBG : $user->student_JMBG = '' ?>" class="form-control"></td>
                     </tr>
                     <tr>
                        <th>Status</th>
                        <td>
                            <input type="text" name="status" id="status" value="<?php echo $user->status; ?>" class="form-control">
                            <input type="hidden" name="was_status" id="was_status" value="">
                        </td>
                    </tr>                    
                    <tr id="teacher_role">
                        <th>Teacher Role</th>
                        <td>
                            <input type="text" name="teacher_type" id="teacher_type" value="<?php echo (isset($user->teacher_type)) ? $user->teacher_type : $user->teacher_type = '' ?>" class="form-control">
                            <input type="hidden" name="was_teacher_type" id="was_teacher_type" value="<?php echo (isset($user->teacher_type)) ? $user->teacher_type : $user->teacher_type = '' ?>">
                        </td>

                    </tr>
                    <tr id="subjects_list">                        
                        <th>Teacher Subjects:</th>
                        <td>
                            <div id="teachers_subjects_list"  multiple='multiple' size='10'>
                            <?php
                                $subjects = Mapper::getSubjectList();
                                if(!empty($teacher_subjects) && is_array($teacher_subjects)){                                   
                                    foreach($subjects as $subject){
                                        if(in_array($subject->subjects_id, $teacher_subjects)){
                                            echo "<p><input type='checkbox' class='ch_box' name='subject[]' value='" . $subject->subjects_id ."' checked><span>&nbsp;" . $subject->name ."</span></p>";
                                        } else {
                                            echo "<p><input type='checkbox' class='ch_box' name='subject[]' value='" . $subject->subjects_id ."'><span>&nbsp;" . $subject->name ."</span></p>";
                                        }                                        
                                    }
                                } else {
                                    foreach($subjects as $subject){
                                        echo "<p><input type='checkbox' class='ch_box' name='subject[]' value='" . $subject->subjects_id . "'><span>&nbsp;" . $subject->name . "</span></p>";
                                    }
                                }
                                ?>
                            </div>
                        </td>                        
                    </tr>           
                    <tr>
                        <td></td>
                        <td><input type="submit" name="upd_submit" value="Save Changes" class="btn btn-primary"></td>
                    </tr>
                </table>
            </form>
            <?php
        }

        if(isset($_POST['upd_submit'])){
            $user_id = $_POST['users_id'];
            $was_status = $_POST['was_status'];
            $status = $_POST['status'];


             
            if($status === $was_status){
                Mapper::updateUserByStatus($status);
            } else {

               
                switch($was_status){
                    case 2:
                        Mapper::deleteDirector($user_id);                        
                        break;
                    case 3:
                        Mapper::deleteTeacher($user_id);
                        break;
                    case 4:
                        Mapper::deleteParent($user_id);
                        break;
                }

                Mapper::addUserByStatus($status, $_POST);
                Mapper::updateUserByStatus($status); 
            }
        }
        ?>

</div>
<!-- /.container-fluid -->

</div>
<script>

    $(document).ready(function(){

        var status = $('#status').val();
        var was_status = $('#was_status').val(status);
        var username = $('#username').val();
        var teacher_type = $('#teacher_type').val();
        var was_teacher_type = $('#teacher_type').val();
        
        if(status == 4){
            $('#st_JMBG').show(500);
        } else {
            $('#st_JMBG').hide();
        }

        if(status == 3){
            if(teacher_type == 2){
                $('#teacher_role').show(500);            
                $('#subjects_list').show(500);
            } else {
                $('#teacher_role').show(500);
                $('#subjects_list').hide();
            }
            
        } else {
            $('#teacher_role').hide(100);
            $('#subjects_list').hide(100);
        }

        if(status == 2){
            $('#st_JMBG').hide(100);
            $('#teacher_role').hide(100);
            $('#subjects_list').hide(100);
        }

        $('#result').hide(); 

        $('#sr_username').keyup(function(){
            $('#result').show();
            var username = $('#sr_username').val();
            $.post("find_user_by_username.php", {
                inputVal : username
            }, function(data, status){
                $('#result').html(data);            
            });
        });

        $(document).on('click', 'p.user', function(){
            var text = $.text(this);
            $('#sr_username').val(text);
            $('#result').hide();            
        });

        function styleOnTrue(obj){
            $(obj).css({
                "background-color" : "#FFDACD",
                "border-color" : "#FF9C83"
            });
        }

        function styleOnFalse(obj){
            $(obj).css({
                "background-color" : "#FFFFFF",
                "border-color" : "#CCCCCC"
            });
        }

        var users_id = $('#users_id').val();
        $(document).on('input', '#users_id', function(){
            var user_id = $('#users_id').val();
            if(user_id != users_id){
                styleOnTrue($('#users_id'));
                $(this).attr('placeholder', 'Cannot change User ID.');                
            } else {
                styleOnFalse($('#users_id'));
            }
        });

        $(document).on('focusout', '#users_id', function(){
            $(this).val(users_id);
            styleOnFalse($('#users_id'));
        });

        $(document).on('input', '#username', function(){
            var username = $('#username').val();
            if(username.length < 2){
                styleOnTrue($('#username'));
                $(this).attr('placeholder', 'Must be at least 2 characters long.');
            } else {
                styleOnFalse($('#username'));
            }
        });
        
        $(document).on('focusout', '#status', function(){
            var was_status = $('#status').val();
            if(status != was_status){
                var conf = confirm("You are about to change status for user: " + username + "\nAre you sure?");
                if(conf){
                    if(was_status == 2){
                        $('#st_JMBG').hide(300);
                        $('#teacher_role').hide(300);
                        $('#subjects_list').hide(300);
                    }

                    if(was_status == 3){
                        $('#teacher_role').show(500);
                        $('#subjects_list').show(500);
                        $('#st_JMBG').hide(100);
                    }

                    if(was_status == 4){                        
                        $('#teacher_role').hide(100);
                        $('#subjects_list').hide(100);
                        $('#st_JMBG').show(500);
                    }
                }
                status = was_status;
                return status;
            }
        });

        $(document).on('focusout', '#teacher_role', function(){
            var teacher_type = $('#teacher_type').val();
            if(teacher_type == 1){
                $('#subjects_list').hide(300);
            }
            if (teacher_type == 2){
                $('#subjects_list').show(500);
            }
            return teacher_type;           
        });
                
        $(document).on('input', '#student_JMBG', function(){
            var student_JMBG = $('#student_JMBG').val();            
            var reg = new RegExp('^[1-3][0-9]{12}$');
            if(!reg.test(student_JMBG)){
                    styleOnTrue($('#student_JMBG'));
                    $(this).attr("placeholder","Must be number and 13 characters long.");                        
            } else {
                styleOnFalse($('#student_JMBG'));
            }
        });

        $(document).on('input', '#status', function(){
            var status = $(this).val();
            var reg = new RegExp('^[2-4]$');
            if(!reg.test(status)){
                styleOnTrue($('#status'));
                $(this).attr("placeholder","Acceptable values: 2, 3 or 4.");
            } else {
                styleOnFalse($('#status'));
            }
        });

        $(document).on('input', '#teacher_type', function(){
            var teacher_type = $(this).val();
            var reg = new RegExp('^[1-2]$');
            if(!reg.test(teacher_type)){
                styleOnTrue($('#teacher_type'));
                $(this).attr("placeholder","Acceptable values: 1 or 2.");
            } else {
                styleOnFalse($('#teacher_type'));
            }
        });

        $(document).on('input', '#firstName', function(){
            var firstName = $(this).val();
            if(firstName.length < 2){
                styleOnTrue($('#firstName'));
                $(this).attr("placeholder", "Must be at least two characters long.");
            } else {
                styleOnFalse($('#firstName'));
            }
        });

        $(document).on('input', '#lastName', function(){
            var lastName = $(this).val();
            if(lastName.length < 2){
                styleOnTrue($('#lastName'));
                $(this).attr("placeholder", "Must be at least two characters long.");
            } else {
                styleOnFalse($('#lastName'));
            }
        });        
        
        $('#update_user').submit(function(e){
            /* e.preventDefault(); */
            var err = [];
            var student_JMBG_reg = new RegExp('^[1-3][0-9]{12}$');
            var student_group_reg = new RegExp('^[1-8]$');
            var status_reg = new RegExp('^[2-4]$');
            var teacher_type_reg = new RegExp('^[1-2]$');
            var user_id = $('#users_id').val();            

            if((status == 2 || status == 3 || status == 4) && teacher_type == 2){
                var checkbox_arr = $(".ch_box:checked");
                var selected_chbox = [];
                checkbox_arr.each(function(){                  
                    selected_chbox.push($(this).val());               
                });
            }

            if(teacher_type == 2 && selected_chbox.length < 1){
                err.push($('#teachers_subjects_list').attr('id'));
            }

            if(status == 4 && ($('#student_JMBG').val() === "")){
                err.push($('#student_JMBG').attr('id'));
            }            
            
            if(user_id != users_id){
                err.push($('#users_id').attr('id'));
            }

            if($('#username').val().length < 2){
                err.push($('#username').attr('id'));            
            }

            if(!status_reg.test(status)){
                err.push($('#status').attr('id')); 
            }

            /* if(!teacher_type_reg.test(teacher_type)){
                if($('#teacher_type').val() !== ''){
                    err.push($('#teacher_type').attr('id'));
                } else {
                    err.push();
                }                 
            } */
            
            if(!student_JMBG_reg.test($('#student_JMBG').val())){
                if($('#status').val() == 4){
                    err.push($('#student_JMBG').attr('id'));
                } else {
                    err.push();
                } 
            }

            if($('#firstName').val().length < 2){
                err.push($('#firstName').attr('id'));
            }

            if($('#lastName').val().length < 2){
                err.push($('#lastName').attr('id'));
            }           
            console.log(teacher_type);
            console.log(err);
            if(err.length == 0){
                e.submit();
            } else {                
                e.preventDefault();
            }          
        });
    });
    
</script>
<?php include("../../private/styles/includes/footer.php"); ?>

