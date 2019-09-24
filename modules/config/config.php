<?php
const SUBDOMAIN = 'mazitov979';
const LOGIN = 'mazitov979@gmail.com';
const HASH_KEY = '5d96f5f42a39f27b06f9e268cb83f02b18b0cb17';

const ADMIN_ID = '3797932';

const DB_HOST = '127.0.0.1';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'w95862lu_db';

const ID_PHONE = '414507';
const ID_EMAIL = '414509';

const OPTIONS = [
    'profile' =>['REHAU' => '615169', 'VEKA' => '615171', 'KBE' => '615173', 'KRAUSS' => '615175', 'SALAMANDER' => '615177', '' => ''],
    'cell_number' =>['2 камеры' => '615179', '3 камеры' => '615181'],
    'mechanism' =>['Поворотный' => '615183', 'Поворотно-откидной' => '615185', 'Раздвижной' => '615187', '' => ''],
    'web_site' => ['http://localhost/good_tech_task_6/index.html' => '616065', 'Не задан' => '616067', '' => ''],
    'gauge' => ['Иванов' => '643495', 'Петров' => '643497', 'Сидоров' => '643499'],
];

const ERRORS = [
    301 => 'Moved permanently',
    400 => 'Bad request',
    401 => 'Unauthorized',
    403 => 'Forbidden',
    404 => 'Not found',
    500 => 'Internal server error',
    502 => 'Bad gateway',
    503 => 'Service unavailable',
];

const CUSTOM_F_ID = [
    'customer_phone' => '414507',
    'customer_email' => '414509',
    'customer_utm_source' => '435863',
    'customer_utm_medium' => '435865',
    'customer_utm_content' => '435867',
    'customer_utm_campaign' => '435869',
    'customer_utm_term' => '435871',
    'customer_referrer' => '435873',
    'customer_height' => '435875',
    'customer_width' => '435877',
    'customer_profile' => '435879',
    'customer_cell_number' => '435881',
    'customer_mechanism' => '435883',
    'customer_web_site' => '436571',
    'customer_source_page' => '',
    'gauge' => '455201',
    'lead_date' => '455193',
    'measurement_date' => '455197',

];

const CUSTOM_F_TYPES = [
    'text' => ['type' => "1", 'element_type' =>  "2"],
    'select' => ['type' => "4", 'element_type' =>  "2"],
    'multiselect' => ['type' => "5", 'element_type' =>  "2"],
];

const ELEMENT_TYPES = [
    'contact' => '1',
    'lead' => '2',
    'company' => '3',
    'task' => '4',
];

const NOTE_TYPES = [
    'Обычное примечание' => '4',
    'Результат по задаче' => '13',
    'Системное сообщение' => '25',
    'Входящее смс' => '102',
    'Исходящее смс' => '103'
];

const LEAD_STATUS =[
    '1_БП' => '29884039',
    '2_БП' => '29884042',
    '3_БП' => '29884045',
];

const TASK_TYPES =[
  'Дата замера' => '1644877',
];

const FORM_FOCUS = [
    'calc_1' => 'Двухстворчатое окно',
    'calc_2' => 'Трехстворчатое окно',
    'calc_3' => 'Балконный блок',
];

const LEAD_NOTE = [
    'customer_name' => 'ФИО:',
    'customer_phone' => 'тел.:',
    'customer_email' => 'Email:',
    'customer_profile' => 'Профиль:',
    'customer_width' => 'Ширина, мм:',
    'customer_height' => 'Высота, мм',
    'customer_mechanism' => 'Механизм:',
    'customer_cell_number' => 'Кол-во камер:',
    'customer_utm_source' => 'utm_source:',
    'customer_utm_medium' => 'utm_medium:',
    'customer_utm_content' => 'utm_content:',
    'customer_utm_campaign' => 'utm_campaign:',
    'customer_utm_term' => 'utm_term:',
    'customer_referrer' => 'referrer:',
];


$GLOBALS['gauges'] = ['Иванов', 'Петров', 'Сидоров'];

const GAUGES_FOLDER = "modules\log\gauges.txt";