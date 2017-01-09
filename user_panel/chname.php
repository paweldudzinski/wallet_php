<?php
    $wallet_id = $_GET['wallet_id'];
    $logged_user = get_logged_user();
    
    if ($logged_user == 0) {
        header('Location: index.php');
    }
    
    if (!$wallet_id) {
        header('Location: portal.php');
    }

    if (!is_wallet_mine($logged_user, $wallet_id)) {
        unpick_wallet();
        header('Location: portal.php?e=M');
    }
    
    $wallet = get_whole_wallet($wallet_id);
?>

<h1>Edytuj portfel</h2>

<section class="form login-form" style="margin-left:0px;">
<form action="user_panel/save_edited_wallet.php" method="POST" id="wallet-form">
    <div class="label">Nazwa portfela:</div>
    <div class="form-input"><input type="text" name="name" id="name" value="<?=$wallet[2]?>" /></div>
    <div style="clear:both; height:10px;"></div>   
    <div class="submit-button">
        <input type="hidden" name="wallet_id" value="<?=$wallet[0]?>" />
        <input class="siButton" type="submit" value="ZAPISZ PORTFEL" id="wallet-submit" />
    </div> 
</form>
</section>
</section>
