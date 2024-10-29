<?php

/*
 * @link http://www.girltm.com/
 * @since 1.0.0
 * @package APOYL_QQ
 * @subpackage APOYL_QQ/includes
 * @author 凹凸曼 <jar-c@163.com>
 *
 */
class Apoyl_Qq_Activator
{

    public static function activate()
    {
        $options_name = 'apoyl-qq-settings';
        $arr_options = array(
            'open' => 1,
            'appid' => '',
            'appkey' => '',
            'role'=>'',

        );
        add_option($options_name, $arr_options);
    }

    public static function install_db()
    {
        global $wpdb;
        $apoyl_qq_db_version = APOYL_QQ_VERSION;
        $tablename = $wpdb->prefix . 'apoyl_qq';
        $ishave = $wpdb->get_var('show tables like \'' . $tablename . '\'');
        $sql='';
        if ($tablename != $ishave) {
            $sql = "CREATE TABLE " . $tablename . " (
                      `id`	bigint(20) unsigned  NOT NULL AUTO_INCREMENT,
                      `userid` bigint(20) unsigned NOT NULL DEFAULT '0',
                      `openid` varchar(64) NOT NULL,
                      `ret` bigint(20) NOT NULL default '0',
                      `nickname` varchar(100) NOT NULL,
                      `figureurl` varchar(200) NOT NULL,
                      `figureurl_1` varchar(200) NOT NULL,
                      `figureurl_2` varchar(200) NOT NULL,
                      `figureurl_qq_2` varchar(200) NOT NULL,
                      `msg` varchar(200) NOT NULL,
                      `gender` varchar(32) NOT NULL,
                      `addtime` int(10) NOT NULL default '0',
                      `modtime` int(10) NOT NULL default '0',
                      PRIMARY KEY (`id`),
                      KEY `userid` (`userid`)
                    );";
        }
    
        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
        add_option('apoyl_qq_db_version', $apoyl_qq_db_version);
    }
}
?>