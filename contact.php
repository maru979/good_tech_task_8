<?php

class Contact{
    private $data = [];
    private $link;

	public function __construct(){
        $this->link = 'https://' . SUBDOMAIN . '.amocrm.ru/api/v2/contacts';
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
     * @param array $custom_fields
     * @return $this
     */
    public function setCustomFields($custom_fields){
        $this->data['custom_fields'] = $custom_fields;
        return $this;
    }

    public function add(){
        $this->data['add'][0] =$this->data;
        return ApiRequest::request($this->link, $this->data);
    }

    public function setId($id){
        $this->data['id'] = $id;
        return $this;
    }

    public function setUpdatedAt($timeStamp){
        $this->data['updated_at'] = $timeStamp;
        return $this;
    }

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