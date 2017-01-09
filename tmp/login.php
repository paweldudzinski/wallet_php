<div class="splash-container">
    <header class="logotype">
        Wirtualny Portfel
    </header>
    
    <?php
        $ok_code = $_GET['o'];
        if ($ok_code == 'R') {
            echo '<div class="alert alert-info">Rejestracja przebiegła pomyślnie.<br />Teraz możesz się zalogować!</div>';
        }
    ?>
    
    <form action="action_login.php" method="POST">
    <section class="form login-form">
        <div class="label">Twój adres e-mail:</div>
        <div class="form-input"><input type="text" name="email" /></div>
        <div style="clear:both; height:10px;"></div>
        
        <div class="label">Hasło:</div>
        <div class="form-input"><input type="password" name="password" /></div>
        <div style="clear:both; height:10px;"></div>
        
        <div class="submit-button">
            <input class="siButton" type="submit" value="ZALOGUJ SIĘ">
        </div>
    </section>
    </form>
    <hr />
    <section title="oszczędzanie, kalkulator wydatków" class="details">
        Pomagamy zaplanować domowy budżet, monitorować oraz robić zestawienia wydatków, przychodów itd.<br />
        <b>Nie łączymy się z twoim bankiem, w 100% jesteś bezpieczny</b><br />
        Nie jesteś zarejestrowany? <a href="?page=register">Możesz to zrobić tu!</a> To nic nie kosztuje, sprawdź!
    </section>
</div>
