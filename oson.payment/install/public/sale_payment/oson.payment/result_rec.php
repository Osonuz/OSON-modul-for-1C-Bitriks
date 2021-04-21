<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$GLOBALS["APPLICATION"]->RestartBuffer();
Bitrix\Main\Loader::includeModule('oson.payment');
\Oson\Payment\Api::run($arParams);