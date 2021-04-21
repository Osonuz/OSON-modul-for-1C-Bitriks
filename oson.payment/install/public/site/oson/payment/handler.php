<?php
define("NOT_CHECK_PERMISSIONS", true);
define("PUBLIC_AJAX_MODE", true);
define("STOP_STATISTICS", true);
define("SM_SAFE_MODE", true);
define("NO_AGENT_CHECK", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");

require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";
\Bitrix\Main\Loader::includeModule('oson.payment');
die(\Oson\Payment\Api::createTransaction());