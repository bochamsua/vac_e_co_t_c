<?php
require_once '../lib/simple_html_dom.php';
error_reporting(0);

$username = 'buixuanphong';
$password = '***';
$loginUrl = 'http://10.4.4.7/vi/dologin.asp';
$userAgent = 'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; GTB7.4; InfoPath.2; SV1; .NET CLR 3.3.69573; WOW64; en-US)';
$cookieFile = 'cookie.txt';

//init curl
$ch = curl_init();

curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookieFile);
curl_setopt($ch, CURLOPT_USERAGENT,  $userAgent); // empty user agents probably not accepted


//Set the URL to work with
curl_setopt($ch, CURLOPT_URL, $loginUrl);

// ENABLE HTTP POST
curl_setopt($ch, CURLOPT_POST, 1);

//Set the post parameters
curl_setopt($ch, CURLOPT_POSTFIELDS, 'txtUsername='.$username.'&txtPassword='.$password);

//Handle cookies for the login
//curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');

//Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
//not to print out the results of its query.
//Instead, it will return the results as a string return value
//from curl_exec() instead of the usual true/false.
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_AUTOREFERER,    1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER,    1);
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
//execute the request (the login)
curl_exec($ch);

//the login is now done and you can continue to get the
//protected content.

//set the URL to the protected file
//curl_setopt($ch, CURLOPT_URL, 'http://portal.vaeco.com.vn/portal/message/inbox.asp');

//execute the request
//$content = curl_exec($ch);

sleep(1);

header( 'refresh: 1; url=' . $_SERVER['PHP_SELF'] );

