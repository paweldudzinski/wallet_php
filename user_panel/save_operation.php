<?php
    ini_set('display_errors', 1);
    include('../utils/db.php');

    $wallet_id = get_picked_wallet();
    $logged_user = get_logged_user();
    
    if ($logged_user == 0) {
        header('Location: index.php');
    }
    
    if (!$wallet_id) {
        header('Location: portal.php');
    }

    if (!is_wallet_mine($logged_user, $wallet_id)) {
        header('Location: portal.php?e=M');
    }
    
    $outcome_amount = $_POST['outcome_amount'];
    $outcome_category = $_POST['outcome_category'];
    $income_amount = $_POST['income_amount'];
    
    $outcome_date = $_POST['outcome_date'];
    $income_date = $_POST['income_date'];
    
    $splitted_income = Array();
    $splitted_outcome = Array();
    
    if ($outcome_amount) {
        $splitted_outcome = explode(".",str_replace(',','.',$outcome_amount));
    }
    
    if ($income_amount) {
        $splitted_income = explode(".",str_replace(',','.',$income_amount));
    }
        
    if ((sizeof($splitted_income)>2) || (sizeof($splitted_outcome)>2)) {
        header('Location: ../portal.php?e=C&page=counter&wallet_id='.$wallet_id);
    }
    
    foreach($splitted_outcome as $so) {
        if (!is_numeric($so)) {
            header('Location: ../portal.php?e=I&page=counter&wallet_id='.$wallet_id);
        }
    }
    
    foreach($splitted_income as $si) {
        if (!is_numeric($si)) {
            header('Location: ../portal.php?e=I&page=counter&wallet_id='.$wallet_id);
        }
    }
    
    if ($outcome_amount) {
        if (!checkDateTime($outcome_date)) {
            header('Location: ../portal.php?e=D&page=counter&wallet_id='.$wallet_id);
        }
    }
    
    if ($income_amount) {
        if (!checkDateTime($income_date)) {
            header('Location: ../portal.php?e=D&page=counter&wallet_id='.$wallet_id);
        }
    }

    $proper_amount_income = implode('.', $splitted_income);
    $proper_amount_outcome = implode('.', $splitted_outcome);

    save_operation($wallet_id, $proper_amount_income, $income_date, $proper_amount_outcome, $outcome_category, $outcome_date);
    
    header('Location: ../portal.php?o=O&page=counter&wallet_id='.$wallet_id);
    
?>
