<?php

@include './model/config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: ./view/login.php');
};
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home page</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="./view/css/style.css">

    <!--Jquery-->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>

    <!-- alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.26/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.26/dist/sweetalert2.min.css">

    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">


</head>

<body>

    <?php include './view/header.php'; ?>
    <div class="container">
        <div class="row">
            <!-- hien thi noi dung đây -->
            <?php
            $ctrl = "home";
            if (isset($_GET['action'])) {
                $ctrl = $_GET['action'];
            }
            include "controller/" . $ctrl . ".php";

            ?>
        </div>
    </div>



    <?php include './view/footer.php'; ?>

    <script src="./view/js/script.js"></script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>