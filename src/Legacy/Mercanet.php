<?php

/**
 * This file is a part of OpenSource Mercanet payment library adjusted for purposes of this project.
 * We are not the authors of the core logic of this class.
 */

namespace BitBag\MercanetBnpParibasPlugin\Legacy;

use function Symfony\Component\String\s;

/**
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl> READ THE FILE HEADER.
 */
class Mercanet
{
    const TEST = "https://payment-webinit-mercanet.test.sips-services.com/rs-services/v2/paymentInit";
    const SIMULATION = "https://payment-webinit.simu.mercanet.bnpparibas.net/rs-services/v2/paymentInit";
    const PRODUCTION = "https://payment-webinit.mercanet.bnpparibas.net/rs-services/v2/paymentInit";

    const INTERFACE_VERSION = "IR_WS_2.17";
    const INSTALMENT = "INSTALMENT";
    const BYPASS3DS_ALL = "ALL";
    const BYPASS3DS_MERCHANTWALLET = "MERCHANTWALLET";

    private array $brandsmap = array(
        'ACCEPTGIRO' => 'CREDIT_TRANSFER',
        'AMEX' => 'CARD',
        'BCMC' => 'CARD',
        'BUYSTER' => 'CARD',
        'BANK CARD' => 'CARD',
        'CB' => 'CARD',
        'IDEAL' => 'CREDIT_TRANSFER',
        'INCASSO' => 'DIRECT_DEBIT',
        'MAESTRO' => 'CARD',
        'MASTERCARD' => 'CARD',
        'MASTERPASS' => 'CARD',
        'MINITIX' => 'OTHER',
        'NETBANKING' => 'CREDIT_TRANSFER',
        'PAYPAL' => 'CARD',
        'PAYLIB' => 'CARD',
        'REFUND' => 'OTHER',
        'SDD' => 'DIRECT_DEBIT',
        'SOFORT' => 'CREDIT_TRANSFER',
        'VISA' => 'CARD',
        'VPAY' => 'CARD',
        'VISA ELECTRON' => 'CARD',
        'CBCONLINE' => 'CREDIT_TRANSFER',
        'KBCONLINE' => 'CREDIT_TRANSFER'
    );

    /** @phpstan-ignore-next-line We should not change our business logic now*/
    private ShaComposer $secretKey;

    private string $pspURL = self::TEST;

    private string $responseData;

    private array $parameters = array();

    private array $pspFields = array(
        'amount', 'cardExpiryDate', 'cardNumber', 'cardCSCValue',
        'currencyCode', 'merchantId', 'interfaceVersion', 'sealAlgorithm',
        'transactionReference', 'keyVersion', 'paymentMeanBrand', 'customerLanguage',
        'billingAddress.city', 'billingAddress.company', 'billingAddress.country',
        'billingAddress', 'billingAddress.postBox', 'billingAddress.state',
        'billingAddress.street', 'billingAddress.streetNumber', 'billingAddress.zipCode',
        'billingContact.email', 'billingContact.firstname', 'billingContact.gender',
        'billingContact.lastname', 'billingContact.mobile', 'billingContact.phone',
        'customerAddress', 'customerAddress.city', 'customerAddress.company',
        'customerAddress.country', 'customerAddress.postBox', 'customerAddress.state',
        'customerAddress.street', 'customerAddress.streetNumber', 'customerAddress.zipCode',
        'customerEmail', 'customerContact', 'customerContact.email', 'customerContact.firstname',
        'customerContact.gender', 'customerContact.lastname', 'customerContact.mobile',
        'customerContact.phone', 'customerContact.title', 'expirationDate', 'automaticResponseUrl',
        'templateName', 'paymentMeanBrandList', 'instalmentData.number', 'instalmentData.datesList',
        'instalmentData.transactionReferencesList', 'instalmentData.amountsList', 'paymentPattern',
        'captureDay', 'captureMode', 'merchantTransactionDateTime', 'fraudData.bypass3DS', 'seal',
        'orderChannel', 'orderId', 'returnContext', 'transactionOrigin', 'merchantWalletId', 'paymentMeanId'
    );

    private array $requiredFields = array(
        'amount', 'currencyCode', 'interfaceVersion', 'keyVersion', 'merchantId', 'normalReturnUrl', 'orderChannel',
        'transactionReference'
    );

    public array $allowedlanguages = array(
        'nl', 'fr', 'de', 'it', 'es', 'cy', 'en'
    );

    private static array $currencies = array(
        'EUR' => '978', 'USD' => '840', 'CHF' => '756', 'GBP' => '826',
        'CAD' => '124', 'JPY' => '392', 'MXP' => '484', 'TRY' => '949',
        'AUD' => '036', 'NZD' => '554', 'NOK' => '578', 'BRC' => '986',
        'ARP' => '032', 'KHR' => '116', 'TWD' => '901', 'SEK' => '752',
        'DKK' => '208', 'KRW' => '410', 'SGD' => '702', 'XPF' => '953',
        'XOF' => '952'
    );

    public static function convertCurrencyToCurrencyCode(string $currency): string
    {
        if (!in_array($currency, array_keys(self::$currencies)))
            throw new \InvalidArgumentException("Unknown currencyCode $currency.");
        return self::$currencies[$currency];
    }

    public static function convertCurrencyCodeToCurrency(string $code): int|string|false
    {
        if (!in_array($code, array_values(self::$currencies)))
            throw new \InvalidArgumentException("Unknown Code $code.");
        return array_search($code, self::$currencies);
    }

    public static function getCurrencies(): array
    {
        return self::$currencies;
    }

    /** @phpstan-ignore-next-line We should not change our business logic now*/
    public function __construct(ShaComposer $secret)
    {
        $this->secretKey = $secret;
    }

    public function shaCompose(array $parameters): string
    {
        // compose SHA string
        $shaString = '';
        foreach ($parameters as $key => $value) {
            if ($key != 'keyVersion') {
                if (is_array($value)) {
                    /** @phpstan-ignore-next-line We should not change our business logic now*/
                    shaCompose($value);
                } else {
                    $shaString .= $value;
                }
            }
        }
        $shaString = str_replace("[", "", $shaString);
        $shaString = str_replace("]", "", $shaString);
        $shaString = str_replace("\",\"", "", $shaString);
        $shaString = str_replace("\"", "", $shaString);
        return $shaString;
    }

    public function getShaSign(): string
    {
        $this->validate();
        /** @phpstan-ignore-next-line We should not change our business logic now*/
        return hash_hmac('sha256', utf8_encode($this->shaCompose($this->toArray())), $this->secretKey);
    }

    public function getUrl(): string
    {
        return $this->pspURL;
    }

    public function setUrl(string $pspUrl): void
    {
        $this->validateUri($pspUrl);
        $this->pspURL = $pspUrl;
    }

    public function setNormalReturnUrl(string $url): void
    {
        $this->validateUri($url);
        $this->parameters['normalReturnUrl'] = $url;
    }

    public function setAutomaticResponseUrl(string $url): void
    {
        $this->validateUri($url);
        $this->parameters['automaticResponseUrl'] = $url;
    }

    public function setTransactionReference(string $transactionReference): void
    {
        if (preg_match('/[^a-zA-Z0-9_-]/', $transactionReference)) {
            throw new \InvalidArgumentException("TransactionReference cannot contain special characters");
        }
        $this->parameters['transactionReference'] = $transactionReference;
    }

    public function setAmount(int $amount): void
    {
        if (!is_int($amount)) {
            throw new \InvalidArgumentException("Integer expected. Amount is always in cents");
        }
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Amount must be a positive number");
        }
        $this->parameters['amount'] = $amount;

    }

    public function setMerchantId(string $merchantId): void
    {
        $this->parameters['merchantId'] = $merchantId;
    }

    public function setKeyVersion(string $keyVersion): void
    {
        $this->parameters['keyVersion'] = $keyVersion;
    }

    public function setCurrency(string $currency): void
    {
        if (!array_key_exists(strtoupper($currency), self::getCurrencies())) {
            throw new \InvalidArgumentException("Unknown currency");
        }
        $this->parameters['currencyCode'] = self::convertCurrencyToCurrencyCode($currency);
    }

    public function setLanguage(string $language): void
    {
        if (!in_array($language, $this->allowedlanguages)) {
            throw new \InvalidArgumentException("Invalid language locale");
        }
        $this->parameters['customerLanguage'] = $language;
    }

    public function setCustomerEmail(string $email): void
    {
        $this->parameters['customerEmail'] = $email;
    }

    public function setPaymentBrand(string $brand): void
    {
        $this->parameters['paymentMeanBrandList'] = '';
        if (!array_key_exists(strtoupper($brand), $this->brandsmap)) {
            throw new \InvalidArgumentException("Unknown Brand [$brand].");
        }
        $this->parameters['paymentMeanBrandList'] = strtoupper($brand);
    }

    public function setBillingContactEmail(string $email): void
    {
        if (strlen($email) > 50) {
            throw new \InvalidArgumentException("Email is too long");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Email is invalid");
        }
        $this->parameters['billingContact.email'] = $email;
    }

    public function setBillingAddressStreet(string $street): void
    {
        if (strlen($street) > 35) {
            throw new \InvalidArgumentException("street is too long");
        }
        $this->parameters['billingAddress.street'] = \Normalizer::normalize($street);
    }

    public function setBillingAddressStreetNumber(string $nr): void
    {
        if (strlen($nr) > 10) {
            throw new \InvalidArgumentException("streetNumber is too long");
        }
        $this->parameters['billingAddress.streetNumber'] = \Normalizer::normalize($nr);
    }

    public function setBillingAddressZipCode(string $zipCode): void
    {
        if (strlen($zipCode) > 10) {
            throw new \InvalidArgumentException("zipCode is too long");
        }
        $this->parameters['billingAddress.zipCode'] = \Normalizer::normalize($zipCode);
    }

    public function setBillingAddressCity(string $city): void
    {
        if (strlen($city) > 25) {
            throw new \InvalidArgumentException("city is too long");
        }
        $this->parameters['billingAddress.city'] = \Normalizer::normalize($city);
    }

    public function setBillingContactPhone(string $phone): void
    {
        if (strlen($phone) > 30) {
            throw new \InvalidArgumentException("phone is too long");
        }
        $this->parameters['billingContact.phone'] = $phone;
    }

    public function setBillingContactFirstname(string $firstname): void
    {
        $this->parameters['billingContact.firstname'] = str_replace(array("'", '"'), '', \Normalizer::normalize($firstname)); // replace quotes
    }

    public function setBillingContactLastname(string $lastname):void
    {
        $this->parameters['billingContact.lastname'] = str_replace(array("'", '"'), '', \Normalizer::normalize($lastname)); // replace quotes
    }

    public function setCaptureDay(string $number): void
    {
        if (strlen($number) > 2) {
            throw new \InvalidArgumentException("captureDay is too long");
        }
        $this->parameters['captureDay'] = $number;
    }

    public function setCaptureMode(string $value): void
    {
        if (strlen($value) > 20) {
            throw new \InvalidArgumentException("captureMode is too long");
        }
        $this->parameters['captureMode'] = $value;
    }

    public function setMerchantTransactionDateTime(string $value): void
    {
        if (strlen($value) > 25) {
            throw new \InvalidArgumentException("merchantTransactionDateTime is too long");
        }
        $this->parameters['merchantTransactionDateTime'] = $value;
    }

    public function setInterfaceVersion(string $value): void
    {
        $this->parameters['interfaceVersion'] = $value;
    }

    public function setSealAlgorithm(string $value): void
    {
        $this->parameters['sealAlgorithm'] = $value;
    }

    public function setOrderChannel(string $value): void
    {
        if (strlen($value) > 20) {
            throw new \InvalidArgumentException("orderChannel is too long");
        }
        $this->parameters['orderChannel'] = $value;
    }

    public function setOrderId(string $value): void
    {
        if (strlen($value) > 32) {
            throw new \InvalidArgumentException("orderId is too long");
        }
        $this->parameters['orderId'] = $value;
    }

    public function setReturnContext(string $value): void
    {
        if (strlen($value) > 255) {
            throw new \InvalidArgumentException("returnContext is too long");
        }
        $this->parameters['returnContext'] = $value;
    }

    public function setTransactionOrigin(string $value): void
    {
        if (strlen($value) > 20) {
            throw new \InvalidArgumentException("transactionOrigin is too long");
        }
        $this->parameters['transactionOrigin'] = $value;
    }

    public function setCardNumber(string $number): void
    {
        if (strlen($number) > 19) {
            throw new \InvalidArgumentException("cardNumber is too long");
        }
        if (strlen($number) < 12) {
            throw new \InvalidArgumentException("cardNumber is too short");
        }
        $this->parameters['cardNumber'] = $number;
    }

    public function setCardExpiryDate(string $date): void
    {
        if (strlen($date) != 6) {
            throw new \InvalidArgumentException("cardExpiryDate value is invalid");
        }
        $this->parameters['cardExpiryDate'] = $date;
    }

    public function setCardCSCValue(string $value): void
    {
        if (strlen($value) > 4) {
            throw new \InvalidArgumentException("cardCSCValue value is invalid");
        }
        $this->parameters['cardCSCValue'] = $value;
    }

    public function setFraudDataBypass3DS(string $value): void
    {
        if (strlen($value) > 128) {
            throw new \InvalidArgumentException("fraudData.bypass3DS is too long");
        }
        $this->parameters['fraudData.bypass3DS'] = $value;
    }

    public function setMerchantWalletId(string $wallet): void
    {
        if (strlen($wallet) > 21) {
            throw new \InvalidArgumentException("merchantWalletId is too long");
        }
        $this->parameters['merchantWalletId'] = $wallet;
    }

    public function setPaymentMeanId(string $value): void
    {
        if (strlen($value) > 6) {
            throw new \InvalidArgumentException("paymentMeanId is too long");
        }
        $this->parameters['paymentMeanId'] = $value;
    }

    public function setInstalmentDataNumber(string $number): void
    {
        if (strlen($number) > 2) {
            throw new \InvalidArgumentException("instalmentData.number is too long");
        }
        if (($number < 2) || ($number > 50)) {
            throw new \InvalidArgumentException("instalmentData.number invalid value : value must be set between 2 and 50");
        }
        $this->parameters['instalmentData.number'] = $number;
    }

    public function setInstalmentDatesList(string $datesList): void
    {
        $this->parameters['instalmentData.datesList'] = $datesList;
    }

    public function setInstalmentDataTransactionReferencesList(string $transactionReferencesList): void
    {
        $this->parameters['instalmentData.transactionReferencesList'] = $transactionReferencesList;
    }

    public function setInstalmentDataAmountsList(string $amountsList): void
    {
        $this->parameters['instalmentData.amountsList'] = $amountsList;
    }

    public function setPaymentPattern(string $paymentPattern): void
    {
        $this->parameters['paymentPattern'] = $paymentPattern;
    }

    public function __call(string $method, array $args): ?string
    {
        if (substr($method, 0, 3) == 'set') {
            $field = lcfirst(substr($method, 3));
            if (in_array($field, $this->pspFields)) {
                $this->parameters[$field] = $args[0];
                return null;
            }
        }

        if (substr($method, 0, 3) == 'get') {
            $field = lcfirst(substr($method, 3));
            if (array_key_exists($field, $this->parameters)) {
                return $this->parameters[$field];
            }
        }

        throw new \BadMethodCallException("Unknown method $method");
    }

    public function toArray(): array
    {
        ksort($this->parameters);
        return $this->parameters;
    }

    public function toParameterString(): string
    {
        ksort($this->parameters);

        $dataName = "";
        $parameterArray = array();
        $chaine = '{';
        foreach ($this->parameters as $key => $val) {
            $dataArray = explode(".", $key);
            if (count($dataArray) > 1) {
                if ($dataName == $dataArray[0]) {
                    $parameterArray[$dataArray[1]] = $val;
                } else {
                    if ($dataName != "") {
                        if (strlen($chaine) != 1) {
                            $chaine .= ",";
                        }
                        $chaine .= '"' . $dataName . '":' . json_encode($parameterArray);
                    }
                    unset($parameterArray);
                    $parameterArray = array();
                    $dataName = $dataArray[0];
                    $parameterArray[$dataArray[1]] = $val;
                }
            } else {
                if ($dataName != "") {
                    if (strlen($chaine) != 1) {
                        $chaine .= ",";
                    }
                    $chaine .= '"' . $dataName . '":' . json_encode($parameterArray);
                    $dataName = "";
                }
                if (strlen($chaine) != 1) {
                    $chaine .= ",";
                }
                $chaine .= '"' . $key . '":"' . $val . '"';
            }
        }
        if ($dataName != "") {
            if (strlen($chaine) != 1) {
                $chaine .= ",";
            }
            $chaine .= '"' . $dataName . '":' . json_encode($parameterArray);
        }

        $chaine .= ',"seal" : "' . $this->getShaSign() . '" }';
        $chaine = str_replace(":\"[", ":[", $chaine);
        $chaine = str_replace("]\"", "]", $chaine);
        $chaine = str_replace("\\\"", "\"", $chaine);
        return $chaine;
    }


    /** @phpstan-ignore-next-line We should not change our business logic now*/
    public static function createFromArray(ShaComposer $shaComposer, array $parameters): PaymentRequest|static
    {
        /** @phpstan-ignore-next-line Unsafe usage of new static() */
        $instance = new static($shaComposer);
        foreach ($parameters as $key => $value) {
            $instance->{"set$key"}($value);
        }
        return $instance;
    }

    public function validate(): void
    {
        foreach ($this->requiredFields as $field) {
            if (empty($this->parameters[$field])) {
                throw new \RuntimeException($field . " can not be empty");
            }
        }
    }

    protected function validateUri(string $uri): void
    {
        if (!filter_var($uri, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Uri is not valid");
        }
        if (strlen($uri) > 200) {
            throw new \InvalidArgumentException("Uri is too long");
        }
    }

    const SHASIGN_FIELD = "SEAL";

    const DATA_FIELD = "DATA";

    public function setResponse(array $httpRequest): void
    {
        $httpRequest = array_change_key_case($httpRequest, CASE_UPPER);
        $this->shaSign = $this->extractShaSign($httpRequest);
        $this->parameters = $this->filterRequestParameters($httpRequest);
    }

    private string $shaSign;

    /** @phpstan-ignore-next-line We should not change that now*/
    private string $dataString;

    /** @phpstan-ignore-next-line We should not change that now*/
    private string $responseRequest;

    /** @phpstan-ignore-next-line We should not change that now*/
    private array $parameterArray;

    private function filterRequestParameters(array $httpRequest): array
    {
        if (!array_key_exists(self::DATA_FIELD, $httpRequest) || $httpRequest[self::DATA_FIELD] == '') {
            throw new \InvalidArgumentException('Data parameter not present in parameters.');
        }
        $parameters = array();
        $this->responseData = $httpRequest[self::DATA_FIELD];
        $dataString = $httpRequest[self::DATA_FIELD];
        $this->dataString = $dataString;
        $dataParams = explode('|', $dataString);
        foreach ($dataParams as $dataParamString) {
            $dataKeyValue = explode('=', $dataParamString, 2);
            $parameters[$dataKeyValue[0]] = $dataKeyValue[1];
        }

        return $parameters;
    }

    public function getSeal(): string
    {
        return $this->shaSign;
    }

    private function extractShaSign(array $parameters): string
    {
        if (!array_key_exists(self::SHASIGN_FIELD, $parameters) || $parameters[self::SHASIGN_FIELD] == '') {
            throw new \InvalidArgumentException('SHASIGN parameter not present in parameters.');
        }

        return $parameters[self::SHASIGN_FIELD];
    }

    public function isValid(): bool
    {
        $resultat = false;

        $signature = $this->responseData;
        $compute = hash('sha256', utf8_encode($signature . $this->secretKey));
        if (strcmp($this->shaSign, $compute) == 0) {
            if ((strcmp($this->parameters['responseCode'], "00") == 0) || (strcmp($this->parameters['responseCode'], "60") == 0)) {
                $resultat = true;
            }
        }
        return $resultat;
    }

    function getXmlValueByTag(string $inXmlset, string $needle): ?string
    {
        $resource = xml_parser_create();
        xml_parse_into_struct($resource, $inXmlset, $outArray);
        xml_parser_free($resource);//Free an XML parser
        for ($i = 0; $i < count($outArray); $i++) {
            if ($outArray[$i]['tag'] == strtoupper($needle)) {
                $tagValue = $outArray[$i]['value'];
            }
        }
        return $tagValue ?? null;
    }

    public function getParam(string $key): string
    {
        return $this->parameterArray[$key];
    }

    public function getResponseRequest(): string
    {
        return $this->responseRequest;
    }

    public function executeRequest(): ?string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->toParameterString());
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept:application/json'));
        curl_setopt($ch, CURLOPT_PORT, 443);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        /** @var string $result */
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);

        if (!$result) {
            Print "curl error: " . curl_error($ch) . "\n";
            curl_close($ch);
            die();
        }

        if ($info['http_code'] != 200) {
            Print "service error: " . $info['http_code'] . "\n";
            Print "return: " . $result . "\n";
            curl_close($ch);
            die();
        }
        curl_close($ch);

        if (strlen($result) == 0) {
            Print "service did not sent back data\n";
            die();
        }
        $result_array = json_decode($result);

        if ($result_array->redirectionStatusCode == "00" ) {

            return "<html><body><form name=\"redirectForm\" method=\"POST\" action=\"" . $result_array->redirectionUrl . "\">" .
                "<input type=\"hidden\" name=\"redirectionVersion\" value=\"" . $result_array->redirectionVersion . "\">" .
                "<input type=\"hidden\" name=\"redirectionData\" value=\"" . $result_array->redirectionData . "\">" .
                "<noscript><input type=\"submit\" name=\"Go\" value=\"Click to continue\"/></noscript> </form>" .
                "<script type=\"text/javascript\"> document.redirectForm.submit(); </script>" .
                "</body></html>";
        }
        return null;
    }
}
