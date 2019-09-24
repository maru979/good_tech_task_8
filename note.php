<?php

class Note{
    private $data = [];
    private $link;

    public function __construct(){
        $this->link = 'https://' . SUBDOMAIN . '.amocrm.ru/api/v2/notes';
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
     * @param string $type
     * @return $this
     */
    public function setNoteType($type){
        $this->data['note_type'] = NOTE_TYPES[$type];
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

    public function update(){
        $this->data['update'][0] =$this->data;
        return  ApiRequest::request($this->link, $this->data);
    }

    public function noteBuilder($data){
        $text = LEAD_NOTE['customer_name'].' '.$data["contact"]["name"].'<br>'.
            LEAD_NOTE['customer_phone'].' '.$data["contact"]["phone"].'<br>'.
            LEAD_NOTE['customer_email'].' '.$data["contact"]["email"].'<br>'.
            'Форма захвата: '.FORM_FOCUS[$data['form']['id']].'<br>---------<br>'.
            LEAD_NOTE['customer_profile'].' '.$data["fields"]["profile"].'<br>'.
            LEAD_NOTE['customer_width'].' '.$data["fields"]["width"].'<br>'.
            LEAD_NOTE['customer_height'].' '.$data["fields"]["height"].'<br>'.
            LEAD_NOTE['customer_mechanism'].' '.$data["fields"]["mechanism"].'<br>'.
            LEAD_NOTE['customer_cell_number'].' '.$data["fields"]["number"].'<br>---------<br>'.
            LEAD_NOTE['customer_utm_source'].' '.$data["utm"]["utm_source"].'<br>'.
            LEAD_NOTE['customer_utm_medium'].' '.$data["utm"]["utm_medium"].'<br>'.
            LEAD_NOTE['customer_utm_content'].' '.$data["utm"]["utm_content"].'<br>'.
            LEAD_NOTE['customer_utm_campaign'].' '.$data["utm"]["utm_campaign"].'<br>'.
            LEAD_NOTE['customer_utm_term'].' '.$data["utm"]["utm_term"].'<br>'.
            LEAD_NOTE['customer_referrer'].' '.$data["utm"]["referrer"].'<br>';
        return $text;
    }
}
?>