<?php
class ControllerPaymentVeritrans extends Controller {

  private $error = array();

  public function index() {
    $this->language->load('payment/veritrans');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');
    $this->load->model('localisation/order_status');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_setting_setting->editSetting('veritrans', $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
    }

    $language_entries = array(

      'heading_title',
      'text_enabled',
      'text_disabled',
      'text_yes',
      'text_live',
      'text_successful',
      'text_fail',
      'text_all_zones',

      'entry_api_version',
      'entry_environment',
      'entry_merchant',
      'entry_server_key',
      'entry_hash',
      'entry_test',
      'entry_total',
      'entry_order_status',
      'entry_geo_zone',
      'entry_status',
      'entry_sort_order',
      'entry_3d_secure',
      'entry_payment_type',
      'entry_enable_bank_installment',
      'entry_currency_conversion',
      'entry_client_key',
      'entry_server_key_v1',
      'entry_client_key_v1',
      'entry_vtweb_success_mapping',
      'entry_vtweb_failure_mapping',
      'entry_vtweb_challenge_mapping',
      'entry_display_name',

      'button_save',
      'button_cancel'
      );

    foreach ($language_entries as $language_entry) {
      $this->data[$language_entry] = $this->language->get($language_entry);
    }

    if (isset($this->error)) {
      $this->data['error'] = $this->error;
    } else {
      $this->data['error'] = array();
    }

    $this->data['breadcrumbs'] = array();

    $this->data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => false
    );

    $this->data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_payment'),
      'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );

    $this->data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('payment/veritrans', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );

    $this->data['action'] = $this->url->link('payment/veritrans', 'token=' . $this->session->data['token'], 'SSL');

    $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

    $inputs = array(
      'veritrans_api_version',
      'veritrans_environment',
      'veritrans_merchant',
      'veritrans_hash',
      'veritrans_server_key_v1',
      'veritrans_server_key_v2',
      'veritrans_test',
      'veritrans_total',
      'veritrans_order_status_id',
      'veritrans_geo_zone_id',
      'veritrans_sort_order',
      'veritrans_3d_secure',
      'veritrans_payment_type',
      'veritrans_installment_terms',
      'veritrans_currency_conversion',
      'veritrans_status',
      'veritrans_client_key_v1',
      'veritrans_client_key_v2',
      'veritrans_vtweb_success_mapping',
      'veritrans_vtweb_failure_mapping',
      'veritrans_vtweb_challenge_mapping',
      'veritrans_display_name',
      'veritrans_enabled_payments',
      'veritrans_sanitization',
      'veritrans_installment_option',
      'veritrans_installment_banks',
      'veritrans_installment_bni_term',
      'veritrans_installment_mandiri_term'
    );

    foreach ($inputs as $input) {
      if (isset($this->request->post[$input])) {
        $this->data[$input] = $this->request->post[$input];
      } else {
        $this->data[$input] = $this->config->get($input);
      }
    }

    $this->load->model('localisation/order_status');

    $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

    $this->load->model('localisation/geo_zone');

    $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

    $this->template = 'payment/veritrans.tpl';
    $this->children = array(
      'common/header',
      'common/footer'
    );

    $this->response->setOutput($this->render());
  }

  protected function validate() {

    // Override version to v2
    $version = 2;

    // temporarily always set the payment type to vtweb if the api_version == 2
    if ($version == 2)
      $this->request->post['veritrans_payment_type'] = 'vtweb';

    $payment_type = $this->request->post['veritrans_payment_type'];
    if (!in_array($payment_type, array('vtweb', 'vtdirect')))
      $payment_type = 'vtweb';

    if (!$this->user->hasPermission('modify', 'payment/veritrans')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    // check for empty values
    if (!$this->request->post['veritrans_display_name']) {
      $this->error['display_name'] = $this->language->get('error_display_name');
    }

    // version-specific validation
    if ($version == 1)
    {
      // check for empty values
      if ($payment_type == 'vtweb')
      {
        if (!$this->request->post['veritrans_merchant']) {
          $this->error['merchant'] = $this->language->get('error_merchant');
        }

        if (!$this->request->post['veritrans_hash']) {
          $this->error['hash'] = $this->language->get('error_hash');
        }
      } else
      {
        if (!$this->request->post['veritrans_client_key_v1']) {
          $this->error['client_key_v1'] = $this->language->get('error_client_key');
        }

        if (!$this->request->post['veritrans_server_key_v1']) {
          $this->error['server_key_v1'] = $this->language->get('error_server_key');
        }
      }
    } else if ($version == 2)
    {
      // default values
      if (!$this->request->post['veritrans_environment'])
        $this->request->post['veritrans_environment'] = 1;

      // check for empty values
      if (!$this->request->post['veritrans_client_key_v2']) {
        $this->error['client_key_v2'] = $this->language->get('error_client_key');
      }

      if (!$this->request->post['veritrans_server_key_v2']) {
        $this->error['server_key_v2'] = $this->language->get('error_server_key');
      }
    }

    //currency conversion to IDR
    if (!$this->request->post['veritrans_currency_conversion'] && !$this->currency->has('IDR'))
    {
      $this->error['currency_conversion'] = $this->language->get('error_currency_conversion');
    }    

    if (!$this->error) {
      return true;
    } else {
      return false;
    }
  }
}
?>