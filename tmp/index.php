<?php
    ini_set("display_errors", 1);
    
    include('utils/db.php');
    $logged_user = get_logged_user();
    
    if (isset($_GET['page'])) {
        $page =  $_GET['page'];
    } else {
        $page = 'login';
    }
    
    
    #like home.py in pyramid
    if ($logged_user != 0 && $page == 'login') {
        if (user_have_wallets($logged_user)) {
            header('Location: portal.php?page=wallet_list');
        } else {
            header('Location: portal.php?page=welcome');
        }
    }
	if ($page!='home' and $page!='register' and $page!='login') {
		header('Location: index.php');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<head>
    <title>Wirtualny Portfel</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Kalkulator wydatków, pomocnik w oszczędzaniu" />
    <meta name="keywords" content="wirtualny portfel, oszczędzanie, kalkulator wydatków" />
    <link rel="shortcut icon" href="images/favicon.ico"/>
    <meta property="og:title" content="Wirtualny Porftel" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://www.vportfel.pl/" />
    <meta property="og:image" content="http://www.vportfel.pl/images/logo.png" />
    <meta property="fb:admins" content="686553089" />
    <link rel="image_src" href="http://www.vportfel.pl/images/logo.png" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
  </head>
</head>
<body>
	<?php
        if (($page=='login' && $logged_user == 0) || $page=='register') {
            include($page.'.php');
        }
    ?>
</body>
</html>

<script src="js/m.js" type="text/javascript"></script>
<script src="js/jquery-1.8.2.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
<script src="js/ui.js" type="text/javascript"></script>
<?php
    if ($page=='register') {
        include('utils/register_validation_js.php');
    }
?>
