<?php

    include('../utils/db.php');

    $wallet_id = $_GET['wallet'];
    $logged_user = get_logged_user();
    
    if ($logged_user == 0) {
        header('Location: index.php');
    }
    
    if (!$wallet_id) {
        header('Location: portal.php');
    }

    if (!is_wallet_mine($logged_user, $wallet_id)) {
        header('Location: portal.php?e=M');
    }
    
    unpick_wallet();
    pick_wallet($wallet_id);
?>
