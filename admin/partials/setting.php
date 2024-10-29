<?php
/*
 * @link http://www.girltm.com
 * @since 1.0.0
 * @package APOYL_QQ
 * @subpackage APOYL_QQ/admin/partials
 * @author 凹凸曼 <jar-c@163.com>
 *
 */

if (! empty($_POST['submit']) && check_admin_referer($options_name, '_wpnonce')) {
        
        $arr_options = array(
        	'open'=>isset ( $_POST ['open'] ) ? ( int ) sanitize_key ( $_POST ['open'] ) :  0,
            'appid' => sanitize_text_field($_POST['appid']),
            'appkey' => sanitize_text_field($_POST['appkey']),
            'role' => sanitize_text_field($_POST['role']),
        );
   
        $updateflag = update_option($options_name, $arr_options);
        $updateflag = true;
    }
    $arr = get_option($options_name);
    
    ?>
    <?php if( !empty( $updateflag ) ) { echo '<div id="message" class="updated fade"><p>' . __('updatesuccess','apoyl-qq') . '</p></div>'; } ?>
    
    <div class="wrap">
    
<?php   require_once APOYL_QQ_DIR . 'admin/partials/nav.php';?>
    </p>
    	<form
    		action="<?php echo admin_url('options-general.php?page=apoyl-qq-settings');?>"
    		name="settings-apoyl-qq" method="post">
    		<table class="form-table">
    			<tbody>
    				<tr>
    					<th><label><?php _e('open','apoyl-qq'); ?></label></th>
    					<td><input type="checkbox" class="regular-text"
    						value="1" id="open" name="open" <?php checked( '1', $arr['open'] ); ?> >
    					<?php _e('open_desc','apoyl-qq'); ?>
    					</td>
    				</tr>
  <tr>
                    <th><label><?php _e('appid','apoyl-qq'); ?></label></th>
                    <td><input type="text" class="regular-text" value="<?php echo esc_attr($arr['appid']); ?>" id="appid" name="appid">
                    <p class="description"><?php _e('appid_desc','apoyl-qq'); ?></p>
                    </td>
                    
                </tr>
               <tr>
                    <th><label><?php _e('appkey','apoyl-qq'); ?></label></th>
                    <td><input type="text" class="regular-text" value="<?php echo esc_attr($arr['appkey']); ?>" id="appkey" name="appkey">
                    <p class="description"><?php _e('appkey_desc','apoyl-qq'); ?></p>
                    </td>
                    
                </tr>

						<tr class="user-role-wrap">
							<th><label for="role"><?php _e( 'Role' ); ?></label></th>
							<td>
								<select name="role" id="role">
									<?php
									// Compare user role against currently editable roles.
									
									$user_role=$arr['role'];
									// Print the full list of roles with the primary one selected.
									wp_dropdown_roles( $user_role );

									// Print the 'no role' option. Make it selected if the user has no role yet.
									if ( $user_role ) {
										echo '<option value="">' . __( '&mdash; No role for this site &mdash;' ) . '</option>';
									} else {
										echo '<option value="" selected="selected">' . __( '&mdash; No role for this site &mdash;' ) . '</option>';
									}
									?>
							</select>
							</td>
						</tr>
			
    			</tbody>
    		</table>
                <?php
                wp_nonce_field("apoyl-qq-settings");
                submit_button();
                ?>
               
    </form>
    </div>