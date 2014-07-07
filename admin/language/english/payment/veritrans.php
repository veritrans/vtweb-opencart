<?php
// Heading
$_['heading_title']      = 'Veritrans';

// Text
$_['text_payment']       = 'Payment';
$_['text_success']       = 'Success: You have modified Veritrans account details!';
$_['text_veritrans']     = '<a href="https://payments.veritrans.co.id/web1/paymentSelect.action" target="_blank"><img src="view/image/payment/veritrans.png" width="120px" alt="Veritrans" title="Veritrans" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_live']          = 'Production';
$_['text_successful']    = 'Always Successful';
$_['text_fail']          = 'Always Fail';

// Entry
$_['entry_api_version']  = 'API Version';
$_['entry_environment']  = 'Environment'; // v2 API only
$_['entry_merchant']     = 'Merchant ID:'; // v1 API only
$_['entry_hash']     	   = 'Merchant Hash Key'; // v1 API only
$_['entry_client_key']   = 'Client Key'; // v2 API only
$_['entry_server_key']   = 'Server Key'; // v2 API only
$_['entry_3d_secure']    = 'Enable 3D Secure?';
$_['entry_payment_type'] = 'Payment Type';
$_['entry_test']         = 'Test Mode:';
$_['entry_total']        = 'Total:<br /><span class="help">The checkout total the order must reach before this payment method becomes active.</span>';
$_['entry_order_status'] = 'Order Status:';
$_['entry_geo_zone']     = 'Geo Zone:';
$_['entry_status']       = 'Status:';
$_['entry_sort_order']   = 'Sort Order:';
$_['entry_enable_bank_installment'] = 'Enable BANK installments?';
$_['entry_currency_conversion'] = 'Currency conversion to IDR';
$_['entry_client_key_v1']   = 'VT-Direct Client Key';
$_['entry_server_key_v1']   = 'VT-Direct Server Key';
$_['entry_vtweb_success_mapping'] = 'Map Payment Success Status to Order Status:';
$_['entry_vtweb_challenge_mapping'] = 'Map Payment Challenge Status to Order Status:';
$_['entry_vtweb_failure_mapping'] = 'Map Payment Failure Status to Order Status:';
$_['entry_display_name'] = 'Display name:';

// Error
$_['error_permission']   = 'Warning: You do not have permission to modify the Veritrans Payment!';
$_['error_merchant']     = 'Merchant ID is required!';
$_['error_hash']    	 	 = 'Merchant Hash Key is required!';
$_['error_client_key']   = 'Client Key is required!';
$_['error_server_key']   = 'Server Key is required!';
$_['error_currency_conversion'] = 'Currency conversion rate is required when IDR currency is not installed in the system!';
$_['error_display_name'] = 'Please specify a name for this payment method!';
?>