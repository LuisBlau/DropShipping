<?php
namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Activity;

use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Psr7;
use Carbon\Carbon;

use App\Models\Ebaysellerproduct;
use App\Models\Beautyfortnewstock;
use App\Models\Beautyfortremovedstock;

class EbayController
{
    public function addproduct(Request $request)
    {
        $app_id  = 'adaezean-dropship-PRD-21da2ce59-b597fea5';
        $dev_id = '2c5db4de-2e9e-48eb-9c4b-1a345eb072f6';
        $cert_id = 'PRD-1da2ce59f123-75c8-48d3-9079-d5b5';

        $compatibility_level = 1125;
        $site_id = 0;
        $call_name = 'AddFixedPriceItem';
        $ebay_api_url = 'https://api.ebay.com/ws/api.dll';
        $ebay_token = 'AgAAAA**AQAAAA**aAAAAA**JVAeYQ**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6MEmIupC5eGqQmdj6x9nY+seQ**9ccGAA**AAMAAA**/QaFW7iMbja7yQaPnpMKf66I4qsIDzXKgmTrQEjuTfhjbp4ngQ39ZG5lsU+/LW/Pjn0/VnCjJqsfloZkn3ZP0hv/Bsr1tHNVA13JmJtDaEL0QhQMxLNClR2YB64ZCmQcXFQaI35+Mqd77q4usiGUg/NSEdTyZhQ+JVQq2y40jQUppM9t8nsyuCm0nc27OnoagxfcMmxgxTKF8twTUKhzd+1PbOMLWVdkVxTAmDhWXnCUr2oSKXdBhJi7JOm2+P5eqY/pUeIDoNDR8rAqSsI2jNgmaGCNC84K0htk0nP0PO2llP1kCSowhyhJ+qlfW2g7a9Tg5ctA6b25v74uEhZiA8Hkae1egKGmNIs2P1VHRXYH7FxqNVACccRZl2AqWm16ctiqJuMUaY7jV4Ynnm/DEST70rtZDSQ8enZpW+8jd6KCtIHb61MXfbzvkNmhUBmFB1k9amGPElgfvtbiMgTF0G35BA0pEzb24qr2sHm3dr8SAsHXoTUZSCZrClZQnBeUMoLuxfrbVg1frLEeRcgrz5hcfqkPzxtx8ivLbdq7VB5N7C0WYnC7GY7pOs8X9rdgFXvFjBK3cNrVXr32fEwkA4lXHmDPXWGsOv3rFjNvH3N9UhBVTL6DssWXqOG8c5hq734r57yaq4Uvf9jfh7jpiqgvkrwTykTj4QqDXYAjeW+shpl6GRwX9wxO6xAIVztgwUHcyhYkDWh1Jsk0ZmlBDZtWmWx4KGtQJOZJBk0WqrYkcxds3iSOZsbgvILwOd58';

        $newstocks = Beautyfortnewstock::get()->toArray();
        foreach($newstocks as $newstock) {
            if(!$newstock['high_res_image_url']) continue;

            $uuid = md5(uniqid(rand(), true));
            $price = $newstock['rrp']?$newstock['rrp']:$newstock['price']*3;
            $post_data = '<?xml version="1.0" encoding="utf-8"?>
            <AddFixedPriceItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
              <RequesterCredentials>
                <eBayAuthToken>'.$ebay_token.'</eBayAuthToken>
              </RequesterCredentials>
              <ErrorLanguage>en_US</ErrorLanguage>
              <WarningLevel>High</WarningLevel>
              <Item>
                <Title>'.$newstock['full_name'].'</Title>
                <Description>'.$newstock['full_name'].'</Description>
                <PrimaryCategory>
                  <CategoryID>11848</CategoryID>
                </PrimaryCategory>
                <StartPrice>'.$price.'</StartPrice>
                <ConditionID>1000</ConditionID>
                <Country>GB</Country>
                <Currency>USD</Currency>
                <DispatchTimeMax>1</DispatchTimeMax>
                <ListingDuration>GTC</ListingDuration>
                <ListingType>FixedPriceItem</ListingType>
                <PaymentMethods>PayPal</PaymentMethods>
                <PayPalEmailAddress>adaezeuber@gmail.com</PayPalEmailAddress>
                <PictureDetails>
                  <PictureURL>'.$newstock['high_res_image_url'].'</PictureURL>
                </PictureDetails>
                <PostalCode>G2 1BP</PostalCode>
                <ItemSpecifics>
                    <NameValueList>
                        <Name>Brand</Name>
                        <Value>'.$newstock['brand'].'</Value>
                    </NameValueList>
                    <NameValueList>
                        <Name>Fragrance Name</Name>
                        <Value>'.$newstock['collection'].'</Value>
                    </NameValueList>
                    <NameValueList>
                        <Name>Type</Name>
                        <Value>'.$newstock['collection'].'</Value>
                    </NameValueList>
                    <NameValueList>
                        <Name>Volume</Name>
                        <Value>0.0</Value>
                    </NameValueList>
                </ItemSpecifics>
                <Quantity>'.$newstock['stock_level'].'</Quantity>
                <ReturnPolicy>
                  <ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>
                  <RefundOption>MoneyBack</RefundOption>
                  <ReturnsWithinOption>Days_30</ReturnsWithinOption>
                  <ShippingCostPaidByOption>Buyer</ShippingCostPaidByOption>
                </ReturnPolicy>
                <ShippingDetails>
                    <ShippingType>Flat</ShippingType>
                    <ShippingServiceOptions>
                    <ShippingServicePriority>1</ShippingServicePriority>
                    <ShippingService>UPSGround</ShippingService>
                    <FreeShipping>true</FreeShipping>
                    <ShippingServiceAdditionalCost currencyID="USD">0.00</ShippingServiceAdditionalCost>
                    </ShippingServiceOptions>
                </ShippingDetails>
                <UUID>'.$uuid.'</UUID>
                <Site>US</Site>
              </Item>
            </AddFixedPriceItemRequest>';

            $ebayapiheader = array (
                "X-EBAY-API-COMPATIBILITY-LEVEL: $compatibility_level",
                "X-EBAY-API-DEV-NAME: $dev_id",
                "X-EBAY-API-APP-NAME: $app_id",
                "X-EBAY-API-CERT-NAME: $cert_id",
                "X-EBAY-API-SITEID: $site_id",
                "X-EBAY-API-CALL-NAME: ".$call_name);

            $ch = curl_init();
            $res= curl_setopt ($ch, CURLOPT_URL,$ebay_api_url);


            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);

            curl_setopt ($ch, CURLOPT_HEADER, 0); // 0 = Don't give me the return header
            curl_setopt($ch, CURLOPT_HTTPHEADER, $ebayapiheader); // Set this for eBayAPI
            curl_setopt($ch, CURLOPT_POST, 1); // POST Method
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); //My XML Request
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            $xmlString = curl_exec ($ch); //Send the request
            curl_close ($ch); // Close the connection

            $xmlObject = simplexml_load_string($xmlString);
            $json = json_encode($xmlObject);
            $array = json_decode($json, true);
//dd($array);exit;
        }
    }
    public function removeproduct(Request $request)
    {
        $app_id  = 'adaezean-dropship-PRD-21da2ce59-b597fea5';
        $dev_id = '2c5db4de-2e9e-48eb-9c4b-1a345eb072f6';
        $cert_id = 'PRD-1da2ce59f123-75c8-48d3-9079-d5b5';

        $compatibility_level = 1125;
        $site_id = 0;
        $call_name = 'EndItems';
        $ebay_api_url = 'https://api.ebay.com/ws/api.dll';
        $ebay_token = 'AgAAAA**AQAAAA**aAAAAA**JVAeYQ**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6MEmIupC5eGqQmdj6x9nY+seQ**9ccGAA**AAMAAA**/QaFW7iMbja7yQaPnpMKf66I4qsIDzXKgmTrQEjuTfhjbp4ngQ39ZG5lsU+/LW/Pjn0/VnCjJqsfloZkn3ZP0hv/Bsr1tHNVA13JmJtDaEL0QhQMxLNClR2YB64ZCmQcXFQaI35+Mqd77q4usiGUg/NSEdTyZhQ+JVQq2y40jQUppM9t8nsyuCm0nc27OnoagxfcMmxgxTKF8twTUKhzd+1PbOMLWVdkVxTAmDhWXnCUr2oSKXdBhJi7JOm2+P5eqY/pUeIDoNDR8rAqSsI2jNgmaGCNC84K0htk0nP0PO2llP1kCSowhyhJ+qlfW2g7a9Tg5ctA6b25v74uEhZiA8Hkae1egKGmNIs2P1VHRXYH7FxqNVACccRZl2AqWm16ctiqJuMUaY7jV4Ynnm/DEST70rtZDSQ8enZpW+8jd6KCtIHb61MXfbzvkNmhUBmFB1k9amGPElgfvtbiMgTF0G35BA0pEzb24qr2sHm3dr8SAsHXoTUZSCZrClZQnBeUMoLuxfrbVg1frLEeRcgrz5hcfqkPzxtx8ivLbdq7VB5N7C0WYnC7GY7pOs8X9rdgFXvFjBK3cNrVXr32fEwkA4lXHmDPXWGsOv3rFjNvH3N9UhBVTL6DssWXqOG8c5hq734r57yaq4Uvf9jfh7jpiqgvkrwTykTj4QqDXYAjeW+shpl6GRwX9wxO6xAIVztgwUHcyhYkDWh1Jsk0ZmlBDZtWmWx4KGtQJOZJBk0WqrYkcxds3iSOZsbgvILwOd58';

        //Please set the end Item ID
        $item_id = '294345048769';
        $request_xml= '<?xml version="1.0" encoding="utf-8"?>
                    <EndItemsRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                        <EndItemRequestContainer>
                        <MessageID>1</MessageID>
                        <EndingReason>NotAvailable</EndingReason>
                        <ItemID>'.$item_id.'</ItemID>
                        </EndItemRequestContainer>
                        <RequesterCredentials>
                        <eBayAuthToken>'.$ebay_token.'</eBayAuthToken>
                        </RequesterCredentials>
                        <WarningLevel>High</WarningLevel>
                    </EndItemsRequest>';
        $headers = array (
                "X-EBAY-API-COMPATIBILITY-LEVEL: $compatibility_level",
                "X-EBAY-API-DEV-NAME: $dev_id",
                "X-EBAY-API-APP-NAME: $app_id",
                "X-EBAY-API-CERT-NAME: $cert_id",
                "X-EBAY-API-SITEID: $site_id",
                "X-EBAY-API-CALL-NAME: ".$call_name);

            $curl = curl_init();
            $res= curl_setopt ($curl, CURLOPT_URL,$ebay_api_url);
            curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt ($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request_xml);
            curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
            $results = curl_exec ($curl);

            curl_close ($curl);
            print_r($results);
    }
    public function sellerproduct(Request $request)
    {
        $today =  date('Y-m-d H:i:s');
        $start_time = date('Y-m-d H:i:s', strtotime($today . ' - 10 day'));
        $start_time = str_replace(" ","T",$start_time);
        $end_time = date('Y-m-d H:i:s', strtotime($today . ' + 90 day'));
        $end_time = str_replace(" ","T",$end_time);

        $app_id  = 'adaezean-dropship-PRD-21da2ce59-b597fea5';
        $dev_id = '2c5db4de-2e9e-48eb-9c4b-1a345eb072f6';
        $cert_id = 'PRD-1da2ce59f123-75c8-48d3-9079-d5b5';

        $compatibility_level = 1125;
        $site_id = 0;
        $call_name = 'GetSellerList';
        $ebay_api_url = 'https://api.ebay.com/ws/api.dll';
        $ebay_token = 'AgAAAA**AQAAAA**aAAAAA**JVAeYQ**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6MEmIupC5eGqQmdj6x9nY+seQ**9ccGAA**AAMAAA**/QaFW7iMbja7yQaPnpMKf66I4qsIDzXKgmTrQEjuTfhjbp4ngQ39ZG5lsU+/LW/Pjn0/VnCjJqsfloZkn3ZP0hv/Bsr1tHNVA13JmJtDaEL0QhQMxLNClR2YB64ZCmQcXFQaI35+Mqd77q4usiGUg/NSEdTyZhQ+JVQq2y40jQUppM9t8nsyuCm0nc27OnoagxfcMmxgxTKF8twTUKhzd+1PbOMLWVdkVxTAmDhWXnCUr2oSKXdBhJi7JOm2+P5eqY/pUeIDoNDR8rAqSsI2jNgmaGCNC84K0htk0nP0PO2llP1kCSowhyhJ+qlfW2g7a9Tg5ctA6b25v74uEhZiA8Hkae1egKGmNIs2P1VHRXYH7FxqNVACccRZl2AqWm16ctiqJuMUaY7jV4Ynnm/DEST70rtZDSQ8enZpW+8jd6KCtIHb61MXfbzvkNmhUBmFB1k9amGPElgfvtbiMgTF0G35BA0pEzb24qr2sHm3dr8SAsHXoTUZSCZrClZQnBeUMoLuxfrbVg1frLEeRcgrz5hcfqkPzxtx8ivLbdq7VB5N7C0WYnC7GY7pOs8X9rdgFXvFjBK3cNrVXr32fEwkA4lXHmDPXWGsOv3rFjNvH3N9UhBVTL6DssWXqOG8c5hq734r57yaq4Uvf9jfh7jpiqgvkrwTykTj4QqDXYAjeW+shpl6GRwX9wxO6xAIVztgwUHcyhYkDWh1Jsk0ZmlBDZtWmWx4KGtQJOZJBk0WqrYkcxds3iSOZsbgvILwOd58';

        $res = [];
        $PageNumber=0;
        $products = [];
        do {
            $PageNumber++;
            $request_xml = '<?xml version="1.0" encoding="utf-8"?>
                <GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                <RequesterCredentials>
                    <eBayAuthToken>'.$ebay_token.'</eBayAuthToken>
                </RequesterCredentials>
                <ErrorLanguage>en_US</ErrorLanguage>
                <WarningLevel>High</WarningLevel>
                <EndTimeFrom>'.$start_time.'.005Z</EndTimeFrom>
                <EndTimeTo>'.$end_time.'.005Z</EndTimeTo>
                <GranularityLevel>Fine</GranularityLevel>
                <Pagination>
                    <EntriesPerPage>200</EntriesPerPage>
                    <PageNumber>'.$PageNumber.'</PageNumber>
                </Pagination>
                </GetSellerListRequest>';
            $headers = array (
                "X-EBAY-API-COMPATIBILITY-LEVEL: $compatibility_level",
                "X-EBAY-API-DEV-NAME: $dev_id",
                "X-EBAY-API-APP-NAME: $app_id",
                "X-EBAY-API-CERT-NAME: $cert_id",
                "X-EBAY-API-SITEID: $site_id",
                "X-EBAY-API-CALL-NAME: ".$call_name);

            try {
                $curl = curl_init();
                $res= curl_setopt ($curl, CURLOPT_URL,$ebay_api_url);
                curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt ($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request_xml);
                curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
                $xmlString = curl_exec ($curl);
                curl_close ($curl);

                $xmlObject = simplexml_load_string($xmlString);

                $json = json_encode($xmlObject);
                $array = json_decode($json, true);

                if(isset($array['ItemArray']['Item'])) {
                    $res = $array['ItemArray']['Item'];
                } else {
                    $res = [];
                }

                if(count($res)>0) {
                    foreach($res as $product) {
                        $products[] = [
                            'title' => $product['Title'],
                            'itemid' => $product['ItemID'],
                            'uuid' => $product['UUID'] ?? '',
                            'startprice' => $product['StartPrice'],
                            'currency' => $product['Currency'],
                            'quantity' => $product['Quantity'],
                            'quantitysold' => $product['SellingStatus']['QuantitySold'],
                            'autopay' => $product['AutoPay'],
                            'country' => $product['Country']
                        ];
                    }

                }
            } catch (ServerException $exception) {
                //$res = $exception->getResponse()->getBody()->getContents();
                //return response()->json($res, 500);
            }
            echo count($res);
            echo '<br>';
        } while(count($res)>0);

        if(count($products)>0) {
            Ebaysellerproduct::truncate();
            foreach (array_chunk($products, 1000) as $t)
            {
                DB::table('ebaysellerproducts')->insert($t);
            }
        }
        return response()->json(true, 500);
    }
}
