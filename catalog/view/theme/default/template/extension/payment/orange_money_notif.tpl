<?php echo $header; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>
<div id="content" >
    <?php echo $content_top; ?>
    <div id="failed" class="container hide">
        <div class="alert alert-danger"><i class="fa fa-times-circle"></i> Your payment was declined. Please check with your bank or try again and proceed with <a href="<?php echo $checkout_link; ?>">checkout</a>.</div>
    </div>
        <div id="loader" class="container hide">
        <img src="https://www.bcmtv.org/templates/images/ajax-loader.gif">
       </div>
    <?php echo $content_bottom; ?>
</div>

<script type="text/javascript">
$(document).ready(function() {
alert(" <?php echo $status ; ?>");
function getToken(fn){
var token=[];
   $.ajax({
    url: 'index.php?route=extension/payment/orange_money/getToken',
    data: {header:"<?php echo $orange_header; ?>"},
    type: 'post',
    dataType: 'json',
    cache: false,
    success: function(json) {
      if (json['error']) {
        alert(json['error']);
        console.log(json);
      }
     fn(json);
    
      if (json['redirect']) {
        location = json['redirect'];
      }
    }
  });
    // alert(token);
  
}

  //alert('<?php echo $orange_money_merchant_key; ?>');
  //ccbdb389
    getToken(function(tok) {

        $.ajax({
              url: 'https://api.orange.com/orange-money-webpay/cm/v1/transactionstatus',
              type: 'POST',
              contentType: 'application/json',
              data: JSON.stringify({
              pay_token:  "<?php echo $orange_money_pay_token; ?>",
              order_id: "<?php echo $orange_money_order_id; ?>",
              amount: '<?php echo $orange_money_amount; ?>',
              }),
              success: function(data) {
                console.log("transaction status",data);
                if (data['status']==="FAILED") {
                  $("#failed").removeClass("hide");
                }else{
                  $("#loader").removeClass("hide");
                 window.location.href ='<?php echo  $success_link; ?>';

                }
              },
              error: function(request, status, error){
                jsonValue = jQuery.parseJSON( request.responseText );
                console.log(error);
                alert(jsonValue.message);
              },
              beforeSend: function(xhr, settings) { 
                $('#button-confirm').button('loading');
                xhr.setRequestHeader('Authorization','Bearer '+tok['access_token']);
                xhr.setRequestHeader('Accept' ,'application/json');
              }

        });
    });
  });
</script>
<?php echo $footer; ?>