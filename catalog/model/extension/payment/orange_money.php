<?php
class ModelExtensionPaymentOrangeMoney extends Model
{
    public function getMethod($address, $total)
    {
        $this->load->language('extension/payment/orange_money');

        $method_data = array();
        $status = true;
        
        if($status)
        {
            $method_data = array(
                'code'       => 'orange_money',
                'title'      => $this->language->get('text_title'),
                'terms'      => '',
                'sort_order' => $this->config->get('orange_money_sort_order')
            );
        }

        return $method_data;
    }
}