<?php

require_once 'header.php';
$pageoffice = 'all';           //设置页面属性 office ：  nc, sh, all
$pagelevel = 2;       // //设置页面等级 0： 只有admin可以访问； 1：库存系统用户； 2:代发用户
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
if (isset($_GET['type']) && $_GET['type'] == 'amazon') {
    $batch = $_GET['id'];
    $filepath = @fopen("./upload/cmp" . $cmpid . "_" . $batch . ".txt", 'r');
    @$content = fgets($filepath);
    $file = ("./upload/Export_Amazon_upload_" . $batch . ".txt");
    $fw = fopen($file, "w");
    fwrite($fw, "order-id\torder-item-id\tquantity\tship-date\tcarrier-code\tcarrier-name\ttracking-number\tship-method\ttransparency_code \n");
    while (@$content = fgetcsv($filepath, 1000, "\t")) {    //每次读取CSV里面的一行内容
        $sql = "SELECT carrier,tracking,service FROM daifaorders where batch='" . $batch . "' and (cmpid='" . $cmpid . "') and orderid='" . $content[0] . "'";  //SELECT * FROM daifaorders where batch='0704_UPS' ORDER by orderid ASC
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        $text = $content[0] . "\t" . strval($content[1]) . "\t" . $content[14] . "\t" . $content[4] . "\t" . $row['carrier'] . "\t \t\"" . $row['tracking'] . "\"\t" . $row['service'] . "\n";
        fwrite($fw, $text);
    }
    fclose($fw);
} elseif (isset($_GET['type']) && $_GET['type'] == 'newegg') {
    $batch = $_GET['id'];
    $filepath = @fopen("./upload/cmp" . $cmpid . "_" . $batch . ".csv", 'r');
    @$content = fgets($filepath);
    $file = ("./upload/Export_Newegg_upload_" . $batch . ".csv");
    $fw = fopen($file, "w");
    fwrite($fw, "Order Number,Order Date & Time,Sales Channel,Fulfillment Option,Ship To Address Line 1,Ship To Address Line 2,	Ship To City,	Ship To State,	Ship To ZipCode,Ship To Country	,Ship To First Name,	Ship To LastName,	Ship To Company,	Ship To Phone Number,	Order Customer Email,	Order Shipping Method,	Item Seller Part #,	Item Newegg #,	Item Unit Price,	Extend Unit Price,	Item Unit Shipping Charge,	Extend Shipping Charge,	Extend VAT,	Extend Duty,	Order Shipping Total,Order Discount Amount,Sales Tax,VAT Total,Duty Total,Recycling Fee Total,Order Total,Quantity Ordered,Quantity Shipped,Actual Shipping Carrier,Actual Shipping Method,	Tracking Number\n");
    while (@$content = fgetcsv($filepath, 1000, ",")) {    //每次读取CSV里面的一行内容
        $sql = "SELECT carrier,tracking,service FROM daifaorders where batch='" . $batch . "' and (cmpid='" . $cmpid . "') and orderid='" . $content[0] . "'";  //SELECT * FROM daifaorders where batch='0704_UPS' ORDER by orderid ASC
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        $text = '';
        for ($index = 0; $index <= 31; $index++) {    //经常会多了一个Recycling Fee Total， 这里改成30，如果没有Recycling Fee Total，下面content【31】改成30. sql里面删掉Recycling Fee Total
            $text = $text . strval($content[$index]) . ",";
        }
        $text = $text . $content[31] . "," . $row['carrier'] . "," . $row['service'] . "," . $row['tracking'] . "\t\n";
        fwrite($fw, $text);
    }
    fclose($fw);
} elseif (isset($_GET['type']) && $_GET['type'] == 'dhl') {
    $batch = $_GET['id'];
    $filepath = @fopen("./upload/cmp" . $cmpid . "_" . $batch . ".csv", 'r');
    @$content = fgets($filepath);
    $file = ("./upload/Export_DHL_" . $batch . ".csv");
    $fw = fopen($file, "w");
    fwrite($fw, "Order ID (required),Order price,Name,Country,State,City,Address,Zipcode,Phone,Product,Total,Weight,Rubber Stamp\n");
    while (@$content = fgetcsv($filepath, 1000, ",")) {    //每次读取CSV里面的一行内容
        $sql = "SELECT * FROM daifaorders where batch='" . $batch . "' and (cmpid='" . $cmpid . "') and orderid='" . $content[0] . "'";  //SELECT * FROM daifaorders where batch='0704_UPS' ORDER by orderid ASC
        $country_ = strtoupper($content[9]);
        switch ($country_) {
            case 'UNITED STATES':$country = 'US';
                break;
            case 'CANADA':$country = 'CA';
                break;
            case 'MEXICO':$country = 'MX';
                break;
            default :$country = 'US';
        }
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        $text = $content[0] . "," . $content[31] * 80 . "," . $row['name'] . "," . $country . "," . $row['state'] . "," . $row['city'] . ","
                . $row['address'] . " " . $row['address2'] . ',' . $row['zipcode'] . ',' . $row['phone'] . ',Computer Component,' . $content[31] . "," . $content[31] * 1.2 . "," . $row['note'] . "\n";
        fwrite($fw, $text);
    }
    fclose($fw);
} elseif (isset($_GET['type']) && $_GET['type'] == 'dhl_invoice') {
    $batch = $_GET['id'];
    $filepath = @fopen("./upload/cmp" . $cmpid . "_" . $batch . ".csv", 'r');
    @$content = fgets($filepath);
    $file = ("./upload/Export_DHL_Invoice_" . $batch . ".csv");
    $fw = fopen($file, "w");
    fwrite($fw, "Order ID (required),Product,Total,Unit price,weight,SKU,Brand\n");
    while (@$content = fgetcsv($filepath, 1000, ",")) {    //每次读取CSV里面的一行内容
        $sku_ = strtoupper($content[16]);
        if (str_starts_with($sku_, 'ASUS')) {
            $brand = 'ASUS';
        } elseif (str_starts_with($sku_, 'GV')) {
            $brand = 'GIGABYTE';
        } elseif (str_starts_with($sku_, 'GIGABYTE')) {
            $brand = 'GIGABYTE';
        } elseif (str_starts_with($sku_, 'GA')) {
            $brand = 'GIGABYTE';
        } elseif (str_starts_with($sku_, 'MSI')) {
            $brand = 'MSI';
        } elseif (str_starts_with($sku_, 'ZOTAC')) {
            $brand = 'ZOTAC';
        } elseif (str_starts_with($sku_, 'SAPPHIRE')) {
            $brand = 'SAPPHIRE';
        } elseif (str_starts_with($sku_, 'EVGA')) {
            $brand = 'EVGA';
        } else {
            $brand = 'ASUS';
        }

        $text = $content[0] . ',Computer Component(' . $content[16] . '),' . $content[31] . ',80,' . $content[31] * 1.2 . ',' . $content[16] . ',' . $brand . "\n";
        fwrite($fw, $text);
    }
    fclose($fw);
} else {
    if (isset($_GET['id']) && ($_GET['id'] != '')) {
        $batch = $_GET['id'];
        $sql = "SELECT * FROM daifaorders where batch='" . $batch . "' and (cmpid='" . $cmpid . "') ORDER by id DESC";  //SELECT * FROM daifaorders where batch='0704_UPS' ORDER by orderid ASC
        $result = mysqli_query($conn, $sql);
//$totalpage = ceil($totalrow / $perpage);

        while ($arr = mysqli_fetch_array($result)) {
            $data[] = $arr;
        }

        $file = ("./upload/Export_Unihorn_" . $batch . ".csv");
    } else {
        $sql = "SELECT * FROM daifaorders where cmpid='" . $cmpid . "' ORDER by id DESC";  //SELECT * FROM daifaorders where batch='0704_UPS' ORDER by orderid ASC
        $result = mysqli_query($conn, $sql);
//$totalpage = ceil($totalrow / $perpage);

        while ($arr = mysqli_fetch_array($result)) {
            $data[] = $arr;
        }

        $file = ("./upload/Export_Order_Summary.csv");
    }
    $sql = "SELECT status FROM daifa where batchname='" . $batch . "' and (cmpid='" . $cmpid . "')";  //SELECT * FROM daifaorders where batch='0704_UPS' ORDER by orderid ASC
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    if ($row[0] == 'PENDING') {
        $fw = fopen($file, "w");
        fwrite($fw, "Order ID (required), Service,Ship To - Name	, Ship To - Address 1 , 	Ship To - Address 2 ,	Ship To - City	, Ship To - State/Province ,	Ship To - Postal Code,	Ship To - Phone,	Total Weight in Oz, Note, \n");

        for ($index = 0; $index < @count($data); $index++) {
            $text = $data[$index]['orderid'] . "," . $data[$index]['service'] . ",\"" . $data[$index]['name'] . "\",\"" . $data[$index]['address'] . "\",\"" . $data[$index]['address2'] . "\",\"" . $data[$index]['city'] . "\",\"" . $data[$index]['state'] . "\",\t" . strval($data[$index]['zipcode']) . "\t,\"" . $data[$index]['phone'] . "\"," . $data[$index]['weight'] . ",\"" . $data[$index]['note'] . "\"\n";
            fwrite($fw, $text);
        }
    } else {


        $fw = fopen($file, "w");
        fwrite($fw, "Order ID (required), Service, Tracking No,Tracking Status, Cost,	Ship To - Name	, Ship To - Address 1 , 	Ship To - Address 2 ,	Ship To - City	, Ship To - State/Province ,	Ship To - Postal Code,	Ship To - Phone,	Total Weight in Oz, Note, \n");

        for ($index = 0; $index < @count($data); $index++) {
            $text =  $data[$index]['orderid'] . "," . $data[$index]['service'] . "," . $data[$index]['tracking'] . "\t," . $data[$index]['status'] . "," . $data[$index]['cost'] . ",\"" . $data[$index]['name'] . "\",\"" . $data[$index]['address'] . "\",\"" . $data[$index]['address2'] . "\",\"" . $data[$index]['city'] . "\",\"" . $data[$index]['state'] . "\",\t" . strval($data[$index]['zipcode']) . "\t,\"" . $data[$index]['phone'] . "\"," . $data[$index]['weight'] . ",\"" . $data[$index]['note'] . "\"\n";

            fwrite($fw, $text);
        }
    }
    fclose($fw);
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file));
flush(); // Flush system output buffer
readfile($file);
unlink($file);
?>