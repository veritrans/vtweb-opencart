<?php
class ControllerPaymentVeritrans extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('payment/veritrans');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('veritrans', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_live'] = $this->language->get('text_live');
		$this->data['text_successful'] = $this->language->get('text_successful');
		$this->data['text_fail'] = $this->language->get('text_fail');

		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_hash'] = $this->language->get('entry_hash');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['merchant'])) {
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
		}

 		if (isset($this->error['hash'])) {
			$this->data['error_hash'] = $this->error['hash'];
		} else {
			$this->data['error_hash'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/veritrans', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('payment/veritrans', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['veritrans_merchant'])) {
			$this->data['veritrans_merchant'] = $this->request->post['veritrans_merchant'];
		} else {
			$this->data['veritrans_merchant'] = $this->config->get('veritrans_merchant');
		}

		if (isset($this->request->post['veritrans_hash'])) {
			$this->data['veritrans_hash'] = $this->request->post['veritrans_hash'];
		} else {
			$this->data['veritrans_hash'] = $this->config->get('veritrans_hash');
		}

		if (isset($this->request->post['veritrans_test'])) {
			$this->data['veritrans_test'] = $this->request->post['veritrans_test'];
		} else {
			$this->data['veritrans_test'] = $this->config->get('veritrans_test');
		}

		if (isset($this->request->post['veritrans_total'])) {
			$this->data['veritrans_total'] = $this->request->post['veritrans_total'];
		} else {
			$this->data['veritrans_total'] = $this->config->get('veritrans_total');
		}

		if (isset($this->request->post['veritrans_order_status_id'])) {
			$this->data['veritrans_order_status_id'] = $this->request->post['veritrans_order_status_id'];
		} else {
			$this->data['veritrans_order_status_id'] = $this->config->get('veritrans_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['veritrans_geo_zone_id'])) {
			$this->data['veritrans_geo_zone_id'] = $this->request->post['veritrans_geo_zone_id'];
		} else {
			$this->data['veritrans_geo_zone_id'] = $this->config->get('veritrans_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['veritrans_status'])) {
			$this->data['veritrans_status'] = $this->request->post['veritrans_status'];
		} else {
			$this->data['veritrans_status'] = $this->config->get('veritrans_status');
		}

		if (isset($this->request->post['veritrans_sort_order'])) {
			$this->data['veritrans_sort_order'] = $this->request->post['veritrans_sort_order'];
		} else {
			$this->data['veritrans_sort_order'] = $this->config->get('veritrans_sort_order');
		}

		$this->template = 'payment/veritrans.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/veritrans')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['veritrans_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['veritrans_hash']) {
			$this->error['veritrans'] = $this->language->get('error_hash');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>