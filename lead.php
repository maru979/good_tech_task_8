<?php

class Lead{
    private $data = [];
    private $link;

    public function __construct(){
        $this->link = 'https://' . SUBDOMAIN . '.amocrm.ru/api/v2/leads';
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name){
        $this->data['name'] = $name;
        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setContactsId($id){
        $this->data['contacts_id'][0] = $id;
        return $this;
    }

    /**
     * @param array $custom_fields
     * @return $this
     */
    public function setCustomFields($custom_fields){
        $this->data['custom_fields'] = $custom_fields;
        return $this;
    }


    /**
     * @param int $budget
     * @return $this
     */
    public function setPrice($budget){
        $this->data['price'] = $budget;
        return $this;
    }

    public function setStatusId($id){
        $this->data['status_id'] = $id;
        return $this;
    }

    public function add(){
        $this->data['add'][0] =$this->data;
        return ApiRequest::request($this->link, $this->data);
    }

    /**
     * @param int $leadId
     * @return $this
     */
    public function setId($leadId){
        $this->data['id'] = $leadId;
        return $this;
    }

    /**
     * @param int $timestamp
     * @return $this
     */
    public function setUpdatedAt($timestamp){
        $this->data['updated_at'] = $timestamp;
        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setResponsibleUserId($id){
        $this->data['responsible_user_id'] = $id;
        return $this;
    }

    public function update(){
        $this->data['update'][0] =$this->data;
        return  ApiRequest::request($this->link, $this->data);
    }
}
?>