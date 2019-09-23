<?php 	#mazitov979@gmail.com / 5d96f5f42a39f27b06f9e268cb83f02b18b0cb17

class  BusinessProcess{

    public static function processLead($action, $fieldsData){
        if ($action == 'add' && $fieldsData['pipeline_id'] == '2043433'){ # Добавлена сделка с первичным этапом
            $budget = $fieldsData['custom_fields'][1]['values'][0]['value'] *
                $fieldsData['custom_fields'][0]['values'][0]['value'] /
                100;
            $budget = ($fieldsData['custom_fields'][3]['values'][0]['enum'] == '615181') ? $budget * 1.5 : $budget;
            $budget = ($fieldsData['custom_fields'][2]['values'][0]['value'] == 'VEKA') ? $budget - 5000 : $budget;
            $string_data = file_get_contents(GAUGES_FOLDER);
            $gauges = unserialize($string_data);
            array_push($gauges, array_shift($gauges));
            file_put_contents(GAUGES_FOLDER, serialize($gauges));
            $customField = [ 'gauge' => OPTIONS['gauge'][$gauges[0]],
                'lead_date' => date('d.m.Y'),
            ];

            $upLead = (new Lead('update'))
                ->setUpdateId($fieldsData['id'])
                ->setUpdatedAt(time())
                ->setSale(strval(round($budget)))
                ->setCustomFields((new SetFields())->setLeadFields($customField)->data)
                ->save();
        }
    }

/*    function getFieldsData(){
        if (isset($_POST)){
            $fieldsData = $_POST;
            LogEdit::writeLogs("got unique webhook");
            var_dump($fieldsData);
            return $fieldsData;
        }
        else{
            LogEdit::writeLogs("can't get unique webhook");
            return false;
        }
    }*/

}

/*LogEdit::writeLogs("unique webhook");
$bp= new BusinessProcess();
$fieldsData = $bp->getFieldsData();*/

