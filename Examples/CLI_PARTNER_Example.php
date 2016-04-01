#!/usr/bin/php
<?php
/**
* Example of PARTNER Weather station API
* If you need more details, please take a glance at https://dev.netatmo.com/doc
*/

define('__ROOT__', dirname(dirname(__FILE__)));
require_once (__ROOT__.'/src/Netatmo/autoload.php');
require_once 'Utils.php';
require_once 'Config.php';

//App client configuration
$config = array("client_id" => $client_id,
                "client_secret" => $client_secret,
                "username" => $test_username,
                "password" => $test_password);

$client = new Netatmo\Clients\NAWSApiClient($config);

//Authentication with Netatmo server (OAuth2)
try
{
    $tokens = $client->getAccessToken();
}
catch(Netatmo\Exceptions\NAClientException $ex)
{
    handleError("An error happened while trying to retrieve your tokens: " .$ex->getMessage()."\n", TRUE);
}
try
{
    $deviceList = $client->getPartnerDevices();
}
catch(Netatmo\Exceptions\NAClientException $ex)
{
    handleError("An error occured while retrieving device list: ". $ex->getMessage()."\n", TRUE);
}

if(!isset($deviceList['weather_stations']) || empty($deviceList['weather_stations']))
{
    echo "No Weather stations affiliated to partner app";
}
else
{
    try
    {
        $data = $client->getData($deviceList['weather_stations'][0]);
        printWSBasicInfo($data['devices'][0]);
    }
    catch(Netatmo\Exceptions\NAClientException $ex)
    {
        handleError("An error occured while retrieving device data " . $ex->getMessage() . "\n", TRUE);
    }
}
