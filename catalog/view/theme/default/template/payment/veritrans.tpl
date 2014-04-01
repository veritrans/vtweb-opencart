<?php if (count($errors) > 0): ?>
  <?php foreach ($errors as $error): ?>
    <div class="error"><?php echo $error ?></div>
  <?php endforeach ?>
<?php else: ?>
  <?php if ($this->config->get('veritrans_payment_type') == 'vtweb'): ?>
    
    <div class="buttons">
      <div class="right">
        <a class="button" href="<?php echo $this->url->link('payment/veritrans/process_order') ?>"><?php echo $button_confirm ?></a>
      </div>
    </div>
    <!-- v2 VT-Web form -->

  <?php else: ?>
    
    <div class="checkout-product">
      <form id="payment-form" method="post" action="<?php echo $this->url->link('payment/veritrans/process_order') ?>">
        <table class="form">
          
          <thead>
            <tr>
              <td colspan="2">Please insert your credit card number:</td>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td><span class="required">*</span>Credit Card Number: </td>
              <td>
                <input type="text" class="card-number" class="large-field" maxlength="16" />    
              </td>
            </tr>
            <!-- CC number -->
            
            <tr>
              <td><span class="required">*</span>Expiration Year (YYYY): </td>
              <td>
                <select class="card-expiry-year">
                  <?php $current_year = intval(date("Y")) ?>
                  <?php for($i = $current_year; $i < $current_year + 10; $i++): ?>
                    <?php $value = sprintf('%d', $i) ?>
                    <option value="<?php echo $value ?>"><?php echo $value ?></option>
                  <?php endfor; ?>
                </select>  
              </td>
            </tr>
            <!-- CC expiry year -->
            
            <tr>
              <td><span class="required">*</span>Expiration Month</td>
              <td>
                <select class="card-expiry-month">
                  <?php for($i = 1; $i < 13; $i++): ?>
                    <?php $value = sprintf('%02d', $i) ?>
                    <option value="<?php echo $value ?>"><?php echo $value ?></option>
                  <?php endfor; ?>
                </select>  
              </td>
            </tr>
            <!-- CC expiry month -->
            
            <tr>
              <td><span class="required">*</span> CVV</td>
              <td>
                <input type="password" class="card-cvv" maxlength="4" />    
              </td>  
            </tr>
            <!-- CC CVV -->

            <input type="hidden" id="token_id" name="token_id" />

          </tbody>
        </table>
        <div class="buttons">
          <div class="right">
            <input type="submit" value="<?php echo $button_confirm; ?>" class="button submit-button" />
          </div>
        </div>
      </form>
    </div>      
    <!-- VT-Direct form -->
    
    <?php if ($this->config->get('veritrans_api_version') == 1): ?>
      <script type="text/javascript">
        $(function() {
          $.getScript('https://payments.veritrans.co.id/vtdirect/veritrans.min.js')
            .done(function() {

              Veritrans.client_key = '<?php echo $this->config->get('veritrans_client_key_v1') ?>'; // please add client-key from veritrans

              function _cardSet() {
                return {
                  "card_number" : $('.card-number').val(),
                  "card_exp_month": $('.card-expiry-month').val(),
                  "card_exp_year" : $('.card-expiry-year').val(),
                  "card_cvv" : $('.card-cvv').val()
                }
              };

              function _success(d) {
                console.log(d.data.token_id);
                $('#token_id').val(d.data.token_id); // store token data in input #token_id
                console.log($('#token_id').val());
                $("#payment-form")[0].submit(); //submits Token to merchant server
              };

              function _error(d) {
                alert("Error: " + d.message); // please customize the error
                $('.submit-button').removeAttr("disabled");
              };

              $("#payment-form").on('submit', function(e){
                Veritrans.tokenGet(_cardSet, _success, _error);
                return false;
              });
            });
        });
      </script>
    <?php else: ?>
      <script>
        $(
        function(){
          <?php if ($this->config->get('veritrans_environment') == 'production'): ?>
            var url = 'https://api.veritrans.co.id/assets/js/veritrans.js';
          <?php else: ?>
            var url = 'https://api.sandbox.veritrans.co.id/v2/assets/js/veritrans.js';
          <?php endif ?>
          $.getScript(url).done(function() {

            <?php if ($this->config->get('veritrans_environment') == 'development'): ?>
              Veritrans.url = 'https://api.sandbox.veritrans.co.id/v2/token';
            <?php endif ?>
            Veritrans.client_key = '<?php echo $this->config->get('veritrans_client_key_v2') ?>'; // please add client-key from veritrans

            var card = function() {
              return {
                "card_number" : $('.card-number').val(),
                "card_exp_month": $('.card-expiry-month').val(),
                "card_exp_year" : $('.card-expiry-year').val(),
                "card_cvv" : $('.card-cvv').val()
              }
            };

            function callback(response) {

              if (response.redirect_url) {

              } else if (response.status_code == '200') {
                $("#token_id").val(response.token_id);
                $("#payment-form")[0].submit();
              } else {
                alert(response.status_code + ' ' + response.status_message);
              }
            }

            $("#payment-form").on('submit', function(e){
              Veritrans.token(card, callback);
              return false;
            });
          });
        });
      </script>      
    <?php endif ?>
  <?php endif ?>
<?php endif ?>
