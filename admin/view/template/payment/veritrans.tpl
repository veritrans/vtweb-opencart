<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <!-- breadcrumb -->
  
  <?php if (isset($error['warning'])): ?>
    <div class="warning"><?php echo $error['warning']; ?></div>
  <?php endif; ?>
  <!-- error -->

  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <!-- heading -->

    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">

          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="veritrans_status">
              <?php $options = array('1' => $text_enabled, '0' => $text_disabled) ?>
              <?php foreach ($options as $key => $value): ?>
                <option value="<?php echo $key ?>" <?php if ($key == $veritrans_status) echo 'selected' ?> ><?php echo $value ?></option>
              <?php endforeach ?>
              </select></td>
          </tr>
          <!-- Status -->

          <tr>
            <td><span class="required">*</span> <?php echo $entry_api_version; ?></td>
            <td>
              <?php $options = array('1' => 'v1', '2' => 'v2'); ?>
              <select name="veritrans_api_version" id="veritransApiVersion">
                <?php foreach ($options as $key => $value): ?>
                  <option value="<?php echo $key ?>" <?php if ($key == $veritrans_api_version) echo 'selected' ?> ><?php echo $value ?></option>
                <?php endforeach ?>
              </select>
          </tr>
          <!-- API Version -->

          <tr class="v2_settings">
            <td><span class="required">*</span> <?php echo $entry_environment; ?></td>
            <td>
              <select name="veritrans_environment">
                <?php $options = array('development' => 'Sandbox', 'production' => 'Production') ?>
                <?php foreach ($options as $key => $value): ?>
                  <option value="<?php echo $key ?>" <?php if ($key == $veritrans_environment) echo 'selected' ?> ><?php echo $value ?></option>  
                <?php endforeach ?>
              </select>
              <?php if (isset($error['environment'])): ?>
                <span class="error"><?php echo $error['environment']; ?></span>
              <?php endif; ?></td>
          </tr>
          <!-- Environment (v2-specific) -->

          <tr class="v1_settings vtweb_settings">
            <td><span class="required">*</span> <?php echo $entry_merchant; ?></td>
            <td><input type="text" name="veritrans_merchant" value="<?php echo $veritrans_merchant; ?>" />
              <?php if (isset($error['merchant'])): ?>
              <span class="error"><?php echo $error['merchant']; ?></span>
              <?php endif; ?></td>
          </tr>
          <!-- Merchant ID (v1-specific) -->

          <tr class="v1_settings vtweb_settings">
            <td><span class="required">*</span> <?php echo $entry_hash; ?></td>
            <td><input type="text" name="veritrans_hash" value="<?php echo $veritrans_hash; ?>" />
              <?php if (isset($error['hash'])): ?>
              <span class="error"><?php echo $error['hash']; ?></span>
              <?php endif; ?></td>
          </tr>
          <!-- Merchant Hash Key (v1-specific) -->

          <tr class="v1_settings vtdirect_settings">
            <td><span class="required">*</span> <?php echo $entry_client_key_v1; ?></td>
            <td><input type="text" name="veritrans_client_key_v1" value="<?php echo $veritrans_client_key_v1; ?>" />
              <?php if (isset($error['client_key_v1'])): ?>
              <span class="error"><?php echo $error['client_key_v1']; ?></span>
              <?php endif; ?></td>
          </tr>
          <!-- VT-Direct Client Key (v1-specific) -->

          <tr class="v1_settings vtdirect_settings">
            <td><span class="required">*</span> <?php echo $entry_server_key_v1; ?></td>
            <td><input type="text" name="veritrans_server_key_v1" value="<?php echo $veritrans_server_key_v1; ?>" />
              <?php if (isset($error['server_key_v1'])): ?>
              <span class="error"><?php echo $error['server_key_v1']; ?></span>
              <?php endif; ?></td>
          </tr>
          <!-- VT-Direct Server Key (v1-specific) -->

          <tr class="v2_settings">
            <td><span class="required">*</span> <?php echo $entry_client_key; ?></td>
            <td><input type="text" name="veritrans_client_key_v2" value="<?php echo $veritrans_client_key_v2; ?>" />
              <?php if (isset($error['client_key_v2'])): ?>
              <span class="error"><?php echo $error['client_key_v2']; ?></span>
              <?php endif; ?></td>
          </tr>
          <!-- Client Key (v2-specific) -->
          
          <tr class="v2_settings">
            <td><span class="required">*</span> <?php echo $entry_server_key; ?></td>
            <td><input type="text" name="veritrans_server_key_v2" value="<?php echo $veritrans_server_key_v2; ?>" />
              <?php if (isset($error['server_key_v2'])): ?>
              <span class="error"><?php echo $error['server_key_v2']; ?></span>
              <?php endif; ?></td>
          </tr>
          <!-- Server Key (v2-specific) -->

          <tr>
            <td><span class="required">*</span> <?php echo $entry_payment_type; ?></td>
            <td>
              <?php $options = array('vtweb' => 'VT-Web', 'vtdirect' => 'VT-Direct'); ?>
              <select name="veritrans_payment_type" id="veritransPaymentType">
                <?php foreach ($options as $key => $value): ?>
                  <option value="<?php echo $key ?>" <?php if ($key == $veritrans_payment_type) echo 'selected' ?> ><?php echo $value ?></option>
                <?php endforeach ?>
              </select>
          </tr>
          <!-- Payment Type -->

          <?php $banks = array('bni' => 'BNI', 'cimb' => 'CIMB', 'mandiri' => 'Mandiri') ?>
          <?php foreach ($banks as $bank_key => $bank_value): ?>
            <tr class="vtweb_settings">
              <td>
                <?php echo preg_replace('/BANK/', $bank_value, $entry_enable_bank_installment); ?>
              </td>
              <td>
                <?php $installment_terms = array(3, 6, 9, 12, 18, 24); ?>
                <?php foreach ($installment_terms as $installment_term): ?>
                  <input type="checkbox" name="veritrans_installment_terms[<?php echo $bank_key ?>][<?php echo $installment_term ?>]" <?php if ($veritrans_installment_terms && array_key_exists($bank_key, $veritrans_installment_terms) && array_key_exists($installment_term, $veritrans_installment_terms[$bank_key]) && $veritrans_installment_terms[$bank_key][$installment_term]) echo 'checked'; ?> /> <?php echo $installment_term ?>
                <?php endforeach ?>
            </tr>  
          <?php endforeach ?>
          
          <!-- Installment -->

          <tr>
            <td><span class="required">*</span> <?php echo $entry_3d_secure; ?></td>
            <td><input type="checkbox" name="veritrans_3d_secure" <?php if ($veritrans_3d_secure) echo 'checked'; ?> />
            </td>
          </tr>
          <!-- 3D Secure -->

          <?php foreach (array('vtweb_success_mapping', 'vtweb_failure_mapping', 'vtweb_challenge_mapping') as $status): ?>
            <tr class="vtweb_settings">
              <td><span class="required">*</span> <?php echo ${'entry_' . $status} ?></td>
              <td>
                <select name="<?php echo 'veritrans_' . $status ?>" id="veritransPaymentType">
                  <?php foreach ($order_statuses as $option): ?>
                    <option value="<?php echo $option['order_status_id'] ?>" <?php if ($option['order_status_id'] == ${'veritrans_' . $status}) echo 'selected' ?> ><?php echo $option['name'] ?></option>
                  <?php endforeach ?>
                </select>
              </td>
            </tr>
            
          <?php endforeach ?>
          <!-- VTWeb Mapping -->

          <?php if (!$this->currency->has('IDR')): ?>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_currency_conversion; ?></td>
              <td><input type="text" name="veritrans_currency_conversion" value="<?php echo $veritrans_currency_conversion ?>" />
              </td>
            </tr>
          <?php endif ?>
          <!-- Currency -->

          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td>
              <select name="veritrans_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                  <?php if ($geo_zone['geo_zone_id'] == $veritrans_geo_zone_id) { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                  <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            </td>
          </tr>
          <!-- Geo Zone -->

          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="veritrans_sort_order" value="<?php echo $veritrans_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
    <!-- content -->
  </div>
</div>
<script>
  $(function() {
    function versionDependentOptions() {
      var api_version = $('#veritransApiVersion').val();
      if (api_version == 1)
      {
        $('.v2_settings').hide();
        $('.v1_settings').show();
      } else
      {
        $('.v1_settings').hide();
        $('.v2_settings').show();
      }
    }

    function paymentApiDependentOptions() {
      var payment_type = $('#veritransPaymentType').val();
      if (payment_type == 'vtweb')
      {
        $('.vtweb_settings').show();
        $('.vtdirect_settings').hide();
      } else
      {
        $('.vtweb_settings').hide();
        $('.vtdirect_settings').show();
      }
    }

    versionDependentOptions();
    paymentApiDependentOptions();

    $("#veritransApiVersion").on('change', function(e, data) {
      versionDependentOptions();
    });
    $("#veritransPaymentType").on('change', function(e, data) {
      paymentApiDependentOptions();
    });

  });
</script>
<?php echo $footer; ?>
