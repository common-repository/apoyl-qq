<?php
/*
 * Plugin Name: apoyl-qq
 * Plugin URI:  http://www.girltm.com/
 * Description: 实现QQ一键登录，让用户不在繁琐去注册用户，一键实现QQ登录，极大的方便用户登录网站.
 * Version:     1.8.0
 * Author:      凹凸曼
 * Author URI:  http://www.girltm.com/
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: apoyl-qq
 * Domain Path: /languages
 */
if ( ! defined( 'WPINC' ) ) {
    die;
}
define('APOYL_QQ_VERSION','1.8.0');
define('APOYL_QQ_PLUGIN_FILE',plugin_basename(__FILE__));
define('APOYL_QQ_URL',plugin_dir_url( __FILE__ ));
define('APOYL_QQ_DIR',plugin_dir_path( __FILE__ ));

function activate_apoyl_qq(){
    require plugin_dir_path(__FILE__).'includes/activator.php';
    Apoyl_Qq_Activator::activate();
    Apoyl_Qq_Activator::install_db();
}
register_activation_hook(__FILE__, 'activate_apoyl_qq');

function uninstall_apoyl_qq(){
    require plugin_dir_path(__FILE__).'includes/uninstall.php';
    Apoyl_Qq_Uninstall::uninstall();
}

register_uninstall_hook(__FILE__,'uninstall_apoyl_qq');

require plugin_dir_path(__FILE__).'includes/qq.php';

function run_apoyl_qq(){
    $plugin=new APOYL_QQ();
    $plugin->run();
}

function apoyl_qq_file($filename)
{
    $file = WP_PLUGIN_DIR . '/apoyl-common/v1/apoyl-qq/components/' . $filename . '.php';
    if (file_exists($file))
        return $file;
    return '';
}
run_apoyl_qq();
?>