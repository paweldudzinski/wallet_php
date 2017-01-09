<?php

    include('../utils/db.php');

    $wallet_id = $_GET['id'];
    $logged_user = get_logged_user();
    
    if ($logged_user == 0) {
        header('Location: ../index.php');
    }
    
    if (!$wallet_id) {
        header('Location: ../portal.php');
    }
    
    if (!is_wallet_mine($logged_user, $wallet_id)) {
        header('Location: ../portal.php?e=M');
    }
        
    $sql = "UPDATE wallets SET state = 'C' where id = ".$wallet_id;
    mysql_query($sql);
    
    //echo $sql;
    
    header('Location: ../portal.php');
?>
