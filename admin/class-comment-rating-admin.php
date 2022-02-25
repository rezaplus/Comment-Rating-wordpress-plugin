<?php

/**
 * register admin page 
 * get api link
 * 
 * @since    1.0.0
 */
class comment_rating_Admin
{

	public static $instance = null;

	public function __construct()
	{

		add_action('admin_menu', array($this, 'menu_page'));
	}

	/**
	 * Register the menu page for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function menu_page()
	{
		add_submenu_page('edit-comments.php', 'Comment rating settings', 'Comment rating', 'manage_options', 'cmr', array($this, 'cm_rates_callback'));
	}

	/**
	 * Comment rate page callback
	 * display view-settings.php page
	 * insert api url
	 * @since    1.0.0
	 */
	function cm_rates_callback()
	{
		if (isset($_GET['server_url'])) {
			//insert or update server url
			update_option('cm_rating_server_url', $_GET['server_url']);
		}
		return include 'view-settings.php';
	}

	public static function get_instance()
	{
		// If the single instance hasn't been set, set it now.
		if (null == self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}
}

comment_rating_Admin::get_instance();
