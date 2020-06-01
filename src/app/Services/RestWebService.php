<?php

namespace Razavi\Atom\Services;


class RestWebService
{
    protected $service_rest;


    public function __construct(\GuzzleHttp\Client $service_rest)
    {
        $this->service_rest = $service_rest;
    }

    public function request(string $method,string  $url,string $model,array $option)
    {
        if(isset($model) && $model != ""){

            $url = env($url).'/'.$model;
        }else{
            $url = env($url);
        }

        $method = strtolower($method);

        $res = $this->service_rest->$method( $url, $option);
        $status_code = $res->getStatusCode();
        if(preg_match('/^2[0-9]+$/', $status_code)){
            return $res->getBody();
        }else
            return $res->getStatusCode();


    }


}

