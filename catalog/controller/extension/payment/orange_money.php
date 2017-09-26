<?php
class ControllerExtensionPaymentOrangeMoney extends Controller
{


    public function index()
    {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['continue'] = $this->url->link('checkout/success');
        
        # Prepare the data to send to orange_money
        $data['orange_money_url'] = "https://api.orange.com/orange-money-webpay/dev/v1/webpayment";
        $data['orange_money_order_id'] = $this->session->data['order_id'];
        $data['orange_money_detail'] = "Payment_for_order_".$this->session->data['order_id'];
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $data['orange_money_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
          $data['orange_money_merchant_key'] = $this->config->get('orange_money_merchant_key');
         $data['orange_money_currency'] = $this->config->get('orange_money_currency');
          $data['orange_money_authorization_header']=$this->config->get('orange_money_authorization_header');
            $this->session->data['orange_header']=$this->config->get('orange_money_authorization_header');
        //$this->session->data['order_id']=$this->session->data['order_id']."".rand() ;
        $data['orange_money_order_id'] = $this->session->data['order_id'];
        $data['orange_money_amount'] = str_replace('$', '', $data['orange_money_amount']);
        $data['orange_money_amount'] = str_replace(',', '', $data['orange_money_amount']);
        $this->session->data['orange_money_amount'] =$data['orange_money_amount'];
        $data['orange_money_hash'] = md5($this->config->get('orange_money_secret_key').$data['orange_money_detail'].$data['orange_money_amount'].$this->session->data['order_id']);
        $data["notif_url"]= $this->url->link('extension/payment/orange_money/notif', '', true);
		$data['return_url'] = $this->url->link('extension/payment/orange_money/return_1', '', true);
		$data['cancel_url'] = $this->url->link('checkout/checkout', '', true); 
        if ($this->customer->isLogged()) {
            $name = $this->customer->getFirstName().' '.$this->customer->getLastName();
            $email = $this->customer->getEmail();
            $phone = $this->customer->getTelephone();
        } elseif (isset($this->session->data['guest'])) {
            $name = $this->session->data['guest']['firstname'] . $this->session->data['guest']['lastname'];
            $email = $this->session->data['guest']['email'];
            $phone = $this->session->data['guest']['telephone'];
        } else {
            $name = '';
            $email = '';
            $phone = '';
        }

        $data['orange_money_customer_name'] = $name;
        $data['orange_money_email'] = $email;
        $data['orange_money_phone'] = $phone;

        return $this->load->view('extension/payment/orange_money.tpl', $data);
    }

    public function getToken($param=null){


        $header=$_POST['header'];
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.orange.com/oauth/v2/token",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_SSL_VERIFYPEER=>false,
          CURLOPT_POSTFIELDS => "grant_type=client_credentials",
          CURLOPT_HTTPHEADER => array(
            "authorization: ".$this->session->data['orange_header'],
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded",
            "postman-token: 5bc40dc3-55a4-c7fd-f2b8-bd6362b7bc6a"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else if($param==null) {
          echo $response;
        }else if($param!=null){
            return json_decode($response);
        }
    }


    public function return_1(){
            $token=$this->getToken('tok');
        $status=$this->statut($token->access_token);
        
        $this->load->language('extension/payment/orange_money');
        $this->document->setTitle($this->language->get('text_payment_title'));
       
        // $data['column_left'] = $this->load->controller('common/column_left');
        // $data['column_right'] = $this->load->controller('common/column_right');
        // $data['content_top'] = $this->load->controller('common/content_top');
        // $data['content_bottom'] = $this->load->controller('common/content_bottom');
        // $data['footer'] = $this->load->controller('common/footer');
        // $data['header'] = $this->load->controller('common/header');
        $this->notif($status->status,$status->order_id);
      
    }
   public  function statut($token) {
        // $data['orange_money_order_id'] = $this->session->data['order_id'];
        // $data['orange_money_amount'] =  $this->session->data['orange_money_amount'];
        // $data['orange_money_pay_token'] = $this->session->data['pay_token'];  
        // $data['orange_header'] = $this->session->data['orange_header'];       
        // $data['column_left'] = $this->load->controller('common/column_left');
        // $data['column_right'] = $this->load->controller('common/column_right');
        // $data['content_top'] = $this->load->controller('common/content_top');
        // $data['content_bottom'] = $this->load->controller('common/content_bottom');
        // $data['footer'] = $this->load->controller('common/footer');
        // $data['header'] = $this->load->controller('common/header');
        // $data['success_link']=  $this->url->link('checkout/success', '', true);
        // $data['checkout_link'] = $this->url->link('checkout/checkout', '', true);
        // $data['status']=$this->session->data['status'];
        // $this->response->setOutput($this->load->view('extension/payment/orange_money_notif.tpl', $data));
        $post = array( 
            "order_id" =>$this->session->data['order_id'] , 
            "amount" => $this->session->data['orange_money_amount'], 
            "pay_token" => $this->session->data['pay_token']
        );
       $postText = json_encode($post);
      // echo $token;
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.orange.com/orange-money-webpay/cm/v1/transactionstatus",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $postText,
          CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "authorization: Bearer ".$token,
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 5bc40dc3-55a4-c7fd-f2b8-bd6362b7bc6a"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          return json_decode($response);
        }   
    }

public function notif($status,$order_id) {


		$this->load->model('checkout/order');
		$order_id=$this->session->data['order_id'];
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$this->load->language('extension/payment/cheque');
		$this->load->model('checkout/order');
        

		if ($order_info) {
			$this->model_checkout_order->addOrderHistory($order_id, 'INITIATED');
         
			if ($status) {
				switch($status) {
					// case 'INITIATED':
					// 	$this->model_checkout_order->addOrderHistory($order_id,$this->config->get('orange_money_order_status_id'), '', true);
					// 	break;
					// case 'PENDING':
					// 	$this->model_checkout_order->addOrderHistory($order_id,$this->config->get('orange_money_order_status_id'), '', true);
					// 	break;
					// case 'EXPIRED':
					// 	$this->model_checkout_order->addOrderHistory($order_id,$this->config->get('orange_money_order_status_id'), '', true);
					// 	break;
					case 'SUCCESS':
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('orange_money_order_status_id'), 'Payment was made using orange Money.  transaction id is '.$order_id, true);

						 if($this->cart->hasProducts()){
                                $this->cart->clear();
						 }
                        
                         $this->response->redirect($this->url->link('checkout/success', '', true));
						break;
					case 'FAILED':
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('orange_money_failed_status_id'), 'Payment was Failed using orange Money.  transaction id is '.$order_id, false);
						  $this->cancel();

						break;
				}
			} else {
				$this->log->write('md5sig returned (' + $md5sig + ') does not match generated (' + $md5hash + '). Verify Manually. Current order state: ' . $this->config->get('config_order_status_id'));
			}
		}
	}

     public function save(){
      $this->session->data['pay_token'] =$_POST['pay_token'];
     }
    public function cancel(){ 
    	        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
      
            $data['checkout_link'] = $this->url->link('checkout/checkout', '', true);
            $this->response->setOutput($this->load->view('extension/payment/orange_money_failed.tpl', $data));
    }

}