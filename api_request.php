<?php

class ApiRequest{

    public static function request($apiLink, $data){
        var_dump($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
        curl_setopt($curl, CURLOPT_URL, $apiLink);
        if ($data){
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        }
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
        curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $out = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE); 
        curl_close($curl);
        $code = (int) $code;
        $logInfo=AmoAuth::getErrorByCode($code, substr_replace($apiLink, '', 0, 29));
        LogEdit::writeLogs($logInfo);
        $Response = json_decode($out, true);
        $Response = $Response['_embedded']['items'];
        return $Response;
    }
}