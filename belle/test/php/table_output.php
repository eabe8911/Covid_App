<?php
require_once "whoareU.php";

function search_table3($sql, $table_list)
{
    $conn = mysqli_connect("localhost","libo_user","xxx");
    mysqli_select_db($conn, "libodb");
    
    $result_total = $conn->query("SELECT uuid from libodb.covid_test");
    $total=$result_total->num_rows;

    $result = $conn->query($sql);

    $count=$result->num_rows;

    $complete= $total-$count;

    echo "<div id='text1'>總計 {$total} 筆，已完成 {$complete} 筆，待完成 {$count} 筆。<br></div>";

    if ($result->num_rows > 0) {
        echo '<table class="table table-hover">';
        echo '<tr>';
        for ($i = 0; $i < count($table_list); $i++) {
            if($i==0){
                    
            }else{
            echo '<th style="color:#556B2F" id="'.$table_list[$i].'">' . $table_list[$i] . '</th>';
            }
        }
        echo '</tr>';

        $future_apdat=0;
        $uncheckin=0;
        $unexam_1=0;
        $unexam_2=0;
        $unreport_1=0;
        $unreport_2=0;
        $unemail_1=0;
        $unemail_2=0;

        while ($row = $result->fetch_assoc()) {
            $i=0;
            date_default_timezone_set("Asia/Taipei");
            echo '<tr>';
            foreach ($row as $item) {
                $item=trim($item);
                if($i==0){
                    
                }
                else if($i==7 &&($item>date("Y-m-d"))){
                    echo "<td><span style='color:blue;'>{$item}</td>";
                    $future_apdat+=1;
                }
                else if($i==8 &&(($item>date("Y-m-d H:i:s")||$item=="0000-00-00 00:00:00"))){
                    echo "<td><span style='color:blue;'>{$item} (未報到)</td>";
                    $uncheckin+=1;
                }
                else if ($i==9 && $row["testtype"]=="1"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unexam_1+=1;
                }
                else if($i==11 && $row["testtype"]=="1"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unreport_1+=1;
                }
                else if ($i==10 && $row["testtype"]=="2"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unexam_2+=1;
                }
                else if($i==12 && $row["testtype"]=="2"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unreport_2+=1;
                }
                else if($i==9 && $row["testtype"]=="3"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unexam_1+=1;
                }
                else if($i==10 && $row["testtype"]=="3"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unexam_2+=1;
                }
                else if($i==11 && $row["testtype"]=="3"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unreport_1+=1;
                }
                else if($i==12 && $row["testtype"]=="3"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unreport_2+=1;
                }
                else if(($i==13 && $item!="") || ($i==14&& $item!="")){
                    $item = StatusCheck($item);
                    echo "<td>{$item}</td>";
                }
                else if($i==13 && $row["testtype"]=="1"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unemail_1+=1;
                }
                else if($i==14 && $row["testtype"]=="2"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unemail_2+=1;
                }                
                else if($i==13 && $row["testtype"]=="3"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unemail_1+=1;
                }
                else if($i==14 && $row["testtype"]=="3"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unemail_2+=1;
                }
                else{
                    echo "<td>{$item}</td>";
                }
                $i+=1;

            }
            echo '</tr>';
        }
        echo '</table>';
    
        $text="未至檢測日 {$future_apdat} 筆，待報到 {$uncheckin} 筆。<br>
        快篩未檢測 {$unexam_1} 筆，PCR 未檢測 {$unexam_2} 筆。<br>
        快篩報告待產生 {$unreport_1} 筆，PCR 報告待產生 {$unreport_2} 筆。<br>快篩報告待寄送 {$unemail_1} 筆，PCR 報告待寄送 {$unemail_2} 筆。<br>";
        echo '<div id="text" hidden>'.$text.'</div>';

    } else {
        echo "待完成檢體 0 筆。";
    }

// Close connection
mysqli_close($conn);
}

function search_table4($sql, $table_list)
{
    $conn = mysqli_connect("localhost","libo_user","xxx");
    mysqli_select_db($conn, "libodb");
    
    $result_total = $conn->query("SELECT uuid from libodb.covid_test");
    $total=$result_total->num_rows;

    $result = $conn->query($sql);

    $count=$result->num_rows;

    $complete= $total-$count;

    // echo "<div id='text1'>總計 {$total} 筆，已完成 {$complete} 筆，待完成 {$count} 筆。<br></div>";

    if ($result->num_rows > 0) {
        echo '<table class="table table-hover">';
        echo '<tr>';
        for ($i = 0; $i < count($table_list); $i++) {
            if($i==0){
                    
            }else{
            echo '<th style="color:#556B2F" id="'.$table_list[$i].'">' . $table_list[$i] . '</th>';
            }
        }
        echo '</tr>';

        $future_apdat=0;
        $uncheckin=0;
        $unexam_1=0;
        $unexam_2=0;
        $unreport_1=0;
        $unreport_2=0;
        $unemail_1=0;
        $unemail_2=0;

        while ($row = $result->fetch_assoc()) {
            $i=0;
            date_default_timezone_set("Asia/Taipei");
            echo '<tr>';
            foreach ($row as $item) {
                $item=trim($item);
                if($i==0){

                }
                else if($i==7 &&($item>date("Y-m-d"))){
                    echo "<td><span style='color:blue;'>{$item}</td>";
                    $future_apdat+=1;
                }
                else if($i==8 &&(($item>date("Y-m-d H:i:s")||$item=="0000-00-00 00:00:00"))){
                    echo "<td><span style='color:blue;'>{$item} (未報到)</td>";
                    $uncheckin+=1;
                }else if(($i==11&& $item!="")||($i==12&& $item!="")){
                    $item = pdf_StatusCheck($item);
                    echo "<td>{$item}</td>";
                }else if(($i==13&& $item!="")||($i==14&& $item!="")){
                    $item = StatusCheck($item);
                    echo "<td>{$item}</td>";
                }
                else if ($i==9 && $row["testtype"]=="1"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unexam_1+=1;
                }
                else if($i==11 && $row["testtype"]=="1"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unreport_1+=1;
                }
                else if ($i==10 && $row["testtype"]=="2"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unexam_2+=1;
                }
                else if($i==12 && $row["testtype"]=="2"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unreport_2+=1;
                }
                else if($i==9 && $row["testtype"]=="3"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unexam_1+=1;
                }
                else if($i==10 && $row["testtype"]=="3"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unexam_2+=1;
                }
                else if($i==11 && $row["testtype"]=="3"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unreport_1+=1;
                }
                else if($i==12 && $row["testtype"]=="3"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unreport_2+=1;
                }
                else if($i==13 && $row["testtype"]=="1"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unemail_1+=1;
                }
                else if($i==14 && $row["testtype"]=="2"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unemail_2+=1;
                }                
                else if($i==13 && $row["testtype"]=="3"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unemail_1+=1;
                }
                else if($i==14 && $row["testtype"]=="3"&& $item ==""){
                    echo "<td><span style='color:red;'>未完成</td>";
                    $unemail_2+=1;
                }
                else{
                    echo "<td>{$item}</td>";
                }
                $i+=1;

            }
            $valid_date = date('Y-m-d', strtotime($row["tdat"]));
            if ($row["testtype"]=="1"){
                $sampleid=$row["sampleid1"];
                echo "<td><a href='../pdf_reports/{$valid_date}/{$sampleid}.pdf' target='_blank'>快篩 PDF</a></td>";
            }else if($row["testtype"]=="2"){
                $sampleid=$row["sampleid2"];
                echo "<td><a href='../pdf_reports/{$valid_date}/{$sampleid}.pdf' target='_blank'>PCR PDF</a></td>";
            }else{
                $sampleid1_pdf=$row["sampleid1"];
                echo "<td>";
                echo "<a href='../pdf_reports/{$valid_date}/{$sampleid1_pdf}.pdf' target='_blank'>快篩 PDF</a><br>";
                $sampleid2_pdf=$row["sampleid2"];
                echo "<a href='../pdf_reports/{$valid_date}/{$sampleid2_pdf}.pdf' target='_blank'>PCR PDF</a><br>";
                echo "</td>";
            }
            echo '</tr>';
        }
        echo '</table>';
    
        // $text="未至檢測日 {$future_apdat} 筆，待報到 {$uncheckin} 筆。<br>
        // 快篩未檢測 {$unexam_1} 筆，PCR 未檢測 {$unexam_2} 筆。<br>
        // 快篩報告待產生 {$unreport_1} 筆，PCR 報告待產生 {$unreport_2} 筆。<br>快篩報告待寄送 {$unemail_1} 筆，PCR 報告待寄送 {$unemail_2} 筆。<br>";
        // echo '<div id="text" hidden>'.$text.'</div>';

    } else {
        echo "已完成檢體 0 筆。";
    }

// Close connection
mysqli_close($conn);
}
?>