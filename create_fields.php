<?php

class CreateFields{
    private $i = 0;
    private $j = 0;
    private $link;
    private $data = [];
    public $Response;

    public function __construct(){
        $this->link = 'https://' . SUBDOMAIN . '.amocrm.ru/api/v2/fields';
    }

    /**
     * @param string $name
     * @return $this
     */
    public function createLeadTextField($name){
        $this->data['add'][$this->i] = ['name' => $name,
            'type' => '1',
            'element_type' =>  '2',
            'origin' => uniqid(),
            'is_editable' => '1',
        ];
        $this->i += 1;
        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     * @return $this
     */
    public function createLeadSelectField($name, $options){
        $this->data['add'][$this->i] = ['name' => $name,
            'type' => '4',
            'element_type' =>  '2',
            'origin' => uniqid(),
            'is_editable' => '1',
        ];
        $this->j = 0;
        foreach ($options as $option){
            $this->data['add'][$this->i]['enums'][$this->j] = $option;
            $this->j += 1;
        }
        $this->i += 1;
        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     * @return $this
     */
    public function createLeadMultiSelectField($name, $options){
        $this->data['add'][$this->i] = ['name' => $name,
            'type' => '5',
            'element_type' =>  '2',
            'origin' => uniqid(),
            'is_editable' => '1',
        ];
        $this->j = 0;
        foreach ($options as $option){
            $this->data['add'][$this->i]['enums'][$this->j] = $option;
            $this->j += 1;
        }
        $this->i += 1;
        return $this;
    }

    public function saveCreated(){
        $this->Response = ApiRequest::request($this->link, $this->data);
        return $this;
    }
}
?>