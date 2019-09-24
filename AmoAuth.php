<?php

class AmoAuth{

    public static function authorize($subdomain, $login, $hash){
        $task = 'Авторизация';
        $user = ['USER_LOGIN' => $login, 'USER_HASH' => $hash];
        $link = 'https://' . $subdomain . '.amocrm.ru/private/api/auth.php?type=json';
        return ApiRequest::request($link, $user);
    }

    public static function getErrorByCode($code, $task){
        if ($code != 200 && $code != 204) {
            if(ERRORS[$code]){
                $logInfo = date('Y-m-d H:i:s').' '.$task.' Error '.$code.': '.ERRORS[$code];
            }
            else{
                $logInfo = date('Y-m-d H:i:s').' '.$task.' Error '.$code.': Unknown error.';
            }
        }
        else {
            $logInfo = 'Successful: '.$task;
        }
        return $logInfo;
    }
}