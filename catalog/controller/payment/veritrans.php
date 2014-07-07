<?php

require_once(DIR_SYSTEM . 'library/veritrans-php/Veritrans.php');

class ControllerPaymentVeritrans extends Controller {

  public function index() {

    $this->data['errors'] = array();
    $this->data['button_confirm'] = $this->language->get('button_confirm');

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template')
          . '/template/payment/veritrans.tpl')) {
      $this->template = $this->config->get('config_template')
        . '/template/payment/veritrans.tpl';
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

    $this->data['button_confirm'] = $this->language->get('button_confirm');

    $order_info = $this->model_checkout_order->getOrder(
        $this->session->data['order_id']);

    $transaction_details                 = array();
    $transaction_details['order_id']     = $this->session->data['order_id'];
    $transaction_details['gross_amount'] = $order_info['total'];

    $billing_address                 = array();
    $billing_address['first_name']   = $order_info['payment_firstname'];
    $billing_address['last_name']    = $order_info['payment_lastname'];
    $billing_address['address']      = $order_info['payment_address_1'];
    $billing_address['city']         = $order_info['payment_city'];
    $billing_address['postal_code']  = $order_info['payment_postcode'];
    $billing_address['country_code'] = $order_info['payment_iso_code_3'];
    $billing_address['phone']        = $order_info['telephone'];

    if ($this->cart->hasShipping()) {
      $shipping_address = array();
      $shipping_address['first_name']   = $order_info['shipping_firstname'];
      $shipping_address['last_name']    = $order_info['shipping_lastname'];
      $shipping_address['address']      = $order_info['shipping_address_1'];
      $shipping_address['city']         = $order_info['shipping_city'];
      $shipping_address['postal_code']  = $order_info['shipping_postcode'];
      $shipping_address['phone']        = $order_info['telephone'];
      $shipping_address['country_code'] = $order_info['payment_iso_code_3'];
    } else {
      $shipping_address = $billing_address;
    }

    $customer_details                     = array();
    $customer_details['billing_address']  = $billing_address;
    $customer_details['shipping_address'] = $shipping_address;
    $customer_details['first_name']       = $order_info['payment_firstname'];
    $customer_details['last_name']        = $order_info['payment_lastname'];
    $customer_details['email']            = $order_info['email'];
    $customer_details['phone']            = $order_info['telephone'];

    $products = $this->cart->getProducts();
    $item_details = array();
    foreach ($products as $product) {
      if (($this->config->get('config_customer_price')
            && $this->customer->isLogged())
          || !$this->config->get('config_customer_price')) {
        $product['price'] = $this->tax->calculate(
            $product['price'],
            $product['tax_class_id'],
            $this->config->get('config_tax'));
      }

      $item = array(
          'id'       => $product['product_id'],
          'price'    => $product['price'],
          'quantity' => $product['quantity'],
          'name'     => substr($product['name'], 0, 17) . '...'
        );
      $item_details[] = $item;
    }

    if ($this->cart->hasShipping()) {
      $shipping_info = $this->session->data['shipping_method'];
      if (($this->config->get('config_customer_price')
            && $this->customer->isLogged())
          || !$this->config->get('config_customer_price')) {
        $shipping_info['cost'] = $this->tax->calculate(
            $shipping_info['cost'],
            $shipping_info['tax_class_id'],
            $this->config->get('config_tax'));
      }

      $shipping_item = array(
          'id'       => 'SHIPPING',
          'price'    => $shipping_info['cost'],
          'quantity' => 1,
          'name'     => 'SHIPPING'
        );
      $item_details[] = $shipping_item;
    }

    // convert all item prices to IDR
    if ($this->config->get('config_currency') != 'IDR') {
      if ($this->currency->has('IDR')) {
        foreach ($item_details as &$item) {
          $item['price'] = intval($this->currency->convert(
              $item['price'],
              $this->config->get('config_currency'),
              'IDR'
            ));
        }

        $transaction_details['gross_amount'] = intval($this->currency->convert(
            $transaction_details['gross_amount'],
            $this->config->get('config_currency'),
            'IDR'
          ));
      }
      else if ($this->config->get('veritrans_currency_conversion') > 0) {
        foreach ($item_details as &$item) {
          $item['price'] = intval($item['price']
              * $this->config->get('veritrans_currency_conversion'));
        }

        $transaction_details['gross_amount'] = intval(
            $transaction_details['gross_amount']
            * $this->config->get('veritrans_currency_conversion'));
      }
      else {
        $this->data['errors'][] = "Neither the IDR currency is installed or "
            . "the Veritrans currency conversion rate is valid. "
            . "Please review your currency setting.";
      }
    }

    if ($this->config->get('veritrans_environment') == 'production') {
      Veritrans_Config::$isProduction = true;
    }
    else {
      Veritrans_Config::$isProduction = false;
    }

    Veritrans_Config::$serverKey = $this->config->
        get('veritrans_server_key_v2');

    if ($this->config->get('veritrans_3d_secure') == 'on') {
      Veritrans_Config::$is3ds = true;
    }
    else {
      Veritrans_Config::$is3ds = false;
    }

    // TODO
    // // Installment terms
    // if ($this->config->get('veritrans_installment_terms'))
    // {
    //   $installment_config = $this->config->get('veritrans_installment_terms');
    //   $veritrans->installment_banks = array_keys($installment_config);
    //   $installment_terms = array();
    //   foreach ($installment_config as $key => $value) {
    //     $installment_terms[$key] = array_keys($value);
    //   }
    //   $veritrans->installment_terms = $installment_terms;
    // }

    // enable smart sanitization
    // $veritrans->force_sanitization = TRUE;

    $payloads = array();
    $payloads['transaction_details'] = $transaction_details;
    $payloads['item_details']        = $item_details;
    $payloads['customer_details']    = $customer_details;

    if ($this->config->get('veritrans_payment_type') == 'vtdirect') {
      // TODO
    }
    //
    else {
      try {
        $redirUrl = Veritrans_VtWeb::getRedirectionUrl($payloads);
        $this->cart->clear();
        $this->redirect($redirUrl);
      }
      catch (Exception $e) {
        $this->data['errors'][] = $e->getMessage();
      }
    }
  }

  public function payment_notification() {
    error_log('payment notification');

    $this->load->model('checkout/order');
    $this->load->model('payment/veritrans');

    Veritrans_Config::$serverKey = $this->config->
        get('veritrans_server_key_v2');
    $notif = new Veritrans_Notification();

    if ($notif->isVerified()) {
      error_log('verified');

      $transaction = $notif->transaction_status;
      $fraud = $notif->fraud_status;

      $logs = '';

      if ($transaction == 'capture') {
        $logs .= 'capture ';
        if ($fraud == 'challenge') {
          $logs .= 'challenge ';
          $this->model_checkout_order->confirm(
              $notif->order_id,
              $this->config->get('veritrans_vtweb_challenge_mapping'),
              'VT-Web payment challenged. Please take action on '
                . 'your Merchant Administration Portal.');
        }
        else if ($fraud == 'accept') {
          $logs .= 'accept ';
          $this->model_checkout_order->confirm(
              $notif->order_id,
              $this->config->get('veritrans_vtweb_success_mapping'),
              'VT-Web payment successful.');
          $this->model_checkout_order->update(
              $notif->order_id,
              $this->config->get('veritrans_vtweb_success_mapping'),
              'VT-Web payment successful.');
        }
      }
      else if ($transaction == 'cancel') {
        $logs .= 'cancel ';
        if ($fraud == 'challenge') {
          $logs .= 'challenge ';
          $this->model_checkout_order->update(
              $notif->order_id,
              $this->config->get('veritrans_vtweb_failure_mapping'),
              'VT-Web payment failed.');
        }
        else if ($fraud == 'accept') {
          $logs .= 'accept ';
          $this->model_checkout_order->update(
              $notif->order_id,
              $this->config->get('veritrans_vtweb_failure_mapping'),
              'VT-Web payment failed.');
        }
      }
      else if ($transaction == 'deny') {
        $logs .= 'deny ';
        $this->model_checkout_order->confirm(
            $notif->order_id,
            $this->config->get('veritrans_vtweb_failure_mapping'),
            'VT-Web payment failed.');
      }

      error_log($logs);
    }
  }
}