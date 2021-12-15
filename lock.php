<?php
require_once 'header.php';

if (!isset($_COOKIE['userid'])) {
    setcookie("userid", $_SESSION['user_info']['userid'], time() + 300);
    setcookie("cmpid", $_SESSION['user_info']['cmpid'], time() + 300);
    $sql = "select * from employees where (cmpid='" . $_SESSION['user_info']['cmpid'] . "') and username='" . $_SESSION['user_info']['userid'] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    unset($_SESSION['user_info']);
} else {
    $sql = "select * from employees where (cmpid='" . @$_COOKIE['cmpid'] . "') and username='" . @$_COOKIE['userid'] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
}
$var = $row['password'];
if (isset($_POST['submit'])) {
    $password = @$_POST['password'];
    if ($password != $var) {
        print "<script> alert('The password is not right!');</script>";
    } else {
        $_SESSION['user_info']['userid'] = $row['username'];
        $_SESSION['user_info']['firstname'] = $row['firstname'];
        $_SESSION['user_info']['lastname'] = $row['lastname'];
        $_SESSION['user_info']['office'] = $row['office'];
        $_SESSION['user_info']['level'] = $row['level'];
        $_SESSION['user_info']['cmpid'] = $row['cmpid'];
        $_SESSION['user_info']['childid'] = json_decode($row['childid'], true);
        ;

        unset($_COOKIE['userid']);
        unset($_COOKIE['cmpid']);
        header("location:homepage.php");
    }
}
?>
<html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>UNIHORN</title>
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
        <!-- forms CSS
                    ============================================ -->
        <link rel="stylesheet" href="css/form/all-type-forms.css">
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

        <div class="color-line"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="back-link back-backend">
                        <a href="logout.php" class="btn btn-primary">Log Out</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="hpanel">
                        <div class="panel-body text-center lock-inner">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                            <br/>
                            <h4><strong><?php print $str; ?></strong></h4>
                            <p>Your are in lock screen. Main app was shut down and you need to enter your password to go back to app.</p>
                            <form name="form" action="" method="post" class="m-t">
                                <div class="form-group">
                                    <input type="password" name="password"  required="" placeholder="******" class="form-control">
                                </div>
                                <input name="submit" class="btn btn-primary block full-width" type="submit" value="Unlock">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
            </div>
            <div class="row">
                <div class="col-md-12 col-md-12 col-sm-12 col-xs-12 text-center login-footer">
                    <p><?php print $_SERVER['REMOTE_HOST']; ?></p>
                    <p>Copyright © 2019 <a href="https://www.unihorn.tech">Unihorn </a>. All rights reserved.</p>
                </div>
            </div>
        </div>
        <!-- jquery
                    ============================================ -->
        <script src="js/vendor/jquery-1.11.3.min.js"></script>
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
        <!-- tab JS
                    ============================================ -->
        <script src="js/tab.js"></script>
        <!-- icheck JS
                    ============================================ -->
        <script src="js/icheck/icheck.min.js"></script>
        <script src="js/icheck/icheck-active.js"></script>
        <!-- plugins JS
                    ============================================ -->
        <script src="js/plugins.js"></script>
        <!-- main JS
                    ============================================ -->
        <script src="js/main.js"></script>
    </body>

</html>