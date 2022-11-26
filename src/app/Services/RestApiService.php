<?php

namespace Emamie\Atom\Services;

use Exception;
class RestApiService implements RestApiServiceInterface


{
    public $url;
    public $header;
    public $user ;
    public $pass ;
    public $request;
    public $method;
    public $body;
    protected $service_rest;


    public function __construct(\GuzzleHttp\Client $service_rest)
    {     
      $this->header = config('app.atom.api_service.headers') ;    
      $this->service_rest = $service_rest;
    }

    public function setHeader($header)
    {
      $this->header = $header;
    }
    public function setUrl($url)
    {
      $this->url = $url;
    }
    public function setPass($pass)
    {
      $this->pass = $pass;
    }
    public function setUser($user)
    {
      $this->user = $user;
    }

  public function requestPost($method,$body){   
    $output = ["status" => null, "data" => NULL,"message" => null];
    
    $body_json = json_encode($body);
   
    try{
        $response = $this->service_rest->post($this->url.'/' . $method, [
            'headers' => $this->header,
            'body' => $body_json,
        ]);
        
        $output["status"] = $response->getStatusCode();
        
        if ($response->getStatusCode() === 200) {
    
            $json_data = json_decode($response->getBody()->getContents(),true);
             // اگر دی کد کردن جیسون خطا داشته باشد
            if (!empty($this->handerErrorJson($json_data))) {
                $output["message"] = $this->handerErrorJson($json_data);
                return $output;
            }
            $output["data"] = $json_data ; 
           
    
        }else{
            $output["message"] = "خطا در دریافت اطلاعات";
        }
    }catch(Exception $ex){
        $output["message"] = $ex->getMessage();
    }
    
    return $output;
  }

  public function handerErrorJson($decoded) {
    //Backwards compatability.
    $error = "";
    if (!function_exists('json_last_error')) {
      if ($decoded === FALSE || $decoded === NULL) {
        $error = 'Could not decode JSON! -';
      }
    }
    else {

      //Get the last JSON error.
      $jsonError = json_last_error();

      //In some cases, this will happen.
      if (is_null($decoded) && $jsonError == JSON_ERROR_NONE) {
        $error = 'Could not decode JSON! - ';
      }

      //If an error exists.
      if ($jsonError != JSON_ERROR_NONE) {
        $error = 'Could not decode JSON! - ';

        //Use a switch statement to figure out the exact error.
        switch ($jsonError) {
          case JSON_ERROR_DEPTH:
            $error .= 'Maximum depth exceeded!';
            break;
          case JSON_ERROR_STATE_MISMATCH:
            $error .= 'Underflow or the modes mismatch!';
            break;
          case JSON_ERROR_CTRL_CHAR:
            $error .= 'Unexpected control character found';
            break;
          case JSON_ERROR_SYNTAX:
            $error .= 'Malformed JSON';
            break;
          case JSON_ERROR_UTF8:
            $error .= 'Malformed UTF-8 characters found!';
            break;
          case 0:
            $error .= 'JSON_ERROR_NONE';
            break;
          case 1:
            $error .= 'JSON_ERROR_DEPTH';
            break;

          case 2:
            $error .= 'JSON_ERROR_STATE_MISMATCH';
            break;
          case 3:
            $error .= 'JSON_ERROR_CTRL_CHAR';
            break;
          case 4:
            $error .= 'JSON_ERROR_SYNTAX';
            break;
          case 5:
            $error .= 'JSON_ERROR_UTF8';
            break;
          default:
            $error .= 'Unknown error!';
            break;
        }

      }
    }
    return $error;
  }
 

}

