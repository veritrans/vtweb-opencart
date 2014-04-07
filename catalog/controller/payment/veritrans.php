<?php

require_once(DIR_SYSTEM . 'library/veritrans/veritrans.php');
require_once(DIR_SYSTEM . 'library/veritrans/veritrans_notification.php');

class ControllerPaymentVeritrans extends Controller {
  
  private $order_id = "";

	public function index() {

		$this->data['errors'] = array();
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/veritrans.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/veritrans.tpl';
		} else {
			$this->template = 'default/template/payment/veritrans.tpl';
		}

		$this->render();
	}

	public function process_order() {
		
		$this->load->model('payment/veritrans');
		$this->load->model('checkout/order');
    $this->load->model('total/shipping');
		$this->language->load('payment/veritrans');
		
		$this->data['errors'] = array();
		
		$products = $this->cart->getProducts();
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
    $this->order_id = $order_info['order_id'];
		$this->data['merchant'] = $this->config->get('veritrans_merchant');
		$this->data['trans_id'] = $this->session->data['order_id'];
		$this->data['hash'] = $this->config->get('veritrans_hash');
		$this->data['amount'] = $order_info['total'];
		$this->data['bill_name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$this->data['bill_addr_1'] = $order_info['payment_address_1'];
		$this->data['bill_addr_2'] = $order_info['payment_address_2'];
		$this->data['bill_city'] = $order_info['payment_city'];
		$this->data['bill_state'] = $order_info['payment_zone'];
		$this->data['bill_post_code'] = $order_info['payment_postcode'];
		$this->data['bill_country'] = $order_info['payment_country'];
		$this->data['bill_tel'] = $order_info['telephone'];
		$this->data['bill_email'] = $order_info['email'];
		
		$veritrans = new Veritrans;
		
		if ($this->cart->hasShipping()) {
			$veritrans->required_shipping_address = '1';
			$veritrans->billing_different_with_shipping = '1';
			$veritrans->shipping_first_name =$order_info['shipping_firstname'];
			$veritrans->shipping_last_name = $order_info['shipping_lastname'];
			$this->data['ship_addr_1'] = $order_info['shipping_address_1'];
			$this->data['ship_addr_2'] = $order_info['shipping_address_2'];
			$this->data['ship_city'] = $order_info['shipping_city'];
			$this->data['ship_state'] = $order_info['shipping_zone'];
			$this->data['ship_post_code'] = $order_info['shipping_postcode'];
			$this->data['ship_country'] = $order_info['shipping_country'];
		} else {
			$veritrans->required_shipping_address = '0';
			$veritrans->billing_different_with_shipping = '1';
			$veritrans->shipping_first_name =$order_info['payment_firstname'];
			$veritrans->shipping_last_name = $order_info['payment_lastname'];
			$this->data['ship_addr_1'] = $order_info['payment_address_1'];
			$this->data['ship_addr_2'] = $order_info['payment_address_2'];
			$this->data['ship_city'] = $order_info['payment_city'];
			$this->data['ship_state'] = $order_info['payment_zone'];
			$this->data['ship_post_code'] = $order_info['payment_postcode'];
			$this->data['ship_country'] = $order_info['payment_country'];
		}

		$veritrans->order_id = $this->session->data['order_id'];
		$veritrans->session_id = $this->session->data['order_id'];

		$veritrans->first_name = $order_info['payment_firstname'];
		$veritrans->last_name = $order_info['payment_lastname'];
		$veritrans->address1 = $this->data['bill_addr_1'];
		$veritrans->address2 = $this->data['bill_addr_2'];
		$veritrans->city = $this->data['bill_city'];
		$veritrans->country_code = $order_info['payment_iso_code_3'];
		$veritrans->postal_code = $this->data['ship_post_code'];
		$veritrans->phone = $order_info['telephone'];
		$veritrans->email = $this->data['bill_email'];

		$veritrans->shipping_address1 = $this->data['ship_addr_1'];
		$veritrans->shipping_address2 = $this->data['ship_addr_2'];
		$veritrans->shipping_city = $this->data['ship_city'];
		$veritrans->shipping_country_code = $order_info['payment_iso_code_3'];
		$veritrans->shipping_postal_code = $this->data['ship_post_code'];
		$veritrans->shipping_phone = $order_info['telephone'];

		if ($veritrans->shipping_phone==null){
			$veritrans->shipping_phone="02111111111";
		}
		
		$veritrans->gross_amount = $this->data['amount'];
		// The gross amount MUST NOT be formatted.
		
		$commodities = array();
    $taxes = array();
		$index = 1;
		$productprice = 0;

		foreach ($products as $product){
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$product['price'] = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
			}
			$commodity_item = array("item_id" => $product['product_id'],
				"price" => $product['price'],
				"quantity" => $product['quantity'],
				"item_name1" => (substr($product['name'],0,17)). "...",
				"item_name2" => (substr($product['name'],0,17)). "...");
						
			array_push($commodities, $commodity_item);
			$productprice += $product['price'] * $product['quantity'];
		}

    if($this->cart->hasShipping())
    {
      $shipping_info = $this->session->data['shipping_method'];
      if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
        $shipping_info['cost'] = $this->tax->calculate($shipping_info['cost'], $shipping_info['tax_class_id'], $this->config->get('config_tax'));
      }      
      array_push($commodities, array(
        'item_id' => 'SHIPPING',
        'price' => $shipping_info['cost'],
        'quantity' => 1,
        "item_name1" => (substr($shipping_info['title'], 0, 17)) . "...",
        "item_name2" => (substr($shipping_info['title'], 0, 17)) . "..."
        ));
      $productprice += $shipping_info['cost'];
    }

		// convert all item prices to IDR
		if ($this->config->get('config_currency') != 'IDR')
		{
			if ($this->currency->has('IDR'))
			{
				foreach ($commodities as &$commodity) {
					$commodity['price'] = intval($this->currency->convert($commodity['price'], $this->config->get('config_currency'), 'IDR'));
				}
				$veritrans->gross_amount = intval($this->currency->convert($veritrans->gross_amount, $this->config->get('config_currency'), 'IDR'));
			} elseif ($this->config->get('veritrans_currency_conversion') > 0)
			{
				foreach ($commodities as &$commodity) {
					$commodity['price'] = intval($commodity['price'] * $this->config->get('veritrans_currency_conversion'));
				}
				$veritrans->gross_amount = intval($veritrans->gross_amount * $this->config->get('veritrans_currency_conversion'));
			} else
			{
				$this->data['errors'][] = "Neither the IDR currency is installed or the Veritrans currency conversion rate is valid. Please review your currency setting.";
			}
		}
		$veritrans->items = $commodities;
		
    // $veritrans->finish_payment_return_url = $this->url->link('checkout/success');
    // $veritrans->unfinish_payment_return_url = $this->url->link('checkout/cart');
    // $veritrans->error_payment_return_url = $this->url->link('checkout/cart');

    // VT-Web or VT-Direct?
    if ($this->config->get('veritrans_payment_type') == 'vtdirect') {
    	$veritrans->payment_type = Veritrans::VT_DIRECT;
    	$veritrans->token_id = $_POST['token_id'];
    } 
    else 
    {
    	$veritrans->payment_type = Veritrans::VT_WEB;
    }

    if ($this->config->get('veritrans_payment_type') == 'vtdirect' && $this->config->get('veritrans_api_version') == 1)
    {
    	$veritrans->server_key = $this->config->get('veritrans_server_key_v1');
    }

    // Version-specific Veritrans settings
    $veritrans->version = $this->config->get('veritrans_api_version');
    if ($veritrans->version == 2)
    {
    	if ($this->config->get('veritrans_environment') == 'production')
    	{
    		$veritrans->environment = Veritrans::ENVIRONMENT_PRODUCTION;	
    	} else
    	{
    		$veritrans->environment = Veritrans::ENVIRONMENT_DEVELOPMENT;	
    	}    	
    	$veritrans->server_key = $this->config->get('veritrans_server_key_v2');
    } else
    {
    	$veritrans->merchant_id = $this->data['merchant'];
			$veritrans->merchant_hash_key = $this->data['hash'];
    }

    // Optional parameters
    if ($this->config->get('veritrans_3d_secure') == "on")
    	$veritrans->enable_3d_secure = TRUE;

    // Installment terms
    if ($this->config->get('veritrans_installment_terms'))
    {
    	$installment_config = $this->config->get('veritrans_installment_terms');
    	$veritrans->installment_banks = array_keys($installment_config);
    	$installment_terms = array();
    	foreach ($installment_config as $key => $value) {
    		$installment_terms[$key] = array_keys($value);
    	}
    	$veritrans->installment_terms = $installment_terms;
    }

    // enable smart sanitization
    $veritrans->force_sanitization = TRUE;

    // if we use VT-Direct, charge the CC. If we use VT-Web, display the form
    if ($veritrans->payment_type == Veritrans::VT_DIRECT) {
    	
    	$this->data['key'] = $veritrans->charge();

    	if ($this->config->get('veritrans_api_version') == 2)
    	{
        if ($this->data['key']['transaction_status'] == 'capture')
    		{
    			$paymentSuccess = TRUE;
    		} else
    		{
    			$paymentSuccess = FALSE;
    		}
    	} else // v1 or else
    	{
    		if ($this->data['key']['status'] == 'success')
    		{
    			$paymentSuccess = TRUE;
    		} else
    		{
    			$paymentSuccess = FALSE;
    		}
    	}

    } else {
    	
    	$this->data['key'] = $veritrans->getTokens();

      // handle charge result		
			if(!$this->data['key']) {
				$veritrans_error = "";
				if ($veritrans->error != NULL) {
					foreach ($veritrans->errors as $key => $value) {
						$veritrans_error .= "$key: $value";
					}
				}			
				$this->data['errors'][] = $veritrans_error;
			}

			// save order
			if ($this->config->get('veritrans_api_version') == 1 && $this->config->get('veritrans_payment_type') == 'vtweb')
			{
				$dataToken = array(
					'order_id' => $order_info['order_id'],
					'token_browser' => $this->data['key']['token_browser'],
					'token_merchant'=> $this->data['key']['token_merchant']
				);
				$this->model_payment_veritrans->addToken($dataToken);
			}

			switch ($this->config->get('veritrans_test')) {
				case 'live':
					$status = 'live';
					break;
				case 'successful':
				default:
					$status = 'true';
					break;
				case 'fail':
					$status = 'false';
					break;
			}

			$this->data['options'] = 'test_status=' . $status . ',dups=false,cb_post=false';
			
    }

    $this->data['veritrans'] = $veritrans;    

		if ($this->config->get('veritrans_payment_type') == 'vtweb')
		{
      if ($this->config->get('veritrans_api_version') == 2) {
        $this->cart->clear();
        $this->redirect($this->data['key']['redirect_url']);
      } else {
        $this->cart->clear();
        $this->template = 'default/template/payment/veritrans_v1_vtweb.tpl';
        $this->response->setOutput($this->render(TRUE));
      }
		} else {
			if ($paymentSuccess) {
        $this->model_checkout_order->confirm($this->order_id, 5, 'success');
        $this->cart->clear();
				$this->redirect($this->url->link('checkout/success'));
			} else
			{
        $this->model_checkout_order->confirm($this->order_id, 10, 'failure');
				$this->redirect($this->url->link('checkout/failure'));
			}
		}

	}

	// public function success()
	// {
	// 	$this->data = array_merge($this->language->load('payment/veritrans'), $this->data);
	// 	$this->document->setTitle($this->language->get('heading_title'));

	// 	$this->cart->clear();
	// 	unset($this->session->data['order_id']);
		
	// 	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/veritrans_success.tpl')) {
	// 		$this->template = $this->config->get('config_template') . '/template/payment/veritrans_success.tpl';
	// 	} else {
	// 		$this->template = 'default/template/payment/veritrans_success.tpl';
	// 	}

	// 	$this->children = array(
	// 		'common/column_left',
	// 		'common/column_right',
	// 		'common/content_top',
	// 		'common/content_bottom',
	// 		'common/footer',
	// 		'common/header'
	// 	);

	// 	$this->response->setOutput($this->render(true));
	// }

	// public function failure()
	// {
	// 	$this->language->load('payment/veritrans');

	// 	$this->document->setTitle($this->language->get('heading_title'));

	// 	$this->data['heading_title'] = $this->language->get('heading_title');
	// 	$this->data['text_payment_failed'] = $this->language->get('text_payment_failed');

	// 	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/veritrans_failure.tpl')) {
	// 		$this->template = $this->config->get('config_template') . '/template/payment/veritrans_failure.tpl';
	// 	} else {
	// 		$this->template = 'default/template/payment/veritrans_failure.tpl';
	// 	}

	// 	$this->children = array(
	// 		'common/column_left',
	// 		'common/column_right',
	// 		'common/content_top',
	// 		'common/content_bottom',
	// 		'common/footer',
	// 		'common/header'
	// 	);

	// 	$this->response->setOutput($this->render(true));	
	// }

  public function payment_notification()
  {
		$this->load->model('checkout/order');
		$this->load->model('payment/veritrans');

    if ($this->config->get('veritrans_api_version') == 2)
    {

      $veritrans_notification = new VeritransNotification();

      // Debug
      ob_start();
      var_dump($veritrans_notification);
      $contents = ob_get_contents();
      ob_end_clean();
      error_log($contents);

    } else
    {
      $veritrans_notification = new VeritransNotification();
      $token_merchant = $this->model_payment_veritrans->getTokenMerchant($veritrans_notification->orderId);

      if ($veritrans_notification->mStatus != 'fatal')
      {
        // Verify the Merchant Key
        if($token_merchant == $veritrans_notification->TOKEN_MERCHANT) 
        {
          if ($veritrans_notification->mStatus == 'success')
          {
            $this->model_checkout_order->confirm($veritrans_notification->orderId, $this->config->get('veritrans_vtweb_success_mapping'), 'VT-Web payment successful.');  
          } else if ($veritrans_notification->mStatus == 'challenge')
          {
            $this->model_checkout_order->confirm($veritrans_notification->orderId, $this->config->get('veritrans_vtweb_challenge_mapping'), 'VT-Web payment challenged. Please take action on your Merchant Administration Portal.');  
          } else if ($veritrans_notification->mStatus == 'failure')
          {
            $this->model_checkout_order->confirm($veritrans_notification->orderId, $this->config->get('veritrans_vtweb_failure_mapping'), 'VT-Web payment failed.');  
          }         
        }
      }       
    }	
  }
}

