<?php
/*
 * @link       http://www.girltm.com/
 * @since      1.0.0
 * @package    APOYL_QQ
 * @subpackage APOYL_QQ/includes
 * @author     凹凸曼 <jar-c@163.com>
 *
 */
class APOYL_QQ {
	
	protected $loader;
	
	protected $plugin_name;
	
	protected $version;
	
	public function __construct() {
	    
		if ( defined( 'APOYL_QQ_VERSION' ) ) {
			$this->version = APOYL_QQ_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'apoyl-qq';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}
	
	private function load_dependencies() {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/loader.php';
	
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/i18n.php';
	
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/admin.php';
	
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/public.php';
		$this->loader = new Apoyl_Qq_Loader();
	}
	
	private function set_locale() {
		$plugin_i18n = new Apoyl_Qq_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}
	
	private function define_admin_hooks() {
		$plugin_admin = new Apoyl_Qq_Admin( $this->get_plugin_name(), $this->get_version() );
			
		$this->loader->add_action('admin_menu', $plugin_admin, 'menu');
		$this->loader->add_action('wp_before_admin_bar_render',$plugin_admin,'apoyl_qq_wp_before_admin_bar_render');
		$this->loader->add_filter('plugin_action_links_'.APOYL_QQ_PLUGIN_FILE, $plugin_admin, 'links',10, 2);
		

		
	}

	private function define_public_hooks() {

    		$plugin_public = new Apoyl_Qq_Public( $this->get_plugin_name(), $this->get_version() );
    		
    		$this->loader->add_filter( 'sanitize_user',$plugin_public,'apoyl_qq_sanitize_user',10,3);
    		
    		$this->loader->add_action( 'login_form',$plugin_public,'login');
    		
    		$this->loader->add_action('wp_ajax_apoyl_qq_ajax', $plugin_public,'apoyl_qq_ajax');
    		$this->loader->add_action('wp_ajax_apoyl_qq_callback', $plugin_public,'apoyl_qq_callback');
    		
    		$this->loader->add_action('wp_ajax_nopriv_apoyl_qq_ajax', $plugin_public,'apoyl_qq_ajax');
    		$this->loader->add_action('wp_ajax_nopriv_apoyl_qq_callback', $plugin_public,'apoyl_qq_callback');

	}

	public function run() {
		$this->loader->run();
	}
	
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}
}
?>