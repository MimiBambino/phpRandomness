#!/usr/bin/env php
<?php
echo "Hello world\n";

// ensure PHP cURL library is installed
if(function_exists('curl_init'))
{
 // set cURL resource
 $curl = curl_init('http://www.google.com');
 // set return transfer as string to true
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 // set variable with response string
 $request_response = htmlentities(curl_exec($curl));
 // close cURL resource
 curl_close($curl);
 // display response string
 echo '<pre>' . print_r($request_response, true) . '</pre>';
}
else // PHP cURL library not installed
{
 echo 'Please install PHP cURL library';
}