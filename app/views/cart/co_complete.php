

<?php if( $order_num ) { ?>

<div style="background-color: #ffd; padding: 10px; border: 2px solid #ff3;border-radius: 5px">
  <b>Thank you!</b> 
  <p>
  Your order has been placed and we have sent you a confirmation email.
  </p>
  <p>
  Order <a href="/profile/page/history"><?=$order_num?></a>
  </p>
</div>

<?php } else { ?>

error

<?php } ?>
