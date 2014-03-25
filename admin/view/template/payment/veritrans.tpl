<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <!-- breadcrumb -->
  
  <?php if ($error_warning): ?>
    <div class="warning"><?php echo $error_warning; ?></div>
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
                <?php if ($veritrans_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
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
              <?php if ($error_merchant): ?>
                <span class="error"><?php echo $error_merchant; ?></span>
              <?php endif; ?></td>
          </tr>
          <!-- API Version -->

          <tr class="v2_settings">
            <td><span class="required">*</span> <?php echo $entry_environment; ?></td>
            <td>
              <select name="veritrans_environment" value="<?php echo $veritrans_environment; ?>">
                <option value="1">Development</option>
                <option value="2">Production</option>
              </select>
              <?php if ($error_merchant): ?>
                <span class="error"><?php echo $error_merchant; ?></span>
              <?php endif; ?></td>
          </tr>
          <!-- Environment (v2-specific) -->

          <tr class="v1_settings">
            <td><span class="required">*</span> <?php echo $entry_merchant; ?></td>
            <td><input type="text" name="veritrans_merchant" value="<?php echo $veritrans_merchant; ?>" />
              <?php if ($error_merchant): ?>
              <span class="error"><?php echo $error_merchant; ?></span>
              <?php endif; ?></td>
          </tr>
          <!-- Merchant ID (v1-specific) -->

          <tr class="v1_settings">
            <td><span class="required">*</span> <?php echo $entry_hash; ?></td>
            <td><input type="text" name="veritrans_hash" value="<?php echo $veritrans_hash; ?>" />
              <?php if ($error_hash): ?>
              <span class="error"><?php echo $error_hash; ?></span>
              <?php endif; ?></td>
          </tr>
          <!-- Merchant Hash Key (v1-specific) -->

          <tr class="v2_settings">
            <td><span class="required">*</span> <?php echo $entry_server_key; ?></td>
            <td><input type="text" name="veritrans_server_key" value="<?php echo $veritrans_server_key; ?>" />
              <?php if ($error_hash): ?>
              <span class="error"><?php echo $error_hash; ?></span>
              <?php endif; ?></td>
          </tr>
          <!-- Server Key (v2-specific) -->

          <tr>
            <td><span class="required">*</span> <?php echo $entry_api_version; ?></td>
            <td>
              <?php $options = array('vtweb' => 'VT-Web', 'vtdirect' => 'VT-Direct'); ?>
              <select name="veritrans_payment_type">
                <?php foreach ($options as $key => $value): ?>
                  <option value="<?php echo $key ?>" <?php if ($key == $veritrans_payment_type) echo 'selected' ?> ><?php echo $value ?></option>
                <?php endforeach ?>
              </select>
              <?php if ($error_merchant): ?>
                <span class="error"><?php echo $error_merchant; ?></span>
              <?php endif; ?></td>
          </tr>
          <!-- Payment Type -->

          <?php $banks = array('bni' => 'BNI', 'cimb' => 'CIMB', 'mandiri' => 'Mandiri') ?>
          <?php foreach ($banks as $bank_key => $bank_value): ?>
            <tr>
              <td>
                <?php echo preg_replace('/BANK/', $bank_value, $entry_enable_bank_installment); ?>
              </td>
              <td>
                <?php $installment_terms = array(3, 6, 9, 12, 18, 24); ?>
                <?php foreach ($installment_terms as $installment_term): ?>
                  <input type="checkbox" name="veritrans_installment_terms[<?php echo $bank_key ?>][<?php echo $installment_term ?>]" <?php if (array_key_exists($bank_key, $veritrans_installment_terms) && array_key_exists($installment_term, $veritrans_installment_terms[$bank_key]) && $veritrans_installment_terms[$bank_key][$installment_term]) echo 'checked'; ?> /> <?php echo $installment_term ?>
                <?php endforeach ?>
                <?php if ($error_merchant): ?>
                  <span class="error"><?php echo $error_merchant; ?></span>
                <?php endif; ?></td>
            </tr>  
          <?php endforeach ?>
          
          <!-- Installment -->

          <tr>
            <td><span class="required">*</span> <?php echo $entry_3d_secure; ?></td>
            <td><input type="checkbox" name="veritrans_3d_secure" <?php if ($veritrans_3d_secure) echo 'checked'; ?> />
              <?php if ($error_hash): ?>
                <span class="error"><?php echo $error_hash; ?></span>
              <?php endif; ?>
            </td>
          </tr>
          <!-- 3D Secure -->

          <?php if (!$this->currency->has('IDR')): ?>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_currency_conversion; ?></td>
              <td><input type="text" name="veritrans_currency_conversion" value="<?php echo $veritrans_currency_conversion ?>" />
                <?php if ($error_hash): ?>
                  <span class="error"><?php echo $error_hash; ?></span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endif ?>
          <!-- Currency -->

          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="veritrans_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $veritrans_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
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
    versionDependentOptions();
    $("#veritransApiVersion").on('change', function(e, data) {
      versionDependentOptions();
    });
  });
</script>
<?php echo $footer; ?>
