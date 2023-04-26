<?php
namespace appoint;
use appoint\Ads_Param;
use stdClass;
use PDO;
use PDOException;
use Exception;
require_once "./Ads_Param.php";

$param = new Ads_Param();

header('Content-type: application/json');
try {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if(trim($data['apdat'])<>""){
        $apdat = date_format(date_create($data['apdat']), "Y-m-d");
    }
    echo(gettype($data['tdat'])."value=".$data['tdat']);
    if($data["tdat"]<>""){
        echo("OK1");
        $tdat = 'NULL';
    }else{
        echo("OK2");
        $tdat = date_format(date_create($data['tdat']), "Y-m-d H:i:s");
    }
    if(trim($data['dob'])<>""){
        $dob = date_format(date_create($data['dob']), "Y-m-d");
    }
    if(trim($data['rdat'])<>""){
        $rdat = date_format(date_create($data['rdat']), "Y-m-d H:i:s");
    }
    $uuid           = $data['uuid'];
    $cname          = $data['cname'];
    $fname          = $data['fname'];
    $userid         = $data['userid'];
    $passportid     = $data['passportid'];
    $mtpid          = $data['mtpid'];
    $hicardno       = $data['hicardno'];
    $mobile         = $data['mobile'];
    $uemail         = $data['uemail'];
    $sampleid2      = $data['sampleid2'];
    $twrpturgency   = $data['twrpturgency'];
    $testreason     = $data['testreason'];
    $nationality    = $data['nationality'];
    $conn = new PDO("mysql:host=$param->MySQLServerHost;dbname=$param->LIBOBIO_DB;port=$param->ServerHostPort",
            $param->MySQLUser,$param->MySQLPassword
        );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //prepare sql and bind parameters
    $sql = "UPDATE covid_trans SET
            cname = :cname,fname = :fname,userid = :userid,passportid = :passportid,mtpid = :mtpid,
            hicardno = :hicardno,mobile = :mobile,uemail = :uemail,apdat = :apdat,tdat = :tdat,
            sampleid2 = :sampleid2,twrpturgency = :twrpturgency,testreason = :testreason,dob = :dob,
            nationality = :nationality,rdat = :rdat
            WHERE 
            uuid = :uuid
            ";
    $sth = $conn->prepare($sql);
    $sth->bindValue(':uuid',         $uuid);
    $sth->bindValue(':cname',        $cname);
    $sth->bindValue(':fname',        $fname);
    $sth->bindValue(':userid',       $userid);
    $sth->bindValue(':passportid',   $passportid);
    $sth->bindValue(':mtpid',        $mtpid);
    $sth->bindValue(':hicardno',     $hicardno);
    $sth->bindValue(':mobile',       $mobile);
    $sth->bindValue(':uemail',       $uemail);
    $sth->bindValue(':apdat',        $apdat);
    $sth->bindValue(':tdat',         $tdat);
    $sth->bindValue(':sampleid2',    $sampleid2);
    $sth->bindValue(':twrpturgency', $twrpturgency);
    $sth->bindValue(':testreason',   $testreason);
    $sth->bindValue(':dob',          $dob);
    $sth->bindValue(':nationality',  $nationality);
    $sth->bindValue(':rdat',         $rdat);
    $sth->execute();
    header("HTTP/1.0 200 OK");
    echo('OK');
}
catch(PDOException | Exception $e){
    header("HTTP/1.0 400 Bad Request");
    echo($uuid.$e->getMessage().print_r($data));
}
//closes the DB
$conn = null;                                          
?>