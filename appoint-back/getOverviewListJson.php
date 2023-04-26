<?php
namespace appoint;

use appoint\Ads_Param;
use stdClass;
use PDO;
use PDOException;

require_once "./Ads_Param.php";

$param = new Ads_Param();

$page = filter_input(INPUT_GET, "page");
$limit = filter_input(INPUT_GET, "limit");
$sidx = filter_input(INPUT_GET, "sidx");
$sord = filter_input(INPUT_GET, "sord");
$query_appoint_date = filter_input(INPUT_GET, "date");
$where = "";
if ($query_appoint_date <> "") {
    $where = " WHERE apdat='" . $query_appoint_date . "' ";
}
if (!$sidx) {
    $sidx = 1;
}

$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows'] : false;
if ($totalrows) {
    $limit = $totalrows;
}
try {
    // shell_exec("ssh -L 3377:localhost:3306 willie@localhost");
    // $conn = new PDO("mysql:host=localhost; dbname=covid_trans; port=3306", "root", "password");
    $conn = new PDO(
        "mysql:host=$param->MySQLServerHost; dbname=$param->LIBOBIO_DB; port=$param->ServerHostPort",
        $param->MySQLUser, $param->MySQLPassword
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT COUNT(*) AS count FROM covid_trans $where";
    // echo $sql;
    // die();
    $sth = $conn->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    $Count = count($result);
    if ($limit < 1) {
        $limit = 30;
    }

    if ($Count > 0) {
        $total_pages = ceil($Count / $limit);
    } else {
        $total_pages = 0;
    }
    if ($page > $total_pages) {
        $page = $total_pages;
    }
    if ($page < 1) {
        $page = 1;
    }

    if ($limit < 0) {
        $limit = 0;
    }
    $start = $limit * $page - $limit; // do not put $limit*($page - 1)
    if ($start < 0) {
        $start = 0;
    }
    $sql = "SELECT * FROM covid_trans " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit";
    //$sql = "SELECT * FROM covid_trans ORDER BY $sidx $sord LIMIT $start , $limit";
    $sth = $conn->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    $Count = count($result);
    //echo $Count; die();
    if ($Count > 0) {
        $responce = new stdClass();
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $Count;
        $i = 0;

        foreach ($result as $row) {
            $ReportAgeing = getReportAgeing($row['twrpturgency']);
            $InspectionType = getInspectionType($row['testtype']);
            $payflag = getPayFlag($row['payflag']);
            $qrptflag = getQrptFlag($row['qrptflag']);
            $responce->rows[$i]['id'] = $row['uuid'];
            $responce->rows[$i]['cell'] = array(
                $row['uuid'], $row['cname'], $row['fname'], $row['userid'],
                $row['passportid'], $row['mtpid'], $row['hicardno'],
                $row['mobile'], $row['uemail'],
                $row['apdat'], $row['tdat'],
                $InspectionType, $row['sampleid2'],
                $ReportAgeing,
                $row['companytitle'], $row['sendname'], $row['receiptid'],
                $row['testreason'], $payflag, $qrptflag,
                $row['rdat'], $row['pcrtest']
            );
            $i++;
        }
        echo json_encode($responce);
    }
} catch (PDOException $e) {
    //reports a DB connection failure
    header("HTTP/1.0 400 Bad Request");
    echo ($e->getMessage());
}
//closes the DB
$conn = null;

function getPayFlag($type)
{
    switch ($type) {
        case 'N':
        case '1':
            $payflag = "未付款";
            break;
        case '2':
            $payflag = "現金";
            break;
        case '3':
            $payflag = "信用卡";
            break;
        case '4':
            $payflag = "月結";
            break;
        case '5':
            $payflag = "匯款";
            break;
        case '6':
            $payflag = "日航";
            break;
        default:
            $payflag = "";
            break;
    }
    return $payflag;
}

function getQrptFlag($type){
    switch ($type) {
       

        case 'Y':
            $qrptflag = "已寄送";
            break;
        default:
            $qrptflag = "";
            break;
    }
    return $qrptflag;
}

function getReportAgeing($type)
{
    switch ($type) {
        case 'normal':
            $reportAgeing = "一般件";
            break;
        case 'urgent':
            $reportAgeing = "急件";
            break;
        case 'hiurgent':
            $reportAgeing = "急件特別診";
            break;
        default:
            $reportAgeing = "";
            break;
    }
    return $reportAgeing;
}

function getInspectionType($type)
{
    switch ($type) {
        case 1:
            $InspectionType = "抗原快篩";
            break;
        case 2:
            $InspectionType = "PCR檢測";
            break;
        default:
            $reportAgeing = "PCR檢測";
            break;
    }
    return $InspectionType;
}

?>