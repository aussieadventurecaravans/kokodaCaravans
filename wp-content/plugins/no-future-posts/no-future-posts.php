<?php
/*
Plugin Name: No Future Posts
Plugin URI: http://www.tomsdimension.de/wp-plugins/no-future-posts
Description: Simplest "Event-Calender" - changes the post status from "future" to "publish"
Version: 1.4
License: Postcardware
Author: Tom Braider
Author URI: http://www.tomsdimension.de
*/

class NoFuturePosts
{

/**
 * init
 */
function __construct()
{
	$path = ABSPATH.PLUGINDIR.'/no-future-posts/no-future-posts.php';
	
	// actions
	add_action('admin_init', array(&$this, 'init_locale'), 98);
	add_action('admin_menu', array(&$this, 'menu'));
	add_action('save_post', array(&$this, 'no_future_posts'));

	// hooks
	register_activation_hook($path, array('NoFuturePosts', 'no_future_posts'));
	register_uninstall_hook($path, array('NoFuturePosts', 'uninstall'));
	register_deactivation_hook($path, array('NoFuturePosts', 'reset_future'));
	
	// settings link on plugin page
	add_filter('plugin_action_links', array(&$this,'plugin_actions'), 10, 2);
}

/**
 * change post status 
 */
static function no_future_posts() 
{
	global $wpdb;
	$tposts = $wpdb->posts;
	$trel = $wpdb->term_relationships;
	$ttax = $wpdb->term_taxonomy;
	
	// get excluded post IDs...
	$e_posts = get_option('nofutureposts_posts');
	if ( empty($e_posts) )
		$e_posts = 0;
	
	// add post IDs in excluded categories
	$e_cats = get_option('nofutureposts_cats');
	if ( !empty($e_cats) )
	{
		$sql = "SELECT	ID
				FROM	$tposts AS p
				LEFT	JOIN $trel AS r
						ON r.object_id = p.ID
				LEFT	JOIN $ttax AS t
						ON t.term_taxonomy_id = r.term_taxonomy_id
						AND t.taxonomy = 'category'
				WHERE	t.term_id IN ($e_cats)";
		$res = $wpdb->get_results($sql);
		foreach ( $res as $r )
			$e_posts .= ','.$r->ID;
	}
	
	// set future posts
	$sql = "UPDATE	$tposts
			SET		post_status = 'publish'
			WHERE	post_status = 'future'
			AND		id NOT IN ($e_posts)";
	$wpdb->query($sql);
	
	// reset excluded posts
	$sql = "UPDATE	$tposts
			SET		post_status = 'future'
			WHERE	id IN ($e_posts)
			AND		post_date > now()";
	$wpdb->query($sql);
	
	NoFuturePosts::update_count();
}

/**
 * set menu
 */
function menu()
{
	add_options_page('NoFuturePosts', 'No Future Posts', 'manage_options', 'no-future-posts/options.php');
}

/**
 * adds an "settings" link to the plugins page
 */
function plugin_actions($links, $file)
{
	if( $file == 'no-future-posts/no-future-posts.php'
			&& strpos( $_SERVER['SCRIPT_NAME'], '/network/') === false ) // not on network plugin page
	{
		$link = '<a href="options-general.php?page=no-future-posts/options.php">'.__('Settings').'</a>';
		array_unshift( $links, $link );
	}
	return $links;
}

/**
 * update post counts in categories
 */
static function update_count()
{
	$terms = get_all_category_ids();
	wp_update_term_count($terms, 'category');
}

/**
 * set future posts to future again
 */
static function reset_future()
{
	global $wpdb;
	$sql = "UPDATE	$wpdb->posts
			SET		post_status = 'future'
			WHERE	post_date > now()";
	$wpdb->query($sql);
	NoFuturePosts::update_count();
}

/**
 * uninstall
 */
static function uninstall()
{
	NoFuturePosts::reset_future();
	delete_option('nofutureposts_posts');
	delete_option('nofutureposts_cats');
}

/**
 * load locale
 */
function init_locale()
{
	if (defined('WPLANG') && function_exists('load_plugin_textdomain'))
		load_plugin_textdomain('nfp', false, 'no-future-posts/locale');
}

} // class

new NoFuturePosts();
