<?php
function whoareU($item){
    if ($item=="B122408253"){
        $whoIM="陳柏源";//0570
        return $whoIM;
    }else if($item=="N123478768"){
        $whoIM="陳毓峻";//0564
        return $whoIM;
    }else if($item=="D221222459"){
        $whoIM="石鴻瑞";//0581
        return $whoIM;
    }else if($item=="H123160258"){
        $whoIM="鄭偉志";//0550
        return $whoIM;
    }else if($item=="N225198185"){
        $whoIM="黃琴涵";//0561
        return $whoIM;
    }else if($item=="P124237860"){
        $whoIM="陳奕勳";//0559
        return $whoIM;
    }else if($item=="P222717661"){
        $whoIM="李桂榕";//0544
        return $whoIM;
    }else if($item=="A225558000"){
        $whoIM="許育華";
        return $whoIM;
    }else if($item=="yh"){
        $whoIM="生資測試";
        return $whoIM;
    }else{
        return $item;
    }
}
function StatusCheck($item)
{
    if ($item == "C") {
        $Status = "已覆核";
        return $Status;
    } else if ($item == "Y") {
        $Status = "已寄出";
        return $Status;
    } else if ($item == "S") {
        $Status = "不需操作";
        return $Status;
    } else if ($item == "N") {
        $Status = "尚無資料";
        return $Status;
    } else {
        return $item;
    }
}
function pdf_StatusCheck($item){
    if ($item=="Y"){
        $Status="已產生";
        return $Status;
    }else{
        return $item;
    }
}
function ConfirmCheck($item)
{
    if ($item == "Y") {
        $Status = "<td>結果一致</td>";
        return $Status;
    } else if ($item == "N") {
        $Status = "<td style='color:red;'>不符合</td>";
        return $Status;
    } else {
        $Status = "<td>{$item}</td>";
        return $Status;
    }
}
?>