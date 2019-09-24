<?php
include ("include.php");

class  WebhooksHandler{

    public $entity;
    public $action;

    function getFieldsData(){
        if (isset($_POST)){
            $fieldsData = $_POST;
            LogEdit::writeLogs("got webhook");
            $this->entity = array_keys($fieldsData)[0];
            $this->action = array_keys($fieldsData[$this->entity])[0];
            var_dump('hook: '.$this->entity.' '.$this->action);
            $fieldsData = $fieldsData[$this->entity][$this->action][0];
            var_dump($fieldsData);
            return $fieldsData;
        }
        else{
            LogEdit::writeLogs("can't get webhook");
            return false;
        }
    }

    function isUnique($data){
        $hash = md5(serialize($data));
        $conn = new mysqli(DB_HOST,DB_USER,DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die('Ошибка подключения: ('. $conn->connect_errno .') '. $conn->connect_error);
        }

        $result = mysqli_query($conn,"SELECT hash FROM Webhook_hashes WHERE hash = '$hash'");
        if (mysqli_num_rows($result) > 0){ #хэш уже существует
            $conn->close();
            return false;
        }
        else{ #уникальный хэш
            $result = mysqli_query($conn,"INSERT INTO Webhook_hashes (hash) VALUES ('$hash')");
            $conn->close();
            return true;
        }
    }
}

AmoAuth::authorize(SUBDOMAIN, LOGIN, HASH_KEY);
$webhook = new webhooksHandler;
$fieldsData = $webhook->getFieldsData();
if ($webhook->isUnique($fieldsData)){
    LogEdit::writeLogs("hash is unique");
    BusinessProcess::processLead($webhook->action, $fieldsData);
    LogEdit::writeLogs('------------------------');
}
else{
    LogEdit::writeLogs("hash already exist");
    LogEdit::writeLogs('------------------------');
}

