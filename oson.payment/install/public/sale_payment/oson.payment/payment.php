<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->toArray();
?>
<form action="/oson/payment/handler.php?ORDER_ID=<?=$request['ORDER_ID']?>" id="oson_form" method="POST">
    <div class="osonPay">
        <!-- to change button color change class (oson_dark_btn) to (oson_light_btn) -->
        <button class="osonOpen <?=\CSalePaySystemAction::GetParamValue("BUTTON_THEME") == 'Y' ? 'oson_dark_btn' : 'oson_light_btn'?>" type="submit">Oson Pay</button>
    </div>
</form>
<script type="text/javascript" src="https://pay.oson.uz/assets/btn_invoice/cdn.js"></script>
