<?php
/**
 * Разработчик: Oson
 * Сайт: https://oson.uz/
 * Документация к api: https://oson.uz/docs/
 */

namespace Oson\Payment;

use Bitrix\Main\{SystemException, Loader, UserPhoneAuthTable, UserTable};
use \Bitrix\Sale;

Loader::includeModule('main');
Loader::includeModule('sale');
Loader::includeModule('currency');
Loader::includeModule('catalog');

/**
 * Class Api
 * @package Oson\Payment
 */
class Api
{
    const CREATE_TRANSACTION = 'https://core.oson.uz:8443/api/invoice/create/',
          CHECK_TRANSACTION_STATUS = 'https://core.oson.uz:8443/api/invoice/status/';
    /**
     * Создаем транзакцию в Oson
     * @param string $token
     * @param array $params
     * @return array|bool|string
     * @throws \Bitrix\Main\ArgumentException
     */
    protected static function send(string $path, string $token, array $params)
    {
        if(empty($params)) return [
            'error' => 'Params is empty'
        ];

        $httpClient = new \Bitrix\Main\Web\HttpClient([
            // Устанавливаем Хедеры
            'headers' => [
                'Content-Type' => 'application/json',
                'token' => $token
            ]
        ]);

        // Отправляем запрос через curl
        return $httpClient->post($path, json_encode($params));
    }


    /**
     * Запуск
     * @param array $params
     * @return array
     * @throws \Bitrix\Main\SystemException
     */
    public static function run(array $params)
    {
        try
        {
            /**
             * Получаем из Рекуеста ID заказа
             * */
            $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->toArray();
            if(!$request['ORDER_ID'])
            {
                return [
                    'message' => 'Заказ не найден!'
                ];
            }

            /**
             * Получаем объект заказа
             * */
            $order = Sale\Order::load($request['ORDER_ID']);

            /**
             * Получаем данные о ПТ
             * */
            $paySystemAction = \CSalePaySystemAction::GetList(false, ['=ID' => $order->getField('PAY_SYSTEM_ID')])->Fetch();
            $paySystemParams = unserialize($paySystemAction['PARAMS']);

            /**
             * Формируем запрос и отправляем
             * */
            $data = self::send(self::CHECK_TRANSACTION_STATUS, $paySystemParams['OSON_AUTH_KEY']['VALUE'], [
                'merchant_id' => $paySystemParams['OSON_MERCHANT_ID']['VALUE'],
                'transaction_id' => $order->getId(),
            ]);

            /**
             * Проверка статуса
             * */
            if($data['status'] == 'PAID') // если заказ оплачен
            {
                \CSaleOrder::PayOrder($order->getId(), "Y", false, false, 0, [
                    "DATE_PAYED" => date('d.m.Y H:i:s'),
                    "PAY_VOUCHER_DATE" => date('d.m.Y H:i:s')
                ]);
                \CSaleOrder::StatusOrder($order->getId(), "P");

                if(!empty($paySystemParams['SUCCESS_PAGE']['VALUE']))
                {
                    LocalRedirect($paySystemParams['SUCCESS_PAGE']['VALUE']);
                }
            }
            elseif($data['status'] == 'DECLINED') // если заказ отменен
            {
                \CSaleOrder::PayOrder($order->getId(), "N", false, false, 0);
                \CSaleOrder::CancelOrder($order->getId(), "Y", $data['message']);

                if(!empty($paySystemParams['CANCEL_PAGE']['VALUE']))
                {
                    LocalRedirect($paySystemParams['CANCEL_PAGE']['VALUE']);
                }
            }

        } catch (SystemException $e) {
            echo $e->getMessage();
        }
    }


    /**
     * Уведомления об оплате заказа
     * @param array $params
     * @return array
     * @throws \Bitrix\Main\SystemException
     */
    public static function notify()
    {
        try
        {
            /**
             * Получаем из Рекуеста ID заказа
             * */
            $requestBody = json_decode(\Bitrix\Main\HttpRequest::getInput(), JSON_OBJECT_AS_ARRAY);


			if(!$requestBody['transaction_id'])
			{
			    return [
			        'message' => 'Заказ не найден!'
			    ];
			}

			/**
			 * Проверка статуса
			 * */
			if($requestBody['status'] == 'PAID') // если заказ оплачен
			{
			    \CSaleOrder::PayOrder($requestBody['transaction_id'], "Y", false, false, 0, [
			        "DATE_PAYED" => date('d.m.Y H:i:s'),
			        "PAY_VOUCHER_DATE" => date('d.m.Y H:i:s')
			    ]);
			    \CSaleOrder::StatusOrder($requestBody['transaction_id'], "P");
			}
			elseif($requestBody['status'] == 'DECLINED') // если заказ отменен
			{
			    \CSaleOrder::PayOrder($requestBody['transaction_id'], "N", false, false, 0);
			    \CSaleOrder::CancelOrder($requestBody['transaction_id'], "Y", 'Отменен');
			}

        } catch (SystemException $e) {
            return $e->getMessage();
        }

        return json_encode($data);
    }

    /**
     * Создание транзакции
     * */
    public static function createTransaction()
    {
        $response = [];
        try
        {
            /**
             * Получаем из Рекуеста ID заказа
             * */
            $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->toArray();
            if(!$request['ORDER_ID'])
            {
                return json_encode([
                    'message' => 'Заказ не найден!'
                ]);
            }

            /**
             * Получаем объект заказа
             * */
            $order = Sale\Order::load($request['ORDER_ID']);

            /**
             * Получаем данные о пользователе
             * */
            $userData = UserTable::getById($order->getUserId())->fetch();

            /**
             * Получаем данные телефон пользователя
             * */
            $userPhone = UserPhoneAuthTable::getList(['filter' => ['USER_ID' => $userData['ID']]])->fetch();

            /**
             * Получаем данные о ПТ
             * */
            $paySystemAction = \CSalePaySystemAction::GetList(false, ['=ID' => $order->getField('PAY_SYSTEM_ID')])->Fetch();
            $paySystemParams = unserialize($paySystemAction['PARAMS']);

            /**
             * Формируем запрос и отправляем
             * */
            $data = self::send(self::CREATE_TRANSACTION, $paySystemParams['OSON_AUTH_KEY']['VALUE'], [
                'merchant_id' => $paySystemParams['OSON_MERCHANT_ID']['VALUE'],
                'transaction_id' => $order->getId(),
                'phone' => $userPhone['PHONE_NUMBER'] ? str_replace('+', '', $userPhone['PHONE_NUMBER']) : '',
                'user_account' => $userData['ID'],
                'amount' => number_format($order->getPrice(), 2, '.', '' ),
                'currency' => $paySystemParams['TRANSACTION_CURRENCY']['VALUE'] ? $paySystemParams['TRANSACTION_CURRENCY']['VALUE'] : \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                'comment' => $paySystemParams['TRANSACTION_NOTE']['VALUE'] ? $paySystemParams['TRANSACTION_NOTE']['VALUE'] : $order->getField('USER_DESCRIPTION'),
                'return_url' => $paySystemParams['RETURN_URL']['VALUE'] . "?ORDER_ID={$order->getId()}",
                'lifetime' => $paySystemParams['TRANSACTION_LIFETIME']['VALUE'],
                'lang' => $paySystemParams['LANG']['VALUE'] ? $paySystemParams['LANG']['VALUE'] : LANGUAGE_ID,
            ]);

            $response = json_decode($data, JSON_OBJECT_AS_ARRAY);

            if(!empty($response['error_code']) && !empty($response['message']))
            {
                $response = [
                    'status' => 9,
                    'error_text' => $response['message']
                ];
            }

            if(isset($response['transaction_id'], $response['pay_url']) && !empty($response['pay_url']))
            {
                $response = [
                    'status' => true,
                    'pay_url' => $response['pay_url'],
                    'error_text' => ''
                ];
            }

        } catch (SystemException $e) {
            return $e->getMessage();
        }
        return json_encode($response);
    }
}