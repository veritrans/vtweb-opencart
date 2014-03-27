<script type="text/javascript" src="https://payments.veritrans.co.id/vtdirect/veritrans.min.js"></script>
<script>
  $(
    function() {
      Veritrans.client_key = '<?php echo $veritrans->client_key ?>'; // please add client-key from veritrans

      function _cardSet(){
        return {
          "card_number" : $('input.card-number').val(),
          "card_exp_month": $('input.card-expiry-month').val(),
          "card_exp_year" : $('input.card-expiry-year').val(),
          "card_cvv" : $('input.card-cvv').val()
        }
      };

      function _success(d){
        $('#token_id').val(d.data.token_id); // store token data in input #token_id
        $("#payment-form")[0].submit(); //submits Token to merchant server
      };

      function _error(d){
        alert(d.message); // please customize the error
        $('.submit-button').removeAttr("disabled");
      };

      $("#payment-form").submit(function(event){
        $('.submit-button').attr("disabled", "disabled"); // disable the submit button
        Veritrans.tokenGet(_cardSet, _success, _error);
        return false;
      });
    });
</script>