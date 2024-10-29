<?php
/*
 * @link       http://www.girltm.com/
 * @since      1.0.0
 * @package    APOYL_QQ
 * @subpackage APOYL_QQ/api/qqapi
 * @author     凹凸曼 <jar-c@163.com>
 *
 */
class QqConnect{

    const VERSION = "2.0";
    const GET_AUTH_CODE_URL = "https://graph.qq.com/oauth2.0/authorize";
    const GET_ACCESS_TOKEN_URL = "https://graph.qq.com/oauth2.0/token";
    const GET_OPENID_URL = "https://graph.qq.com/oauth2.0/me";
    const GET_USER_INFO_URL="https://graph.qq.com/user/get_user_info";

    function __construct(){
        $this->cache = get_option('apoyl-qq-settings');
        $this->init_callback();


    }
    function init_callback(){
        $ajaxurl = admin_url('admin-ajax.php');
        $nonce = wp_create_nonce('qq_nonce');
        $params=array(
            'action'=>'apoyl_qq_callback',
            '_ajax_nonce'=>wp_create_nonce('qq_nonce'),
        
        );
        
        $this->callback=urlencode($ajaxurl.'?'.build_query($params));
    }

    public function qq_login(){
        $appid = $this->cache['appid'];

        $scope = 'get_user_info';


        $keysArr = array(
            "response_type" => "code",
            "client_id" => $this->cache['appid'],
            "redirect_uri" => $this->callback,
            "state" => wp_create_nonce('qq_nonce'),
            "scope" => $scope
        );
       
        $login_url =  self::GET_AUTH_CODE_URL.'?'.build_query($keysArr);

        wp_redirect($login_url);
    }

    public function qq_callback(){
        if(!wp_verify_nonce($_GET['state'],'qq_nonce')) 
            throw new Exception("error:30001,description:state error",500);
        $keysArr = array(
            "grant_type" => "authorization_code",
            "client_id" => $this->cache['appid'],
            "redirect_uri" => $this->callback,
            "client_secret" => $this->cache['appkey'],
            "code" => sanitize_text_field($_GET['code']),
        );

        $token_url =  self::GET_ACCESS_TOKEN_URL.'?'.build_query($keysArr);
        
        $response = $this->httpGet($token_url);

        if(strpos($response, "callback") !== false){

            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);

            if(isset($msg->error)){
               throw new Exception("error:".$msg->error.",description:".$msg->error_description,500);
            }
        }
        $params = array();
        parse_str($response, $params);
		 
        return $params;

    }

    public function get_openid($access_token){


        $keysArr = array(
            "access_token" => $access_token,
        );

        $graph_url = self::GET_OPENID_URL.'?'.build_query($keysArr); 
        $response = $this->httpGet($graph_url);
		
        if(strpos($response, "callback") !== false){

            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos -1);
        }

        $user = json_decode($response,true);
        if(isset($user->error)){
             throw new Exception("error:".$msg->error.",description:".$msg->error_description,500);
        }

        return $user['openid'];

    }
    public function get_user_info($openid,$access_token){
    
    
        $keysArr = array(
            "access_token" => $access_token,
            "oauth_consumer_key"=>$this->cache['appid'],
            'openid'=>$openid,
            
        );
    
        $graph_url = self::GET_USER_INFO_URL.'?'.build_query($keysArr);
        $response = $this->httpGet($graph_url);
    	
        if(strpos($response, "callback") !== false){
    
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos -1);
        }
    
        $user = json_decode($response,true);
        if(isset($user->error)){
             throw new Exception("error:".$msg->error.",description:".$msg->error_description,500);
        }
	
        return $user;
    
    }
    private function httpGet($url,$param=array())
    {
         
        $res = wp_remote_retrieve_body(wp_remote_get($url, array(
            'timeout' => 30,
            'body' =>  $param,
        )));
         
    
        return $res;
    }
}
