<?php

class Task{
    private $data = [];
    private $link;

    public function __construct(){
        $this->link = 'https://' . SUBDOMAIN . '.amocrm.ru/api/v2/tasks';
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setElementId($id){
        $this->data['element_id'] = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setElementType($name){
        $this->data['element_type'] = ELEMENT_TYPES[$name];
        return $this;
    }

    /**
     * @param int $timestamp
     * @return $this
     */
    public function setCompleteTill($timestamp){
        $this->data['complete_till'] = $timestamp;
        return $this;
    }

    /**
     * @param int $typeId
     * @return $this
     */
    public function setTaskType($typeId){
        $this->data['task_type'] = $typeId;
        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text){
        $this->data['text'] = $text;
        return $this;
    }

    public function add(){
        $this->data['add'][0] =$this->data;
        return ApiRequest::request($this->link, $this->data);
    }

    public function setUpdatedAt($timestamp){
        $this->data['updated_at'] = $timestamp;
        return $this;
    }
    public function setTaskId($id){
        $this->data['id'] = $id;
        return $this;
    }

    public function update(){
        $this->data['update'][0] =$this->data;
        return  ApiRequest::request($this->link, $this->data);
    }
}
?>