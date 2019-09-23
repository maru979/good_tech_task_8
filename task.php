<?php

class Task{
    private $data = [];
    private $link;
    public $Response;

    public function __construct(){
        $this->link = 'https://' . SUBDOMAIN . '.amocrm.ru/api/v2/tasks';
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setElementId($id){
        $this->data['add'][0]['element_id'] = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setElementType($name){
        $this->data['add'][0]['element_type'] = ELEMENT_TYPES[$name];
        return $this;
    }

    /**
     * @param int $timestamp
     * @return $this
     */
    public function setCompleteTill($timestamp){
        $this->data['add'][0]['complete_till'] = $timestamp;
        return $this;
    }

    /**
     * @param int $typeId
     * @return $this
     */
    public function setTaskType($typeId){
        $this->data['add'][0]['task_type'] = $typeId;
        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text){
        $this->data['add'][0]['text'] = $text;
        return $this;
    }

    public function save(){
        $this->Response = ApiRequest::request($this->link, $this->data);
        return $this;
    }
}
?>