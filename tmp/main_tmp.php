<?php 	#mazitov979@gmail.com / 5d96f5f42a39f27b06f9e268cb83f02b18b0cb17
include ("include.php");

$subdomain = 'mazitov979';
$login = 'mazitov979@gmail.com';
$hash_key = '5d96f5f42a39f27b06f9e268cb83f02b18b0cb17';
AmoAuth::authorize($subdomain, $login, $hash_key);

if (isset($_POST['queryData'])){
	$json = $_POST['queryData'];
	$data = json_decode($json, true);
	LogEdit::writeLogs("got json queryData");
}
else{
	LogEdit::writeLogs("can't get json queryData");
}

$customer_name = $data["contact"]["name"];
$customer_phone = $data["contact"]["phone"];
$customer_email = $data["contact"]["email"];

$link = 'https://' . $subdomain . '.amocrm.ru/api/v2/contacts/?query='.substr($customer_phone, 2);
$link2 = 'https://' . $subdomain . '.amocrm.ru/api/v2/contacts/?query='.$customer_email;
$id_phone = '414507';
$id_email = '414509';

$ResponseForQuery = getContactsByQuery($link);

if ($ResponseForQuery) {
    $customer_id = false;
    $customer_id = findByPhone($ResponseForQuery, $customer_phone, $id_phone);
    LogEdit::writeLogs($customer_id);
    if (!$customer_id) {
        $ResponseForQuery = getContactsByQuery($link2);
        $customer_id = findByEmail($ResponseForQuery, $customer_email, $id_email);
        if (!$customer_id) {
            $customer_id = Contact::createContact($subdomain, $customer_name, $customer_phone, $customer_email, $id_phone, $id_email);
            LogEdit::writeLogs("-------------");
        }
        else {
            $customer_id = Contact::createContact($subdomain, $customer_name, $customer_phone, $customer_email, $id_phone, $id_email);
            LogEdit::writeLogs("-------------");
        }
    }
}


$options = [
    'profile' =>['REHAU' => '615169', 'VEKA' => '615171', 'KBE' => '615173', 'KRAUSS' => '615175', 'SALAMANDER' => '615177'],
    'cell_num' =>['2 камеры' => '615179', '3 камеры' => '615181'],
    'mechanism' =>['Поворотный' => '615183', 'Поворотно-откидной' => '615185', 'Раздвижной' => '615187'],
    'web_site' => ['http://localhost/good_tech_task_5/index.html' => '616065', 'Не задан' => '616067'],
];

$customer_info = array(
    'customer_name' => $data["contact"]["name"],
    'customer_phone' => $data["contact"]["phone"],
    'customer_email' => $data["contact"]["email"],
    'customer_utm_source' => $data["utm"]["utm_source"],
    'customer_utm_medium' => $data["utm"]["utm_medium"],
    'customer_utm_campaign' => $data["utm"]["utm_campaign"],
    'customer_utm_content' => $data["utm"]["utm_content"],
    'customer_utm_term' => $data["utm"]["utm_term"],
    'customer_referrer' => $data["utm"]["referrer"],
    'customer_height' => $data["fields"]["height"],
    'customer_width' => $data["fields"]["width"],
    'customer_profile' => $options['profile'][$data["fields"]["profile"]],
    'customer_cell_number' => $options['cell_num'][$data["fields"]["number"]],
    'customer_mechanism' => $options['mechanism'][$data["fields"]["mechanism"]],
    'customer_source_page' => $data["form"]["page"],
    'customer_web_site' => $options['web_site'][$data["form"]["page"]],
    'customer_id' => $customer_id,
);

#Lead::createLeadFields($subdomain);
#Lead::createLead($subdomain, $customer_info);

##############################################################################################################

function findByPhone($arr, $phone, $id_phone){
	foreach ($arr as $el) {
		foreach ($el['custom_fields'] as $cf) {
			if ($cf['id'] == $id_phone){
				foreach ($cf['values'] as $vs) {
					if( substr($vs['value'], 2) == substr($phone, 2)){
						printId($el['id'], "'Phone'");
						return $el['id'];
					}
				}
			}
		}
	}
	return false;
}

function findByEmail($arr, $email, $id_email){
	foreach ($arr as $el) {
		foreach ($el['custom_fields'] as $cf) {
			if ($cf['id'] == $id_email){
				foreach ($cf['values'] as $vs) {
					if($vs['value'] == $email){
						printId($el['id'], "'Email'");
						return $el['id'];
					}
				}
			}
		}
	}
	return false;
}

function printId($id, $field_name){
    LogEdit::writeLogs('User with same '.$field_name.' already exists and his ID: '.$id);
    LogEdit::writeLogs("-------------");

}

function getContactsByQuery($link){
    $task = 'Запрос к API';
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
	curl_setopt($curl, CURLOPT_URL, $link);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	$out = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
	$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array());
	curl_close($curl);
	$code = (int) $code;
    $logInfo = AmoAuth::getErrorByCode($code, $task);
	$Response = json_decode($out, true);
	$Response = $Response['_embedded']['items'];
	return $Response;
}
?>