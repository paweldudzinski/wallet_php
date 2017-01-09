<?php
    $logged_user = get_logged_user();
    $selected_category_id = $_GET['category'];
    
    if ($logged_user == 0) {
        header('Location: index.php');
    }
    
    $dashboard = get_overall_outcome_by_category($logged_user);
    
    if (isset($selected_category_id)) {
        $per_category_chart = get_outcome_by_category_and_wallets($logged_user, $selected_category_id);
        $per_category_chart_amounts = Array();
        foreach($per_category_chart as $pcc) {
            array_push($per_category_chart_amounts, (int)$pcc['amount']);
        }
    }
?>

<h1>Statystyki</h1>

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
    
    if (sizeof($per_category_chart)>0) {
        $MAX_WALLET_SUM_FOR_CATEGORY = max($per_category_chart_amounts);
        $JEDNOSTKA2 = (float)$BAR_WIDTH / (float)$MAX_WALLET_SUM_FOR_CATEGORY;
    }
?>

<form action="portal.php" method="GET" id="dashboard-form">
<section id="line-chart" class="rounded-corners add-opacity bg-white" style="position:relative; display:none; padding-top:1px;">
    <h4>Ogólny rozkład wydatków per kategoria</h4>
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

<section id="bar-chart" class="rounded-corners add-opacity bg-white" style="position:relative; padding-top:1px;">
    <h4>Ogólny rozkład wydatków per kategoria</h4>
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

<section id="bar-chart" class="rounded-corners add-opacity bg-white" style="position:relative; padding-top:1px;">
    <h4>Szczegółowy rozkład wydatków per portfel i kategoria
    <select name="category" id="category-select" style="margin-left:20px;" class="small-select">
        <option value="">-------- wybierz kategorię --------</option>
        <?php
            foreach(get_categories() as $cat) {
        ?>
            <option value="<?=$cat['id']?>" <?=($cat['id'] == $selected_category_id) ? 'selected="selected"' : ''?>>
                <?=$cat['name']?>
            </option>
        <?php
        }
        ?>
    </select>
    <input type="hidden" name="page" value="stats" />
    </h4>
    
    <?php
        if (sizeof($per_category_chart)>0 && isset($selected_category_id)) {
            foreach ($per_category_chart as $d) {
    ?>
        <div class="category-name"><?=mb_convert_case($d['wallet_name'], MB_CASE_UPPER, "UTF-8");?></div>
        <div class="bar-space">
            <div title="" 
                class="bar cat-<?=$selected_category_id;?>" style="width:<?=(int)($JEDNOSTKA2*$d['amount'])?>px;"></div>
            <div class="amount">
                <strong><?=number_format($d['amount'], $decimals=2, $dec_point='.', $thousands_sep='');?> PLN</strong>
            </div>
        </div>
        <div style="clear:both; height:10px;"></div>
    <?php
    }}
    ?>
    
    <?php
    if (!sizeof($per_category_chart) && isset($selected_category_id)) {
        echo "<em>brak wyników</strong></em>";
    }
    ?>
</section>
</form>

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
        
        $('#category-select').change(function() {
            $('#dashboard-form').submit();
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
