<?php
    include('../utils/db.php');
    $name = $_POST['name'];
    $logged_user = get_logged_user();
    $sql = "INSERT INTO wallets SET state='O', name='".$name."', amount_in=0, amount_out=0, us_id=".$logged_user.";";
    mysql_query($sql);
    header("Location: ../portal.php?o=W");
?>
