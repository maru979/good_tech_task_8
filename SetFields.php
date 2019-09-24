<?php

class SetFields{
    public $data = [];

    /**
     * @param int $id
     * @param string $value
     * @return $this
     */
    public function setText($id, $value){
        $this->data['id'] = $id;
        $this->data['values'][0]['value'] = $value;
        return $this;
    }

    /**
     * @param int $id
     * @param int $optionId
     * @return $this
     */
    public function setSelect($id, $optionId){
        $this->data['id'] = $id;
        $this->data['values'][0]['value'] = $optionId;
        return $this;
    }

    /**
     * @param int $id
     * @param array $options
     * @return $this
     */
    public function setMultiSelect($id, $options){
        $this->data['id'] = $id;
        $this->data['values'] = $options;
        return $this;
    }

    /**
     * @param array $contact_custom_fields
     * @param string $enum
     * @return $this
     */
    public function setContactFields($contact_custom_fields, $enum = 'WORK'){
        $j = 0;
        foreach ($contact_custom_fields as $key => $value) {
            $this->data[$j]['id'] = CUSTOM_F_ID[$key];
            $this->data[$j]['values'][0]['value'] = $value;
            $this->data[$j]['values'][0]['enum'] = $enum;
            $j += 1;
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
        $this->data['id'] = $id;
        $this->data['values'][0]['value'] = $value;
        $this->data['values'][0]['enum'] = $enum;
        return $this;
    }

    /**
     * @param int $value
     * @param string $enum
     * @return $this
     */
    public function setContactPhone($value, $enum = 'WORK')
    {
        $this->data['id'] = CUSTOM_F_ID['customer_phone'];
        $this->data['values'][0]['value'] = $value;
        $this->data['values'][0]['enum'] = $enum;
        return $this;
    }

    /**
     * @param string $value
     * @param string $enum
     * @return $this
     */
    public function setContactEmail($value, $enum = 'WORK')
    {
        $this->data['id'] = CUSTOM_F_ID['customer_email'];
        $this->data['values'][0]['value'] = $value;
        $this->data['values'][0]['enum'] = $enum;
        return $this;
    }

    /**
     * @param array $lead_custom_fields
     * @return $this
     */
    public function setLeadFields($lead_custom_fields){
        foreach ($lead_custom_fields as $key => $value) {
            $j = 0;
            $this->data[$j]['id'] = CUSTOM_F_ID[$key];
            $this->data[$j]['values'][0]['value'] = $value;
            $j += 1;
        }
        return $this;
    }
}
?>