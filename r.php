<?php

class REST{

    public function __construct()
    {
        $handler=fopen('php://input','r');
        echo $request=stream_get_contents($handler);

    }
    public function validateRequest(){


    }
    public function validateParams($fileName,$value,$dataType,$required){

        
    }
    public function returnResponse(){

        
    }
    public function processApi(){

        
    }
    public function throwError(){

        
    }

}