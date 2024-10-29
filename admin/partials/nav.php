<?php
/*
 * @link http://www.girltm.com
 * @since 1.0.0
 * @package APOYL_QQ
 * @subpackage APOYL_QQ/admin/partials
 * @author 凹凸曼 <jar-c@163.com>
 *
 */
?>
<h1 class="wp-heading-inline"><?php _e('settings','apoyl-qq'); ?></h1>
<p><?php _e('settings_desc','apoyl-qq'); ?></p>
<p><?php _e('callbackmsg','apoyl-qq'); ?></p>
<ul class="subsubsub">
	<li><a href="options-general.php?page=apoyl-qq-settings"
		<?php if($do=='') echo 'class="current"';?> aria-current="page"><?php _e('settingsname','apoyl-qq'); ?><span
			class="count"></span></a> |</li>
	<li><a href="options-general.php?page=apoyl-qq-settings&do=list"
		<?php if($do=='list') echo 'class="current"';?>><?php _e('list','apoyl-qq'); ?><span
			class="count"></span></a></li>
</ul>

<div class="clear"></div>
<hr>