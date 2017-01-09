<?php
    include('../utils/db.php');
    $name = $_POST['name'];
    $wallet_id = $_POST['wallet_id'];
    $sql = "UPDATE wallets SET name='".$name."' WHERE id=".$wallet_id.";";
    mysql_query($sql);
    header("Location: ../portal.php?o=W");
?>
