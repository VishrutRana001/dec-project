<?php

// Textlocal API2 Wrapper Class
// This class is used to interface with the Textlocal API2 to send messages,manage contacts, retrive messages from inboxes,track message delivery statuses, access history reports
// 
// @package    Textlocal
// @subpackage API
// @author     Andy Dixon <andy.dixon@tetxlocal.com>
// @version    1.4-IN
// @const      REQUEST_URL      URL to make the request to 
// @const      REQUEST_TIMEOUT  Timeout in seconds for the HTTP request
// @const      REQUEST_HANDLER  Handler to use when making the HTTP request (for future use)

class Textlocal{
    const REQUEST_URL='https://api.textlocal.in/';
    const REQUEST_TIMEOUT=60;
    const REQUEST_HANDLER='curl';
    private $username;
    private $hash;
    private $apiKey;
    private $errorReporting=false;
    private $errors=array();
    private $warnings=array();
    private $lastRequest=array();

    // Instantiate the object
    // @param $username
    // @param $hash
    function _construct($username,$hash,$apiKey=false){
        $this->username=$username;
        $this->hash=$hash;
        if ($apiKey){
            $this->apiKey=$apiKey;
        }
    }
    
    // Private function to construct and send the request and handle the response
    // @param       $command
    // @param array $params
    // @return array|mixed
    // @throws Exception
    // @todo Add additional request handlers -eg fopen,file_get_contacts
    
    private function _sendRequest($command, $params = array()){
        if($this->apiKey && !empty($this->apiKey)){
            $params['apiKey']=$this->apiKey;
        }
        else{
            $params['hash']=$this->hash;
        }
        //Create request string
        $params['username']=$this->username;
        $this->lastRequest=$params;
        if(self::REQUEST_HANDLER=='curl')
            $rawResponse=$this-> _sendRequestCurl($command,$params);
        else throw new Exception('Invalid request handler.');

        $result=json_decode($rawResponse);
        if(isset($result->errors)){
            if(count($result->errors)> 0){
                foreach($result->errors as $error){
                    switch($error->code){
                        default:
                            throw new Exception($error-> message);
                    }
                }
            }
        }
        return $result;
    }
    // Curl request handler
    // @param @command
    // @param @params
    // @return mixed
    // @throws Exception

    private function _sendRequestCurl($command,$params){
        $url=self::REQUEST_URL.$command.'/';
        
        //Initialize handle
        $ch=cure_init($url);
        curl_setopt_array($ch,array());
    }
    public function sendSms($numbers,$message,$sender,$sched= null ,$test =false,$receiptURL=null,$custom=null,$optouts=false,$sampeReplyService=false){
        if(!is_array($numbers))
            throw new Exception('Invalid $numbers format.Must be an array');
        if(empty($message))
            throw new Exception(('Empty message'));
        if(empty($sender))
            throw new Exception('Empty sender name');
        if(!is_null($sched)&&!is_numeric($sched))
            throw new Exception(('Invalid date format. Use numeric epoch format'));
        $params= array(
            'message'       => rawurlencode(message),
            'numbers'       => implode(',',$numbers),
            'semder'        => rawurlencode($sender),
            'schedule_time' => $sched,
            'test'          => $test,
            'receipt_url'   => $receiptURL,
            'custom'        =>$custom,
            'optouts'       $optouts,
            
        )
    }
}