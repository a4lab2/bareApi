<?php
class API extends REST{
    public function __construct()
    {
        parent::__construct();
    }
    public function generateToken(){
        // print_r($this->param);
        $email=$this->validateParams('email',$this->param['email'],"STRING"); //required is not neccessary cos we have a default true
        $pass=$this->validateParams('pass',$this->param['pass'],"STRING"); //required is not neccessary cos we have a default true
        // echo $email;
        $user=new USER();
        if(!is_array( $userInfo=$user->login($email,$pass))){
            $this->returnResponse(INVALID_USER_PASS,"Invalid login credentials ");
        }
        $payload=[
            'iat'=>time(),
            'iss'=>'localhost',
            'exp'=>time()+(60),
            'email'=>$userInfo['email']
        ];
        $jwt=new JWT;
        $token=$jwt->encode($payload,SECRET_KEY);
        $data=['token'=>$token];
        $this->returnResponse(SUCCESSS_RESPONSE,$data);
    }
    public function register()
    {
        $email=$this->validateParams('email',$this->param['email'],"STRING"); //required is not neccessary cos we have a default true
        $pass=$this->validateParams('pass',$this->param['pass'],"STRING"); //required is not neccessary cos we have a default true
        $user=new USER();
    
            try{
                echo $token=$this->getBearerToken();
                $payload=JWT::decode($token,SECRET_KEY,['HS256']);
                print_r($payload);
                if(!$user->register($email,$pass)){
                    $msg="Insert Failed";
                }
                else{
                    $msg="Insert Successful";
                }
                $this->returnResponse(SUCCESSS_RESPONSE,$msg  );
            }catch(Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS,$e->getMessage());
            }
        
    }

}