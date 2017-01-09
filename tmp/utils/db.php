<?php
//ini_set('display_errors', 1);

$username = "dbo443604442";
$password = "wallet666";
$hostname = "db443604442.db.1and1.com";
$database ="db443604442";

if (!session_id()) session_start();

function init_db($hostname, $username, $password, $database) {
    $dbhandle = mysql_connect($hostname, $username, $password);
    mysql_select_db($database) or die("No");
    return $dbhandle;
}

GLOBAL $dbhandle;
$dbhandle = init_db($hostname, $username, $password, $database);


function checkDateTime($data) {
    if (date('d-m-Y', strtotime($data)) == $data) {
        return true;
    } else {
        return false;
    }
}

function check_login($email, $password) {
    $query = "SELECT * FROM users WHERE email = '".$email."' AND password = '".$password."'";
    $result = mysql_query($query);
    while($row = mysql_fetch_array($result)) { 
        return $row['id']; 
    }
    return 0;
}

function check_user($email) {
    $query = "SELECT * FROM users WHERE email = '".$email."'";
    $result = mysql_query($query);
    while($row = mysql_fetch_array($result)) { 
        return $row['id']; 
    }
    return 0;
}


function login_user($user_id) {
    if ($user_id > 0) {
        $_SESSION['logged_user'] = $user_id;
    } else {
        unset($_SESSION['logged_user']); 
    }
    header("Location: index.php");
}

function logout_user() {
    unset($_SESSION['logged_user']);
    header("Location: index.php");
}

function get_logged_user() {
    if (isset($_SESSION['logged_user'])) {
        return $_SESSION['logged_user'];
    } else {
        return 0;
    }
}

function unpick_wallet() {
    unset($_SESSION['wallet']); 
}

function pick_wallet($wallet) {
    $_SESSION['wallet'] = $wallet;
    header("Location: ../portal.php?page=wallet");
}

function get_picked_wallet() {
    if (isset($_SESSION['wallet'])) {
        return $_SESSION['wallet'];
    } else {
        return 0;
    }
}

function is_wallet_mine($user_id, $wallet_id) {
    $sql = "SELECT id FROM wallets WHERE us_id = ".$user_id." AND id = ".$wallet_id;
    if (mysql_fetch_array(mysql_query($sql))) {
        return true;
    } else {
        return false;
    }
}

function is_operation_mine($wallet_id, $operation_id) {
    $sql = "SELECT id FROM operations WHERE wallet_id = ".$wallet_id." AND id = ".$operation_id;
    if (mysql_fetch_array(mysql_query($sql))) {
        return true;
    } else {
        return false;
    }
}

function save_user($data) {
    $email = $data['email'];
    $name = $data['name'];
    $password = $data['password'];
    $query = "INSERT INTO users SET name = '".$name."', email='".$email."', password='".$password."';";
    $result = mysql_query($query);
    return $result;
}

function user_have_wallets($user_id) {
    $query = "SELECT * FROM wallets WHERE us_id = ".$user_id." LIMIT 1";
    $result = mysql_query($query);
    while($row = mysql_fetch_array($result)) { 
        return $row['id']; 
    }
    return 0;
}

function is_wallet_picked_and_its_active() {
    $wallet_id = get_picked_wallet();
    $wallet = get_whole_wallet($wallet_id);
    
    if ($wallet['state'] == 'O') {
        return true;
    } else {
        return false;
    }
}

function exists_closed_wallet($user_id) {
    $query = "SELECT * FROM wallets WHERE state = 'C' AND us_id = ".$user_id." LIMIT 1";
    $result = mysql_query($query);
    while($row = mysql_fetch_array($result)) { 
        return $row['id']; 
    }
    return 0;
}

function get_whole_wallet($wallet_id) {
    $query = "SELECT * FROM wallets WHERE id = ".$wallet_id;
    $result = mysql_query($query);
    while($row = mysql_fetch_array($result)) { 
        return $row;
    }
}

function get_active_wallets($user_id) {
    $a = Array();
    $query = "SELECT * FROM wallets WHERE us_id = ".$user_id." AND state = 'O'";
    $result = mysql_query($query);
    while($row = mysql_fetch_array($result)) { 
        array_push($a, $row);
    }
    return $a;
}

function get_closed_wallets($user_id) {
    $a = Array();
    $query = "SELECT * FROM wallets WHERE us_id = ".$user_id." AND state = 'C'";
    $result = mysql_query($query);
    while($row = mysql_fetch_array($result)) { 
        array_push($a, $row);
    }
    return $a;
}

function print_category($cat_id) {
    return mysql_result(mysql_query("SELECT name FROM categories WHERE id = ".$cat_id), 0);
}

function get_wallet_name_by_id($wallet_id) {
    return mysql_result(mysql_query("SELECT name FROM wallets WHERE id = ".$wallet_id), 0);
}

function is_wallet_opened($wallet_id) {
    $state = mysql_result(mysql_query("SELECT state FROM wallets WHERE id = ".$wallet_id), 0);
    if ($state == 'O') {
        return true;
    } else {
        return false;
    }
}

function bilance($wallet_id) {
    return mysql_result(mysql_query("SELECT amount_in - amount_out FROM wallets WHERE id = ".$wallet_id), 0);
}

function get_class_by_bilance($bilance) {
    if ($bilance >= 0) {
        return 'green';
    } else {
        return 'red';
    }
}
    
function get_class_by_bilance_include_white($billance) {
    if ($bilance >= 0) {
        return 'white';
    } else {
        return 'red';
    }
}

function get_outcome_by_category($wallet_id) {
    $query = "
        SELECT
            c.name category_name, c.id category_id, sum(o.amount) amount
        FROM 
            operations o join categories c on o.category_id = c.id
        WHERE
            o.wallet_id = ".$wallet_id."
            AND o.type = 'O'
        GROUP BY o.category_id
        ORDER BY sum(o.amount) DESC
    ";
    
    $result = mysql_query($query);
    $a = Array();
    while($row = mysql_fetch_array($result)) { 
        array_push($a, $row);
    }
    
    return $a;
}

function get_overall_outcome_by_category($user_id) {
    $query = "
        SELECT
            c.name category_name, c.id category_id, sum(o.amount) amount
        FROM 
            operations o join categories c on o.category_id = c.id
        WHERE
            o.us_id = ".$user_id."
            AND o.type = 'O'
        GROUP BY o.category_id
        ORDER BY sum(o.amount) DESC
    ";
    
    $result = mysql_query($query);
    $a = Array();
    while($row = mysql_fetch_array($result)) { 
        array_push($a, $row);
    }
    
    return $a;
}

function get_outcome_by_category_and_wallets($user_id, $cat_id) {
    $query = "
        SELECT
            w.name wallet_name, sum(o.amount) amount
        FROM
            operations o join wallets w on o.wallet_id = w.id join categories c on c.id = o.category_id
        WHERE
            o.us_id = ".$user_id."
            AND c.id = ".$cat_id."
        GROUP BY w.id
        ORDER BY w.when_created DESC;
    ";
    
    $result = mysql_query($query);
    $a = Array();
    while($row = mysql_fetch_array($result)) { 
        array_push($a, $row);
    }
    
    return $a;
}

function wallet_operations($wallet_id) {
    $query = "SELECT * FROM operations WHERE wallet_id = ".$wallet_id." ORDER BY when_created DESC;";
    $result = mysql_query($query);
    $a = Array();
    while($row = mysql_fetch_array($result)) { 
        array_push($a, $row);
    }
    
    return $a;
}

function recalculate($wallet_id) {
    $amount_in = 0;
    $amount_out = 0;
    foreach (wallet_operations($wallet_id) as $o) {
        if ($o['type']=='I') {
            $amount_in+=$o['amount'];
        } else {
            $amount_out+=$o['amount'];
        }
    }
    
    mysql_query("UPDATE wallets SET amount_in=".$amount_in.", amount_out=".$amount_out." WHERE id=".$wallet_id);
}

function delete_operation($operation_id, $wallet_id) {
    mysql_query("DELETE FROM operations WHERE id = ".$operation_id);
    recalculate($wallet_id);
}

function get_categories() {
    $query = "SELECT * FROM categories;";
    $result = mysql_query($query);
    $a = Array();
    while($row = mysql_fetch_array($result)) { 
        array_push($a, $row);
    }
    
    return $a;
}

function increase_decrease_in_wallet($wallet_id, $income, $outcome) {
    $sql = "UPDATE wallets SET amount_out = amount_out + ".$outcome.", amount_in = amount_in + ".$income." WHERE id = ".$wallet_id;
    mysql_query($sql);
}

function save_operation($wallet_id, $proper_amount_income, $income_date, $proper_amount_outcome, $outcome_category, $outcome_date) {
    
    is_numeric($proper_amount_income) ? $proper_amount_income = $proper_amount_income : $proper_amount_income = 0;
    is_numeric($proper_amount_outcome) ? $proper_amount_outcome = $proper_amount_outcome : $proper_amount_outcome = 0;
    increase_decrease_in_wallet($wallet_id, $proper_amount_income, $proper_amount_outcome);
    
    if ($proper_amount_income > 0) {
        $sql = " INSERT INTO operations SET
                 us_id = ".get_logged_user().",
                 wallet_id = ".$wallet_id.",
                 amount = ".$proper_amount_income.",
                 type = 'I',
                 when_created = '".date("Y-m-d", strtotime($income_date))."'";
        mysql_query($sql);
    }
    
    if ($proper_amount_outcome > 0) {
        $sql = " INSERT INTO operations SET
                 us_id = ".get_logged_user().",
                 wallet_id = ".$wallet_id.",
                 amount = ".$proper_amount_outcome.",
                 category_id = ".$outcome_category.",
                 type = 'O',
                 when_created = '".date("Y-m-d", strtotime($outcome_date))."'";
        mysql_query($sql);
    }
}
?>
