<?php

class SetFields{
    private $i = 0;
    public $data = [];

    /**
     * @param array $contact_custom_fields
     * @param string $enum
     * @return $this
     */
    public function setContactFields($contact_custom_fields, $enum = 'WORK'){
        foreach ($contact_custom_fields as $key => $value) {
            $this->data[$this->i]['id'] = CUSTOM_F_ID[$key];
            $this->data[$this->i]['values'][0]['value'] = $value;
            $this->data[$this->i]['values'][0]['enum'] = $enum;
            $this->i += 1;
        }
        return $this;
    }

    /**
     * @param int $id
     * @param string $value
     * @param string $enum
     * @return $this
     */
    public function setContactField($id, $value, $enum = 'WORK'){
        $this->data[$this->i]['id'] = $id;
        $this->data[$this->i]['values'][0]['value'] = $value;
        $this->data[$this->i]['values'][0]['enum'] = $enum;
        $this->i += 1;
        return $this;
    }

    /**
     * @param int $value
     * @param string $enum
     * @return $this
     */
    public function setContactPhone($value, $enum = 'WORK')
    {
        $this->data[$this->i]['id'] = CUSTOM_F_ID['customer_phone'];
        $this->data[$this->i]['values'][0]['value'] = $value;
        $this->data[$this->i]['values'][0]['enum'] = $enum;
        $this->i += 1;
        return $this;
    }

    /**
     * @param string $value
     * @param string $enum
     * @return $this
     */
    public function setContactEmail($value, $enum = 'WORK')
    {
        $this->data[$this->i]['id'] = CUSTOM_F_ID['customer_email'];
        $this->data[$this->i]['values'][0]['value'] = $value;
        $this->data[$this->i]['values'][0]['enum'] = $enum;
        $this->i += 1;
        return $this;
    }

    /**
     * @param array $lead_custom_fields
     * @return $this
     */
    public function setLeadFields($lead_custom_fields){
        foreach ($lead_custom_fields as $key => $value) {
            $this->data[$this->i]['id'] = CUSTOM_F_ID[$key];
            $this->data[$this->i]['values'][0]['value'] = $value;
            $this->i += 1;
        }
        return $this;
    }

    /**
     * @param int $id
     * @param int $valueId
     * @return $this
     */
    public function setLeadField($id, $valueId){
        $this->data[$this->i]['id'] = $id;
        $this->data[$this->i]['values'][0]['value'] = $valueId;
        $this->i += 1;
        return $this;
    }
}
?>