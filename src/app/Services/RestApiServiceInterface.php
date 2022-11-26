<?php

namespace Emamie\Atom\Services;


interface RestApiServiceInterface {
    public function setHeader($header);
    public function setUrl($url);
    public function setPass($pass);
    public function setUser($user);
    public function requestPost( $method, $body);
    public function handerErrorJson( $decoded);
}