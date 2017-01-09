<?php

    include('../utils/db.php');

    $wallet_id = get_picked_wallet();
    $logged_user = get_logged_user();
    $operation_id = $_GET['id'];
    
    if ($logged_user == 0) {
        header('Location: index.php');
    }
    
    if (!$wallet_id) {
        header('Location: portal.php');
    }

    if (!is_wallet_mine($logged_user, $wallet_id)) {
        header('Location: portal.php?e=M');
    }
    
    if (!is_operation_mine($wallet_id, $operation_id)) {
        header('Location: ../portal.php?page=wallet&e=O');
    } else {
        delete_operation($operation_id, $wallet_id);
        header('Location: ../portal.php?page=wallet&o=M');
    }
?>
