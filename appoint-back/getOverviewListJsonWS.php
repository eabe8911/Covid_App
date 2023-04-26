<?php 
//require("dbconfig.php");
use appoint\Ads_Param;
/*
$page = $_REQUEST['page']; // get the requested page
$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST['sord']; // get the direction
*/
$page  = filter_input(INPUT_GET, "page");
$limit = filter_input(INPUT_GET, "limit");
$sidx  = filter_input(INPUT_GET, "sidx");
$sord  = filter_input(INPUT_GET, "sord");

if (!$sidx) {
    $sidx = 1;
}

$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
if($totalrows) {
    $limit = $totalrows;
}
//call web service to get data
$result = getTransactionList();
$command = json_decode($result, true);
$data = $command['data'];
if($command['status']=='ok'){
}
$count = $command['recordcount'];

if ($limit < 1) {
    $limit = 30;
}

if( $count >0 ) {
	$total_pages = ceil($count/$limit);
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
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
if ($start < 0) {
    $start = 0;
}
$responce = [];
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
$i=0;
foreach($result as $row){
    $responce->rows[$i]['id']=$row['ID'];
    $responce->rows[$i]['cell']=array(
        $row['ID'],$row['Create_Date'],$row['Name'],$row['Type'],$row['TypeName'],$row['StoreID'],$row['StoreName'],
        $row['Image'],$row['Start_Date'],$row['End_Date'],$row['PromotionID'],$row['PromotionName'],$row['PID']
    );
    $i++;
}
echo json_encode($responce);
flush();

function getTransactionList(){
    //include('Common.php');
    require_once("./Ads_Param.php");
    $param = new Ads_Param();
    $SQLKEY = $param->TOKEN;
    $pkgRequest = [
        'status'        => 'REQUEST',
        'command'       => '333202001',
        'organization'  => 'ANY',
        'transaction'   => date("Y-m-d H:i:s"),
        'data'          => 'ANY'
    ];
    //連結到本地端的資料庫讀取廣告資料
    $url = "ads.maxcheng.tw";
    $ch = curl_init();
    $data = json_encode($pkgRequest);
    $options = array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING       => "",
        CURLOPT_AUTOREFERER    => true,
        CURLOPT_CONNECTTIMEOUT => 120,
        CURLOPT_TIMEOUT        => 120,
        CURLOPT_MAXREDIRS      => 10,
    );
    curl_setopt_array( $ch, $options );
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("key"=>$SQLKEY, "data"=>$data))); 
    $mResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    switch($httpCode){
        case 200:
            echo('httpcode->'.$httpCode.'<br>');
            echo('response->'.$mResponse.'<br>');
            break;
        default:
            echo("Return http code is {$httpCode} \n".curl_error($ch).'<br>');
            echo("response is {$mResponse} '<br>'");
    }
    curl_close($ch);
}