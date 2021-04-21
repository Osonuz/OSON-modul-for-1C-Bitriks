<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?

$psTitle = 'Платежная система Oson.uz';
$psDescription = 'Платежная система Oson.uz';
$psLogotip = 'https://oson.uz/docs/images/logo/logop2.png';
$arPSCorrespondence = [
    "BUTTON_THEME" => [
        "NAME" => 'Тема кнопки',
        "DESCR" => 'Темная/Светлая тема',
        "SORT" => "1",
        "VALUE" => "Y",
        "INPUT" => [
            'TYPE' => 'Y/N'
        ]
    ],
    "OSON_MERCHANT_ID" => [
        "NAME" => 'Merchand ID',
        "DESCR" => 'Уникальный ID мерчанта, предоставляется со стороны OSON',
        "SORT" => "2",
        "VALUE" => "",
        "TYPE" => ""
    ],
    "OSON_AUTH_KEY" => [
        "NAME" => 'Уникальный секретный ключ',
        "DESCR" => 'Уникальный секретный ключ мерчанта, предоставляется со стороны OSON.',
        "SORT" => "3",
        "VALUE" => "",
        "TYPE" => ""
    ],
    "TRANSACTION_ID" => [
        "NAME" => 'Заказ ID',
        "DESCR" => 'Заказ ID',
        "SORT" => "4",
        "VALUE" => "",
        "DEFAULT" => [
            "PROVIDER_KEY" => "ORDER",
            "PROVIDER_VALUE" => "ID"
        ],
        "TYPE" => ""
    ],
    "TRANSACTION_CURRENCY" => [
        "NAME" => 'Валюта',
        "DESCR" => 'Валюта',
        "SORT" => "5",
        "VALUE" => "UZS",
        "DEFAULT" => [
            "PROVIDER_KEY" => "ORDER",
            "PROVIDER_VALUE" => "CURRENCY",
        ],
        "TYPE" => ""
    ],
    "RETURN_URL" => [
        "NAME" => 'URL на который следует перенаправить покупателя после завершения оплаты',
        "DESCR" => 'URL на который следует перенаправить покупателя после завершения оплаты',
        "SORT" => "6",
        "VALUE" => "https://{$_SERVER['SERVER_NAME']}/oson/payment/callback.php",
        "DEFAULT" => [
            "PROVIDER_VALUE" => "https://{$_SERVER['SERVER_NAME']}/oson/payment/callback.php",
        ],
        "TYPE" => ""
    ],
    "TRANSACTION_AMOUNT" => [
        "NAME" => 'Сумма заказа',
        "DESCR" => 'Сумма заказа',
        "SORT" => "8",
        "VALUE" => "",
        "DEFAULT" => [
            "PROVIDER_KEY" => "ORDER",
            "PROVIDER_VALUE" => "SHOULD_PAY"
        ],
        "TYPE" => ""
    ],
    "TRANSACTION_LIFETIME" => [
        "NAME" => 'Время жизни заказа',
        "DESCR" => 'Время жизни платежа с момента создания (в минутах). / default 10days / min 30, max 14400',
        "SORT" => "9",
        "VALUE" => "14400",
        "DEFAULT" => [
            "PROVIDER_VALUE" => '14400',
        ],
        "TYPE" => ""
    ],
    "TRANSACTION_NOTE" => [
        "NAME" => 'Комментарий',
        "DESCR" => 'Комментарий к счету (не более 128 символов)',
        "SORT" => "10",
        "VALUE" => "",
        "TYPE" => ""
    ],
    "LANG" => [
        "NAME" => 'Язык',
        "DESCR" => 'ru|uz|en',
        "SORT" => "11",
        "VALUE" => "ru",
        "DEFAULT" => array(
            "PROVIDER_VALUE" => 'ru',
        ),
        "TYPE" => ""
    ],
    "NOTIFY_URL" => [
        "NAME" => "https://{$_SERVER['SERVER_NAME']}/oson/payment/notify.php",
        "DESCR" => 'Эта ссылка указывается в кабинете мерчанта',
        "SORT" => "12",
        "VALUE" => "https://{$_SERVER['SERVER_NAME']}/oson/payment/notify.php",
        "DEFAULT" => [
            "PROVIDER_VALUE" => "https://{$_SERVER['SERVER_NAME']}/oson/payment/notify.php",
        ]
    ],
    "SUCCESS_PAGE" => [
        "NAME" => 'Страница успешной оплаты',
        "DESCR" => 'Если заказ был оплачен',
        "SORT" => "13",
        "VALUE" => "https://{$_SERVER['SERVER_NAME']}/oson/payment/success/",
        "DEFAULT" => [
            "PROVIDER_VALUE" => "https://{$_SERVER['SERVER_NAME']}/oson/payment/success/",
        ]
    ],
    "CANCEL_PAGE" => [
        "NAME" => 'Страница отклонение оплаты',
        "DESCR" => 'Если заказ был отменен',
        "SORT" => "14",
        "VALUE" => "https://{$_SERVER['SERVER_NAME']}/oson/payment/cancel/",
        "DEFAULT" => [
            "PROVIDER_VALUE" => "https://{$_SERVER['SERVER_NAME']}/oson/payment/cancel/",
    ]
    ],
];