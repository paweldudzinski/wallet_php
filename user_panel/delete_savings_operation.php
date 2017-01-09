<?php

    include('../utils/db.php');

    $logged_user = get_logged_user();
    $operation_id = $_GET['id'];
    
    if ($logged_user == 0) {
        header('Location: index.php');
    }
    

    delete_savings_operation($operation_id, $logged_user);
    header('Location: ../portal.php?page=wallet_savings&o=M');
?>
