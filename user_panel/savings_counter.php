<?php
    $logged_user = get_logged_user();
    
    if ($logged_user == 0) {
        header('Location: index.php');
    }
    
    $overall_balance = 0;
    $closed_wallets = get_closed_wallets($logged_user);
    foreach ($closed_wallets as $wallet) {
        $overall_balance = $overall_balance + bilance($wallet['id']);
    }
    
    $savings_from_db = get_savings($logged_user);
?>
<div style="float:left;">
    <h1 style="margin:0px;">Oszczędności</h1>
</div>
<div style="float:right; margin-top:15px;">
    Aktualny bilans:
    <span class="bigger <?=get_class_by_bilance_include_white(bilance($overall_balance));?>">
    <?=number_format($overall_balance-$savings_from_db['amount_out'], $decimals=2, $dec_point='.', $thousands_sep='');?> PLN
    </span>
</div>
<div style="clear:both;"></div>

<hr class="full" />
<div class="details" style="margin:5px 0px 5px 0px;">
    <div style="float:left;">
    Suma oszczędności: <strong><?=number_format($overall_balance, $decimals=2, $dec_point='.', $thousands_sep='');?> PLN</strong>
    &nbsp;&nbsp;&nbsp;&nbsp;::&nbsp;&nbsp;&nbsp;
    Wykorzystane oszczedności: <strong><?=number_format($savings_from_db['amount_out'], $decimals=2, $dec_point='.', $thousands_sep='');?> PLN</strong>
    </div>
    <div style="clear:both;"></div>
</div>
<hr class="full" />
<br />



<form action="user_panel/save_savings_operation.php" method="POST" id="operation-form">
<section class="form login-form" style="width:900px;">
    <div class="label">Wypłata (w PLN):</div>
    <div class="form-input" style="margin-right:10px; width:240px;">
        <input type="text" name="outcome_amount" id="outcome_amount" />
    </div>
    <div class="form-input" style="margin-right:10px; width:110px;">
        <input type="text" name="outcome_date" id="outcome_date" value="<?=date("d-m-Y")?>" />
    </div>
    <div style="margin-top:5px;">
    <a href="#" id="category-picker" style="font-size:0.9em;" class="sniButton">WYBIERZ KATEGORIĘ</a>
    <input style="margin-left:5px;" class="siButton operation-submit" type="submit" value="ZAPISZ" />
    </div>
    
    <div style="clear:both; height:10px;"></div>
    
    <div style="width:200px; float:left;">&nbsp;</div>
    <div style="float:left;">
    <input type="hidden" name="outcome_category" id="outcome_category" value="" />
    Kategoria wypłaty: <strong><span id="outcome_category_name">-</span></strong>
    </div>

    <div style="clear:both; height:10px;"></div>

</section>
</form>

<div id="categories-dialog" class="rounded-corners" style="display:none;">
    <ul>
        
    <?php
        foreach(get_categories() as $cat) {
    ?>
        <li class="category-li" rel="<?=$cat['id']?>">
            <a class="category-icon category-icon-<?=$cat['name']=='Inne' ? '100' : $cat['id']?>"></a><br />
            <span><?=$cat['name']?></span>
        </li>
    <?php
    }
    ?>
    <ul>
    <div style="width:100%; text-align:center;">
        <a href="#" id="close-dialog" class="siButton">Zamknij</a>
    </div>
</div>

<script>
    $(document).ready(function() {
    
        $('#close-dialog').click(function() {
            $('#categories-dialog').hide();
            return false;
        });
        
        $('.category-li').click(function() {
            var catID = $(this).attr('rel');
            var catName = $(this).find('span').html();
            $('#outcome_category_name').html(catName);
            $('#outcome_category').val(catID);
            $('#categories-dialog').hide();
            return false;
        });
        
        $('#category-picker').click(function() {
            $('#categories-dialog').show();
            return false;
        });

        settings = {
                        dateFormat : 'dd-mm-yy',
                        monthNames : ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
                        dayNames : ['Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela'],
                        dayNamesShort : ['Pon', 'Wt', 'Śr', 'Czw', 'Pt', 'Sb', 'Nie'],
                        dayNamesMin :  ['Pon', 'Wt', 'Śr', 'Czw', 'Pt', 'Sb', 'Nie']
                    }
    
        $('#outcome_date').datepicker(settings);
        $('#income_date').datepicker(settings);
    
        $('.operation-submit').click(function() {
            var outcome_amount = $('#outcome_amount').val();
            var outcome_category = $('#outcome_category').val();
            var income_amount = $('#income_amount').val();
            var outcome_date = $('#outcome_date').val();
            var income_date = $('#income_date').val();
            
            if (!outcome_amount && !income_amount) {
                alert('Należy wpisać jakąś kwotę.');
                return false;
            }
            
            if ((outcome_amount && !outcome_category) || (!outcome_amount && outcome_category)) {
                alert('Żeby zapisać wypłatę należy ustalić sumę i kategorię.');
                return false;
            }
            
            if ((outcome_amount && !outcome_date) || (income_amount && !income_date)) {
                alert('Proszę podaj datę.');
                return false;
            }
            
            $('#operation-form').submit();
        });
        
        $("#categories-dialog").position({
               my: "center",
               at: "center",
               of: $('.login-form')
            });      
    });
</script>