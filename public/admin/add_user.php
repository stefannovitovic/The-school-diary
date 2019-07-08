<?php 
ob_start();

require ("../../private/initialize.php");
?>
<div id="page-wrapper">
    <div class="container-fluid">

     <!-- Page Heading -->
     <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Add User
                    </h1>
                    <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                        </li>
                        <li class="active">
                            <i class="fa fa-file"></i> Add User
                        </li>
                    </ol>
                </div>
            </div>
            
            <div class="col-lg-12">
                <form action="add_user.php" method="POST" id="form_add_user">
                    <div class="col-lg-8">
                    
                        <div class="form-group">
                                <div>
                                    <label for="username">Username:</label>
                                    <input type="text" name="username" id="username" class="form-control">
                                </div>
                                <div>
                                    <label for="password">Password:</label>
                                    <input type="text" name="password" id="password" class="form-control">
                                </div>
                                <div>
                                    <label for="ch_password">Retype password:</label>
                                    <input type="text" name="ch_password" id="ch_password" class="form-control">
                                </div>
                                <div>
                                    <label for="status">User status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option> - Choose status - </option>
                                        <option value="4">Parent</option>
                                        <option value="3">Teacher</option>
                                        <option value="2">Director</option>
                                    </select>
                                </div>
                                <div id="st_JMBG">
                                    <label for="student_JMBG">Insert Student JMBG:</label><br>
                                    <input type="text" name="student_JMBG" id="student_JMBG" class="form-control">
                                </div>
                                <div id="st_group_year">
                                    <label for="student_group_year">Insert Group Year:</label><br>
                                    <input type="text" name="student_group_year" id="student_group_year" class="form-control">
                                </div>
                                <div id="st_group_number">
                                    <label for="student_group_number">Insert Group Number:</label><br>
                                    <input type="text" name="student_group_number" id="student_group_number" class="form-control">
                                </div>
                                <div id="teacher_role">
                                    <label for="teacher_role">Teacher role:</label><br>
                                    <div class="form-control">
                                        <span class="teacher"><input type="radio" name="teacher_type" class="teacher_role" value="1" checked>&nbsp;Teacher</span>
                                        <span class="professor"><input type="radio" name="teacher_type" class="teacher_role" value="2">&nbsp;Professor</span>
                                    </div>
                                </div>
                                <div>
                                    <label for="name">Name:</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                                <div>
                                    <label for="lastname">Lastname:</label>
                                    <input type="text" name="lastname" id="lastname" class="form-control">
                                </div><br>
                                <div>
                                    <input type="submit" name="submit" value="Add User" class="btn btn-primary">
                                </div>
                        </div>
                </div> <!-- 8 -->
                
                <div class="col-lg-4" id="teachers_subjects">
                    <p style="font-weight: bold;">Assign subjects:</p>
                    <div id="teachers_subjects_list"  multiple='multiple' size='10'>
                    <?php

                        $subjects = Mapper::getSubjectList();
                        foreach($subjects as $subject){
                            
                            ?>
                                <p><input type="checkbox" name="subject[]" value="<?php echo $subject->subjects_id; ?>"><span>&nbsp;<?php echo $subject->name; ?></span></p>
                            <?php
                        }

                    ?>
                    </div> 
                </div> <!-- 4 -->
               
                </form>
                
                <?php

                    if(isset($_POST['submit'])){         
                        Mapper::addUser($_POST);
                        Mapper::addUserByStatus($_POST['status'], $_POST);
                        
                    } 
                    
                ?>    
        </div> <!-- 12 -->
    </div>
</div>
<script>
        $(document).ready(function(){
            $('#st_group_year').hide();
            $('#st_group_number').hide();
            $('#teacher_role').hide();
            $('#teachers_subjects').hide();
            $('#st_JMBG').hide();
            $('#status').change(function(){

                if($('#status').val() == '2'){
                    $('#student_JMBG').val("");
                    $('#student_group_year').val("");
                    $('#student_group_number').val("");
                }

                if($('#status').val() == '3') {
                    $('#st_group_year').show(500);
                    $('#st_group_number').show(500);
                    $('#teacher_role').show(500);
                    $('#student_JMBG').val("");
                } else {
                    $('#st_group_year').hide(1000);
                    $('#st_group_number').hide(1000);
                    $('#teacher_role').hide(1000);
                 } 

                 if($('#status').val() == '4') {
                     $('#st_JMBG').show(400);
                     $('#student_JMBG').val("");
                     $('#student_group_year').val(""); 
                     $('#teacher_role').val("");
                } else {
                    $('#st_JMBG').hide(1000); 
                 } 
            });

            $("input[type=radio][name=teacher_type]").change(function(){
                if(this.value == 2){
                    $('#teachers_subjects').show(600);
                } else {
                    $('#teachers_subjects').hide(400);
                }    
            });


            console.log($('.teacher_role').val());
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

            $('#username').on('input', function(){
                var username = $(this).val();
                if(username.length < 2) {
                    styleOnTrue($('#username'));
                    $(this).attr("placeholder", "Must be at least two characters long.");
                } else {
                    styleOnFalse($('#username'));
                }     
            });

            $('#password').on('input', function(){
                var password = $(this).val();
                if(password.length < 8){
                    styleOnTrue($('#password'));
                    $(this).attr("placeholder", "Weak password. Must be at least 8 characters long.");
                } else {
                    styleOnFalse($('#password'));
                }
            });

            $('#ch_password').on('input', function(){
                var password = $('#password').val();
                var ch_password = $(this).val();
                if(password !== ch_password){
                    styleOnTrue($('#ch_password'));
                    $(this).attr("placeholder","Password doesn't match.");
                } else {
                    styleOnFalse($('#ch_password'));
                }
            });
            
            $('#student_JMBG').on('input', function(){
                var student_JMBG = $(this).val();
                var reg = new RegExp('^[1-9][0-9]{12}$');
                if(!reg.test(student_JMBG)){
                    styleOnTrue($('#student_JMBG'));
                    $(this).attr("placeholder","Must be number and 13 characters long.");                        
                } else {
                    styleOnFalse($('#student_JMBG'));
                }
            });

            $('#student_group_year').on('input', function(){
                var student_group_year = $(this).val();
                var reg = new RegExp('^[1-8]$');
                if(!reg.test(student_group_year)){
                    styleOnTrue($('#student_group_year'));
                    $(this).attr("placeholder", "Between 1-8");
                } else {
                    styleOnFalse($('#student_group_year'));
                }
            });

            $('#student_group_number').on('input', function(){
                var student_group_number = $(this).val();
                var reg = new RegExp('^[1-8]$');
                if(!reg.test(student_group_number)){
                    styleOnTrue($('#student_group_number'));
                    $(this).attr("placeholder", "Between 1-8");
                } else {
                    styleOnFalse($('#student_group_number'));
                }
            });

            $('#name').on('input', function(){
                var name = $(this).val();
                if(name.length < 2){
                    styleOnTrue($('#name'));
                    $(this).attr("placeholder", "Must be at least two characters long.");
                } else {
                    styleOnFalse($('#name'));
                }
            });

            $('#lastname').on('input', function(){
                var lastname = $(this).val();
                if(lastname.length < 2){
                    styleOnTrue($('#lastname'));
                    $(this).attr("placeholder", "Must be at least two characters long.");
                } else {
                    styleOnFalse($('#lastname'));
                }
            });


            

            $('#form_add_user').submit(function(e) {

                var err = [];
                var username = $('#username').val();
                var student_group_year = $('#student_group_year').val();
                var student_group_number = $('#student_group_number').val();
                var student_JMBG = $('#student_JMBG').val();
                var status = $('#status').val();
                
                if(username.length < 2){
                    err.push($('#username').attr('id'));
                } 
                
                if($('#password').val().length < 8){
                    err.push($('#password').attr('id'));
                }

                if($('#password').val() !== $('#ch_password').val()){
                    err.push($('#ch_password').attr('id'));
                }

                if(status != 2 && $('#student_JMBG').val().length != 13 && student_group_year == "" && student_group_number == ""){
                    err.push($('#student_JMBG').attr('id'));
                }

                if(status != 2 && $('#student_group_year').val().length > 1 && student_JMBG == ""){
                    err.push($('#student_group_year').attr('id'));
                }

                if(status != 2 && $('#student_group_number').val().length > 1 && student_JMBG == ""){
                    err.push($('#student_group_number').attr('id'));
                }

                if($('#name').val().length < 2){
                    err.push($('#name').attr('id'));
                }

                if($('#lastname').val().length < 2){
                    err.push($('#lastname').attr('id'));
                }
                
                if(err.length == 0){
                    $('#form_add_user').submit();
                } else {
                    e.preventDefault();
                }      
                
            });

            
 
        });
  
        </script>
<?php include("../../private/styles/includes/footer.php"); ?>


