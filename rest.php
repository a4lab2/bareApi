<?php
require_once "consts.php";
class REST{
    protected $request;
    protected $serviceName;
    protected $param;
    public function __construct()
    {
        if($_SERVER["REQUEST_METHOD"]!=="POST"){
            $this->throwError(INVALID_REQUEST_METHOD, "method is ". $_SERVER["REQUEST_METHOD"] .": POST required");
       
        }
        $handler=fopen('php://input','r');
        $this->request=stream_get_contents($handler);
        $this->validateRequest();

    }
    public function validateRequest(){
        if($_SERVER['CONTENT_TYPE']!=='application/json')
        {
            $this->throwError(INVALID_CONTENT_TYPE,"Request content type : ".$_SERVER['CONTENT_TYPE'].": Json required");
        }
        $data=json_decode($this->request,true);
        if(!isset($data)|| $data['name']==""){
            $this->throwError(API_NAME_REQUIRED,"API name is required none given");
        }
        $this->serviceName=$data['name'];


        if(!is_array($data['param'])){
            $this->throwError(API_PARAM_REQUIRED,"API param is required none given");
        }
        $this->param=$data['param'];
    }
    public function validateParams($fieldName,$value,$dataType,$required=true){

        if($required==true && empty($value)==true){
            $this->throwError(VALIDATE_PARAM_REQUIRED,"$fieldName is required none provided");
        }
        //check if datatype is correct example expecting bool
        switch ($dataType) {
            case 'BOOLEAN':
                if(!is_bool($value))
                {
                    $this->throwError(INVALID_PARAM_DATATYPE,"Data type not valid for $value : Boolean expected ");
                }
                break;
            case 'INTEGER':
                if(!is_numeric($value))
                {
                    $this->throwError(INVALID_PARAM_DATATYPE,"Data type not valid for $value : Number expected ");
                }
                break;
            case 'STRING':
                if(!is_string($value))
                {
                    $this->throwError(INVALID_PARAM_DATATYPE,"Data type not valid for $value : String expected ");
                }
                break;
            default:
            $this->throwError(INVALID_PARAM_DATATYPE,"Data type not available for $value ");
            break;
        }
        return $value;
    }
    //search how to get bearer token on google
    public function getAuthourizationHeader(){
        $headers=null;
        if(isset($_SERVER['Authorization'])){
            $headers=trim($_SERVER['HTTP_AUTHORIZATION']);
        }
        elseif(isset($_SERVER['HTTP_AUTHORIZATION'])){
            $headers=trim($_SERVER['HTTP_AUTHORIZATION']);
        }
        elseif(function_exists('apache_request_headers'))
        {
            $requestHeaders= apache_request_headers();

            $requestHeaders=array_combine(array_map('ucwords',array_keys($requestHeaders)),array_values($requestHeaders));
        }
    }
    public function getBearerToken(){
        $headers->getAuthourizationHeader();
        if(!empty($headers)){
            if(preg_match("/Bearer\s(\S+)/",$headers,$matches)){
                return $matches[1];
            }
        }
        $this->throwError(ACCESS_TOKEN_ERRORS,"Invalid access token");
    }
    public function returnResponse($code,$data){
        header("content-type:application/json");
        $response=json_encode(['response'=>['status'=>$code,'result'=>$data]]);
        echo $response;
    }
    public function processApi(){

        $api=new API;
        //reflection method 
        $r=new ReflectionMethod('API',$this->serviceName);
        //check if method exist or not in the class whose reflection is called
        if(!method_exists($api,$this->serviceName))
        {
            $this->throwError(API_DEST_NOT_EXIST,"Invalid API");
        }
        $r->invoke($api);
    }
    public function throwError($code,$message){
        header("content-type:application/json");
       $errorMsg= json_encode(['error' => ['status'=>$code,'msg'=>$message]]
    );
        echo $errorMsg;
        exit;
    }

}