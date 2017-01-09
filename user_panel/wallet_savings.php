<?php
    $logged_user = get_logged_user();
    
    if ($logged_user == 0) {
        header('Location: index.php');
    }
    
    $savings_from_db = get_savings($logged_user);
    
    $overall_balance = 0;
    $closed_wallets = get_closed_wallets($logged_user);
    foreach ($closed_wallets as $wallet) {
        $overall_balance = $overall_balance + bilance($wallet['id']);
    }
    $dashboard = get_savings_outcome_by_category($logged_user);
?>
<div style="float:left;">
    <h1 style="margin:0px;">Oszczędności</h1>
</div>
<div style="float:right; margin-top:15px;">
    Aktualny bilans:
    <span class="bigger <?=get_class_by_bilance_include_white(bilance($overall_balance));?>">
    <?=number_format($overall_balance, $decimals=2, $dec_point='.', $thousands_sep='');?> PLN
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

<?php
    if (!$dashboard) {
        echo "Nie zarejestrowano operacji.<br />";
        exit(0);
    }
?>

<?php
    #ADDITIONAL VARS
    $BAR_WIDTH = 630;
    $MAX_WALLET_SUM_PER_CATEGORY = $dashboard[0][2];
    $JEDNOSTKA = (float)$BAR_WIDTH / (float)$MAX_WALLET_SUM_PER_CATEGORY;
?>

<section id="line-chart" class="rounded-corners add-opacity bg-white" style="position:relative; display:none;;">
    <div class="chart-menu" style="position:absolute; top:20px; right:20px;">
        <a href="#" id="shared-dashboard-pie" class="shared shared-dashboard-pie"></a>
        <span class="shared shared-dashboard-line"></span>
    </div>
    <?php
        foreach($dashboard as $d) {
    ?>
        <div class="category-name"><?=$d['category_name']?></div>
        <div class="bar-space">
            <div title="" 
                class="bar cat-<?=$d['category_id']?>" style="width:<?=(int)($JEDNOSTKA*$d['amount'])?>px;"></div>
            <div class="amount"> 
                <strong><?=number_format($d['amount'], $decimals=2, $dec_point='.', $thousands_sep='');?> PLN</strong>
            </div>
        </div>
        <div style="clear:both; height:10px;"></div>
    <?php
    }
    ?>
</section>

<section id="bar-chart" class="rounded-corners add-opacity bg-white" style="position:relative;">
    <div class="chart-menu" style="position:absolute; top:20px; right:20px;">
        <span class="shared shared-dashboard-pie"></span>
        <a href="#" id="shared-dashboard-line" class="shared shared-dashboard-line"></a>
    </div>
    <div id="chart_div" style="width: 600px; height: 250px; min-width: 600px; min-height: 250px; text-align:center; margin:0px auto;">
        <div style="padding-top:110px;">
        <img src="images/loader.gif" />
        </div>
    </div>
</section>

<section class="rounded-corners add-opacity bg-white">
<strong style="font-size:0.8em;">Historia wszystkich operacji w ramach oszczędności</strong><br />
<span style="font-size:0.8em;" class="gray">Tu możesz usunąć operację jeżeli dane zostały błędnie wprowadzone</span>

<table class="operations">
    <tr>
        <th style="width:150px;">Data</th>
        <th style="width:200px;">Kwota</th>
        <th style="width:520px;">Cel</th>
        <th style="width:50px;"></th>
    </tr>
<?php 
    foreach(savings_operations($logged_user) as $o) {
?>
    <tr>
        <td style="text-align:center;"><?=date("d-m-Y",strtotime($o['when_created']));?></td>
        <td style="text-align:right; padding-right:20px;">
            <?php
                echo '<span class="red">-'.number_format($o["amount"], $decimals=2, $dec_point=".", $thousands_sep="").' PLN</span>';
            ?>
        </td>
        <td style="padding-left:20px;">
            <?php
                if ($o['category_id']) {
                    echo print_category($o['category_id']);
                } else {
                    echo "<em>?</em>";
                }
            ?>
        </td>
        <td style="text-align:center;">
            <a class="confirm" href="user_panel/delete_savings_operation.php?id=<?=$o['id']?>">
            <img src="images/delete-icon.png" />
            </a>
        </td>
    </tr>
<?php
}
?>
</table>
</section>


<?php
if ($dashboard) {
?>

<script>
    $('#shared-dashboard-pie').click(function() {
        $('#line-chart').hide();
        $('#bar-chart').show();
        return false;
    });
    
    $('#shared-dashboard-line').click(function() {
        $('#line-chart').show();
        $('#bar-chart').hide();
        return false;
    });
    
    $(document).ready(function() {
        $('#shared-dashboard-pie').click(function() {
            $('#line-chart').hide();
            $('#bar-chart').show();
            return false;
        });
        
        $('#shared-dashboard-line').click(function() {
            $('#line-chart').show();
            $('#bar-chart').hide();
            return false;
        });
    });
</script>

<?php
    $colors_for_categories = Array(
            1 => '#FF4848',
            2 => '#FF68DD',
            3 => '#9A03FE',
            4 => '#2966B8',
            5 => '#62D0FF',
            6 => '#0AFE47',
            7 => '#9D9D00',
            8 => '#1FCB4A',
            9 => '#FFB428',
            10 => '#FF800D',
            11 => '#C47557',
            12 => '#4A9586',
            13 => '#B96F6F',
            14 => '#D1D17A',
            15 => '#BABA21',
            16 => '#C27E3A',
            17 => '#C47557',
            18 => '#B05F3C',
            19 => '#B05F3C',
            20 => '#ABFF73',
            21 => '#A5FF8A',
            22 => '#93EEAA',
            100 => '#93EEAA'
    );
        
    $slices = "{";
    
    
    for ($i=0; $i<=sizeof($dashboard)-1; $i++) {
        $color = isset($colors_for_categories[$dashboard[$i]['category_id']]) ? $colors_for_categories[$dashboard[$i]['category_id']] : 'white';
        $slices.= "$i: {color : '$color'}, ";
    }
    $slices.= '}';
    
?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Wydatki', 'Wydatki per kategoria'],
      
      <?php
        foreach($dashboard as $d) {
            $line = '[';
            $line.= "'".$d['category_name']."', ".number_format($d['amount'], $decimals=2, $dec_point='.', $thousands_sep='');
            $line.= '],';
            echo $line; 
        }
      ?>
    ]);



    var options = { 
        legend : {position: 'right', alignment:  'center', textStyle: {color: 'black', fontSize: 14}},
        slices: <?=$slices;?>,
        titleTextStyle: {fontSize: 10},
        chartArea: { left: 0, top: 2, width: "100%", height: "100%"},
        backgroundColor : { fill: '#E5E5E5' }
      };

    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  }
</script>

<?php
}
?>
