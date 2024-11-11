<?php

    namespace CGLicense;

    class License {

        private static $serverName;
        private static $licenseKey;
        private static $productId;
        private static $apiUrl = "https://license.callvgroup.net/api/client/license";

        public static function initialize() {
            if (!isset($_SERVER["SERVER_NAME"], $_SERVER["LICENSE_KEY"], $_SERVER["PRODUCT_ID"])) {
                header("Location: https://wa.me/62895392168277");
                exit();
            }

            self::$serverName = $_SERVER["SERVER_NAME"];
            self::$licenseKey = $_SERVER["LICENSE_KEY"];
            self::$productId = $_SERVER["PRODUCT_ID"];
        }

        public static function run() {
            self::initialize();

            $request = self::curlInstance(self::$apiUrl, [
                "product_id" => self::$productId,
                "license_key" => self::$licenseKey,
                "domain" => self::$serverName
            ], [
                "Accept: application/json"
            ]);

            if (!$request || !$request["status"]) {
                header("Location: https://wa.me/62895392168277");
                exit();
            }
        }

        private static function curlInstance($url, $params = [], $headers = []) {
            if (!empty($params)) {
                $url .= '?' . http_build_query($params);
            }

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPGET, true);

            if (!empty($headers)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new \Exception('cURL Error: ' . curl_error($ch));
            }

            curl_close($ch);

            return $response ? json_decode($response, true) : null;
        }
    }
