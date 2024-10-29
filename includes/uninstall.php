<?php
/*
 * @link       http://www.girltm.com/
 * @since      1.0.0
 * @package    APOYL_QQ
 * @subpackage APOYL_QQ/includes
 * @author     凹凸曼 <jar-c@163.com>
 *
 */
class Apoyl_Qq_Uninstall {

	
	public static function uninstall() {
	    global $wpdb;
        delete_option('apoyl-qq-settings');
	}

}
