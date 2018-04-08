<?php
/**
 * Created by PhpStorm.
 * User: yashit
 * Date: 05-Mar-18
 * Time: 2:20 PM
 */

class ConfigVars {
    public $api_url = "http://carzrideon.com/estRideon/v1/index.php/";

    public function send_post_request($fields, $func) {
        $url = $this->api_url.$func;
        $ch = curl_init($url);

        $postString = http_build_query($fields, '', '&');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function send_post_request_file($fields, $func, $file) {
        $url = $this->api_url.$func;
        $ch = curl_init($url);

        $cfile = new CURLFile($file['tmp_name'], $file['type'], $file['name']);
        $fields['uploaded_file'] = $cfile;

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    function send_get_request($fields, $func) {
        $ch = curl_init();
        $getString = http_build_query($fields, '', '&');
        $url = $this->api_url.$func."?";
        $url = $url.$getString;
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
?>