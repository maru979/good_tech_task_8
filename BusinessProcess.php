<?php

class  BusinessProcess{

    public static function processLead($action, $fieldsData){
        //При создании сделки на этапе Новая заявка:
        /*
        -Заполнить поле бюджет из рассчета - (Ширина * Высота)/100 руб.
            -Если кол-во камер = 3, то полученную сумму увеличить на 50%.
            -При выборе профиля = VEKA предоставляется скидка в размере 5000 рублей.
            -Итоговой бюджет - это целое число без копеек.
        -Заполнить поле Дата заявки текущей датой в формате dd.mm.yyyy;
        -Заполнить поле Замерщик, необходимо реализовать очередь распределения заявок для всех значений поля.
         В первой созданной сделке Иванов, в следующей Петров и т. д.
         */
        if ($action == 'add' && $fieldsData['status_id'] == '29805766'){ # Добавлена сделка с первичным этапом
            LogEdit::writeLogs('Сделка добавлена');
            foreach($fieldsData['custom_fields'] as $cf) {
                if($cf['id'] == CUSTOM_F_ID['customer_width']) {
                   $width = $cf['values'][0]['value'];
                }
                if($cf['id'] == CUSTOM_F_ID['customer_height']) {
                    $height = $cf['values'][0]['value'];
                }
                if($cf['id'] == CUSTOM_F_ID['customer_cell_number']) {
                    $cell_number = $cf['values'][0]['enum'];
                }
                if($cf['id'] == CUSTOM_F_ID['customer_profile']) {
                    $profile = $cf['values'][0]['value'];
                }
            }

            if ($width && $height) {
                $budget = $width * $height / 100;
                $budget = ($cell_number == '615181') ? $budget * 1.5 : $budget;
                $budget = ($profile == 'VEKA') ? $budget - 5000 : $budget;
            }
            $string_data = file_get_contents(GAUGES_FOLDER);
            $gauges = unserialize($string_data);
            array_push($gauges, array_shift($gauges));
            file_put_contents(GAUGES_FOLDER, serialize($gauges));
            $customField = [ 'gauge' => OPTIONS['gauge'][$gauges[0]],
                'lead_date' => date('d.m.Y'),
            ];

            $upLead = (new Lead())
                ->setId($fieldsData['id'])
                ->setUpdatedAt(time())
                ->setPrice(strval(round($budget)))
                ->setCustomFields((new SetFields())->setLeadFields($customField)->data)
                ->update();
        }
        elseif ($action == 'update'){
            LogEdit::writeLogs('Сделка обновлена');
            /*
            При изменении поля Дата замера необходимо дать задачу в сделку с параметрами:
                -Тип задачи = Дата замера;
                -Текст задачи = "{date} назначан замер, необходимо выехать к клиенту.", где {date} - это значение поля Дата замера в формате dd.mm.yyyy;
                -Срок исполнения = до конца дня указанного в поле Дата замера;
                -Если поле Дата замера изменилось, но задача уже давалась ранее - необходимо изменить прошлую задачу, а не создавать новую.
            */
            foreach ($fieldsData['custom_fields'] as $cf) {
                    if ($cf['id'] == CUSTOM_F_ID['measurement_date']) {
                        $measurementDate = $cf['values'][0];
                    }
                }
            if (!empty($measurementDate)) {
                $elementId = $fieldsData['id'];
                $taskType = TASK_TYPES['Дата замера'];
                $taskText = date('d.m.Y', $measurementDate) . ' назначен замер, необходимо выехать к клиенту.';
                $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                ($conn->connect_error) ? die('Ошибка подключения: (' . $conn->connect_errno . ') ' . $conn->connect_error) : null;
                $result = mysqli_query($conn, "SELECT taskId FROM Tasks WHERE elementId = '$elementId' AND taskType =  '$taskType'") or LogEdit::writeLogs('q1: ' . mysqli_error($conn));
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_array($result);
                    $taskId = $row[0];
                    $upTask = (new Task())
                        ->setTaskId($taskId)
                        ->setUpdatedAt(time())
                        ->setText($taskText)
                        ->setCompleteTill(strtotime(date('d.m.Y 23:59:59', $measurementDate)))
                        ->update();
                } else {
                    $task = (new Task())
                        ->setElementId($elementId)
                        ->setElementType('lead')
                        ->setCompleteTill(strtotime(date('d.m.Y 23:59:59', $measurementDate)))
                        ->setTaskType($taskType)
                        ->setText($taskText)
                        ->add();
                    $taskId = $task[0]['id'];
                    $result = mysqli_query($conn, "INSERT INTO Tasks (taskId, elementId, taskType) VALUES ('$taskId', '$elementId', '$taskType')") or LogEdit::writeLogs('q2: ' . mysqli_error($conn));
                    $conn->close();
                }
            }
        }
        elseif ($action == 'status'){
            LogEdit::writeLogs('Обновлен статус сделки');
            //При переходе сделки на этап 1_БП:
            if ($fieldsData['status_id'] == '29884039'){
                //Получить ID основного контакта, привязанного к сделке.
                $link = 'https://' . SUBDOMAIN . '.amocrm.ru/api/v2/leads?id='.$fieldsData['id'].'&entity=leads';
                $leadInfo = ApiRequest::request($link, false);
                $mainContactId = $leadInfo['0']['main_contact']['id'];
                //Сменить ответственного за контакта ответственным за текущую сделку.
                $responsibleUserId = $fieldsData['responsible_user_id'];
                $upContact = (new Contact())
                    ->setId($mainContactId)
                    ->setUpdatedAt(time())
                    ->setResponsibleUserId($responsibleUserId)
                    ->update();

                /*
                -Сменить ответственного за сделку на Администратора акаунта
                -Сменить этап сделки на 2_БП;
                */
                $upLead = (new Lead())
                    ->setId($fieldsData['id'])
                    ->setUpdatedAt(time())
                    ->setResponsibleUserId(ADMIN_ID)
                    ->setStatusId('29884042')
                    ->update();

                /*Дать задачу в сделку с параметрами:
                    -Тип задачи = связаться с клиентом;
                    -Текст задачи = Назначить время замера;
                    - Срок исполнения = 30 минут
                */
                $task = (new Task())
                    ->setElementId($fieldsData['id'])
                    ->setElementType('lead')
                    ->setCompleteTill(time() + 60 * 30)
                    ->setTaskType('1')
                    ->setText('Назначить время замера.')
                    ->add();
            }
            //При переходе сделки на этап 2_БП:
            elseif ($fieldsData['status_id'] == '29884042'){
                //Если бюджет сделки >= 10000, то перевести её на этап 3_БП
                if ((int)$fieldsData['price'] >= 10000){
                    $upLead = (new Lead())
                        ->setId($fieldsData['id'])
                        ->setUpdatedAt(time())
                        ->setStatusId('29884045')
                        ->update();
                }
                //Иначе добавить обычное примечание в сделку с текстом: "Низкий бюджет сделки, предложить клиенту доп. продукты"
                else{
                    $note = (new Note())
                        ->setElementId($fieldsData['id'])
                        ->setElementType('lead')
                        ->setNoteType('Обычное примечание')
                        ->setText("Низкий бюджет сделки, предложить клиенту доп. продукты")
                        ->add();
                }
            }
        }
    }
}

