<?php
    global $PaymentSuccessfull, $asp_error;
    if($PaymentSuccessfull) {
        
        if(!empty($content)) {
            echo $content;
        }
        else 
            echo __('Thank you for your purchase.');

        if(!empty($item_url)) {
            echo "Please <a href='".$item_url."'>click here</a> to download.";
        }
    }
    else
    {
        echo __("System was not able to complete the payment.".$asp_error);
    }
?>