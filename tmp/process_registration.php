<?php
    include('utils/db.php');
    $user_id = check_user($_POST['email']);
    
    if ($user_id >0) {
        header('Location: index.php?page=register&e=E');
    } else {
        $result = save_user($_POST);
        header('Location: index.php?o=R');
    }
?>
