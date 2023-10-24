<?php

@include '../model/config.php';

session_start();

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_SPECIAL_CHARS);
    $pass = md5($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_SPECIAL_CHARS);

    $sql = "SELECT * FROM `users` WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email, $pass]);
    $rowCount = $stmt->rowCount();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $message = [];
    if ($rowCount > 0) {

        if ($row['user_type'] == 'admin') {

            $_SESSION['admin_id'] = $row['id'];
            header('location:admin_page.php');
        } elseif ($row['user_type'] == 'user') {
            $_SESSION['user_id'] = $row['id'];
            header('location: ../index.php');
        } else {
            $message[] = 'No user found!';
            header('location: ../view/login.php?message=' . json_encode($message));
        }
    } else {
        $message[] = 'Incorrect email or password!';
        header('location: ../view/login.php?message=' . json_encode($message));
    }
}
