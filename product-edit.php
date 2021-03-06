<?php
require_once 'header.php';
$pageoffice = 'all';           //设置页面属性 office ：  nc, sh, all
$pagelevel = 1;       // //设置页面等级 0： 只有admin可以访问； 1：库存系统用户； 2:代发用户
check_session_expiration();
$user = $_SESSION['user_info']['userid'];
$fn = $_SESSION['user_info']['firstname'];
$ln = $_SESSION['user_info']['lastname'];
$useroffice = $_SESSION['user_info']['office'];
$userlevel = $_SESSION['user_info']['level'];           //userlevel  0: admin; else;
$cmpid = $_SESSION['user_info']['cmpid'];
$childid = $_SESSION['user_info']['childid'];
check_access($useroffice, $userlevel, $pageoffice, $pagelevel);

// 换cmpid在页面顶端
if (sizeof($childid) > 1) {
    foreach ($childid as $x) {
        $title = "UCMP" . $x;
        if (isset($_POST["{$title}"])) {
            $_SESSION['user_info']['cmpid'] = $x;
            $cmpid = $_SESSION['user_info']['cmpid'];
        }
    }
}

$datanote = check_note($cmpid);
$totalnotes = sizeof($datanote);

if (isset($_SESSION['editsku'])) {
    $sku = $_SESSION['editsku'];
    $sql = "select sku,brand, category, price, ram,cpu,quality,web from product where (cmpid='" . $cmpid . "') and sku='" . $sku . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $brand = $row['brand'];
    $asin = $row['category'];
    $dhl_tracking = $row['price'];
    $ram = $row['ram'];
    $cpu = $row['cpu'];
    $quality = $row['quality'];
    $web = $row['web'];
    unset($_SESSION['editsku']);
} else {
    $sku = 0;
    $brand = 0;
    $asin = 0;
    $dhl_tracking = 0;
    $ram = 0;
    $cpu = 0;
    $quality = 0;
    $web = 0;
}
?>

<?php
if (isset($_POST["save"])) {
    $isku = @$_POST["isku"];
    $ibrand = @$_POST['ibrand'];
    $icategory = @$_POST['icategory'];
    $iprice = @$_POST["iprice"];
    $iram = @$_POST["iram"];
    $icpu = @$_POST["icpu"];
    $iqulity = @$_POST["iqulity"];
    $iweb = @$_POST["iweb"];
    if (checkinput($isku)) {
        $sql = "select * from product where (cmpid='" . $cmpid . "') and sku='" . $isku . "'";
        $result = mysqli_query($conn, $sql);
        if (!$result || mysqli_num_rows($result) == 0) {

            $sql = "insert into product(sku,brand, category, price, ram,cpu,quality,web, cmpid) values('" . $isku . "','" . $ibrand . "','" . $icategory . "','" . $iprice . "','" . $iram . "','" . $icpu . "','" . $iquality . "','" . $iweb . "','" . $cmpid . "')";
            $result = mysqli_query($conn, $sql);
            print '<script>alert("Add Successful!")</script>';
            print '<script> location.replace("product-list.php"); </script>';
        } else {
            print '<script>alert("The SKU has existed, please use a different SKU.")</script>';
        }
    }
}

if (isset($_POST["update"])) {
    $isku = @$_POST["isku"];
    $ibrand = @$_POST['ibrand'];
    $icategory = @$_POST['icategory'];
    $iprice = @$_POST["iprice"];
    $iram = @$_POST["iram"];
    $iweb = @$_POST["iweb"];
    $icpu = @$_POST["icpu"];
    $iquality = @$_POST["iquality"];
    $sql = "select * from product where (cmpid='" . $cmpid . "') and sku='" . $isku . "'";
    $result = mysqli_query($conn, $sql);
    if (!$result || mysqli_num_rows($result) == 0) {

        print '<script>alert("This SKU is not existed, Please add a new product!")</script>';
    } else {
        $sql = "UPDATE product SET brand='$ibrand',category='$icategory',price='$iprice',ram='$iram',cpu='$icpu',quality='$iquality',web='$iweb' WHERE (cmpid='" . $cmpid . "') AND sku='$isku'";

        $result = mysqli_query($conn, $sql);
        print '<script>alert("Edit Successful!")</script>';
        print '<script> location.replace("product-list.php"); </script>';
    }
}

function checkinput($isku) {
    if (isEmpty($isku)) {
        print '<script>alert("The user name should not be empty!")</script>';
        return FALSE;
    }
    return TRUE;
}
?>

<html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Unihorn| Manage System</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- favicon
                    ============================================ -->
        <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
        <!-- Google Fonts
                    ============================================ -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
        <!-- Bootstrap CSS
                    ============================================ -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- Bootstrap CSS
                    ============================================ -->
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <!-- nalika Icon CSS
                ============================================ -->
        <link rel="stylesheet" href="css/nalika-icon.css">
        <!-- owl.carousel CSS
                    ============================================ -->
        <link rel="stylesheet" href="css/owl.carousel.css">
        <link rel="stylesheet" href="css/owl.theme.css">
        <link rel="stylesheet" href="css/owl.transitions.css">
        <!-- animate CSS
                    ============================================ -->
        <link rel="stylesheet" href="css/animate.css">
        <!-- normalize CSS
                    ============================================ -->
        <link rel="stylesheet" href="css/normalize.css">
        <!-- meanmenu icon CSS
                    ============================================ -->
        <link rel="stylesheet" href="css/meanmenu.min.css">
        <!-- main CSS
                    ============================================ -->
        <link rel="stylesheet" href="css/main.css">
        <!-- morrisjs CSS
                    ============================================ -->
        <link rel="stylesheet" href="css/morrisjs/morris.css">
        <!-- mCustomScrollbar CSS
                    ============================================ -->
        <link rel="stylesheet" href="css/scrollbar/jquery.mCustomScrollbar.min.css">
        <!-- metisMenu CSS
                    ============================================ -->
        <link rel="stylesheet" href="css/metisMenu/metisMenu.min.css">
        <link rel="stylesheet" href="css/metisMenu/metisMenu-vertical.css">
        <!-- calendar CSS
                    ============================================ -->
        <link rel="stylesheet" href="css/calendar/fullcalendar.min.css">
        <link rel="stylesheet" href="css/calendar/fullcalendar.print.min.css">
        <!-- style CSS
                    ============================================ -->
        <link rel="stylesheet" href="style.css">
        <!-- responsive CSS
                    ============================================ -->
        <link rel="stylesheet" href="css/responsive.css">
        <!-- modernizr JS
                    ============================================ -->
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    </head>

    <body>
        <!--[if lt IE 8]>
                <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
            <![endif]-->
        <div class="left-sidebar-pro">
            <nav id="sidebar" class="">              
                <div class="nalika-profile">
                    <div class="profile-dtl">
                        <a href="homepage.php"><img src="img/uni.jpg" alt="" /></a>
                        <h2> <?php print $fn; ?> &nbsp;<span class="min-dtn"> <?php print $ln; ?></span></h2>
                    </div>
                    <div class="profile-social-dtl">
                        <ul class="dtl-social">
                            <li><a href="#" onclick="openNewWin('http://www.facebook.com');"><i class="icon nalika-facebook"></i></a></li>
                            <li><a href="#" onclick="openNewWin('http://www.twitter.com');"><i class="icon nalika-twitter"></i></a></li>
                            <li><a href="#" onclick="openNewWin('http://www.linkedin.com');"><i class="icon nalika-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="left-custom-menu-adp-wrap comment-scrollbar">
                    <nav class="sidebar-nav left-sidebar-menu-pro">
                        <ul class="metismenu" id="menu1">

                            <li >
                                <a class="has-arrow" href="homepage.php">
                                    <i class="icon nalika-home icon-wrap"></i>
                                    <span class="mini-click-non">仪表盘</span>
                                </a>
                                <ul class="submenu-angle" aria-expanded="false">
                                    <li><a title="Dashboard" href="homepage.php"><span class="mini-sub-pro">仪表盘</span></a></li>                                
                                    <li><a title="Notification" href="notification.php"><span class="mini-sub-pro">通知</span></a></li>
                                </ul>
                            </li>

                            <li class="active">
                                <a class="has-arrow" href="product-list.php">

                                    <i class="icon nalika-table icon-wrap"></i>
                                    <span class="mini-click-non">商品信息</span>
                                </a>
                                <ul class="submenu-angle" aria-expanded="false">                                   
                                    <li><a title="Product List" href="product-list.php"><span class="mini-sub-pro">商品列表</span></a></li>
                                    <li><a title="Product Edit" href="product-edit.php"><span class="mini-sub-pro">编辑商品</span></a></li>
                                    <li><a title="Product Detail" href="product-detail.php"><span class="mini-sub-pro">商品详情</span></a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="has-arrow" href="mailbox.html" aria-expanded="false"><i class="icon nalika-mail icon-wrap"></i> <span class="mini-click-non">进/出口</span></a>
                                <ul class="submenu-angle" aria-expanded="false">
                                    <li><a class="has-arrow" title="Import" href="supply.php"><span >Incoming</span></a>
                                        <ul class="submenu-angle" aria-expanded="false">     
                                            <li><a title="Supply" href="supply.php"><span class="mini-sub-pro">Supply & Return(NC)</span></a></li>
                                            <!-- <li><a title="Supply" href="supplysh.php"><span class="mini-sub-pro">Supply & Return(SH)</span></a></li> -->
                                            <li><a title="Import Stock" href="stockaccept.php"><span class="mini-sub-pro">Import Stock</span></a></li>                                             
                                        </ul>
                                    </li>
                                    <li><a class="has-arrow" title="Export" href="outgoingnc.php"><span >Outgoing</span></a>
                                        <ul class="submenu-angle" aria-expanded="false">   
                                            <li><a title="Order & Replacement" href="outgoingnc.php"><span class="mini-sub-pro">Order & Replace(NC)</span></a></li>
                                            <!-- <li><a title="Order & Replacement" href="outgoingsh.php"><span class="mini-sub-pro">Order & Replace(SH)</span></a></li> -->
                                            <li><a title="Batch Order" href="add-batch.php"><span class="mini-sub-pro">Batch Order</span></a></li>
                                            <li><a title="Export Stock" href="stocktrans.php"><span class="mini-sub-pro">Export Stock</span></a></li>                                             
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="has-arrow" href="inventory-1.php" aria-expanded="false"><i class="icon nalika-diamond icon-wrap"></i> <span class="mini-click-non">仓库库存</span></a>
                                <ul class="submenu-angle" aria-expanded="false">
                                    <li><a title="Inventory" href="inventory-1.php"><span class="mini-sub-pro">库存</span></a></li>
                                    <li><a title="Shanghai" href="recordsh.php"><span class="mini-sub-pro">国内库存</span></a></li>
                                    <li><a title="Greensboro" href="recordnc.php"><span class="mini-sub-pro">海外仓库存</span></a></li>
                                </ul>
                            </li>

                            <!-- <li>
                                <a class="has-arrow" href="bar-charts.html" aria-expanded="false"><i class="icon nalika-bar-chart icon-wrap"></i> <span class="mini-click-non">Charts</span></a>
                                <ul class="submenu-angle" aria-expanded="false">
                                    <li><a title="Bar Charts" href="bar-charts.html"><span class="mini-sub-pro">Bar Charts</span></a></li>
                                    <li><a title="Line Charts" href="line-charts.html"><span class="mini-sub-pro">Line Charts</span></a></li>
                                    <li><a title="Area Charts" href="area-charts.html"><span class="mini-sub-pro">Area Charts</span></a></li>
                                    <li><a title="Rounded Charts" href="rounded-chart.html"><span class="mini-sub-pro">Rounded Charts</span></a></li>
                                    <li><a title="C3 Charts" href="c3.html"><span class="mini-sub-pro">C3 Charts</span></a></li>
                                    <li><a title="Sparkline Charts" href="sparkline.html"><span class="mini-sub-pro">Sparkline Charts</span></a></li>
                                    <li><a title="Peity Charts" href="peity.html"><span class="mini-sub-pro">Peity Charts</span></a></li>
                                </ul>
                            </li> -->
                            <li>
                                <a class="has-arrow" href="static-table.html" aria-expanded="false"><i class="icon nalika-table icon-wrap"></i> <span class="mini-click-non">批量发货</span></a>
                                <ul class="submenu-angle" aria-expanded="false">

                                    <li><a title="Data Table" href="data-table.php"><span class="mini-sub-pro">批量发货汇总</span></a></li>
                                    <li><a href="add-batch.php"><span class="mini-sub-pro">添加批次</span></a></li>          
                                    <li><a href="orderupdate.php"><span class="mini-sub-pro">订单更新</span></a></li>                                      
                                    <li><a href="orderinfo.php"><span class="mini-sub-pro">订单汇总</span></a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="has-arrow" href="#" aria-expanded="false"><i class="icon nalika-new-file icon-wrap"></i> <span class="mini-click-non">Website Link</span></a>
                                <ul class="submenu-angle" aria-expanded="false">
                                    <li><a title="Finance" href="bookmark.php"><span class="mini-sub-pro">Bookmark</span></a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </nav>
        </div>
        <!-- Start Welcome area -->
        <div class="all-content-wrapper">

            <div class="header-advance-area">
                <div class="header-top-area">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="header-top-wraper">
                                    <div class="row">

                                        <div class="col-lg-1 col-md-0 col-sm-1 col-xs-12">
                                            <div class="menu-switcher-pro">
                                                <button type="button" id="sidebarCollapse" class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
                                                    <i class="icon nalika-menu-task"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
                                            <form method="post">
                                                <div class="header-top-menu tabl-d-n">


                                                    <ul class="nav navbar-nav mai-top-nav">
                                                        <li><a>ACCOUNT_ID：</a></li>
                                                        <?php
                                                        foreach ($childid as $x) {
                                                            $title = "UCMP" . $x;
                                                            if ($cmpid == $x) {
                                                                ?>
                                                                <li ><a style='color:rgba(204, 154, 129, 55)'><?php print $title; ?></a>
                                                                </li>
                                                            <?php } else { ?>
                                                                <li ><a><input type="submit" style='background-color:rgba(204, 154, 129, 0);color:fff' name='<?php print $title; ?>' value='<?php print $title; ?>' /></a>
                                                                </li>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </ul>

                                                </div>
                                            </form>
                                        </div>

                                        <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                                            <div class="header-right-info">
                                                <ul class="nav navbar-nav mai-top-nav header-right-menu">

                                                    <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="icon nalika-menu-task"></i></a>
                                                        <ul role="menu" class="dropdown-header-top author-log dropdown-menu animated zoomIn">
                                                            <li><a href="#"><span class="icon nalika-home author-log-ic"></span> Dashboard</a>
                                                                <a title="Dashboard" href="homepage.php"><span class="mini-sub-pro">Dashboard</span></a>                       
                                                                <a title="Notification" href="notification.php"><span class="mini-sub-pro">Notification</span></a>
                                                            </li>

                                                            <li><a href="#"><span class="icon nalika-diamond author-log-ic"></span> Warehouse</a>
                                                            <li><a title="Inventory" href="inventory-1.php"><span class="mini-sub-pro">Inventory</span></a>
                                                                <a title="Shanghai" href="recordsh.php"><span class="mini-sub-pro">Record SH</span></a>
                                                                <a title="Greensboro" href="recordnc.php"><span class="mini-sub-pro">Record NC</span></a></li>
                                                        </ul>
                                                    </li>
                                                    <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="icon nalika-alarm" aria-hidden="true"></i><span class="<?php if ($totalnotes != 0) print 'indicator-nt' ?>"></span></a>
                                                        <div role="menu" class="notification-author dropdown-menu animated zoomIn">
                                                            <div class="notification-single-top">
                                                                <h1>Notifications</h1>
                                                            </div>
                                                            <ul class="notification-menu">
                                                                <?php
                                                                for ($i = 0; $i < count($datanote) && $i < 3; $i++) {
                                                                    print "<li>
                                                                    <a href='notification.php'>
                                                                        <div class='notification-icon'>
                                                                            <i class='icon nalika-tick' aria-hidden='true'></i>
                                                                        </div>
                                                                        <div class='notification-content'>                                                                            
                                                                            <h2>";
                                                                    print $datanote[$i]['date'];
                                                                    print "</h2>
                                                                            <p>" . $datanote[$i]['subject'] . "</p>
                                                                        </div>
                                                                    </a>
                                                                </li>";
                                                                }
                                                                ?>
                                                            </ul>
                                                            <div class="notification-view">
                                                                <?php if (count($datanote) > 3) print "<a href='notification.php'>View All Notification</a>"; ?>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                                                            <i class="icon nalika-user"></i>
                                                            <span class="admin-name"><?php print $user ?></span>
                                                            <i class="icon nalika-down-arrow nalika-angle-dw"></i>
                                                        </a>
                                                        <ul role="menu" class="dropdown-header-top author-log dropdown-menu animated zoomIn">
                                                            <li><a href="register.php"><span class="icon nalika-home author-log-ic"></span> Register</a>
                                                            </li>
                                                            <li><a href="#"><span class="icon nalika-user author-log-ic"></span> My Profile</a>
                                                            </li>
                                                            <li><a href="lock.php"><span class="icon nalika-diamond author-log-ic"></span> Lock</a>
                                                            </li>
                                                            <li><a href="#"><span class="icon nalika-settings author-log-ic"></span> Settings</a>
                                                            </li>
                                                            <li><a href="logout.php"><span class="icon nalika-unlocked author-log-ic"></span> Log Out</a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu start -->

                <!-- Mobile Menu end -->
                <div class="breadcome-area">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="breadcome-list">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <div class="breadcomb-wp">
                                                <div class="breadcomb-icon">
                                                    <i class="icon nalika-edit"></i>
                                                </div>
                                                <div class="breadcomb-ctn">
                                                    <h2>编辑商品</h2>
                                                    <p>欢迎使用鸿运仓库管理系统 <span class="bread-ntd"></span></p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Single pro tab start-->
            <div class="single-product-tab-area mg-b-30">
                <!-- Single pro tab review Start-->
                <div class="single-pro-review-area">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="review-tab-pro-inner">
                                    <ul id="myTab3" class="tab-review-design">
                                        <li class="active"><a href="#description"><i class="icon nalika-edit" aria-hidden="true"></i> 编辑商品/Edit</a></li>
                                        <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> 图片/Pics</a></li>
                                        <li><a href="#INFORMATION"><i class="icon nalika-chat" aria-hidden="true"></i> 评价/Reviews</a></li>
                                    </ul>
                                    <div id="myTabContent" class="tab-content custom-product-edit">

                                        <div class="product-tab-list tab-pane fade active in" id="description">
                                            <form name="form" method="post" action="">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <div class="review-content-section">

                                                            <div class="input-group mg-b-pro-edt">
                                                                <span class="input-group-addon"><i class="icon nalika-forms" aria-hidden="true"></i> 仓库Sku</span>
                                                                <input name='isku' type="text" required="" class="form-control" placeholder="Product SKU" <?php
                                                                if ($sku) {
                                                                    print "value='" . $sku . "'";
                                                                } unset($_SESSION['editsku']);
                                                                ?>>
                                                            </div>
                                                            <div class="input-group mg-b-pro-edt">
                                                                <span class="input-group-addon"><i class="icon nalika-edit" aria-hidden="true"></i> 主题/Title</span>
                                                                <input name='iram' type="text" required="" class="form-control" placeholder="Title" <?php
                                                                if ($ram) {
                                                                    print "value='" . $ram . "'";
                                                                } unset($_SESSION['editsku']);
                                                                ?>>
                                                            </div>


                                                            <div class="input-group mg-b-pro-edt">
                                                                <span class="input-group-addon"><i class="icon nalika-menu" aria-hidden="true"></i> 品牌/Brand</span>
                                                                <input name="ibrand" type="text" class="form-control" placeholder="Brand" <?php
                                                                if ($brand) {
                                                                    print "value='" . $brand . "'";
                                                                } unset($_SESSION['editsku']);
                                                                ?>>
                                                            </div>


                                                            <div class="input-group mg-b-pro-edt">
                                                                <span class="input-group-addon"><i class="icon nalika-folder" aria-hidden="true"></i> 备注/Note</span>
                                                                <input name="iquality" type="text" class="form-control" placeholder="Note" <?php
                                                                if ($quality) {
                                                                    print "value='" . $quality . "'";
                                                                } unset($_SESSION['editsku']);
                                                                ?>>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <div class="review-content-section">

                                                            <div class="input-group mg-b-pro-edt">
                                                                <span class="input-group-addon"><i class="fa fa-amazon" aria-hidden="true"></i> ASIN</span>
                                                                <input name='icategory' type="text"  class="form-control" placeholder="ASIN" <?php
                                                                if ($asin) {
                                                                    print "value='" . $asin . "'";
                                                                } unset($_SESSION['editsku']);
                                                                ?>>
                                                            </div>

                                                            <div class="input-group mg-b-pro-edt">
                                                                <span class="input-group-addon"><i class="fa fa-amazon" aria-hidden="true"></i> FNSku</span>
                                                                <input name="icpu" type="text" class="form-control" placeholder="FNSKU" <?php
                                                                if ($cpu) {
                                                                    print "value='" . $cpu . "'";
                                                                } unset($_SESSION['editsku']);
                                                                ?>>
                                                            </div>

                                                            <div class="input-group mg-b-pro-edt">
                                                                <span class="input-group-addon"><i class="fa fa-yen" aria-hidden="true"></i> 价格/Price</span>

                                                                <input name="iprice" type="text" class="form-control" placeholder="Price" <?php
                                                                if ($dhl_tracking) {
                                                                    print "value='" . $dhl_tracking . "'";
                                                                } unset($_SESSION['editsku']);
                                                                ?>>
                                                            </div>
                                                            <div class="input-group mg-b-pro-edt">
                                                                <span class="input-group-addon"><i class="fa fa-internet-explorer" aria-hidden="true"></i> 网站/Website</span>

                                                                <input name="iweb" type="text" class="form-control" placeholder="Website" <?php
                                                                if ($web) {
                                                                    print "value='" . $web . "'";
                                                                } unset($_SESSION['editsku']);
                                                                ?>>
                                                            </div> 


                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="text-center custom-pro-edt-ds">
                                                            <input name="save" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="ADD NEW">
                                                            <input name="update" type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10" value="UPDATE">
                                                            <a href='product-edit.php' class="btn btn-ctl-bt waves-effect waves-light">Discard
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="product-tab-list tab-pane fade" id="reviews">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="review-content-section">
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="pro-edt-img">
                                                                    <img src="img/new-product/5-small.jpg" alt="" />
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="product-edt-pix-wrap">
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">TT</span>
                                                                                <input type="text" class="form-control" placeholder="Label Name">
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-radio">
                                                                                        <form>
                                                                                            <div class="radio radiofill">
                                                                                                <label>
                                                                                                    <input type="radio" name="radio"><i class="helper"></i>大图
                                                                                                </label>
                                                                                            </div>
                                                                                            <div class="radio radiofill">
                                                                                                <label>
                                                                                                    <input type="radio" name="radio"><i class="helper"></i>中图
                                                                                                </label>
                                                                                            </div>
                                                                                            <div class="radio radiofill">
                                                                                                <label>
                                                                                                    <input type="radio" name="radio"><i class="helper"></i>小图
                                                                                                </label>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="product-edt-remove">
                                                                                        <button type="button" class="btn btn-ctl-bt waves-effect waves-light">移除
                                                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="pro-edt-img">
                                                                    <img src="img/new-product/6-small.jpg" alt="" />
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="product-edt-pix-wrap">
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">TT</span>
                                                                                <input type="text" class="form-control" placeholder="Label Name">
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-radio">
                                                                                        <form>
                                                                                            <div class="radio radiofill">
                                                                                                <label>
                                                                                                    <input type="radio" name="radio"><i class="helper"></i>大图
                                                                                                </label>
                                                                                            </div>
                                                                                            <div class="radio radiofill">
                                                                                                <label>
                                                                                                    <input type="radio" name="radio"><i class="helper"></i>中图
                                                                                                </label>
                                                                                            </div>
                                                                                            <div class="radio radiofill">
                                                                                                <label>
                                                                                                    <input type="radio" name="radio"><i class="helper"></i>小图
                                                                                                </label>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="product-edt-remove">
                                                                                        <button type="button" class="btn btn-ctl-bt waves-effect waves-light">移除
                                                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="pro-edt-img mg-b-0">
                                                                    <img src="img/new-product/7-small.jpg" alt="" />
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="product-edt-pix-wrap">
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">TT</span>
                                                                                <input type="text" class="form-control" placeholder="Label Name">
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-radio">
                                                                                        <form>
                                                                                            <div class="radio radiofill">
                                                                                                <label>
                                                                                                    <input type="radio" name="radio"><i class="helper"></i>大图
                                                                                                </label>
                                                                                            </div>
                                                                                            <div class="radio radiofill">
                                                                                                <label>
                                                                                                    <input type="radio" name="radio"><i class="helper"></i>中图
                                                                                                </label>
                                                                                            </div>
                                                                                            <div class="radio radiofill">
                                                                                                <label>
                                                                                                    <input type="radio" name="radio"><i class="helper"></i>小图
                                                                                                </label>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="product-edt-remove">
                                                                                        <button type="button" class="btn btn-ctl-bt waves-effect waves-light">移除
                                                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-tab-list tab-pane fade" id="INFORMATION">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="review-content-section">
                                                        <div class="card-block">
                                                            <div class="text-muted f-w-400">
                                                                <p>No reviews yet.</p>
                                                            </div>
                                                            <div class="m-t-10">
                                                                <div class="txt-primary f-18 f-w-600">
                                                                    <p>Your Rating</p>
                                                                </div>
                                                                <div class="stars stars-example-css detail-stars">
                                                                    <div class="review-rating">
                                                                        <fieldset class="rating">
                                                                            <input type="radio" id="star5" name="rating" value="5">
                                                                            <label class="full" for="star5"></label>
                                                                            <input type="radio" id="star4half" name="rating" value="4 and a half">
                                                                            <label class="half" for="star4half"></label>
                                                                            <input type="radio" id="star4" name="rating" value="4">
                                                                            <label class="full" for="star4"></label>
                                                                            <input type="radio" id="star3half" name="rating" value="3 and a half">
                                                                            <label class="half" for="star3half"></label>
                                                                            <input type="radio" id="star3" name="rating" value="3">
                                                                            <label class="full" for="star3"></label>
                                                                            <input type="radio" id="star2half" name="rating" value="2 and a half">
                                                                            <label class="half" for="star2half"></label>
                                                                            <input type="radio" id="star2" name="rating" value="2">
                                                                            <label class="full" for="star2"></label>
                                                                            <input type="radio" id="star1half" name="rating" value="1 and a half">
                                                                            <label class="half" for="star1half"></label>
                                                                            <input type="radio" id="star1" name="rating" value="1">
                                                                            <label class="full" for="star1"></label>
                                                                            <input type="radio" id="starhalf" name="rating" value="half">
                                                                            <label class="half" for="starhalf"></label>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="clear"></div>
                                                                </div>
                                                            </div>
                                                            <div class="input-group mg-b-15 mg-t-15">
                                                                <span class="input-group-addon"><i class="icon nalika-user" aria-hidden="true"></i></span>
                                                                <input type="text" class="form-control" placeholder="User Name">
                                                            </div>
                                                            <div class="input-group mg-b-15">
                                                                <span class="input-group-addon"><i class="icon nalika-user" aria-hidden="true"></i></span>
                                                                <input type="text" class="form-control" placeholder="Last Name">
                                                            </div>
                                                            <div class="input-group mg-b-15">
                                                                <span class="input-group-addon"><i class="icon nalika-mail" aria-hidden="true"></i></span>
                                                                <input type="text" class="form-control" placeholder="Email">
                                                            </div>
                                                            <div class="form-group review-pro-edt mg-b-0-pt">
                                                                <button type="submit" class="btn btn-ctl-bt waves-effect waves-light">提交
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-copyright-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="footer-copy-right">
                                <p>Copyright © 2021 <a href="https://www.unihorn.tech">hyhstorage</a> All rights reserved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- jquery
                    ============================================ -->
        <script src="js/vendor/jquery-1.12.4.min.js"></script>
        <!-- bootstrap JS
                    ============================================ -->
        <script src="js/bootstrap.min.js"></script>
        <!-- wow JS
                    ============================================ -->
        <script src="js/wow.min.js"></script>
        <!-- price-slider JS
                    ============================================ -->
        <script src="js/jquery-price-slider.js"></script>
        <!-- meanmenu JS
                    ============================================ -->
        <script src="js/jquery.meanmenu.js"></script>
        <!-- owl.carousel JS
                    ============================================ -->
        <script src="js/owl.carousel.min.js"></script>
        <!-- sticky JS
                    ============================================ -->
        <script src="js/jquery.sticky.js"></script>
        <!-- scrollUp JS
                    ============================================ -->
        <script src="js/jquery.scrollUp.min.js"></script>
        <!-- mCustomScrollbar JS
                    ============================================ -->
        <script src="js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="js/scrollbar/mCustomScrollbar-active.js"></script>
        <!-- metisMenu JS
                    ============================================ -->
        <script src="js/metisMenu/metisMenu.min.js"></script>
        <script src="js/metisMenu/metisMenu-active.js"></script>
        <!-- sparkline JS
                    ============================================ -->
        <script src="js/sparkline/jquery.sparkline.min.js"></script>
        <script src="js/sparkline/jquery.charts-sparkline.js"></script>
        <!-- calendar JS
                    ============================================ -->
        <script src="js/calendar/moment.min.js"></script>
        <script src="js/calendar/fullcalendar.min.js"></script>
        <script src="js/calendar/fullcalendar-active.js"></script>
        <!-- float JS
                ============================================ -->
        <script src="js/flot/jquery.flot.js"></script>
        <script src="js/flot/jquery.flot.resize.js"></script>
        <script src="js/flot/curvedLines.js"></script>
        <script src="js/flot/flot-active.js"></script>
        <!-- plugins JS
                    ============================================ -->
        <script src="js/plugins.js"></script>
        <!-- main JS
                    ============================================ -->
        <script src="js/main.js"></script>


        <script type="text/javascript">
                                function openNewWin(url)
                                {
                                    window.open(url);
                                }
        </script>
    </body>

</html>