<?php
    ini_set('display_errors', 1);
    include('utils/db.php');
    $user_id = check_login($_POST['email'], $_POST['password']);
    login_user($user_id);
?>