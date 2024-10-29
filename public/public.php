<?php

/*
 * @link http://www.girltm.com/
 * @since 1.0.0
 * @package APOYL_QQ
 * @subpackage APOYL_QQ/public
 * @author 凹凸曼 <jar-c@163.com>
 *
 */
class Apoyl_Qq_Public
{

    private $plugin_name;

    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function apoyl_qq_callback()
    {
        global $wpdb;
        if (! check_ajax_referer('qq_nonce'))
            exit();
        $redirect_to = home_url();
        $login_to = admin_url();
        try {
            require_once (APOYL_QQ_DIR . 'api/qqapi/QqConnect.class.php');
            $qqobj = new QqConnect();
            $token = $qqobj->qq_callback();
            $openid = $qqobj->get_openid($token['access_token']);
            $data = $qqobj->get_user_info($openid, $token['access_token']);
        } catch (Exception $e) {
            wp_redirect($login_to);
            exit();
        }
        $qquser = $wpdb->get_row($wpdb->prepare("select id,
                userid
                from {$wpdb->prefix}apoyl_qq
                where openid=%s
                limit 1;", $openid));
        
        if ($qquser->userid) {
            $userid = $qquser->userid;
            $secure_cookie = false;
            if (get_user_option('use_ssl', $userid)) {
                $secure_cookie = true;
            }
            wp_set_auth_cookie($userid, true, $secure_cookie);
            wp_redirect($redirect_to);
        } else {
            $arr = get_option('apoyl-qq-settings');
            
            $userid = get_current_user_id();
            if ($userid <= 0) {
                $newuser = array(
                    'user_login' => $data['nickname'],
                    'user_email' => '',
                    'user_pass' => wp_generate_password(12),
                    'role' => $arr['role']
                );
                $userid = wp_insert_user($newuser);
                if (! isset($userid->errors) && $userid > 0) {
                    $secure_cookie = false;
                    if (get_user_option('use_ssl', $userid)) {
                        $secure_cookie = true;
                    }
                    wp_set_auth_cookie($userid, true, $secure_cookie);
                }
            }
            if ($userid > 0) {
                
                $now = current_time('timestamp');
                $qqdata = array(
                    'openid' => $openid,
                    'userid' => $userid,
                    'ret' => $data['ret'],
                    'nickname' => $data['nickname'],
                    'figureurl' => $data['figureurl'],
                    'figureurl_1' => $data['figureurl_1'],
                    'figureurl_2' => $data['figureurl_2'],
                    'figureurl_qq_2' => $data['figureurl_qq_2'],
                    'figureurl_qq_2' => $data['figureurl_qq_2'],
                    'gender' => $data['gender'],
                    'msg' => $data['msg'],
                    'modtime' => $now,
                    'addtime' => $now
                );
                
                $re = $wpdb->insert($wpdb->prefix . 'apoyl_qq', $qqdata);
                if ($re)
                    wp_redirect($redirect_to);
                else
                    wp_redirect($login_to);
            } else {
                wp_redirect($login_to);
            }
        }
    }

    public function apoyl_qq_ajax()
    {
        if (! check_ajax_referer('qq_nonce'))
            exit();
        require_once (APOYL_QQ_DIR . "api/qqapi/QqConnect.class.php");
        $qqobj = new QqConnect();
        $qqobj->qq_login();
    }

    public function login()
    {
        $arr = get_option('apoyl-qq-settings');
        
        if (isset($arr['open'])) {
            $ajaxurl = admin_url('admin-ajax.php');
            $nonce = wp_create_nonce('qq_nonce');
            $params = array(
                'action' => 'apoyl_qq_ajax',
                '_ajax_nonce' => wp_create_nonce('qq_nonce')
            );
            $url = $ajaxurl . '?' . build_query($params);
            require_once plugin_dir_path(__FILE__) . 'partials/public-display.php';
        }
    }

    public function apoyl_qq_sanitize_user($username, $raw_username, $strict)
    {
        $file = apoyl_qq_file('chinese');
        if ($file) {
            include $file;
        } else {
            $raw_username = $username;
            $username = wp_strip_all_tags($username);
            $username = remove_accents($username);
            
            $username = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '', $username);
            
            $username = preg_replace('/&.+?;/', '', $username);
            
            if ($strict) {
                $username = preg_replace('|[^a-z0-9 _.\-@]|i', '', $username);
            }
            
            $username = trim($username);
            
            $username = preg_replace('|\s+|', ' ', $username);
        }
        
        return $username;
    }
}