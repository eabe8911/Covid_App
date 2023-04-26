<?php
header('Cache-Control: no-cache, must-revalidate');
//下載處方籤至手機
//PreDetail_Get();die();
//下載處方籤至手機
//Prescription_Get();die();
// 處方籤通知
//Prescription_Notice();
// 存處方籤
//Prescription_Save();die();
//廣告推播
//ADs_Pull();
//廣告點擊
//ADs_Fire();
//接收廣告
//ADs_Receive();
// save advertisement
ADs_Save();
//get promotion
//Promotion_Get();

function PreDetail_Get(){
    //下載處方籤明細至手機
    echo("下載處方籤明細至手機<br>");
    $RequestData = [
        'IDX'          => "J102683873",
        'TRN_NO'       => '1081219120004' 
    ];
    $pkgRequest = [
        'status'        => 'REQUEST',
        'command'       => '112203017',
        'organization'  => 'SJEN',
        'transaction'   => date("Y-m-d H:i:s"),
        'data'          => $RequestData
    ];
    CallWebService($pkgRequest);
}

function Prescription_Get(){
    //下載處方籤至手機
    echo("下載處方籤至手機<br>");
    $RequestData = [
        'PID'          => "J102683873"
    ];
    $pkgRequest = [
        'status'        => 'REQUEST',
        'command'       => '112203015',
        'organization'  => 'SJEN',
        'transaction'   => date("Y-m-d H:i:s"),
        'data'          => $RequestData
    ];
    CallWebService($pkgRequest);
}

function Prescription_Notice(){
    //處方籤通知
    echo("處方籤通知<br>");
    $Notice = [
        'P_ID'          => "A123456789",
        'PRE_ID'        => date("YmdHis"),
        'STATUS'        => 1,
        'TRANS_DATE'    => date("Y-m-d H:i:s"),
        'NOTICE_DATE'   => date("Y-m-d"),
        'PICK_DATE'     => date("Y-m-d"),
    ];
    $pkgRequest = [
        'status'        => 'REQUEST',
        'command'       => '112203016',
        'organization'  => 'SJEN',
        'transaction'   => date("Y-m-d H:i:s"),
        'data'          => $Notice
    ];
    CallWebService($pkgRequest);
}

function PrescriptionDetail_Save(){
    //處方籤存檔
    echo("處方籤存檔<br>");
    $RequestDetail1 = [
        'TRN_NO'        => date("YmdHis"),
        'SEQ'           => 'SE',
        'DATA_FROM'     => 'DF',
        'MARK_NO'       => 'MARK_NO',
        'COST_DAT'      => 'COSTD',
        'APPLY_FOR'     => 'A',
        'CASE1'         => 'C',
        'IDX'           => 'IDX',
        'CODE'          => 'CODE',
        'QUANTITY'      => 1.11,
        'FREQUENCE'     => 'FREQUENCE',
        '_USAGE'        => 'U1',
        'AMOUNT'        => 2.22,
        'PRICE'         => 3.33,
        'COST'          => 4.44,
        'NAME'          => "NAME",
        'P_PRICE'       => 5.55,
        'UX'            => 'U',
        'PRE_NO'        => 'PRE_NO',
        'FLAG01'        => '1',
        'FLAG02'        => 'F2',
        'FLAG03'        => 'FL3',
        'FLAG04'        => 'F4',
        'FLAG05'        => 'FL5',
        'P01'           => 'P01',
        'P02'           => 'P02',
        'P03'           => 'P03',
        'N01'           => 6
    ];
    sleep(1);
    $RequestDetail2 = [
        'TRN_NO'        => date("YmdHis"),
        'SEQ'           => 'SE',
        'DATA_FROM'     => 'DF',
        'MARK_NO'       => 'MARK_NO',
        'COST_DAT'      => 'COSTD',
        'APPLY_FOR'     => 'A',
        'CASE1'         => 'C',
        'IDX'           => 'IDX',
        'CODE'          => 'CODE',
        'QUANTITY'      => 1.11,
        'FREQUENCE'     => 'FREQUENCE',
        '_USAGE'        => 'U2',
        'AMOUNT'        => 2.22,
        'PRICE'         => 3.33,
        'COST'          => 4.44,
        'NAME'          => "NAME",
        'P_PRICE'       => 5.55,
        'UX'            => 'U',
        'PRE_NO'        => 'PRE_NO',
        'FLAG01'        => '1',
        'FLAG02'        => 'F2',
        'FLAG03'        => 'FL3',
        'FLAG04'        => 'F4',
        'FLAG05'        => 'FL5',
        'P01'           => 'P01',
        'P02'           => 'P02',
        'P03'           => 'P03',
        'N01'           => 6
    ];
    $pkgRequest = [
        'status'        => 'REQUEST',
        'command'       => '112203014',
        'organization'  => 'HMO002',
        'transaction'   => date("Y-m-d H:i:s"),
        'data'          => array($RequestDetail1, $RequestDetail2)
    ];
    CallWebService($pkgRequest);
}

function Prescription_Save(){
    //處方籤存檔
    echo("處方籤存檔<br>");
    $RequestDetail1 = [
        'TRN_NO'        => date("YmdHis"),
        'SEQ'           => 'SE',
        'DATA_FROM'     => 'DF',
        'MARK_NO'       => 'MARK_NO',
        'COST_DAT'      => 'COSTD',
        'APPLY_FOR'     => 'A',
        'CASE1'         => 'C',
        'IDX'           => 'IDX',
        'CODE'          => 'CODE',
        'QUANTITY'      => 1.11,
        'FREQUENCE'     => 'FREQUENCE',
        '_USAGE'         => 'USAG',
        'AMOUNT'        => 2.22,
        'PRICE'         => 3.33,
        'COST'          => 4.44,
        'NAME'          => "NAME",
        'P_PRICE'       => 5.55,
        'UX'            => 'U',
        'PRE_NO'        => 'PRE_NO',
        'FLAG01'        => '1',
        'FLAG02'        => 'F2',
        'FLAG03'        => 'FL3',
        'FLAG04'        => 'F4',
        'FLAG05'        => 'FL5',
        'P01'           => 'P01',
        'P02'           => 'P02',
        'P03'           => 'P03',
        'N01'           => 6
    ];
    sleep(1);
    $RequestDetail2 = [
        'TRN_NO'        => date("YmdHis"),
        'SEQ'           => 'SE',
        'DATA_FROM'     => 'DF',
        'MARK_NO'       => 'MARK_NO',
        'COST_DAT'      => 'COSTD',
        'APPLY_FOR'     => 'A',
        'CASE1'         => 'C',
        'IDX'           => 'IDX',
        'CODE'          => 'CODE',
        'QUANTITY'      => 1.11,
        'FREQUENCE'     => 'FREQUENCE',
        '_USAGE'         => 'USAG',
        'AMOUNT'        => 2.22,
        'PRICE'         => 3.33,
        'COST'          => 4.44,
        'NAME'          => "NAME",
        'P_PRICE'       => 5.55,
        'UX'            => 'U',
        'PRE_NO'        => 'PRE_NO',
        'FLAG01'        => '1',
        'FLAG02'        => 'F2',
        'FLAG03'        => 'FL3',
        'FLAG04'        => 'F4',
        'FLAG05'        => 'FL5',
        'P01'           => 'P01',
        'P02'           => 'P02',
        'P03'           => 'P03',
        'N01'           => 6
    ];
    $RequestData = array(
        "TRN_NO"            => date("YmdHis"),    //1
        "DATA_FROM"         => "DF",
        "MEDICAL_NO"        => "MEDICAL_NO",
        "MARK_NO"           => "MARK_NO",
        "COST_DAT"          => "COSTD",
        "APPLY_FOR"         => "A",
        "CASE1"             => "C",
        "CASE2"             => "CA",
        "TREAT1"            => "T1",
        "TREAT2"            => "T2",    //10
        "TREAT3"            => "T3",
        "TREAT4"            => "T4",
        "SEEK_SECT"         => "SS",
        "SEEK_DAY"          => "SEEKDAY",
        "MAKE_DAY"          => "MAKEDAY",
        "BIRTHDAY"          => "BIRTHDA",
        "IDX"               => "IDX",
        "NOX"               => "NOX",
        "GIVE"              => "G",
        "PRE_NO"            => "PRE_NO",    //20
        "SICK1"             => "SICK1",
        "SICK2"             => "SICK2",
        "SICK3"             => "SICK3",
        "SUCC"              => "SUC",
        "SUCC_NO"           => "S",
        "SUCC_TIME"         => "S",
        "MEDICALDAY"        => "MED",
        "DOCTOR"            => "DOCTOR",
        "PHARMACIST"        => "PHARMACIST",
        "TOTAL"             => 1.11,    //30
        "SEVICE_NO"         => "SEVICE_NO",
        "SEVICE"            => 2.22,
        "COST"              => 333,
        "NAME"              => "NAME",
        "SHARE"             => 444,
        "SUMT"              => 555,
        "FLAG01"            => "F",
        "FLAG02"            => "FL",
        "FLAG03"            => "FLAG",
        "FLAG04"            => "F4",    //40
        "FLAG05"            => "F5",
        "FLAG06"            => "6",
        "FLAG07"            => "7",
        "P01"               => 666,
        "P02"               => "P02",
        "P03"               => "P03",
        "N01"               => 777,
        "N02"               => 888,
        "N03"               => 9.99,
        "SWT"               => "S",     //50
        "T_FLAG"            => "T",
        "L_DATE"            => "L_DATE",    //52
        "L_EMP"             => "L_EMP",
        "C_DATE"            => "C_DATE",
        "C_EMP"             => "C_EMP",
        "BABYBIRTHDAY"      => "BABYBIR",
        "RED_ORG"           => "RED_ORG", //57
        "presubs"           => array($RequestDetail1, $RequestDetail2)
    );
    sleep(1);
    $RequestDetail3 = [
        'TRN_NO'        => date("YmdHis"),
        'SEQ'           => 'SE',
        'DATA_FROM'     => 'DF',
        'MARK_NO'       => 'MARK_NO',
        'COST_DAT'      => 'COSTD',
        'APPLY_FOR'     => 'A',
        'CASE1'         => 'C',
        'IDX'           => 'IDX',
        'CODE'          => 'CODE',
        'QUANTITY'      => 1.11,
        'FREQUENCE'     => 'FREQUENCE',
        '_USAGE'         => 'USAG',
        'AMOUNT'        => 2.22,
        'PRICE'         => 3.33,
        'COST'          => 4.44,
        'NAME'          => "NAME",
        'P_PRICE'       => 5.55,
        'UX'            => 'U',
        'PRE_NO'        => 'PRE_NO',
        'FLAG01'        => '1',
        'FLAG02'        => 'F2',
        'FLAG03'        => 'FL3',
        'FLAG04'        => 'F4',
        'FLAG05'        => 'FL5',
        'P01'           => 'P01',
        'P02'           => 'P02',
        'P03'           => 'P03',
        'N01'           => 6
    ];

    $RequestData1 = array(
        "TRN_NO"            => date("YmdHis"),    //1
        "DATA_FROM"         => "DF",
        "MEDICAL_NO"        => "MEDICAL_NO",
        "MARK_NO"           => "MARK_NO",
        "COST_DAT"          => "COSTD",
        "APPLY_FOR"         => "A",
        "CASE1"             => "C",
        "CASE2"             => "CA",
        "TREAT1"            => "T1",
        "TREAT2"            => "T2",    //10
        "TREAT3"            => "T3",
        "TREAT4"            => "T4",
        "SEEK_SECT"         => "SS",
        "SEEK_DAY"          => "SEEKDAY",
        "MAKE_DAY"          => "MAKEDAY",
        "BIRTHDAY"          => "BIRTHDA",
        "IDX"               => "IDX",
        "NOX"               => "NOX",
        "GIVE"              => "G",
        "PRE_NO"            => "PRE_NO",    //20
        "SICK1"             => "SICK1",
        "SICK2"             => "SICK2",
        "SICK3"             => "SICK3",
        "SUCC"              => "SUC",
        "SUCC_NO"           => "S",
        "SUCC_TIME"         => "S",
        "MEDICALDAY"        => "MED",
        "DOCTOR"            => "DOCTOR",
        "PHARMACIST"        => "PHARMACIST",
        "TOTAL"             => 1.11,    //30
        "SEVICE_NO"         => "SEVICE_NO",
        "SEVICE"            => 2.22,
        "COST"              => 333,
        "NAME"              => "NAME",
        "SHARE"             => 444,
        "SUMT"              => 555,
        "FLAG01"            => "F",
        "FLAG02"            => "FL",
        "FLAG03"            => "FLAG",
        "FLAG04"            => "F4",    //40
        "FLAG05"            => "F5",
        "FLAG06"            => "6",
        "FLAG07"            => "7",
        "P01"               => 666,
        "P02"               => "P02",
        "P03"               => "P03",
        "N01"               => 777,
        "N02"               => 888,
        "N03"               => 9.99,
        "SWT"               => "S",     //50
        "T_FLAG"            => "T",
        "L_DATE"            => "L_DATE",    //52
        "L_EMP"             => "L_EMP",
        "C_DATE"            => "C_DATE",
        "C_EMP"             => "C_EMP",
        "BABYBIRTHDAY"      => "BABYBIR",
        "RED_ORG"           => "RED_ORG", //57
        'presubs'           => array($RequestDetail3)
    );
    $pkgRequest = [
        'status'        => 'REQUEST',
        'command'       => '112203014',
        'organization'  => 'HMO002',
        'transaction'   => date("Y-m-d H:i:s"),
        'data'          => array($RequestData, $RequestData1)
    ];
    CallWebService($pkgRequest);
}

function Promotion_Get(){
    // get promotion
        //讀取促銷方案
        echo("讀取促銷方案<br>");
        $RequestData = [
            "start_date"    => date("Y-m-d"),
            "end_date"      => date("Y-m-d")
        ];
        $pkgRequest = [
            'status'        => 'REQUEST',
            'command'       => '112202013',
            'organization'  => 'HMO002',
            'transaction'   => date("Y-m-d H:i:s"),
            'data'          => $RequestData
        ];
        CallWebService($pkgRequest); 
}

function ADs_Save(){
    //廣告推播存檔
    echo("廣告推播存檔<br>");
    $Picture = base64_encode(file_get_contents("test.png"));
    $RequestData = [
        "id"            => "001",
        "type"          => "1",
        "storeid"       => "001",
        "image"         => "img.png",
        "start_date"    => date("Y-m-d H:i:s"),
        "end_date"      => date("Y-m-d H:i:s"),
        "picture"       => $Picture
    ];
    $pkgRequest = [
        'status'        => 'REQUEST',
        'command'       => '112202012',
        'organization'  => 'HMO002',
        'transaction'   => date("Y-m-d H:i:s"),
        'data'          => $RequestData
    ];
    CallWebService($pkgRequest);
}

function ADs_Pull(){
    //廣告推播
    echo("廣告推播<br>");
    $RequestData = [
        "type"   => "all"
    ];
    $pkgRequest = [
        'status'        => 'REQUEST',
        'command'       => '112202009',
        'organization'  => 'HMO002',
        'transaction'   => date("Y-m-d H:i:s"),
        'data'          => $RequestData
    ];
    CallWebService($pkgRequest);
}

function ADs_Fire(){
    //廣告點擊
    echo("廣告點擊<br>");
    $RequestData = [
        "Member_ID"    => "A123456789",
        "Store_ID" => "store001",
        "Ads_ID" => "ads001",
        "Image_ID" => "img001"
    ];
    $pkgRequest = [
        'status'        => 'REQUEST',
        'command'       => '112202010',
        'organization'  => 'HMO002',
        'transaction'   => date("Y-m-d H:i:s"),
        'data'          => $RequestData
    ];
    CallWebService($pkgRequest);
}

function ADs_Receive(){
    //接收廣告
    echo("接收廣告圖<br>");
    $RequestData = [
        "ads_id"   => "20190101001",
        "image_id" => "01201901"
    ];
    $pkgRequest = [
        'status'        => 'REQUEST',
        'command'       => '112202011',
        'organization'  => 'HMO002',
        'transaction'   => date("Y-m-d H:i:s"),
        'data'          => $RequestData
    ];
    CallWebService($pkgRequest);
}

function CallWebService(array $Package){
    try {
        include('common.php');
        $url = $TMS_Transaction_URL;
        echo("web service -> " . $url . "<br>");
        $ch = curl_init();
        $data = json_encode($Package);
        echo('client->' .$data. '<br>');
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
        //echo("parameter ->" . http_build_query(array("key"=>$SQLKEY, "data"=>$data)) . "<br>");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("key"=>$SQLKEY, "data"=>$data))); 
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        switch($httpCode){
            case 200:
                echo('httpcode->'.$httpCode.'<br>');
                echo('response->'.$response.'<br>');
                    /*foreach ($response as $key => $value) {
                    if($key == "data"){
                        foreach ($value as $FV) {
                            foreach ($FV as $K1 => $V1) {
                                if ($K1=="Picture") {
                                    //$V1 = base64_encode($V1);
                                    echo("<img src='data:image/png;base64, $V1' /><br>");
                                }else{
                                    echo "{$K1} => {$V1} <br>";
                                }
                            }
                        }
                    }else{
                        echo "{$key} => {$value} <br>";
                    }
                }*/
                break;
            default:
                echo("response is {$response} <br>"); 
                echo("Return http code is {$httpCode} <br>".curl_error($ch)."<br>");
        }
        curl_close($ch);
    } catch (Exception $th) {
            echo($th."<br>");
    }
}
//-------------------------------------end----------------------------