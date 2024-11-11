<?php

    namespace CGLicense;

    class License {

        private $serverName;
        private $licenseKey;
        private $productId;
        private $apiUrl;

        public function __construct() {
            if (!isset($_SERVER["SERVER_NAME"], $_SERVER["LICENSE_KEY"], $_SERVER["PRODUCT_ID"])) {
                header("Location: https://wa.me/62895392168277");
                exit();
            }

            $this->serverName = $_SERVER["SERVER_NAME"];
            $this->licenseKey = $_SERVER["LICENSE_KEY"];
            $this->productId = $_SERVER["PRODUCT_ID"];
            $this->apiUrl = "https://license.callvgroup.net/api/client/license";
        }

        public function run() {
            $request = $this->curlInstance($this->apiUrl, [
                "product_id" => $this->productId,
                "license_key" => $this->licenseKey
            ], [
                "Accept: application/json"
            ]);

            if (!$request || !$request["status"]) {
                header("Location: https://wa.me/62895392168277");
                exit();
            }
        }

        private function curlInstance($url, $params = [], $headers = []) {
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
