<?php

function aws_signed_request($params)
{
        /*
    Parameters:
        $region - the Amazon(r) region (ca,com,co.uk,de,fr,co.jp)
        $params - an array of parameters, eg. array("Operation"=>"ItemLookup",
                        "ItemId"=>"B000X9FLKM", "ResponseGroup"=>"Small")
        $public_key - your "Access Key ID"
        $private_key - your "Secret Access Key"
        $version (optional)
    */
    
    // some paramters
    $region = 'com';    
    $method = 'GET';
    $host = 'webservices.amazon.'.$region;
    $uri = '/onca/xml';
    $public_key = 'AKIAJYEJUMMIADYKMK3A';
    $private_key = 'Lj+qXJFbvn6MEOPUq6RghKPNKFJVtJNKgU9A7cTx';
    $associate_tag = 'wwwmimibambin-20';
    // additional parameters
    $params['Service'] = 'AWSECommerceService';
    $params['AWSAccessKeyId'] = $public_key;
    // GMT timestamp
    $params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
    // API version
    $params['Version'] = '2013-08-01';
    if ($associate_tag !== NULL) {
        $params['AssociateTag'] = $associate_tag;
    }
    
    // sort the parameters
    ksort($params);
    
    // create the canonicalized query
    $canonicalized_query = array();
    foreach ($params as $param=>$value)
    {
        $param = str_replace('%7E', '~', rawurlencode($param));
        $value = str_replace('%7E', '~', rawurlencode($value));
        $canonicalized_query[] = $param.'='.$value;
    }
    $canonicalized_query = implode('&', $canonicalized_query);
    
    // create the string to sign
    $string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;
    
    // calculate HMAC with SHA256 and base64-encoding
    $signature = base64_encode(hash_hmac('sha256', $string_to_sign, $private_key, TRUE));
    
    // encode the signature for the request
    $signature = str_replace('%7E', '~', rawurlencode($signature));
    
    // create request
    $request = 'http://'.$host.$uri.'?'.$canonicalized_query.'&Signature='.$signature;
    
    return $request;
}
?>
