<?php
    ini_set('display_errors', 1);
    include('../utils/db.php');

    $logged_user = get_logged_user();
    
    if ($logged_user == 0) {
        header('Location: index.php');
    }
        
    $outcome_amount = $_POST['outcome_amount'];
    $outcome_category = $_POST['outcome_category'];
    
    $outcome_date = $_POST['outcome_date'];
    
    $splitted_outcome = Array();
    
    
    
    if ($outcome_amount) {
        $splitted_outcome = explode(".",str_replace(',','.',$outcome_amount));
    }
    
    
    if (sizeof($splitted_outcome)>2) {
        header('Location: ../portal.php?e=C&page=savings_counter');
    }
    
    foreach($splitted_outcome as $so) {
        if (!is_numeric($so)) {
            header('Location: ../portal.php?e=I&page=savings_counter');
        }
    }
    
    if ($outcome_amount) {
        if (!checkDateTime($outcome_date)) {
            header('Location: ../portal.php?e=D&page=savings_counter');
        }
    }
    
    $proper_amount_outcome = implode('.', $splitted_outcome);

    save_savings_operation($logged_user, $proper_amount_outcome, $outcome_date, $outcome_category);
    
    header('Location: ../portal.php?o=O&page=savings_counter');
    
?>

