<?php
class ControllerExtensionPaymentOrangeMoney extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/payment/orange_money');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if(($this->request->server['REQUEST_METHOD'] == 'POST'))
        {
            $this->model_setting_setting->editSetting('orange_money', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/extension', 'token='.$this->session->data['token'] . '&type=payment', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_order_cancel'] = $this->language->get('entry_order_cancel');
        $data['entry_order_pending'] = $this->language->get('entry_order_pending');
        $data['entry_order_failed'] = $this->language->get('entry_order_failed');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
        $data['entry_merchant_key'] = $this->language->get('entry_merchant_key');
        $data['entry_secret_key'] = $this->language->get('entry_secret_key');
        $data['entry_client_key'] = $this->language->get('entry_client_key');
        $data['entry_header_key'] = $this->language->get('entry_header_key');
        $data['entry_currency'] = $this->language->get('entry_currency');


        $data['help_order_status'] = $this->language->get('help_order_status');
        $data['help_merchant_id'] = $this->language->get('help_merchant_id');
        $data['help_merchant_key'] = $this->language->get('help_merchant_key');
        $data['help_secret_key'] = $this->language->get('help_secret_key');
        $data['help_client_key'] = $this->language->get('help_client_key');
        $data['help_header_key'] = $this->language->get('help_header_key');
        $data['help_currency'] = $this->language->get('help_currency');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/extension', 'token='.$this->session->data['token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/orange_money', 'token='.$this->session->data['token'], true)
        );

        $data['action'] = $this->url->link('extension/payment/orange_money', 'token='.$this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token='.$this->session->data['token'] . '&type=payment', true);


         if (isset($this->request->post['orange_money_merchant_key']))
            $data['orange_money_merchant_key'] = $this->request->post['orange_money_merchant_key'];
        else
            $data['orange_money_merchant_key'] = $this->config->get('orange_money_merchant_key');
         if (isset($this->request->post['orange_money_currency']))
            $data['orange_money_currency'] = $this->request->post['orange_money_currency'];
        else
            $data['orange_money_currency'] = $this->config->get('orange_money_currency');
        if (isset($this->request->post['orange_money_merchant_id']))
            $data['orange_money_merchant_id'] = $this->request->post['orange_money_merchant_id'];
        else
            $data['orange_money_merchant_id'] = $this->config->get('orange_money_merchant_id');
        
        if (isset($this->request->post['orange_money_secret_key']))
            $data['orange_money_secret_key'] = $this->request->post['orange_money_secret_key'];
        else
            $data['orange_money_secret_key'] = $this->config->get('orange_money_secret_key');
        if (isset($this->request->post['orange_money_client_key']))
            $data['orange_money_client_key'] = $this->request->post['orange_money_client_key'];
        else
            $data['orange_money_client_key'] = $this->config->get('orange_money_client_key');
        if (isset($this->request->post['orange_money_authorization_header']))
            $data['orange_money_authorization_header'] = $this->request->post['orange_money_authorization_header'];
        else
            $data['orange_money_authorization_header'] = $this->config->get('orange_money_authorization_header');
        

        if(isset($this->request->post['orange_money_order_status_id']))
            $data['orange_money_order_status_id'] = $this->request->post['orange_money_order_status_id'];
        else
            $data['orange_money_order_status_id'] = $this->config->get('orange_money_order_status_id');


        if (isset($this->request->post['orange_money_pending_status_id'])) {

            $data['orange_money_pending_status_id'] = $this->request->post['orange_money_pending_status_id'];

        } else {

            $data['orange_money_pending_status_id'] = $this->config->get('orange_money_pending_status_id');

        }



        if (isset($this->request->post['orange_money_canceled_status_id'])) {

            $data['orange_money_canceled_status_id'] = $this->request->post['orange_money_canceled_status_id'];

        } else {

            $data['orange_money_canceled_status_id'] = $this->config->get('orange_money_canceled_status_id');

        }



        if (isset($this->request->post['orange_money_failed_status_id'])) {

            $data['orange_money_failed_status_id'] = $this->request->post['orange_money_failed_status_id'];

        } else {

            $data['orange_money_failed_status_id'] = $this->config->get('orange_money_failed_status_id');

        }



        if (isset($this->request->post['orange_money_chargeback_status_id'])) {

            $data['orange_money_chargeback_status_id'] = $this->request->post['orange_money_chargeback_status_id'];

        } else {

            $data['orange_money_chargeback_status_id'] = $this->config->get('orange_money_chargeback_status_id');

        }
        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['orange_money_sort_order'])) {
            $data['orange_money_sort_order'] = $this->request->post['orange_money_sort_order'];
        } else {
            $data['orange_money_sort_order'] = $this->config->get('orange_money_sort_order');
        }

        if (isset($this->request->post['orange_money_status'])) {
                $data['orange_money_status'] = $this->request->post['orange_money_status'];
        } else {
                $data['orange_money_status'] = $this->config->get('orange_money_status');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/orange_money.tpl', $data));
    }
}