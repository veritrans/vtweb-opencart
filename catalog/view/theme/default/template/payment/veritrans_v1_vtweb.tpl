<!DOCTYPE html>
<html>
<head>
    <script language="javascript" type="text/javascript">
    <!--
    function onloadEvent() {
      document.form_auto_post.submit();
    }
    //-->
    </script>
</head>

<body onload="onloadEvent();">
<form action="<?php echo Veritrans::PAYMENT_REDIRECT_URL ?>" method="post" name='form_auto_post'>
<input type="hidden" name="MERCHANT_ID" value="<?php echo $merchant ?>" />
<input type="hidden" name="ORDER_ID" value="<?php echo $trans_id ?>" />
<input type="hidden" name="TOKEN_BROWSER" value="<?php echo $key['token_browser'] ?>" />
<span>Please wait. You are being redirected to Veritrans payment page...</span>
</form>

</body>
<!-- v1 VT-Web form -->