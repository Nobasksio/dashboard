<? session_start();
if (version_compare(phpversion(), "5.3.0", ">=") == 1)
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
else
    error_reporting(E_ALL & ~E_NOTICE);
header("Content-type: text/html; charset=utf-8");
//require_once 'right.php';
require_once 'mysqlcon.php';

$sRequest = @mysqli_query($connect_sql,"SELECT * FROM `ss`");

if ($sRequest) {

    while ($row = mysqli_fetch_array($sRequest)) {

        $ss = $row['ss'];
        $Department = $row['Department'];
        $DishName = $row['DishName'];
        $date = $row['date'];
        $arr_name["$Department"]["$DishName"][$date]['ss'] =$ss;

    }
}


//include "loginClass.php";
//include "regu.php"; 

?>
<html >
<head >

    <title >Dashboard</title >
    <meta charset="utf-8" >
    <META Name='keywords' content='Текущий месяц' />
    <META Name='description'
          content='Сервис для разработки меню ресторана. Аналитика, инжиниринг, подсказки профессионалов, четкий поэтапный план.' />
    <link rel="stylesheet" href="dash/assets/vendor/bootstrap/css/bootstrap.min.css" >
    <link href="dash/assets/vendor/fonts/circular-std/style.css" rel="stylesheet" >
    <link rel="stylesheet" href="dash/assets/libs/css/style.css" >
    <link rel="stylesheet" href="dash/assets/vendor/fonts/fontawesome/css/fontawesome-all.css" >
    <link rel="stylesheet" href="dash/assets/vendor/charts/chartist-bundle/chartist.css" >
    <link rel="stylesheet" href="dash/assets/vendor/charts/morris-bundle/morris.css" >
    <link rel="stylesheet" href="dash/assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css" >
    <link rel="stylesheet" href="dash/assets/vendor/charts/c3charts/c3.css" >
    <link rel="stylesheet" href="dash/assets/vendor/fonts/flag-icon-css/flag-icon.min.css" >

    <link rel="stylesheet" href="jqui/jquery-ui.css" >
    <link rel="icon" type="image/png" href="img/fav.png" />
    <script src="jqui/external/jquery/jquery.js" ></script >
    <script src="jqui/jquery-ui.js" ></script >

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js" ></script >
</head >

<body class='' >


<?
foreach($arr_name as $departmen=>$array_dishs){
    print " <div class=\"row\" >
                    <div class=\"col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12\" >
                        <div class=\"page-header\" >
                            <h2 class=\"pageheader-title\" >$departmen</h2 >
          
          
                        </div >
                    </div >
                </div >";

    foreach($array_dishs as $dish_name=>$dish_arr){
        print "
<div class=\"col-xl-9 col-lg-12 col-md-6 col-sm-12 col-12\" >
                            <div class=\"card\" >
                                <h5 class=\"card-header\" >$dish_name</h5 >
                                <div class=\"card-body p-0\" >";
        print "<div class=\"table-responsive\" ><table class='table'> <tr>";
        $arr_ss = array();
        foreach($dish_arr as $date=>$arr_date){
            $Date_update_obj = DateTime::createFromFormat("Y-m-d H:i:s", $date);
            $date = $Date_update_obj->format('d.m.Y');
            print "<th>$date</th>";
            $arr_ss[] = $arr_date['ss'];
        }
        print "</tr><tr>";
        foreach($arr_ss as $ss){
            print "<td>$ss</td>";

        }
        print "</tr></table> </div ></div ></div></div>";
    }


}
?>

<div class='chart' >
    <canvas id="myChart" ></canvas >
</div >
<script type="text/javascript" >

    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'pie',

        // The data for our dataset
        data: {
            labels: <? print $name_cat_obj;?>,
            datasets: [{
                label: "My First dataset",
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(10, 159, 64, 0.2)',
                    'rgba(125, 27, 64, 0.2)',
                    'rgba(16, 159, 64, 0.2)',
                    'rgba(255, 43, 64, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(34, 12, 64, 0.2)',
                    'rgba(198, 159, 64, 0.2)',
                    'rgba(87, 5, 64, 0.2)'
                ],
                borderColor: 'rgb(255, 99, 132)',
                data: <? print $title; ?>,
            }],
            title:<? print $title?>,
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(10, 159, 64, 1)',
                'rgba(125, 27, 64, 1)',
                'rgba(16, 159, 64, 1)',
                'rgba(255, 43, 64, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(34, 12, 64, 1)',
                'rgba(198, 159, 64, 1)',
                'rgba(87, 5, 64, 1)'
            ]
        },

        // Configuration options go here
        options: {}
    });

</script >

</body >
</html >
