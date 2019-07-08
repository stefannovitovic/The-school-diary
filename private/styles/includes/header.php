<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/gif" href="https://cdn3.iconfinder.com/data/icons/ios-web-user-interface-flat-circle-vol-3/512/Book_books_education_library_reading_open_book_study-512.png">
    <?php
    if(getCurrentFileName()==='opendoors.php') {
        ?>
        <link rel="stylesheet" href="/resources/demos/style.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

        <?php } else { ?>
    <script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>

    <?php } ?>
    <title>E-Gradebook</title>
    <!-- Bootstrap Core CSS -->
    <link href="../../private/styles/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../../private/styles/css/sb-admin.css" rel="stylesheet">
    <link href="../../private/styles/css/style.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../../private/styles/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700" rel="stylesheet">
    <?php
    if(getCurrentFileName()!= 'update_user.php' && getCurrentFileName()!= 'delete_user.php') {
        ?>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <?php
    }
    ?>
                <!-- jQuery -->
    <script src="../../private/styles/js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="../../private/styles/js/bootstrap.min.js"></script>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
        <?php
    if(getCurrentFileName()==='opendoors.php') {
        ?>
        <style>
            .col-lg-4{
                position: absolute;
                right: 650px;

            }
            #text{
                background-color: white;
            }
            #wrapper{

                background-color: antiquewhite;
                margin-right: 200px;
                 }
            .error{
                color: red;
                background-color: white;
            }
            textarea{
                height: 600px;
            }
        </style>
        <?php

    }
    ?>
    <?php
    if(getCurrentFileName()==='teacher.php' || getCurrentFileName()==='parent.php') {
        ?>
        <style>
            table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
            }
            th, td {
            text-align: left;
            border: 1px solid;
            padding: 8px;
            cursor: pointer;
            width: 100px;
            }
            th{background-color: #51D5FF;}
            tr:nth-child(even) {background-color: #f2f2f2;}
/*            #moving {
            display: none;
            -webkit-transition: width 2s;
            transition: width 2s;
            }

            .content:hover #moving{
            display: inline;
            position: absolute;
            background-color: #51D5FF;
            width: auto;
            height: 150px;
            border-radius: 10px;
            }*/
        </style>
        <?php

    }
    if(getCurrentFIleName()==='axis.php' || getCurrentFIleName()==='axis_odeljenja.php') {
        ?>
        <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            font-size: 9pt;
            background:white;
            }


            html, body {
                margin: 0;
                height: 100%;
                background: #ECE9E6;  /* fallback for old browsers */
                background: -webkit-linear-gradient(to right, #FFFFFF, #ECE9E6);  /* Chrome 10-25, Safari 5.1-6 */
                background: linear-gradient(to right, #FFFFFF, #ECE9E6); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

            }


            #chartdiv {
            width: 100%;
            height: 79%;
            }

            #chartdiv1 {
            width: 100%;
            height: 60%;
            }
            .bigtime{
                height: 20%;
                
            color: #CCCCCC;
                text-shadow: 0 1px 0 #999999, 0 2px 0 #888888,
             0 3px 0 #777777, 0 4px 0 #666666,
             0 5px 0 #555555, 0 6px 0 #444444,
             0 7px 0 #333333, 0 8px 7px rgba(0, 0, 0, 0.4),
             0 9px 10px rgba(0, 0, 0, 0.2);
            
            }
            h1{
                text-align: center;
                vertical-align: middle;
            }
            input {
                background: #F2994A;  /* fallback for old browsers */
                background: -webkit-linear-gradient(to right, #F2C94C, #F2994A);  /* Chrome 10-25, Safari 5.1-6 */
                background: linear-gradient(to right, #F2C94C, #F2994A); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

                border-radius: 8px;
                -webkit-transition-duration: 0.4s;
                transition-duration: 0.4s;
                width: 300px;
                height: 50px;
                
                box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
            }
            input:hover {
            background: #f12711;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #f5af19, #f12711);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #f5af19, #f12711); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

            color: white;

            }
            select{
                height: 50px;
                border-radius: 16px;
            outline: white;
            }
            a{
                color: white;
                width: 100%;
            display: block;
            
            }

            .back {
            position: absolute;
            right:    0;
            bottom:   0;
            display: inline-block;
            border-radius: 4px;
            background: #F2994A;  /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #F2C94C, #F2994A);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #F2C94C, #F2994A); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

            
            text-align: center;
            font-size: 28px;
            width: 200px;
            transition: all 0.5s;
            cursor: pointer;
            }

            .back:hover{
            background: #f12711;  /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #f5af19, #f12711);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #f5af19, #f12711); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

            }
            .back:focus{background: #f12711;  /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #f5af19, #f12711);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #f5af19, #f12711); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

            }

            .back span {
            cursor: pointer;
            display: inline-block;
            position: relative;
            transition: 0.5s;
            }

            .back span:after {
            content: '\00bb';
            position: absolute;
            opacity: 0;
            top: 0;
            right: -20px;
            transition: 0.5s;
            }

            .back:hover span {
            padding-right: 25px;
            }

            .back:hover span:after {
            opacity: 1;
            right: 0;
            }
            .row{
            padding-left: 790px;
            }
            a:-webkit-any-link {
                text-decoration: none;
            }
            option{
            outline: white;
            }
            .navbar{
                display: none;
            }
            a:focus, a:hover{
                color: white;
                
            }
            .alert-warning{
                background-color: transparent;
                border-color:transparent;
                padding-top: 150px;
                font-size: 25px;
            }
            /**/


        </style>
        <?php
            }
            if(getCurrentFileName() === 'pick_page.php') {
                ?>
                <style>
        .side-nav{
            display: none;
                }
        .text-center{
            width: 30%;
                }
        a {
            text-align: center;
            vertical-align: middle;
            line-height: 200px;
            height: 100%;
            width: 100%;
            background-color: transparent;
            display: block;
            font-size: 20px;
        }
        a:hover{
            text-decoration: none;
        }
        .right{
            margin-right: 200px
        }
        .form-group {
            height: 48%;
            margin-bottom: 5px;
            margin-top: 50px;
            width: 100%;
            float: right;
            transition-duration: 0.4s;
            background-color: #ffff4d;
            border-radius: 70px;
        }
        .form-group:hover {
            background-color: #eada00;
            color: white;
            text-decoration: none;
        }
        .text-center {
            width: 80%;
            height: 100%;
            padding-left: 250px;
        }
        .row {
            height: 500px;
            margin-left: 65px;
            margin-right: 300px;
        }
        </style>
        <?php
    }

    if(getCurrentFileName()==='opendoors.php') {
        ?>
        <script>

        </script>
        <?php } ?>
</head>

<body>
<?php
if(getCurrentFIleName()!='axis.php' && getCurrentFIleName()!='axis_odeljenja.php') {
        ?>
<div id="wrapper">
<?php } ?>