<form >
  <input type="hidden" name="detail" value="<?php echo $orange_money_detail; ?>" />
  <input type="hidden" name="amount" value="<?php echo $orange_money_amount; ?>" />
  <input type="hidden" name="hash" value="<?php echo $orange_money_hash; ?>" />
  <input type="hidden" name="name" value="<?php echo $orange_money_customer_name; ?>" />
  <input type="hidden" name="email" value="<?php echo $orange_money_email; ?>" />
  <input type="hidden" name="phone" value="<?php echo $orange_money_phone; ?>" />
    <input type="hidden" name="token" id="token" value="" />
  <div class="buttons">
    <div class="pull-right">
      <input type="button" id="button-confirm" value="<?php echo $button_confirm; ?>"  class="btn btn-primary" />
    </div>
  </div>
</form>

<script type="text/javascript">

function getToken(fn){
var token=[];
   $.ajax({
    url: 'index.php?route=extension/payment/orange_money/getToken',
    type: 'post',
    data: {header:"<?php echo $orange_money_authorization_header; ?>"},
    dataType: 'json',
    cache: false,
    success: function(json) {
      if (json['error']) {
        alert(json['error']);
      }
     fn(json);
    
      if (json['redirect']) {
        location = json['redirect'];
      }
    }
  });
    // alert(token);
  
}

$('#button-confirm').bind('click', function() {
  //alert('<?php echo $orange_money_merchant_key; ?>');
  //ccbdb389
    getToken(function(tok) {

        var order = "<?php echo $orange_money_order_id; ?>";
       // alert("<?php echo $orange_money_amount; ?>");
        $.ajax({
              url: 'https://api.orange.com/orange-money-webpay/cm/v1/webpayment',
              type: 'POST',
              contentType: 'application/json',
              data: JSON.stringify({
              merchant_key: "<?php echo $orange_money_merchant_key; ?>",
              currency: "XAF",
              order_id: order,
              amount: '<?php echo $orange_money_amount; ?>',
              "return_url": "<?php echo $return_url; ?>",
              "cancel_url": "<?php echo $cancel_url; ?>",
              "notif_url": "<?php echo  $notif_url; ?>",
              lang: "fr",
              reference:"EkoMarket"
              }),
              success: function(data) {
              	console.log(data);
               if (data['code']) { 
                alert(data['message']);
             }else{
             	console.log(data);
              var url =  data['payment_url'];
              $.ajax({
              url: 'index.php?route=extension/payment/orange_money/save',
              type: 'POST',
              data: {pay_token:data['pay_token'],
              id:order},
              success: function(data) {
            //    window.open(url, '_blank');
               window.location.href = url;
             //  window.location.target = '_blank';
              
              },
              });      
               // return;
             }
              },
              error: function(request, status, error){
                jsonValue = jQuery.parseJSON( request.responseText );
               // console.log(jsonValue.Message);
                alert(jsonValue.message);
                $('#button-confirm').button('reset');
              },


              beforeSend: function(xhr, settings) { 
                $('#button-confirm').button('loading');
                xhr.setRequestHeader('Authorization','Bearer '+tok['access_token']);
                xhr.setRequestHeader('Accept' ,'application/json');
              },
            
             // $('#button-confirm').button('reset');
             

        });
    });
  });
</script>