<?php

class Contact{
    private $data = [];
    private $link;
    public $Response;

	public function __construct(){
        $this->link = 'https://' . SUBDOMAIN . '.amocrm.ru/api/v2/contacts';
	}

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name){
        $this->data['add'][0]['name'] = $name;
        return $this;
    }

    /**
     * @param array $custom_fields
     * @return $this
     */
    public function setCustomFields($custom_fields){
        $this->data['add'][0]['custom_fields'] = $custom_fields;
        return $this;
    }

    public function save(){
        $this->Response = ApiRequest::request($this->link, $this->data);
        return $this;
    }

}
?>