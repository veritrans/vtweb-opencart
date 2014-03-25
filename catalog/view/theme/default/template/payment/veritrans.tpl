<?php if (count($errors) > 0): ?>
  <?php foreach ($errors as $error): ?>
    <div class="error"><?php echo $error ?></div>
  <?php endforeach ?>
<?php else: ?>
  <?php if ($veritrans->version == 1): ?>
    <form action="<?php echo Veritrans::PAYMENT_REDIRECT_URL ?>" method="post">
      <input type="hidden" name="MERCHANT_ID" value="<?php echo $merchant; ?>" />
      <input type="hidden" name="ORDER_ID" value="<?php echo $trans_id; ?>" />
      <input type="hidden" name="TOKEN_BROWSER" value="<?php echo $key['token_browser'] ?>" />

      <div class="buttons">
        <div class="right">
          <input type="submit" value="<?php echo $button_confirm; ?>" class="button" />
        </div>
      </div>
    </form>  
  <?php else: ?>
    <div class="buttons">
      <div class="right">
        <a class="button" href="<?php echo $key['redirect_url'] ?>"><?php echo $button_confirm ?></a>
      </div>
    </div>
  <?php endif ?>
<?php endif ?>

