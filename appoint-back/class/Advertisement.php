<?php
class Advertisement{
    public function setAdvertisement($data){
        //廣告推播存檔
        $RequestData = [
            "name"          => $data['Name'],
            "type"          => $data['Type'],
            "storeid"       => $data['StoreID'],
            "image"         => $data['Image'],
            "start_date"    => $data['Start_Date'],
            "end_date"      => $data['End_Date'],
            "promotionid"   => $data['PromotionID'],
            "create_date"   => date("Y-m-d H:i:s"),
            "pid"           => $data['PID'],
            "picture"       => $data['Picture']
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

    private function CallWebService(array $Package){
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("key"=>$SQLKEY, "data"=>$data))); 
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        switch($httpCode){
            case 200:
                echo('httpcode->'.$httpCode.'<br>');
                echo('response->'.$response.'<br>');
                break;
            default:
                echo("Return http code is {$httpCode} \n".curl_error($ch).'<br>'); 
                echo("response is {$response} '<br>'"); 
        }
        curl_close($ch);
    }
    
}
?>