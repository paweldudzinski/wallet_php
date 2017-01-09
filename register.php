<?php
    $error_code = $_GET['e'];
?>

<div class="splash-container" style="margin-top:30px;">
    <header class="logotype" style="font-size:40px;">
        Wirtualny Portfel: Rejestracja
    </header>
    <section class="details">
        Zajejestruj się teraz. Serwis jest w 100% darmowy, wypróbuj i oszczędzaj z nami.
    </section>
    <?php
        if ($error_code == 'E') {
            echo '<div class="alert alert-error">Użytkownik o tym adresie email już istnieje.</div>';
        }
    ?>
    <section class="form login-form">
        <form action="process_registration.php" method="POST" id="register-form">
        <div class="label">Twoje imię/nick:</div>
        <div class="form-input"><input type="text" name="name" id="name" /></div>
        <div style="clear:both; height:10px;"></div>
    
        <div class="label">Adres e-mail:</div>
        <div class="form-input"><input type="email" name="email" id="email" /></div>
        <div style="clear:both; height:10px;"></div>
        
        <div class="label">Hasło:</div>
        <div class="form-input"><input type="password" name="password" id="pasword" /></div>
        <div style="clear:both; height:10px;"></div>
        
        <div class="label">Powtórz hasło:</div>
        <div class="form-input"><input type="password" name="passwordr" id="paswordr" /></div>
        <div style="clear:both; height:10px;"></div>
        
        <div class="submit-button">
            <input class="siButton" type="submit" value="ZAREJESTRUJ SIĘ" id="register-submit">
        </div>
        </form>
    </section>
</div>


