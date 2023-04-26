<?php

namespace Composer\Package\Archiver;

use ZipArchive;
use Coposer\Util\Filesystem;
require_once '/usr/share/php/vendor/autoload.php';

$today=$download_link="";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {

    $today= trim($_POST["zipfile_date"]);
    check_zipfile_complete($today);
}

function check_zipfile_complete($today){

    $trigger=0;

    $conn = mysqli_connect("localhost","libo_user","xxx");
    mysqli_select_db($conn, "libodb");

    // $sql="select cname,fname,sampleid2,apdat,pcrpdfflag from libodb.covid_trans where (sampleid2 like 'QH%' and apdat='{$today}' and pcrpdfflag='Y');";
    $sql="select cname,fname,sampleid2,apdat,pcrpdfflag from libodb.covid_trans where (sampleid2 like 'QH%' and apdat='{$today}');";

    #要資料

    $result = $conn->query($sql);

    $result->num_rows;

    if ($result->num_rows > 0) {
        
        while ($row = $result->fetch_assoc()) {
            $i = 0;
            foreach ($row as $item) {
                if($i==4){
                    if($item!="Y"){
                        $trigger=1; 
                    }
                }
                $i+=1;   
            }

        }


        #decide
        if($trigger==0){
            echo "<script language='javascript'>alert('檢驗流程已結束，請下載壓縮檔');</script>";
            zipfile_download($today);
        }
        else if($trigger==1){
            echo "<script language='javascript'>alert('檢驗流程尚未結束');</script>";
        }
    }

    else{
        echo "<script language='javascript'>alert('尚未有檢驗結果');</script>";
    }
    // Close connection
    mysqli_close($conn);

}

function zipfile_download($today){

    $date=substr($today, 5, 5);

    #zip 檔名稱
    $zipname = '../pdf_reports/'.$today.'/'.$today.'.zip';
    #要夾帶的檔案
    // $files = [ 'html/example.xlsx'];
    $zip = new ZipArchive();
    // Open Zip file
    $res = $zip->open($zipname, ZipArchive::CREATE);
    if ($res) {
        // Add Directory
        #壓縮檔內加入子資料夾，並且把檔案丟進去
        // $zip->addEmptyDir('html');
        // $today=date("Y-m-d");

        // echo "Today is " .  . "<br>";
        // Connect to local db

        $conn = mysqli_connect("localhost","libo_user","xxx");
        mysqli_select_db($conn, "libodb");

        $sql="select cname,fname,sampleid2,apdat,pcrpdfflag from libodb.covid_trans where (sampleid2 like 'QH%' and apdat='{$today}' and pcrpdfflag='Y');";

        #要資料

        $result = $conn->query($sql);

        $result->num_rows;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $i = 0;
                foreach ($row as $item) {
                    if($i==0){
                        $cname=$item;
                        // echo $item."<br>";
                    }else if ($i==1){
                        $fname=$item;
                        // echo $item."<br>";
                    }
                    else if($i==2){
                        $sample_id=$item;
                        // echo 'Sampleid:'.$item."<br>";
                        // echo 'Sampleid_id:'.$sample_id."<br>";
                    }else{
                        // echo $item."<br>";
                    }
                    $i+=1;
                }

                #找出有加密的pdf
                if ($cname!=""){
                    $zip->addFile('../pdf_reports/'.$today.'/'.$sample_id.'.pdf');
                    $zip->renameName('../pdf_reports/'.$today.'/'.$sample_id.'.pdf',$sample_id.'_'.$cname.'_'.$date.'.pdf');
                }else{
                    $zip->addFile('../pdf_reports/'.$today.'/'.$sample_id.'.pdf');
                    $zip->renameName('../pdf_reports/'.$today.'/'.$sample_id.'.pdf',$sample_id.'_'.$fname.'_'.$date.'.pdf');
                }
                

                // $pattern = "/{$sample_id}_[0-9a-zA-Z]+.pdf/";
                // $files = glob("pdf_reports/{$today}/*.pdf");
                // foreach ($files as $filename) {
                //     preg_match($pattern, $filename, $matches);
                //     if (!empty($matches)){
                //         $pdf_name=$matches[0];
                //         echo $pdf_name;
                //         // $zip->addFile( 'html/example.xlsx');
        
                //         $zip->addFile('pdf_reports/'.$today.'/'.$pdf_name);
                //         $zip->renameName('pdf_reports/'.$today.'/'.$pdf_name,'pdf_reports/'.$sample_id.'_'.$cname.$fname.$today.'.pdf');
                //     }
                // }
                
            }
        }
        // Close connection
        mysqli_close($conn);
    }

    // Close file
    $zip->close();
    echo "<h3><a href='".$zipname."'>Download file</a></h3>";

}
?>