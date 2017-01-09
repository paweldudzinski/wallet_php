<?php    
    if (isset($_GET['page'])) {
        $page =  $_GET['page'];
    } else {
        $page = 'wallet_list';
    }
    
	if ($page!='chname' && $page!='stats' && $page!='new_wallet' and $page!='wallet_list' and $page!='statistics' and $page!='welcome' and $page!='wallet' and $page!='counter' and $page!='wallet_savings' and $page!='savings_counter') {
		header( 'Location: index.php');
	}
    
    include('utils/db.php');
    $logged_user = get_logged_user();
    $wallet = get_picked_wallet();

    if ($wallet > 0) {
        $is_mine = is_wallet_mine($logged_user, $wallet);
        if ($is_mine == 0) {
            header( 'Location: index.php?e=N');
        }
    }

    if ($logged_user == 0) {
        header( 'Location: index.php');
    }
?>

<!DOCTYPE html class="no-js">
  <head>
    <title>Wirtualny Portfel</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Kalkulator wydatków, pomocnik w oszczędzaniu" />
    <meta name="keywords" content="wirtualny portfel, oszczędzanie, kalkulator wydatków" />
    <meta property="og:title" content="Wirtualny Porftel" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://www.vportfel.pl/" />
    <meta property="og:image" content="http://www.vportfel.pl/images/logo.png" />
    <meta property="fb:admins" content="686553089" />
    <link rel="image_src" href="http://www.vportfel.pl/images/logo.png" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/ui.css" />
    
    <script src="js/m.js" type="text/javascript"></script>
    <script src="js/jquery-1.8.2.js" type="text/javascript"></script>
    <script src="js/ui.js" type="text/javascript"></script>
    <script src="js/main.js" type="text/javascript"></script>
  </head>
  <body class="main">
    <nav>
    <div>
        <ul>
            <li class="logotype-small">Wirtualny Portfel</li>
            <li style="text-align:center;">
                <a class="shared shared-new-wallet" href="?page=new_wallet"></a><br />
                <a href="?page=new_wallet">nowy portfel</a>
            </li>
            <li style="text-align:center;">
                <a class="shared shared-wallet-list" href="?page=wallet_list"></a><br />
                <a href="?page=wallet_list">lista portfeli</a>
            </li>
            <?php
            if (is_wallet_picked_and_its_active()) {
            ?>
            <li style="text-align:center;">
                <a class="shared shared-wallet-operations" href="?page=counter&wallet_id=<?=$wallet;?>"></a><br />
                <a href="?page=counter&wallet_id=<?=$wallet;?>">wpłać/wypłać</a>
            </li>
            <?php
            }
            
            if ($page=='wallet_savings') {
            ?>
            <li style="text-align:center;">
                <a class="shared shared-wallet-operations" href="?page=savings_counter"></a><br />
                <a href="?page=savings_counter">wydaj oszczędności</a>
            </li>
            <?php
            }
            if (exists_closed_wallet($logged_user)) {
            ?>
            <li style="text-align:center;">
                <?php
                if ($page=='savings_counter' || $page=='wallet_savings') {
                ?>
                    <a class="shared shared-stats" href="?page=wallet_savings"></a><br />
                    <a href="?page=wallet_savings">statystyki</a>
                <?php
                } else {
                ?>
                    <a class="shared shared-stats" href="?page=stats"></a><br />
                    <a href="?page=stats">statystyki</a>
                <?php
                }
                ?>
            </li>
            <?php
            }
            ?>
        </ul>
    </div>
    </nav>
    
    <?php
        $ok_message = $_GET['o'];
        if ($ok_message == 'M') {
            echo '<div class="alert alert-info">Operacja wyrzucona!</div>';
        }
        if ($ok_message == 'O') {
            echo '<div class="alert alert-info">Operacja zapisana!</div>';
        }
        if ($ok_message == 'U') {
            echo '<div class="alert alert-info">Portfel wyrzucony!</div>';
        }
        
        $error_message = $_GET['e'];
        if ($error_message == 'O') {
            echo '<div class="alert alert-error">Nieprawidłowa operacja :/</div>';
        }
        if ($error_message == 'M') {
            echo '<div class="alert alert-error">Ten portfel nie istnieje :></div>';
        } 
        if ($error_message == 'C') {
            echo '<div class="alert alert-error">Błąd w kwocie, za dużo miejsc po przecinku... o_O/</div>';
        }
        if ($error_message == 'C') {
            echo '<div class="alert alert-error">Błąd w kwocie, to musi być liczba i tylko liczba... o_O</div>';
        }
        if ($error_message == 'D') {
            echo '<div class="alert alert-error">Błąd w formacie daty... o_O</div>';
        }
    ?>
    
    <section class="content">
        <?php include('user_panel/'.$page.'.php'); ?>
    </section>
  </body>
</html>

<script>
    $(document).ready(function() {
        $('#wallet-submit').click(function() {
            var name = $('#name').val();
            if (!name) {
                alert('Proszę, podaj nazwę portfela.');
                return false;
            }
            $('#wallet-form').submit();
         }); 
    });
</script>
