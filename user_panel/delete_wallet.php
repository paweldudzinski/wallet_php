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
    
    $sql = "DELETE FROM operations WHERE us_id = ".$logged_user." AND wallet_id = ".$wallet_id;
    mysql_query($sql);
    $sql = "DELETE FROM wallets WHERE us_id = ".$logged_user." AND id = ".$wallet_id;
    mysql_query($sql);
    header('Location: ../portal.php?page=wallet_list&o=U');
?>
