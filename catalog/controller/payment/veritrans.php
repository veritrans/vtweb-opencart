<?php

class ControllerPaymentVeritrans extends Controller {
  
  private $order_id = "";

	protected function index() {

		require_once(DIR_SYSTEM . 'library/veritrans/veritrans.php');

		$this->load->model('payment/veritrans');
		
		$products = $this->cart->getProducts();

		$this->language->load('payment/veritrans');

		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$this->order_id=$order_info['order_id'];
		$this->data['merchant'] = $this->config->get('veritrans_merchant');
		$this->data['trans_id'] = $this->session->data['order_id'];
		$this->data['hash'] = $this->config->get('veritrans_hash');
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
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

		$veritrans->merchant_id = $this->data['merchant'];
		$veritrans->merchant_hash_key = $this -> data['hash'];
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
		$veritrans->gross_amount = number_format($this->data['amount'],0,'','');
		//echo "gross amount :".$veritrans->gross_amount."<br>";
		$commodities = array();
		$index=1;
		$productprice=0;
		foreach ($products as $product){
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$product['price']=number_format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')),0,'','');
			}
			$commodity_item = array("item_id" => $product['product_id'],
						"price" => number_format($product['price'],0,'',''),
						"quantity" => $product['quantity'],
						"item_name1" => (substr($product['name'],0,17))."...",
						"item_name2" => (substr($product['name'],0,17))."...");
						
			array_push($commodities, $commodity_item);
			$productprice+=$product['price']*$product['quantity'];
			//echo "product price ".$index++." = ".$productprice."<br>";
		}

		if ($this->cart->hasShipping()){
			if($this->tax->calculate($this->config->get('flat_cost'), $this->config->get('flat_tax_class_id'), $this->config->get('config_tax'))!=0){
				$shipping_cost=$this->tax->calculate($this->config->get('flat_cost'), $this->config->get('flat_tax_class_id'), $this->config->get('config_tax'));
			} else{
				$shipping_cost = $veritrans->gross_amount - $productprice;
			}
			$shipping_fee= array("item_id" => "0",
					"price" => $shipping_cost,
					"quantity" => 1,
					"item_name1" => "SHIPPING FEE",
					"item_name2" => "SHIPPING FEE");
					array_push($commodities, $shipping_fee);
			$productprice+=$shipping_cost;

			//echo "shipping cost = ".$shipping_cost."<br>";
		}

		if ($veritrans->gross_amount!=$productprice){
			$fee= array("item_id" => "aa",
					"price" => $veritrans->gross_amount-$productprice,
					"quantity" => 1,
					"item_name1" => "FEE",
					"item_name2" => "FEE");
					array_push($commodities, $fee);

		}

		$veritrans->items = $commodities;

    $veritrans->finish_payment_return_url = $this->url->link('checkout/success');
    $veritrans->unfinish_payment_return_url = $this->url->link('checkout/cart');
    $veritrans->error_payment_return_url = $this->url->link('checkout/cart');

		// print_r ($veritrans);
		$this->data['key'] = $veritrans->getTokens();

		# printout keys to browser
		//var_dump($this->data['key']);

		if(isset($this->data['key']['error_message'])) {
			echo $this->data['key']['error_message'];
			return false;
		}

		//save order
		$dataToken = array(
		'order_id'    => $order_info['order_id'],
		'token_browser' => $this->data['key']['token_browser'],
		'token_merchant'=> $this->data['key']['token_merchant']
		);
		$this->model_payment_veritrans->addToken($dataToken);

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

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/veritrans.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/veritrans.tpl';
		} else {
			$this->template = 'default/template/payment/veritrans.tpl';
		}

		$this->render();
	}


	   public function payment_notification()
	   {
			require_once(DIR_SYSTEM.'library/veritrans/veritrans.php');
			$this->load->model('checkout/order');
			$this->load->model('payment/veritrans');
			$veritrans_notification = new VeritransNotification();
			$token_merchant=$this->model_payment_veritrans->getTokenMerchant($veritrans_notification->orderId);

			// Verify the Merchant Key
			if($token_merchant != $veritrans_notification->TOKEN_MERCHANT){
			  echo "ERR";
			  exit();
			}

			// Check transaction result

			if($veritrans_notification->mStatus == 'success'){
			  $this->model_checkout_order->confirm($veritrans_notification->orderId,5,'success');
			  echo "OK ";
			  exit;

			}
			 else
			{
			  $this->model_checkout_order->confirm($veritrans_notification->orderId,10,'failed');
			  echo "FAIL";
			  exit;
			}
	   }
}

