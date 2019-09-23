<?php 	#mazitov979@gmail.com / 5d96f5f42a39f27b06f9e268cb83f02b18b0cb17
include ("include.php");

class  webhooksHandler{

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
        if (mysqli_num_rows($result) > 0){
            LogEdit::writeLogs("hash already exist");
            return false;
        }
        else{
            LogEdit::writeLogs("hash is unique");
            $result = mysqli_query($conn,"INSERT INTO Webhook_hashes (hash) VALUES ('$hash')");
            return true;
        }
    }
}

$webhook = new webhooksHandler;
$fieldsData = $webhook->getFieldsData();
if ($webhook->isUnique($fieldsData)){
    BusinessProcess::processLead($webhook->action, $fieldsData);
}


/*if ($webhook->isUnique($fieldsData)){
    $link = 'business_process.php';
    $ch = curl_init($link);
    $jsonData = $fieldsData;
    $jsonDataEncoded = json_encode($jsonData);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $result = curl_exec($ch);
}*/

