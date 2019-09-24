<?php

class CreateFields{

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
        $this->data = ['name' => $name,
            'type' => '1',
            'element_type' =>  '2',
            'origin' => uniqid(),
            'is_editable' => '1',
        ];
        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     * @return $this
     */
    public function createLeadSelectField($name, $options){
        $this->data = ['name' => $name,
            'type' => '4',
            'element_type' =>  '2',
            'origin' => uniqid(),
            'is_editable' => '1',
        ];
        $j = 0;
        foreach ($options as $option){
            $this->data['enums'][$j] = $option;
            $j += 1;
        }
        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     * @return $this
     */
    public function createLeadMultiSelectField($name, $options){
        $this->data = ['name' => $name,
            'type' => '5',
            'element_type' =>  '2',
            'origin' => uniqid(),
            'is_editable' => '1',
        ];
        $j = 0;
        foreach ($options as $option){
            $this->data['enums'][$j] = $option;
            $j += 1;
        }
        return $this;
    }

    public function saveCreated(){
        $this->data['add'][0] =$this->data;
        return ApiRequest::request($this->link, $this->data);
    }
}
?>