<?php

    include('../utils/db.php');

    $logged_user = get_logged_user();
    
    if ($logged_user == 0) {
        header('Location: index.php');
    }
    
    header("Location: ../portal.php?page=wallet_savings");
?>
