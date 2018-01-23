<?php
/**
 * Filename: options.php
 * No Future Posts - Options and Uninstall
 */

// Form auswerten
$mode = '';
if(!empty($_POST['do']))
{
	switch($_POST['do'])
	{
		// update options
		case 'nfp_update' :
			update_option('nofutureposts_posts', wp_strip_all_tags($_POST['nofutureposts_posts']));
			update_option('nofutureposts_cats', wp_strip_all_tags($_POST['nofutureposts_cats']));
			NoFuturePosts::no_future_posts();
			echo '<div id="message" class="updated fade"><p>'.__('Options updated', 'nfp').'</p></div>';
			break;
		//  uninstall plugin
		case __('UNINSTALL No Future Posts', 'nfp') :
			if(trim($_POST['uninstall_nfp_yes']) == 'yes')
			{
				NoFuturePosts::reset_future();
				delete_option('nofutureposts_posts');
				delete_option('nofutureposts_cats');
				echo '<div id="message" class="updated fade"><p>';
				echo __('Exclude lists deleted', 'nfp').'</p></div>';
				$mode = 'end-UNINSTALL';
			}
			break;
		default:
			break;
	}
}

switch($mode) {
	// Deaktivierung
	case 'end-UNINSTALL':
		$deactivate_url = 'plugins.php?action=deactivate&amp;plugin=no-future-posts/no-future-posts.php';
		if ( function_exists('wp_nonce_url') ) 
			$deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_no-future-posts/no-future-posts.php');
		echo '<div class="wrap">';
		echo '<h2>'.__('Uninstall', 'nfp').' "No Future Posts"</h2>';
		echo '<p><strong><a href="'.$deactivate_url.'">'.__('Click here', 'nfp').'</a> '.__('to finish the uninstall and to deactivate "No Future Posts".', 'nfp').'</strong></p>';
		echo '</div>';
		break;
	// Seite anzeigen
	default:
			
		
	?>
	<div class="wrap">
	
	<h2>No Future Posts</h2>
	
	<div id="poststuff">

	<div class="postbox">
		<h3 style="border-bottom: 1px solid #eee;"><?php _e('Settings') ?></h3>
		<div class="inside">
			<p><?php _e('Normaly all posts will change from "future" to "publish".<br />Tell me which posts and categories do you want to keep in future.', 'nfp') ?></p>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
				<table class="form-table">
				<tr>
					<th scope="row" style="padding:0"><?php _e('Exclude posts IDs', 'nfp') ?>:</th>
					<td style="padding:0">
						<input type="text" size="60" name="nofutureposts_posts" value="<?php echo get_option('nofutureposts_posts'); ?>" style="width:100%" />
						<?php _e('comma-separated', 'nfp') ?>
					</td>
				</tr>
				<tr>
					<th scope="row" style="padding:0"><?php _e('Exclude categories IDs', 'nfp') ?>:</th>
					<td style="padding:0">
						<input type="text" size="60" name="nofutureposts_cats" value="<?php echo get_option('nofutureposts_cats'); ?>" style="width:100%" />
						<?php _e('comma-separated', 'nfp') ?>
					</td>
				</tr>
				</table>
				<p>
					<input type="hidden" name="do" value="nfp_update" />
					<input type="submit" name="update" value="<?php _e('Update options', 'nfp') ?>" class="button" />
				</p>
			</form>
		</div>
	</div>
	
	<!-- Uninstall -->
	<div class="postbox">
		<h3 style="border-bottom: 1px solid #eee;"><?php _e('Uninstall', 'nfp') ?></h3>
			<div class="inside">
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
				<p>
					<?php _e('Delete exclude lists and deactivate the plugin.', 'nfp') ?><br/>
				</p>
				<p>
					<input type="checkbox" name="uninstall_nfp_yes" value="yes" />&nbsp;<?php _e('Yes', 'nfp'); ?><br /><br />
					<input type="submit" name="do" value="<?php _e('UNINSTALL No Future Posts', 'nfp') ?>" class="button" style="color:red" />
				</p>
			</form>
		</div>
	</div>
	
	</div>
	
	</div>

<?php
} // End switch($mode)
