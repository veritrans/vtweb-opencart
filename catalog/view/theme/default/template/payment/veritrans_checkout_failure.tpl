<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div class="container"><?php echo $content_top; ?>
	<h2 class="text-center">Payment Failed!</h2>
	<p class="text-center"><?php echo $text_failure ?></p>
	<a href="<?php echo $checkout_url;?>">
		<div class="text-center">
			<button class="btn btn-primary">Re-Checkout!</button>
		</div>
	</a><br/>
	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>