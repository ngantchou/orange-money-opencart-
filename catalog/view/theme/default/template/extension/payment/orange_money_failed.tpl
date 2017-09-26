<?php echo $header; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>
<div id="content">
    <?php echo $content_top; ?>
    <div class="container">
        <div class="alert alert-danger"><i class="fa fa-times-circle"></i> Your payment was declined. Please check with your bank or try again and proceed with <a href="<?php echo $checkout_link; ?>">checkout</a>.</div>
    </div>
    <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>