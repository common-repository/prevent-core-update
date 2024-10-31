<?php
/*
Plugin Name: Prevent Core Update
Description: This plugin prevents anyone from updating the WordPress core, redirecting them to another page.
Version: 1.0
Author: Ramon Fincken, Jesper van Engelen
Author URI: http://www.mijnpress.nl
License: GPLv2 or later
*/

// Only progress if the core functions are loaded
if (function_exists('add_action')) {
	/**
	 * Action: init
	 * Redirect if the user is trying to update or re-install WordPress
	 */
	function pcu_action_init()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if ($_GET['action'] == 'do-core-reinstall' or $_GET['action'] == 'do-core-upgrade') {
				header('Location: ' . trailingslashit(get_admin_url()) . 'options-general.php?page=prevent-core-update');
				exit;
			}
		}
	}
	
	/**
	 * Action: admin_menu
	 * Add admin panel pages
	 */
	function pcu_action_admin_menu()
	{
		global $_registered_pages;
		
		$hookname = get_plugin_page_hookname(plugin_basename('prevent-core-update.php'), 'options-general.php');
		
		if (!empty($hookname)) {
			add_action($hookname, 'pcu_adminpage_error');
		}
		
		$_registered_pages[$hookname] = true;
	}
	
	/**
	 * Admin page: error
	 */
	function pcu_adminpage_error()
	{
		echo '
			<div class="wrap">
				' . (function_exists('get_screen_icon') ? get_screen_icon('tools') : '<div id="icon-tools" class="icon32"></div>') . '
				<h2>' . __('WordPress Updates') . '</h2>
				<div class="updated">
					<p>' . __('We do no allow an update by you. Please contact your webmaster or webhost.') . '</p>
				</div>
			</div>
		';
	}
	
	// Add actions
	add_action('init', 'pcu_action_init');
	add_action('admin_menu', 'pcu_action_admin_menu');
}
?>