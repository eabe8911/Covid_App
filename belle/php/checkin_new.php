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
require_once "./class/Appointment.php";
// require_once "param.php";

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
$fname = $mobile = $sex = $sendname = $testreason = $hicardno = $mtpid = $xmappoint = $xmapdat = $companytitle = $payflag = $email = $memo = $receiptid = $unit = "";
$testtype = '0';
$page = $page_count = 0;
$query_date = "";
$payflag_name="";

// // TODO: receive uuid from url
// $uuid = filter_input( INPUT_GET,'id');
// $query_date = filter_input( INPUT_GET,'date'); 

// $conn = new PDO(
//     "mysql:host=$hostname; dbname=$dbname; port=$port",
//     $username,
//     $password,
//     array(PDO::MYSQL_ATTR_FOUND_ROWS => true)
// );
// $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
        unit= :unit, 
        memo=:memo
        where uuid=:uuid";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':sampleid1', $_POST['sampleid1']);
        $stmt->bindParam(':sampleid2', $_POST['sampleid2']);
        $stmt->bindParam(':apdat', $_POST['apdat']);
        $stmt->bindParam(':dob', $_POST['dob']);
        $stmt->bindParam(':hicardno', $_POST['hicardno']);
        $stmt->bindParam(':companytitle', $_POST['companytitle']);
        $stmt->bindParam(':testtype', $_POST['testtype']);
        $stmt->bindParam(':userid', $_POST['userid']);
        $stmt->bindParam(':cname', $_POST['cname']);
        if (empty($_POST['tdat'])) {
            $stmt->bindValue(':tdat', null, PDO::PARAM_NULL);
        } else {
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
        $stmt->bindParam(':unit', $_POST['unit']);
        $stmt->bindParam(':memo', $_POST['memo']);
        $stmt->bindParam(':uuid', $_POST['uuid']);
        $stmt->execute();
        $save_result = '存檔成功';
        // return to home page
        header("location: ../appoint-back/home.php?date=" . $_POST['query_date'] . "&id=" . $_POST['uuid']);

    } catch (PDOException | Exception $th) {
        throw new Exception($th->getMessage() . print_r($_POST), $th->getCode());
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["checkin_x"])) {
    try {
        $id = filter_input(INPUT_POST, 'uuid');
        $appointment = new Appointment();
        $appointment->CheckIn($id);
        $result_msg = '4';
    } catch (PDOException | Exception $th) {
        $result_msg = '5';
        throw new Exception($th->getMessage() . print_r($_POST), $th->getCode());
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    try {
        $id = filter_input(INPUT_POST, 'pcrid_uuid');
        $appointment = new Appointment();
        $appointment->searchAppointment($id);
        $response = $appointment->get_AppointmentInfo();

        $sql = "SELECT * FROM covid_trans WHERE uuid=:uuid";
        $sql_comment = $_SESSION["username"] . ": " . $sql;
        write_sql($sql_comment);

        $uuid = $response["uuid"];
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
        switch ($response["twrpturgency"]) {
            case 'normal':
                $twrpturgency = "一般件";
                break;
            case 'urgent':
                $twrpturgency = "急件";
                break;
            case 'hiurgent':
                $twrpturgency = "特急件";
                break;

            default:
                $twrpturgency = "";
                break;
        };

        $payflag = $response["payflag"];
        switch ($response["payflag"]) {
            case 'N':
                $payflag_name = "未付款";
                break;
            case '2':
                $payflag_name = "現金";
                break;
            case '3':
                $payflag_name = "刷卡";
                break;
            case '4':
                $payflag_name = "月結";
                break;
            case '5':
                $payflag_name = "匯款";
                break;
            default:
                $payflag_name = "未付款";
                break;
        }


        $dob = $response["dob"];
        $sex = $response["sex"];
        $nationality = $response["nationality"];
        $unit = $response["unit"];
        $hicardno = $response["hicardno"];
        $email = $response["uemail"];
        $companytitle = $response["companytitle"];
        $sendname = $response["sendname"];
        $receiptid = $response["receiptid"];
        $testtype = $response["testtype"];
        $memo = $response["memo"];


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
        $_SESSION["unit"] = $unit;
        $_SESSION["hicardno"] = strval($hicardno);
        $_SESSION["email"] = $email;
        $_SESSION["companytitle"] = $companytitle;
        $_SESSION["sendname"] = $sendname;
        $_SESSION["receiptid"] = $receiptid;
        $_SESSION["memo"] = $memo;
        $result_msg = '2';
    } catch (PDOException | Exception $th) {
        // echo ($th->getMessage());
        $input_err = $th->getMessage();
        $result_msg = '3';
    }
}