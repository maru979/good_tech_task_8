<?php
include ("include.php");

AmoAuth::authorize(SUBDOMAIN, LOGIN, HASH_KEY);
$data = getJSONData();

$customer_name = $data["contact"]["name"];
$customer_phone = $data["contact"]["phone"];
$customer_email = $data["contact"]["email"];
$phoneQuery = 'https://' . SUBDOMAIN . '.amocrm.ru/api/v2/contacts/?query='.substr($customer_phone, 2);
$emailQuery = 'https://' . SUBDOMAIN . '.amocrm.ru/api/v2/contacts/?query='.$customer_email;

$contact_custom_fields = array_filter([
    'customer_phone' => $data["contact"]["phone"],
    'customer_email' => $data["contact"]["email"],
]);
#Создаем контакт, при его отсуствии в базе и получаем его id.
$ResponseForPhoneQuery = ApiRequest::request($phoneQuery, false);
if ($ResponseForPhoneQuery) {
    $contact_id = findByPhone($ResponseForPhoneQuery, $customer_phone);
}
if (is_null($contact_id)) {
    $ResponseForEmailQuery = ApiRequest::request($emailQuery, false);
    $contact_id = findByEmail($ResponseForEmailQuery, $customer_email);
    if (is_null($contact_id)) {
        $contact = (new Contact())
            ->setName($customer_name)
            ->setCustomFields((new SetFields())->setContactFields($contact_custom_fields)->data)
            ->add();
        $contact_id = $contact[0]['id'];
        LogEdit::writeLogs('ID созданного контакта: '.$contact_id);
    }
}

/*(new CreateFields())
    ->createLeadTextField("адрес")
    ->createLeadSelectField('кол-во', ['1','2'])
    ->saveCreated();*/

$lead_custom_fields = array_filter([
    'customer_utm_source' => $data["utm"]["utm_source"],
    'customer_utm_medium' => $data["utm"]["utm_medium"],
    'customer_utm_campaign' => $data["utm"]["utm_campaign"],
    'customer_utm_content' => $data["utm"]["utm_content"],
    'customer_utm_term' => $data["utm"]["utm_term"],
    'customer_referrer' => $data["utm"]["referrer"],
    'customer_height' => $data["fields"]["height"],
    'customer_width' => $data["fields"]["width"],
    'customer_profile' => OPTIONS['profile'][$data["fields"]["profile"]],
    'customer_cell_number' => OPTIONS['cell_number'][$data["fields"]["number"]],
    'customer_mechanism' => OPTIONS['mechanism'][$data["fields"]["mechanism"]],
    'customer_web_site' => OPTIONS['web_site'][$data["form"]["page"]],
]);
#Создаем сделку и получаем её id.
$lead = (new Lead())
    ->setName('Заявка с сайта '.$data["form"]["page"].' от '.date('Y-m-d'))
    ->setResponsibleUserId('3799126')
    ->setContactsId($contact_id)
    ->setCustomFields((new SetFields())->setLeadFields($lead_custom_fields)->data)
    ->add();
$lead_id = $lead[0]['id'];
LogEdit::writeLogs('ID созданной сделки: '.$lead_id);

//Создаём задачу для сделки.
$task = (new Task())
    ->setElementId($lead_id)
    ->setElementType('lead')
    ->setCompleteTill(time() + 60 * 15)
    ->setTaskType('1644175')
    ->setText('Поступила заявка с сайта '.$data["form"]["page"].' 1) Необходимо связаться с клиентом. 2) Затем перевести сделку на следующий этап.')
    ->add();

LogEdit::writeLogs($data['form']['id']);
//Добавляем примечание в сделку.
$note = (new Note())
    ->setElementId($lead_id)
    ->setElementType('lead')
    ->setNoteType('Обычное примечание');
$note->setText($note->noteBuilder($data))
    ->add();

LogEdit::writeLogs("-------------");
##############################################################################################################

function getJSONData(){
    if (isset($_POST['queryData'])){
        $json = $_POST['queryData'];
        $data = json_decode($json, true);
        LogEdit::writeLogs("got json queryData");
        return $data;
    }
    else{
        LogEdit::writeLogs("can't get json queryData");
        return false;
    }
}

function findByPhone($arr, $phone){
    foreach ($arr as $el) {
        foreach ($el['custom_fields'] as $cf) {
            if ($cf['id'] == ID_PHONE){
                foreach ($cf['values'] as $vs) {
                    if( substr($vs['value'], 2) == substr($phone, 2)){
                        printId($el['id'], "'Phone'");
                        return $el['id'];
                    }
                }
            }
        }
    }
    return null;
}

function findByEmail($arr, $email){
    foreach ($arr as $el) {
        foreach ($el['custom_fields'] as $cf) {
            if ($cf['id'] == ID_EMAIL){
                foreach ($cf['values'] as $vs) {
                    if($vs['value'] == $email){
                        printId($el['id'], "'Email'");
                        return $el['id'];
                    }
                }
            }
        }
    }
    return null;
}

function printId($id, $field_name){
    LogEdit::writeLogs('User with same '.$field_name.' already exists and his ID: '.$id);
}