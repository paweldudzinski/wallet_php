<?php
    $logged_user = get_logged_user();
    unpick_wallet();
?>

<h1>Twoje Aktywne portfele</h1>

<section class="rounded-corners add-opacity bg-white">
    <strong class="green">&bull;</strong> <strong>Aktywne portfele</strong><br />
    
    <table class="active-wallet-list" style="margin-top:10px; border-collapse:collapse;">
    <?php
        $active_wallets = get_active_wallets($logged_user);
        foreach ($active_wallets as $wallet) {
    ?>
        <tr>
            <td style="width:200px; vertical-align:top;">
                Stworzono: <?=date("d-m-Y",strtotime($wallet['when_created']));?>
            </td>
            <td style="width:500px;">
                <a href="user_panel/pick_wallet.php?wallet=<?=$wallet['id']?>">
                    Portfel "<?=mb_convert_case($wallet['name'], MB_CASE_UPPER, "UTF-8");?>"
                </a>
                <div class="details" style="margin-bottom:15px;">
                Wpłaty:  <?=number_format($wallet['amount_in'], $decimals=2, $dec_point='.', $thousands_sep='');?> PLN<br />
                Wypłaty: <?=number_format($wallet['amount_out'], $decimals=2, $dec_point='.', $thousands_sep='');?> PLN
                </div>
            </td>
            <td style="width:200px; vertical-align:top;">
                Bilans: 
                <span class="<?=get_class_by_bilance(bilance($wallet['id']));?>">
                    <?=number_format(bilance($wallet['id']), $decimals=2, $dec_point='.', $thousands_sep='');?> PLN
                </span>
            </td>
            <td style="width:100px; vertical-align:top; text-align:center;">
                <a class="shared shared-wallet-close" href="user_panel/close_wallet.php?id=<?=$wallet['id']?>"></a><br />
                <a class="tiny-text" href="user_panel/close_wallet.php?id=<?=$wallet['id']?>">zamknij portfel</a>
            </td>
        </tr>
    <?php
        }
    ?>
        
    <?php
        if (sizeof($active_wallets) < 1) {
            echo "<tr><td class='gray'>Brak aktywnych portfeli.</td></tr>";
        }
    ?>
    </table>
</section>

<section class="rounded-corners add-opacity bg-white">    
    <strong class="red">&bull;</strong> <strong>Zamknięte portfele</strong>
    
    <table class="active-wallet-list" style="margin-top:10px; border-collapse:collapse; width:100%;">
    <?php
        $overall_balance = 0;
        $closed_wallets = get_closed_wallets($logged_user);
        foreach ($closed_wallets as $wallet) {
            $overall_balance = $overall_balance + bilance($wallet['id']);
    ?>
        <tr>
            <td style="width:200px; vertical-align:top;">
                Stworzono: <?=date("d-m-Y",strtotime($wallet['when_created']));?>
            </td>
            <td style="width:500px;">
                <a href="user_panel/pick_wallet.php?wallet=<?=$wallet['id']?>">
                    Portfel "<?=mb_convert_case($wallet['name'], MB_CASE_UPPER, "UTF-8");?>"
                </a>
            </td>
            <td style="width:200px; vertical-align:top;">
                Bilans: 
                <span class="<?=get_class_by_bilance(bilance($wallet['id']));?>">
                    <?=number_format(bilance($wallet['id']), $decimals=2, $dec_point='.', $thousands_sep='');?> PLN
                </span>
            </td>
            <td style="width:100px; vertical-align:top; text-align:center;"></td>
        </tr>
    <?php
    }
    ?>
    
    <?php
        if (sizeof($closed_wallets) > 0) {
    ?>
        <tr><td colspan="4" style="text-align:left; padding-top:20px;">
        <strong>
            Ogólny bilans zamkniętych portfeli:&nbsp;
            <span class="<?=get_class_by_bilance($overall_balance);?>">
                <?=number_format($overall_balance, $decimals=2, $dec_point='.', $thousands_sep='');?> PLN
            </span>
        </strong>
        </td></tr>
    <?php
        }
    ?>
    
    <?php
        if (sizeof($closed_wallets) < 1) {
            echo "<tr><td class='gray'>Brak zamkniętych portfeli.</td></tr>";
        }
    ?>
    </table>
</section>
