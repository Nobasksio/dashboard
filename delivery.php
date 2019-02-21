<?
require_once 'mysqlcon.php';

require_once 'classes/order.php';

$sRequest = @mysqli_query($connect_sql, "SELECT * FROM `today`");

$array_day_hours = array("09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "00", "01");
$array_night_hours = array("01", "02", "03", "04", "05", "06", "07", "08", "09");
$array_podrazdel = array('Култукская', 'Лермонтова 275', 'Солнечный', 'Розы Люксембург');
$kol_dost = 0;
if ($sRequest) {

    while ($row = mysqli_fetch_array($sRequest)) {
        $Otkaz = $row['Otkaz'];
        $this_id = $row['nomer'];


        $Hour = order::clearDate($row['VremayGotovPoSetke'], 'H');
        $Day_k = order::clearDate($row['VremayGotovPoSetke'], 'd');
        $today_day = date('d');
        $now_hour = date('H');

        $Day_d = order::clearDate($row['DateDost'], 'd');
        $hour_d = order::clearDate($row['DateDost'], 'H');;

        $Date_update = order::clearDate($row['date_update'], 'd.m.Y');
        $h_update = order::clearDate($row['date_update'], 'H') + 5;
        $m_update = order::clearDate($row['date_update'], 'i');
        $time_update = $h_update . ":" . $m_update;

        $datetime1 = new DateTime($row['VremayGotovPoSetke']);
        $datetime2 = new DateTime($row['DateDost']);
        $interval = $datetime1->diff($datetime2);
        $delivery_time = $interval->format('%i');

        $time_koord = new DateTime($row['koord']);
        $time_dost = new DateTime($row['done']);


//print"$this_id $Day_k $Hour {$row['summa']}<br>";
        if ($Otkaz == 0) {

            if (($Day_k >= $today_day) and ($Day_d == $today_day)) {


                $SposobDost = $row['SposobDost'];
                $podrazdel = $row['Podrazdel'];


                $Time_viezd = order::isDate($row['DataTimeVezda']);
                $driver_phone = $row['MU'];
                if ($driver_phone != '') {
                    $array_vezd[$podrazdel][$driver_phone][] = $Time_viezd;
                }


                $summ["$this_id"] = $row['summa'];

                $SposobDost = $row['SposobDost'];
                if ($SposobDost == 'Доставка') {
                    $kol_dost++;
                    if (($row['koord'] != NULL) and ($row['done'] != NULL)) {
                        $interval = $time_koord->diff($time_dost);
                        $delivery_time_real = $interval->format('%i');

                        $Array_real_dost[] = $delivery_time_real;
                    }
                    else {

                    }
                }

                $sposob_arr[$SposobDost]++;


                $DriverName = $row['DriverName'];
                if ($DriverName != NULL) {
                    $arr_driver[$DriverName][] = $this_id;
                }

                $arr_name[$podrazdel]["$this_id"] = $row['summa'];

                $arr_for_hours[$podrazdel][$Hour][] = $row['summa'];
                $hours_arr[$Hour][$this_id] = $row['summa'];
                //print "$today_str";
                if (!isset($dost_arr[$podrazdel][$delivery_time])) {
                    $dost_arr[$podrazdel][$delivery_time] = 0;
                }
                $dost_arr[$podrazdel][$delivery_time] = $dost_arr[$podrazdel][$delivery_time] + 1;


                if ($row['opozd_kuh'] > 4) {
                    $opozd_kuh_ar[] = $this_id;


                    $opozd_kuh_ar_podr[$podrazdel][$DriverName][] = array(
                        'id' => $this_id,
                        'adress' => $adress,
                        'time_dost' => $row['DateDost'],
                        'opozd' => $row['opozd_kuh']
                    );
                }
                if ($row['opozd_dost'] > 4) {

                    $prichina_op = $row['PrichinaOpozdania'];
                    $opozd_dost_ar[] = $this_id;
                    $adress = $row['adress'] . " " . $row['dom'];
                    $opozd_dost_ar_podr[$podrazdel][$DriverName][] = array(
                        'id' => $this_id,
                        'adress' => $adress,
                        'time_dost' => $row['DateDost'],
                        'hour' => $hour_d,
                        'prichina' => $prichina_op,
                        'opozd_k' => $row['opozd_kuh'],
                        'opozd_d' => $row['opozd_dost'],
                    );
                    //print "$hour_d $podrazdel<br>";
                    $opozd_dost_ar_podr_hour[$hour_d][$podrazdel][] = array(
                        'id' => $this_id,
                        'adress' => $adress,
                        'time_dost' => $row['DateDost'],
                        'driver' => $DriverName,
                        'prichina' => $prichina_op,
                        'opozd_k' => $row['opozd_kuh'],
                        'opozd_d' => $row['opozd_dost'],
                    );
                }
            }

        } else {
            if ($Hour > 8) {
                if ($row['KorrekZakaz'] == '') {
                    //$arr_name[$podrazdel]["$this_id"] = $row['summa'];
                    if ($row['PrichinaOtmena'] != '') {
                        $podrazdel = $row['Podrazdel'];
                        $otkaz_arr[$this_id] = $row['PrichinaOtmena'];
                        $otkaz_arr_operator[$this_id] = $row['PrichinaOtmenaPolzovatel'];
                        $PrichinaOtmena = $row['PrichinaOtmena'];
                        $operator = $row['PrichinaOtmenaPolzovatel'];

                        $otkaz_arr_operator_all[$podrazdel][$operator][] = array(
                            'id' => $this_id,
                            'prichina' => $PrichinaOtmena,
                            'otmena' => $row['otmena']
                        );
                    } else {

                    }

                }
            }
        }


    }
}
//var_dump($otkaz_arr_operator_all);

foreach ($arr_name as $podrazdel => $order_ar) {
    $podrazdel_summ = array_sum($order_ar);
    $podrazdel_count = count($order_ar);

    if ($podrazdel == 'Култукская') {
        ksort($order_ar);

    }
}

$vir = array_sum($summ);
$count = count($summ);
$opozd_k = count($opozd_kuh_ar);
$opozd_k_per = round($opozd_k / ($count / 100), 2);
$opozd_d = count($opozd_dost_ar);
$opozd_d_per = round($opozd_d / ($count / 100), 2);

//print"выручка $vir,<br> количество $count, <br> опоздание кухни $opozd_k ({$opozd_k_per}%), <br>опоздание доставка $opozd_d ({$opozd_d_per}%),<br>";

$count_dr = count($arr_driver);

foreach ($array_day_hours as $hour) {

    if (isset($opozd_dost_ar_podr_hour[$hour])) {
        foreach ($array_podrazdel as $podrazdel) {
            $late[$podrazdel] = 0;
            if (isset($opozd_dost_ar_podr_hour[$hour][$podrazdel])) {
                //print "у boy";
                $late[$podrazdel] = count($opozd_dost_ar_podr_hour[$hour][$podrazdel]);
            } else {
                $late[$podrazdel] = 0;
                //print "blya";
            }

        }
        $arr_late_podr[] = array(
            'hour' => $hour . ":00",
            'kul' => $late['Култукская'],
            'sol' => $late['Солнечный'],
            'sig' => $late['Лермонтова 275'],
            'roz' => $late['Розы Люксембург'],

        );

    } else {
        $arr_late_podr[] = array(
            'hour' => $hour . ":00",
            'kul' => 0,
            'sol' => 0,
            'sig' => 0,
            'roz' => 0,

        );
    }
}

foreach ($dost_arr as $podrazdel => $min_arr) {
    foreach ($min_arr as $min => $summa) {
        $sum[$min] = $summa;
    }
    $arr_deliveri_time[$podrazdel] = "
     ['20 m ({$sum["20"]})',{$sum["20"]}],
     ['30 m ({$sum["30"]})', {$sum["30"]}],
    ['40 m ({$sum["40"]})', {$sum["40"]}]";
}


//var_dump($arr_hours_podr['Култукская']);

$json_late = json_encode($arr_late_podr);

$json_k_delivery = $arr_deliveri_time['Култукская'];
$json_l_delivery = $arr_deliveri_time['Лермонтова 275'];
$json_s_delivery = $arr_deliveri_time['Солнечный'];
$json_r_delivery = $arr_deliveri_time['Розы Люксембург'];


$json_l = json_encode($arr_late_podr['Лермонтова 275']);
$json_s = json_encode($arr_late_podr['Солнечный']);
$json_r = json_encode($arr_late_podr['Розы Люксембург']);



$per_isdata_time_dost = round(count($Array_real_dost)/($kol_dost/100),2);
$average = round(array_sum($Array_real_dost)/count($Array_real_dost),2);

//print($json_k);
?>
<?=$json_late?>
<!doctype html>
<html lang="en" >


<head >
    <!-- Required meta tags -->
    <meta charset="utf-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" >
    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="dash/assets/vendor/bootstrap/css/bootstrap.min.css" >
    <link rel="stylesheet" href="my_style_dash.css" >
    <link href="dash/assets/vendor/fonts/circular-std/style.css" rel="stylesheet" >
    <link rel="stylesheet" href="dash/assets/libs/css/style.css" >
    <link rel="stylesheet" href="dash/assets/vendor/fonts/fontawesome/css/fontawesome-all.css" >
    <link rel="stylesheet" href="dash/assets/vendor/charts/chartist-bundle/chartist.css" >
    <link rel="stylesheet" href="dash/assets/vendor/charts/morris-bundle/morris.css" >
    <link rel="stylesheet" href="dash/assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css" >
    <link rel="stylesheet" href="dash/assets/vendor/charts/c3charts/c3.css" >
    <link rel="stylesheet" href="dash/assets/vendor/fonts/flag-icon-css/flag-icon.min.css" >
    <title >Доставка дашбоард</title >
</head >

<body >
<!-- ============================================================== -->
<!-- main wrapper -->
<!-- ============================================================== -->
<div class="dashboard-main-wrapper" >
    <!-- ============================================================== -->
    <!-- navbar -->
    <!-- ============================================================== -->
    <div class="dashboard-header" >
        <nav class="navbar navbar-expand-lg bg-white fixed-top" >
            <a class="navbar-brand" href="index.html" >Доставка</a >
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" >
                <span class="navbar-toggler-icon" ></span >
            </button >

        </nav >
    </div >
    <!-- ============================================================== -->
    <!-- end navbar -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- left sidebar -->
    <!-- ============================================================== -->
    <div class="nav-left-sidebar sidebar-dark" >
        <div class="menu-list" >
            <nav class="navbar navbar-expand-lg navbar-light" >
                <a class="d-xl-none d-lg-none" href="#" >Dashboard</a >
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" >
                    <span class="navbar-toggler-icon" ></span >
                </button >
                <div class="collapse navbar-collapse" id="navbarNav" >
                    <ul class="navbar-nav flex-column" >
                        <li class="nav-divider" >
                            Меню
                        </li >
                        <li class="nav-item " >

                            <a class="nav-link active" href="/dash/"
                               aria-controls="submenu-1" ><i
                                        class="fa fa-fw fa-user-circle" ></i >Общая информация <span
                                        class="badge badge-success" >6</span ></a >
                        </li >
                        <li class="nav-item " >
                            <a class="nav-link active" href="/dash/delivery.php"
                               aria-controls="submenu-1" ><i
                                        class="fa fa-fw fa-user-circle" ></i >Доставка <span
                                        class="badge badge-success" >6</span ></a >
                        </li >
                        <li class="nav-item " >
                            <a class="nav-link active" href="/dash/marketing.php"
                               aria-controls="submenu-1" ><i
                                        class="fa fa-fw fa-user-circle" ></i >Маркетинг <span
                                        class="badge badge-success" >6</span ></a >
                        </li >
                        <!--
                           <li class="nav-item">
                               <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2"><i class="fa fa-fw fa-rocket"></i>UI Elements</a>
                               <div id="submenu-2" class="collapse submenu" style="">
                                   <ul class="nav flex-column">
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/cards.html">Cards <span class="badge badge-secondary">New</span></a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/general.html">General</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/carousel.html">Carousel</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/listgroup.html">List Group</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/typography.html">Typography</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/accordions.html">Accordions</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/tabs.html">Tabs</a>
                                       </li>
                                   </ul>
                               </div>
                           </li>
                           <li class="nav-item">
                               <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-3" aria-controls="submenu-3"><i class="fas fa-fw fa-chart-pie"></i>Chart</a>
                               <div id="submenu-3" class="collapse submenu" style="">
                                   <ul class="nav flex-column">
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/chart-c3.html">C3 Charts</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/chart-chartist.html">Chartist Charts</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/chart-charts.html">Chart</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/chart-morris.html">Morris</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/chart-sparkline.html">Sparkline</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/chart-gauge.html">Guage</a>
                                       </li>
                                   </ul>
                               </div>
                           </li>
                           <li class="nav-item ">
                               <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-4" aria-controls="submenu-4"><i class="fab fa-fw fa-wpforms"></i>Forms</a>
                               <div id="submenu-4" class="collapse submenu" style="">
                                   <ul class="nav flex-column">
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/form-elements.html">Form Elements</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/form-validation.html">Parsely Validations</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/multiselect.html">Multiselect</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/datepicker.html">Date Picker</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/bootstrap-select.html">Bootstrap Select</a>
                                       </li>
                                   </ul>
                               </div>
                           </li>
                           <li class="nav-item">
                               <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-5" aria-controls="submenu-5"><i class="fas fa-fw fa-table"></i>Tables</a>
                               <div id="submenu-5" class="collapse submenu" style="">
                                   <ul class="nav flex-column">
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/general-table.html">General Tables</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/data-tables.html">Data Tables</a>
                                       </li>
                                   </ul>
                               </div>
                           </li>
                           <li class="nav-divider">
                               Features
                           </li>
                           <li class="nav-item">
                               <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-6" aria-controls="submenu-6"><i class="fas fa-fw fa-file"></i> Pages </a>
                               <div id="submenu-6" class="collapse submenu" style="">
                                   <ul class="nav flex-column">
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/blank-page.html">Blank Page</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/blank-page-header.html">Blank Page Header</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/login.html">Login</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/404-page.html">404 page</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/sign-up.html">Sign up Page</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/forgot-password.html">Forgot Password</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/pricing.html">Pricing Tables</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/timeline.html">Timeline</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/calendar.html">Calendar</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/sortable-nestable-lists.html">Sortable/Nestable List</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/widgets.html">Widgets</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/media-object.html">Media Objects</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/cropper-image.html">Cropper</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/color-picker.html">Color Picker</a>
                                       </li>
                                   </ul>
                               </div>
                           </li>
                           <li class="nav-item">
                               <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-7" aria-controls="submenu-7"><i class="fas fa-fw fa-inbox"></i>Apps <span class="badge badge-secondary">New</span></a>
                               <div id="submenu-7" class="collapse submenu" style="">
                                   <ul class="nav flex-column">
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/inbox.html">Inbox</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/email-details.html">Email Detail</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/email-compose.html">Email Compose</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/message-chat.html">Message Chat</a>
                                       </li>
                                   </ul>
                               </div>
                           </li>
                           <li class="nav-item">
                               <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-8" aria-controls="submenu-8"><i class="fas fa-fw fa-columns"></i>Icons</a>
                               <div id="submenu-8" class="collapse submenu" style="">
                                   <ul class="nav flex-column">
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/icon-fontawesome.html">FontAwesome Icons</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/icon-material.html">Material Icons</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/icon-simple-lineicon.html">Simpleline Icon</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/icon-themify.html">Themify Icon</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/icon-flag.html">Flag Icons</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/icon-weather.html">Weather Icon</a>
                                       </li>
                                   </ul>
                               </div>
                           </li>
                           <li class="nav-item">
                               <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-9" aria-controls="submenu-9"><i class="fas fa-fw fa-map-marker-alt"></i>Maps</a>
                               <div id="submenu-9" class="collapse submenu" style="">
                                   <ul class="nav flex-column">
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/map-google.html">Google Maps</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="pages/map-vector.html">Vector Maps</a>
                                       </li>
                                   </ul>
                               </div>
                           </li>
                           <li class="nav-item">
                               <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-10" aria-controls="submenu-10"><i class="fas fa-f fa-folder"></i>Menu Level</a>
                               <div id="submenu-10" class="collapse submenu" style="">
                                   <ul class="nav flex-column">
                                       <li class="nav-item">
                                           <a class="nav-link" href="#">Level 1</a>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-11" aria-controls="submenu-11">Level 2</a>
                                           <div id="submenu-11" class="collapse submenu" style="">
                                               <ul class="nav flex-column">
                                                   <li class="nav-item">
                                                       <a class="nav-link" href="#">Level 1</a>
                                                   </li>
                                                   <li class="nav-item">
                                                       <a class="nav-link" href="#">Level 2</a>
                                                   </li>
                                               </ul>
                                           </div>
                                       </li>
                                       <li class="nav-item">
                                           <a class="nav-link" href="#">Level 3</a>
                                       </li>
                                   </ul>
                               </div>
                           </li>-->
                    </ul >
                </div >
            </nav >
        </div >
    </div >
    <!-- ============================================================== -->
    <!-- end left sidebar -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- wrapper  -->
    <!-- ============================================================== -->
    <div class="dashboard-wrapper" >
        <div class="dashboard-ecommerce" >
            <div class="container-fluid dashboard-content " >
                <!-- ============================================================== -->
                <!-- pageheader  -->
                <!-- ============================================================== -->
                <div class="row" >
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" >
                        <div class="page-header" >
                            <h2 class="pageheader-title" >Общая инофрмация</h2 >
                            <p class="pageheader-text" >Nulla euismod urna eros, sit amet scelerisque torton lectus vel
                                mauris facilisis faucibus at enim quis massa lobortis rutrum.</p >
                            <div class="page-breadcrumb" >
                                <nav aria-label="breadcrumb" >
                                    <ol class="breadcrumb" >
                                        <li class="breadcrumb-item active" aria-current="page" >Данные
                                            за <?= $Date_update ?>
                                            (обновлено в <?= $time_update ?>)
                                        </li >
                                    </ol >
                                </nav >
                            </div >
                        </div >
                    </div >
                </div >
                <!-- ============================================================== -->
                <!-- end pageheader  -->
                <!-- ============================================================== -->
                <div class="ecommerce-widget" >


                    <div class="row" >
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12" >
                            <div class="card" >
                                <h5 class="card-header" >Култукская время доставки</h5 >
                                <div class="card-body" >
                                    <div id="kul" style="height: 300px;" ></div >
                                </div >
                            </div >
                        </div >
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12" >
                            <div class="card" >
                                <h5 class="card-header" >Сигма время доставки</h5 >
                                <div class="card-body" >
                                    <div id="sig" style="height: 300px;" ></div >
                                </div >
                            </div >
                        </div >
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12" >
                            <div class="card" >
                                <h5 class="card-header" >Солнечный время доставки</h5 >
                                <div class="card-body" >
                                    <div id="sol" style="height: 300px;" ></div >
                                </div >
                            </div >
                        </div >
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12" >
                            <div class="card" >
                                <h5 class="card-header" >Новоленино время доставки</h5 >
                                <div class="card-body" >
                                    <div id="roz" style="height: 300px;" ></div >
                                </div >
                            </div >
                        </div >


                    </div >
                    <div class="row" >
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->

                        <!-- recent orders  -->
                        <!-- ============================================================== -->


                        <div class="col-xl-6  col-lg-7 col-md-12 col-sm-12 col-12" >
                            <div class="card" >
                                <h5 class="card-header" > Опоздание </h5 >
                                <div class="card-body" >
                                    <div id="myfirstchartK" ></div >
                                </div >
                                <div class="card-footer" >
                                    <p class="display-7 font-weight-bold" ><span
                                                class="text-primary d-inline-block" ><? ?>
                                            руб</span >
                                        <span
                                                class="text-success float-right" ><? ?>
                                            шт</span ></p >
                                </div >
                            </div >
                        </div >
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12" >
                            <div class="card" >
                                <div class="card-body" >
                                    <h5 class="text-muted" >Среднее время доставки</h5 >
                                    <div class="metric-value d-inline-block" >
                                        <h1 class="mb-1" ><?= $average ?> мин</h1 >
                                    </div >
                                    <div class="metric-label d-inline-block float-right text-success font-weight-bold" >
                                        <!--   <span ><i class="fa fa-fw fa-arrow-up" ></i ></span ><span >5.86%</span >-->
                                    </div >
                                </div >
                                <!-- <div id="sparkline-revenue" ></div >-->
                            </div >
                        </div >
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12" >
                            <div class="card" >
                                <div class="card-body" >
                                    <h5 class="text-muted" >Точность времени доставки</h5 >
                                    <div class="metric-value d-inline-block" >
                                        <h1 class="mb-1" ><?= $per_isdata_time_dost ?>%</h1 >
                                    </div >
                                    <div class="metric-label d-inline-block float-right text-success font-weight-bold" >
                                        <!--   <span ><i class="fa fa-fw fa-arrow-up" ></i ></span ><span >5.86%</span >-->
                                    </div >
                                </div >
                                <!-- <div id="sparkline-revenue" ></div >-->
                            </div >
                        </div >





                        <div class="col-xl-9 col-lg-12 col-md-6 col-sm-12 col-12" >
                            <div class="card" >
                                <h5 class="card-header" >Вьезд в зону</h5 >
                                <div class="card-body p-0" >
                                    <div class="table-responsive" >
                                        <table class="table" >
                                            <thead class="bg-light" >
                                            <tr class="border-0" >
                                                <th class="border-0" >#</th >
                                                <th class="border-0" >Подраздление</th >
                                                <th class="border-0" >Телефон</th >
                                                <th class="border-0" >Всего заказов</th >
                                                <th class="border-0" >Есть въезд</th >
                                                <th class="border-0" >%</th >
                                            </tr >
                                            </thead >
                                            <tbody >
                                            <? $i = 0;

                                            if (isset($array_vezd)) {
                                                foreach ($array_vezd as $podrazdel => $array_vezd_podr) {

                                                    foreach ($array_vezd_podr as $odriver_nover => $array_viezs_one) {
                                                        $kol = count($array_viezs_one);
                                                        $uspeh_kol = 0;
                                                        foreach ($array_viezs_one as $uspeh) {
                                                            if ($uspeh) $uspeh_kol++;

                                                        }
                                                        $i++;
                                                        $per = round($uspeh_kol / ($kol / 100), 2);
                                                        //var_dump($otmen_array2);

                                                        if ($per < 50) $style = 'danger';
                                                        elseif ($per < 80) $style = 'warning';
                                                        else $style = 'success';
                                                        print "    
                                                    <tr class='$style'>
                                                        <td > $i</td >
                                                        <td >
                                                            $podrazdel
                                                        </td >
                                                        <td >$odriver_nover</td >
                                                        <td >$kol</td >
                                                        <td >{$uspeh_kol}</td >
                                                        <td >{$per}%</td >
    
                                                        <!--<td >Patricia J. King</td >
                                                        <td ><span class='badge-dot badge-brand mr-1' ></span >InTransit</td >-->
                                                    </tr >";

                                                    }
                                                }
                                            } else {
                                                print "
                                                <tr>
                                                <td colspan=\"7\">Опозданий нет</td>
                                                </tr>";
                                            }


                                            ?>

                                            <tr >
                                                <td colspan="9" ><a href="#" class="btn btn-outline-light float-right" >View
                                                        Details</a ></td >
                                            </tr >
                                            </tbody >
                                        </table >
                                    </div >
                                </div >
                            </div >
                        </div >


                    </div >

                </div >
            </div >
        </div >
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <div class="footer" >
            <div class="container-fluid" >
                <div class="row" >
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12" >
                        Copyright © 2018 Concept. All rights reserved. Dashboard by <a href="https://colorlib.com/wp/" >Colorlib</a >.
                    </div >
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12" >
                        <div class="text-md-right footer-links d-none d-sm-block" >
                            <a href="javascript: void(0);" >About</a >
                            <a href="javascript: void(0);" >Support</a >
                            <a href="javascript: void(0);" >Contact Us</a >
                        </div >
                    </div >
                </div >
            </div >
        </div >
        <!-- ============================================================== -->
        <!-- end footer -->
        <!-- ============================================================== -->
    </div >
    <!-- ============================================================== -->
    <!-- end wrapper  -->
    <!-- ============================================================== -->
</div >
<!-- ============================================================== -->
<!-- end main wrapper  -->
<!-- ============================================================== -->
<!-- Optional JavaScript -->
<!-- jquery 3.3.1 -->
<script src="dash/assets/vendor/jquery/jquery-3.3.1.min.js" ></script >
<!-- bootstap bundle js -->
<script src="dash/assets/vendor/bootstrap/js/bootstrap.bundle.js" ></script >
<!-- slimscroll js -->
<script src="dash/assets/vendor/slimscroll/jquery.slimscroll.js" ></script >
<!-- main js -->
<script src="dash/assets/libs/js/main-js.js" ></script >
<!-- chart chartist js -->
<!--<script src="dash/assets/vendor/charts/chartist-bundle/chartist.min.js" ></script >-->
<!-- sparkline js -->
<script src="dash/assets/vendor/charts/sparkline/jquery.sparkline.js" ></script >
<!-- morris js -->
<script src="dash/assets/vendor/charts/morris-bundle/raphael.min.js" ></script >
<script src="dash/assets/vendor/charts/morris-bundle/morris.js" ></script >
<!-- chart c3 js -->
<script src="dash/assets/vendor/charts/c3charts/c3.min.js" ></script >
<script src="dash/assets/vendor/charts/c3charts/d3-5.4.0.min.js" ></script >
<script src="dash/assets/vendor/charts/c3charts/C3chartjs.js" ></script >
<!--<script src="dash/assets/libs/js/dashboard-ecommerce.js" ></script >-->

<script type="application/javascript" >
    $(function () {
        new Morris.Line({
            // ID of the element in which to draw the chart.
            element: 'myfirstchartK',
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.
            data: <?=$json_late?>,
            // The name of the data record attribute that contains x-values.
            xkey: 'hour',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['kul', 'sol', 'roz', 'sig'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['kul', 'sol', 'roz', 'sig'],
            parseTime: false

        });

        var chart = c3.generate({
            bindto: "#kul",
            color: {pattern: ["#5969ff", "#ff407b", "#25d5f2", "#ffc750"]},
            data: {
                // iris data from R
                columns: [
                    <?= $json_k_delivery?>,]
                ,
                type: 'pie',


            }
        });
        var chart2 = c3.generate({
            bindto: "#sol",
            color: {pattern: ["#5969ff", "#ff407b", "#25d5f2", "#ffc750"]},
            data: {
                // iris data from R
                columns: [
                    <?= $json_s_delivery?>,]
                ,
                type: 'pie',
                labels: true
            }
        });
        var chart3 = c3.generate({
            bindto: "#sig",
            color: {pattern: ["#5969ff", "#ff407b", "#25d5f2", "#ffc750"]},
            data: {
                // iris data from R
                columns: [
                    <?= $json_l_delivery?>,]
                ,
                type: 'pie',


            },

        });
        var chart4 = c3.generate({
            bindto: "#roz",
            color: {pattern: ["#5969ff", "#ff407b", "#25d5f2", "#ffc750"]},
            data: {
                // iris data from R
                columns: [
                    <?= $json_r_delivery?>,]
                ,
                type: 'pie',

            }
        });
    })

</script >
</body >

</html >