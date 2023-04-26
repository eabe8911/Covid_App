<?php
// Initialize the session
// For libo covid 06/07/21 WillieK
// debug on

ini_set("display_errors", "on");
error_reporting(E_ALL);

if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
} else {
    header("location: login.php");
}

require_once "php/log.php";
require_once "param.php";

$gender_opt = array(
    '0' => '',
    '男 / Male' => '男 / Male',
    '女 / Female' => '女 / Female'
);

$twrpturgency_opt = array(
    '0' => '',
    'normal' => '一般件',
    'urgent' => '急件',
    'hiurgent' => '特急件'
);

$testtype_opt = array(
    '0' => '',
    '1' => '快篩 only',
    '2' => 'qPCR only'
);

$payflag_opt = array(
    'N' => '',
    '1' => '未付款',
    '2' => '現金',
    '3' => '刷卡',
    '4' => '月結',
    '5' => '匯款',
    '6' => '日航'
);


// 結果
$result_msg = '1';
// Connect to local db
// $conn = mysqli_connect($hostname, $username, $password);
// mysqli_select_db($conn, "libodb");

// Define variables and initialize with empty values
$pcrid_uuid = "";
$userid_passid = "";
$uuid = "";
$check2 = 0;
$search_result = $save_result = "";
$userid = $passportid = $mobile = $sampleid1 = $sampleid2 = "";
$userid1 = $passportid1 = $mobile1 = $hicardno1 = "";
$id_err = $passport_err = $mobile_err = $sampleid_err = $input_err = $login_err = "";
$sql2 = $stmt = "";
$nationality = $twrpturgency = $per_type = $ename = $tdat = $apdat = $cname = $dob = $lname = "";
$fname = $mobile = $sex = $sendname = $testreason = $hicardno = $mtpid = $xmappoint = $xmapdat = $companytitle = $payflag = $email = $memo = $receiptid = "";
$testtype = '0';
$page = $page_count = 0;
$query_date = "";
// if($stmt = mysqli_prepare($conn, $sql)){

// Attempt to execute the prepared statement
// Close statement
// mysqli_stmt_close($stmt);
// }else{
//     $search_result= "Oops! Something went wrong.<br> Please try again later.";
//     write_sql($search_result);
// }
// $uuid = '57231';
// $uuid = '57039';
// $server = '192.168.2.96';
// $dbname = 'libodb';
// $port = '3306';
// $user = 'root';
// $password = 'password';

// $server = 'localhost';
// $user = 'libo_user';
// $password = 'xxx';

// TODO: receive uuid from url
$uuid = filter_input( INPUT_GET,'id');
$query_date = filter_input( INPUT_GET,'date'); 

$conn = new PDO(
    "mysql:host=$hostname; dbname=$dbname; port=$port",
    $username,
    $password,
    array(PDO::MYSQL_ATTR_FOUND_ROWS => true)
);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"])) {
    try {
        $sql = "update covid_trans set 
        sampleid1= :sampleid1,
        sampleid2= :sampleid2 , 
        apdat= :apdat,
        dob= :dob,
        hicardno= :hicardno, 
        companytitle= :companytitle,
        testtype= :testtype,
        userid= :userid, 
        cname= :cname,
        tdat= :tdat, 
        sex= :sex, 
        uemail= :email,  
        sendname= :sendname,
        passportid= :passportid,  
        fname= :fname,
        twrpturgency= :twrpturgency,
        nationality= :nationality,
        receiptid= :receiptid, 
        mtpid= :mtpid,
        mobile= :mobile, 
        payflag =:payflag,
        memo=:memo
        where uuid=:uuid";
        $stmt = $conn->prepare($sql);

        // foreach ($_POST as $key => $value) {
        //     $item = ':'.$key;
        //     echo($item.'=>'.$value.'<br>');
        //     $stmt->bindParam($item, $value);
        // } 要按照順序對應才可以執行
        $stmt->bindParam(':sampleid1', $_POST['sampleid1']);
        $stmt->bindParam(':sampleid2', $_POST['sampleid2']);
        $stmt->bindParam(':apdat', $_POST['apdat']);
        $stmt->bindParam(':dob', $_POST['dob']);
        $stmt->bindParam(':hicardno', $_POST['hicardno']);
        $stmt->bindParam(':companytitle', $_POST['companytitle']);
        $stmt->bindParam(':testtype', $_POST['testtype']);
        $stmt->bindParam(':userid', $_POST['userid']);
        $stmt->bindParam(':cname', $_POST['cname']);
        if(empty($_POST['tdat'])){
            $stmt->bindValue(':tdat', null, PDO::PARAM_NULL);
        }else{
            $stmt->bindParam(':tdat', $_POST['tdat']);
        }
        $stmt->bindParam(':sex', $_POST['sex']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':sendname', $_POST['sendname']);
        $stmt->bindParam(':passportid', $_POST['passportid']);
        $stmt->bindParam(':fname', $_POST['fname']);
        $stmt->bindParam(':twrpturgency', $_POST['twrpturgency']);
        $stmt->bindParam(':nationality', $_POST['nationality']);
        $stmt->bindParam(':receiptid', $_POST['receiptid']);
        $stmt->bindParam(':mtpid', $_POST['mtpid']);
        $stmt->bindParam(':mobile', $_POST['mobile']);
        $stmt->bindParam(':payflag', $_POST['payflag']);
        $stmt->bindParam(':memo', $_POST['memo']);
        $stmt->bindParam(':uuid', $_POST['uuid']);
        $stmt->execute();
        $save_result = '存檔成功';
        // return to home page
        header("location: ../appoint-back/home.php?date=".$_POST['query_date']."&id=".$_POST['uuid']);

    } catch (PDOException | Exception $th) {
        throw new Exception($th->getMessage() . print_r($_POST), $th->getCode());
    }
} else {
    try {
        $sql = "SELECT * FROM covid_trans WHERE uuid=:uuid";

        $sql_comment = $_SESSION["username"] . ": " . $sql;
        write_sql($sql_comment);

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        $response = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($response) {
            $sampleid2 = $response["sampleid2"];
            $userid = $response["userid"];
            $passportid = $response["passportid"];
            $mtpid = $response["mtpid"];
            $sampleid1 = $response["sampleid1"];
            $cname = $response["cname"];
            $fname = $response["fname"];
            $mobile = $response["mobile"];
            $apdat = $response["apdat"];
            $tdat = $response["tdat"];
            $twrpturgency = $response["twrpturgency"];
            $payflag = $response["payflag"];
            $dob = $response["dob"];
            $sex = $response["sex"];
            $nationality = $response["nationality"];
            $hicardno = $response["hicardno"];
            $email = $response["uemail"];
            $companytitle = $response["companytitle"];
            $sendname = $response["sendname"];
            $receiptid = $response["receiptid"];
            $testtype = $response["testtype"];
            $memo = $response["memo"];

            // $userid_passid  = $response["userid"];    
            // $uuid           = $response["uuid"];
            // $lname          = $response["lname"];
            // $xmappoint      = $response["xmappoint"];
            // $testreason     = $response["testreason"];
            // $per_type       = $response["type"];
            // $xmapdat        = $response["xmapdat"];
            // $companytitle    = $response["companytitle"];
            // $userid1        = $userid;
            // $passportid1    = $passportid;
            // $mobile1        = $mobile;
            // $hicardno1      = $hicardno;

            // $result_msg = '2';

            $_SESSION["uuid"] = $uuid;
            $_SESSION["sampleid2"] = $sampleid2;
            $_SESSION["userid"] = $userid;
            $_SESSION["passportid"] = strval($passportid);
            $_SESSION["mtpid"] = $mtpid;
            $_SESSION["sampleid1"] = $sampleid1;
            $_SESSION["cname"] = $cname;
            $_SESSION["fname"] = $fname;
            $_SESSION["mobile"] = strval($mobile);
            $_SESSION["apdat"] = $apdat;
            $_SESSION["tdat"] = $tdat;
            $_SESSION["twrpturgency"] = $twrpturgency;
            $_SESSION["payflag"] = $payflag;
            $_SESSION["dob"] = $dob;
            $_SESSION["sex"] = $sex;
            $_SESSION["nationality"] = $nationality;
            $_SESSION["hicardno"] = strval($hicardno);
            $_SESSION["email"] = $email;
            $_SESSION["companytitle"] = $companytitle;
            $_SESSION["sendname"] = $sendname;
            $_SESSION["receiptid"] = $receiptid;
            $_SESSION["memo"] = $memo;

            // $_SESSION["xmappoint"]=$xmappoint;
            // $_SESSION["testreason"]=$testreason ;
            // $userid1 = $userid;
            // $passportid1=strval($passportid);
            // $mobile1=strval($mobile);
            // $hicardno1=strval($hicardno);
            // $_SESSION["type"]=$per_type;
            // $_SESSION["xmapdat"]=$xmapdat;
        }
    } catch (PDOException | Exception $th) {
        echo ($th->getMessage());
    }
}

