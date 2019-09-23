<?php

class Lead{
    private $data = [];
    private $action;
    private $link;
    public $Response;

    public function __construct($action){
        $this->action = $action;
        $this->link = 'https://' . SUBDOMAIN . '.amocrm.ru/api/v2/leads';
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name){
        $this->data[$this->action][0]['name'] = $name;
        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setResponsibleUserId($id){
        $this->data[$this->action][0]['responsible_user_id'] = $id;
        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setContactsId($id){
        $this->data[$this->action][0]['contacts_id'][0] = $id;
        return $this;
    }

    /**
     * @param array $custom_fields
     * @return $this
     */
    public function setCustomFields($custom_fields){
        $this->data[$this->action][0]['custom_fields'] = $custom_fields;
        return $this;
    }

    /**
     * @param int $leadId
     * @return $this
     */
    public function setUpdateId($leadId){
        $this->data[$this->action][0]['id'] = $leadId;
        return $this;
    }

    /**
     * @param int $timestamp
     * @return $this
     */
    public function setUpdatedAt($timestamp){
        $this->data[$this->action][0]['updated_at'] = $timestamp;
        return $this;
    }

    /**
     * @param int $budget
     * @return $this
     */
    public function setSale($budget){
        $this->data[$this->action][0]['sale'] = $budget;
        return $this;
    }

    public function save(){
        $this->Response = ApiRequest::request($this->link, $this->data);
        return $this;
    }
}
?>