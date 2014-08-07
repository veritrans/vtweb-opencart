<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
    <?php echo $content_top; ?>
 	<h1>Warning</h1>
 	<div class="content">
 		<?php echo $breadcrumb['message']; ?>
 	</div>
 	<div class="buttons">
    	<div class="right"><a href="<?php echo $breadcrumb['href']; ?>" class="button">Continue with full payment</a></div>
  	</div>    
</div>
<?php echo $footer; ?>