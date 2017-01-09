<?php
    $logged_user = get_logged_user();
    unpick_wallet();
?>

<h1>Nowy portfel</h2>

<?php
if (sizeof(get_active_wallets($logged_user))>0) {
    echo '<div class="details">
    <span class="orange">Uwaga!</span> Masz przynajmniej jeden aktywny portfel. Nie zalecamy tworzyc kilku portfeli w tym samym czasie.<br />
    Może to zaciemnić Twój plan oszczędzania.
    </div>';
}
?>

<section class="form login-form" style="margin-left:0px;">
<form action="user_panel/save_wallet.php" method="POST"  id="wallet-form">
    <div class="label">Nazwa portfela:</div>
    <div class="form-input"><input type="text" name="name" id="name" /></div>
    <div style="clear:both; height:10px;"></div>   
    <div class="submit-button">
        <input class="siButton" type="submit" value="STWÓRZ PORTFEL" id="wallet-submit">
    </div> 
</form>
</section>
</section>
