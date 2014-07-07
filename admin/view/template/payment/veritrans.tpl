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
            <td>
              <select name="veritrans_status">
                <?php $options = array('1' => $text_enabled, '0' => $text_disabled) ?>
                <?php foreach ($options as $key => $value): ?>
                  <option value="<?php echo $key ?>" <?php if ($key == $veritrans_status) echo 'selected' ?> ><?php echo $value ?></option>
                <?php endforeach ?>
              </select>
            </td>
          </tr>
          <!-- Status -->

          <tr>
            <td><span class="required">*</span> <?php echo $entry_display_name; ?></td>
            <td><input type="text" name="veritrans_display_name" value="<?php echo $veritrans_display_name; ?>" />
              <?php if (isset($error['display_name'])): ?>
                <span class="error"><?php echo $error['display_name']; ?></span>
              <?php endif; ?>
            </td>
          </tr>
          <!-- Display name -->

          <tr class="v2_settings sensitive">
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
              <?php endif; ?>
            </td>
          </tr>
          <!-- Environment (v2-specific) -->

          <tr class="v2_settings sensitive">
            <td><span class="required">*</span> <?php echo $entry_client_key; ?></td>
            <td><input type="text" name="veritrans_client_key_v2" value="<?php echo $veritrans_client_key_v2; ?>" />
              <?php if (isset($error['client_key_v2'])): ?>
              <span class="error"><?php echo $error['client_key_v2']; ?></span>
              <?php endif; ?>
            </td>
          </tr>
          <!-- Client Key (v2-specific) -->

          <tr class="v2_settings sensitive">
            <td><span class="required">*</span> <?php echo $entry_server_key; ?></td>
            <td><input type="text" name="veritrans_server_key_v2" value="<?php echo $veritrans_server_key_v2; ?>" />
              <?php if (isset($error['server_key_v2'])): ?>
              <span class="error"><?php echo $error['server_key_v2']; ?></span>
              <?php endif; ?>
            </td>
          </tr>
          <!-- Server Key (v2-specific) -->

          <tr id="veritransPaymentTypeContainer">
            <td><span class="required">*</span> <?php echo $entry_payment_type; ?></td>
            <td>
              <?php $options = array('vtweb' => 'VT-Web', 'vtdirect' => 'VT-Direct'); ?>
              <select name="veritrans_payment_type" id="veritransPaymentType">
                <?php foreach ($options as $key => $value): ?>
                  <option value="<?php echo $key ?>" <?php if ($key == $veritrans_payment_type) echo 'selected' ?> ><?php echo $value ?></option>
                <?php endforeach ?>
              </select>
            </td>
          </tr>
          <!-- Payment Type -->

          <tr class="v2_settings sensitive">
            <td><span class="required">*</span> Enabled Payments</td>
            <td>
              <?php
                $payment_types = array(
                    'credit_card' => 'Credit Card',
                    'cimb_clicks' => 'CIMB Clicks',
                    'mandiri_clickpay' => 'Mandiri ClickPay'
                  );
              ?>

              <?php foreach ($payment_types as $key => $val): ?>
                <?php $isChecked = isset($veritrans_enabled_payments)
                    && array_key_exists($key, $veritrans_enabled_payments)
                    && $veritrans_enabled_payments[$key];
                ?>
                <input type="checkbox"
                    value="1"
                    name="veritrans_enabled_payments[<?php echo $key; ?>]"
                    <?php if ($isChecked) echo 'checked'; ?>>
                <?php echo $val; ?>
              <?php endforeach ?>
            </td>
          </tr>

          <tr class="v1_settings v2_vtweb_settings sensitive">
            <td><span class="required">*</span> <?php echo $entry_3d_secure; ?></td>
            <td>
              <input type="checkbox" name="veritrans_3d_secure" <?php if ($veritrans_3d_secure) echo 'checked'; ?> />
              <span>Please contact us if you wish to activate this feature in the Production environment.</span>
            </td>
          </tr>
          <!-- 3D Secure -->

          <?php foreach (array('vtweb_success_mapping', 'vtweb_failure_mapping', 'vtweb_challenge_mapping') as $status): ?>
            <tr class="">
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
              <td>
                <input type="text" name="veritrans_currency_conversion" value="<?php echo $veritrans_currency_conversion ?>" />
                <?php if (isset($error['currency_conversion'])): ?>
                  <span class="error"><?php echo $error['currency_conversion']; ?></span>
                <?php endif; ?>
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
    function sensitiveOptions() {
      var api_version = 2;
      var payment_type = $('#veritransPaymentType').val();
      var api_string = 'v' + api_version + '_settings';
      var payment_type_string = payment_type;
      var api_payment_type_string = 'v' + api_version + '_' + payment_type + '_settings';
      $('.sensitive').hide();
      $('.' + api_string).show();
      $('.' + payment_type_string).show();
      $('.' + api_payment_type_string).show();

      // temporarily hide vt-direct if the API version is 2
      if (api_version == 2)
      {
        $('#veritransPaymentTypeContainer').hide();
      } else{
        $('#veritransPaymentTypeContainer').show();
      }

    }

    $("#veritransApiVersion").on('change', function(e, data) {
      sensitiveOptions();
    });
    $("#veritransPaymentType").on('change', function(e, data) {
      sensitiveOptions();
    });

    sensitiveOptions();


  });
</script>
<?php echo $footer; ?>
