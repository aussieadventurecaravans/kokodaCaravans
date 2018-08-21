<?php

/* enqueue scripts */

function kokoda_scripts() {

	wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/_css/bootstrap.css' );
	wp_enqueue_style( 'responsivetabs', get_stylesheet_directory_uri() . '/_css/responsive-tabs.css' );
	wp_enqueue_style( 'lightbox', get_stylesheet_directory_uri() . '/_css/lightbox.css' );
	wp_enqueue_style( 'kokoda', get_stylesheet_uri() );
	
	if( !is_admin()){
		wp_deregister_script('jquery');
		
		wp_enqueue_script(
			'jquery', 
			('https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js'), 
			false, 
			'1.11.3', 
			true
		);
	}
	
	wp_enqueue_script(
		'jqueryui',
		('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'),
		array( 'jquery' ),
		'1.11.4',
		true
	);

	wp_enqueue_script(
		'bootstrap',
		get_stylesheet_directory_uri() . '/_js/bootstrap.min.js',
		array( 'jquery' ),
		'1.0.0',
		true
	);
	
	wp_enqueue_script(
		'bootstrap-hover-dropdown',
		get_stylesheet_directory_uri() . '/_js/bootstrap-hover-dropdown.min.js',
		array( 'bootstrap-js' ),
		'1.0.0',
		true
	);
	
	/*wp_enqueue_script(
		'retina',
		get_stylesheet_directory_uri() . '/_js/retina.min.js',
		array( 'jquery' ),
		'1.0.0',
		true
	);*/
	
	wp_enqueue_script(
		'responsivetabs',
		get_stylesheet_directory_uri() . '/_js/jquery.responsiveTabs.min.js',
		array( 'jquery' ),
		'1.0.0',
		true
	);
	
	wp_enqueue_script(
		'flexslider',
		get_stylesheet_directory_uri() . '/_js/jquery.flexslider.min.js',
		array( 'jquery' ),
		'1.0.0',
		true
	);
	
	wp_enqueue_script(
		'isotope',
		get_stylesheet_directory_uri() . '/_js/isotope.pkgd.min.js',
		array( 'jquery' ),
		'1.0.0',
		true
	);
	
	wp_enqueue_script(
		'lightbox',
		get_stylesheet_directory_uri() . '/_js/lightbox.js',
		array( 'jquery' ),
		'1.0.0',
		true
	);

	wp_enqueue_script(
		'nanoscroll',
		get_stylesheet_directory_uri() . '/_js/jquery.nanoscroller.min.js',
		array( 'jquery' ),
		'1.0.0',
		true
	);
	
	wp_enqueue_script(
		'site',
		get_stylesheet_directory_uri() . '/_js/site.js',
		array( 'jquery' ),
		'1.0.0.',
		true
	);
}

add_action( 'wp_enqueue_scripts', 'kokoda_scripts' );

add_filter('gform_init_scripts_footer', 'init_scripts');
	function init_scripts() {
	return true;
}

// Register Custom Post Type
function custom_post_type() {

	$labels = array(
		'name'                  => _x( 'Products', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Product', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Products', 'text_domain' ),
		'name_admin_bar'        => __( 'Product', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Product', 'text_domain' ),
		'description'           => __( 'Kokoda Products', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'revisions', 'page-attributes', ),
		'taxonomies'            => array( 'product-cat' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-book-alt',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'product', $args );

}
add_action( 'init', 'custom_post_type', 0 );

// Register Custom Taxonomy
function custom_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Product Categories', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Product Categories', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Product Categories', 'text_domain' ),
		'all_items'                  => __( 'All Items', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Item', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( 'Items list', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'product-cat', array( 'product' ), $args );

}
add_action( 'init', 'custom_taxonomy', 0 );

/* WPSearch Auth */
function my_searchwp_basic_auth_creds() {
	$credentials = array( 
		'username' => 'kokodadev', 
		'password' => 'ujN}6yZ$8IN-Y?Q' 
	);
	
	return $credentials;
}


/* WPSearch */
function my_fuzzy_word_length()
{
  return 3;
}

add_filter( 'searchwp_fuzzy_min_length', 'my_fuzzy_word_length' );

function my_fuzzy_threshold()
{
  return 50;
}

add_filter( 'searchwp_fuzzy_threshold', 'my_fuzzy_threshold' );
add_filter( 'searchwp_basic_auth_creds', 'my_searchwp_basic_auth_creds' );


/**
 * Simple Custom Post Order prevents SearchWP results from showing, this function removes the problematic
 * filters during searches only
 *
 * @param $wp_query
 *
 * @return mixed
 */
function my_custom_post_order_searchwp_fix( $wp_query ) {
    if ( is_search() && class_exists( 'SCPO_Engine' ) ) {
        // unset Simple Page Ordering filters
        if ( ! empty( $GLOBALS['wp_filter'] )
             && isset( $GLOBALS['wp_filter']['pre_get_posts'] )
             && ! empty( $GLOBALS['wp_filter']['pre_get_posts'] ) ) {
                foreach( $GLOBALS['wp_filter']['pre_get_posts'] as $key => $registered_filters ) {
                    foreach( $registered_filters as $registered_filter_key => $registered_filter ) {
                        if ( isset( $registered_filter['function'] )
                             && isset( $registered_filter['function'][0] )
                             && is_object( $registered_filter['function'][0] )
                             && $registered_filter['function'][0] instanceof SCPO_Engine ) {
                                unset( $GLOBALS['wp_filter']['pre_get_posts'][ $key ][ $registered_filter_key ] );
                        }
                    }
                }
        }
    }

    return $wp_query;
}

add_filter( 'pre_get_posts', 'my_custom_post_order_searchwp_fix', 999 );


/* Register Custom Navigation Walker */
require_once('wp_bootstrap_navwalker.php');

add_theme_support( 'menus' );

add_action( 'after_setup_theme', 'register_kokoda_menus' );
function register_kokoda_menus() {
  register_nav_menu( 'primary', __( 'Primary Menu', 'theme-slug' ) );
  register_nav_menu('mobile-main-menu', __('Mobile Primary Menu', 'theme-slug' ));
  register_nav_menu( 'top-menu', __( 'Top Menu', 'theme-slug' ) );
  register_nav_menu( 'footer-1', __( 'Footer Menu 1', 'theme-slug' ) );
  register_nav_menu( 'footer-2', __( 'Footer Menu 2', 'theme-slug' ) );
  register_nav_menu( 'footer-3', __( 'Footer Menu 3', 'theme-slug' ) );
  register_nav_menu( 'category-listing-caravan', __( 'Caravan Category Listing', 'theme-slug' ) );
  register_nav_menu( 'category-listing-camper', __( 'Camper Category Listing', 'theme-slug' ) );
  register_nav_menu( 'category-listing-hybrid', __( 'Hybrids Category Listing', 'theme-slug' ) );
  register_nav_menu( 'archive-primary', __( 'Primary Menu Archive Page', 'theme-slug' ) );
}

function wpse45700_get_menu_by_location( $location ) {
    if( empty($location) ) return false;

    $locations = get_nav_menu_locations();
    if( ! isset( $locations[$location] ) ) return false;

    $menu_obj = get_term( $locations[$location], 'nav_menu' );

    return $menu_obj;
}


/* ACF Options Page */
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}


/* title */
function kokoda_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'kokoda' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'kokoda_wp_title', 10, 2 );


/* get page slug function */
function the_slug($echo=true) {
  $slug = basename(get_permalink());
  
  do_action('before_slug', $slug);
  $slug = apply_filters('slug_filter', $slug);
  if( $echo ) echo $slug;
    do_action('after_slug', $slug);
  return $slug;
}


/* Remove stuff from wphead() */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );


/* force medium images to crop */
if(false === get_option("medium_crop"))
    add_option("medium_crop", "1");
else
    update_option("medium_crop", "1");
   
/* pagination */
function the_pagination($pages = '', $range = 2)
{  
     $showitems = ($range * 2)+1;  

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         echo "<div class='pagination'><span><strong>Pages:</strong></span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         echo "</div>\n";
     }
}

add_filter('searchwp_live_search_posts_per_page','search_live_search_post_per_page',10);


function search_live_search_post_per_page()
{
	return 20;
}



add_filter('searchwp_posts_per_page','search_post_per_page',10);

function search_post_per_page()
{
	return 50;
}



/** load caravan detail to detail panel at archive page by using AJAX **/

add_action('wp_ajax_archiveitem', 'loading_archive_item_detail_function');
add_action('wp_ajax_nopriv_archiveitem', 'loading_archive_item_detail_function');

function loading_archive_item_detail_function()
{

    if( isset( $_POST['post_id'] ))
	{

        set_query_var('caravan_id', $_POST['post_id']);

        get_template_part( 'content', 'caravan' );

    }
    die();
}

/** add filter to restrict the prodducts with category "Caravan Archive"  show on search result  **/

add_filter('searchwp_live_search_query_args','restrict_archive_caravan_at_search',10,1);

function restrict_archive_caravan_at_search($args)
{
    $terms = get_terms('product-cat','orderby=name' );
    $caravan_archive_category_id = 0;
    foreach ( $terms as $term ){
        if(in_array( $term->name ,array('Caravan Archive')))
        {
            $caravan_archive_category_id = $term->term_id;
            break;
        }
    }


    $args['tax_query'] = array(
        array(
            'taxonomy' => 'product-cat',
            'field'    => 'term_id',
            'terms'    => array($caravan_archive_category_id),
            'operator' => 'NOT IN'
        )
    );
    return $args;
}

/** ENDING THE CUSTOMIZE **/

/** redirect all the archive product template to 404 page or hide them from user.  **/
function redirect_archive_item($params=array()) {

    if( is_single()  && !is_user_logged_in() && !is_admin())
    {
        global $post;
        if ( get_query_var('post_type') ) {
            $post_type = get_query_var('post_type');
        }
        else
        {
            if ( $post )
                $post_type = $post->post_type;
        }

        if ( $post_type != 'product' )
            return;
        else
        {
            //redirect all archive item's access to homepage.
            if(has_term('Caravan Archive','product-cat',$post))
            {
                wp_redirect( home_url());
            }
        }
    }
}
add_action( 'template_redirect', 'redirect_archive_item' );
/** ENDING THE CUSTOMIZE **/

//add version number to all JS and Style files to clear the cache

add_action('admin_init', 'set_up_js_css_version_setting_function');
function set_up_js_css_version_setting_function()
{
    register_setting('general', 'js-css_version-id', 'esc_attr');
    add_settings_field(
        'js-css_version-id',
        'JS and Css Version Number',
        'js_css_version_number_setting_callback_function',
        'general',
        'default',
        array( 'label_for' => 'js-css_version-id' )
    );
}
function js_css_version_number_setting_callback_function()
{
    $value = get_option( 'js-css_version-id');
    echo '<input type="text" id="js-css_version-id" name="js-css_version-id" value="' . $value . '" />';
}

function set_custom_ver_css_js( $src ) {
    // version number from settings / general field
    //add default value when option isnot set yet
    if ( get_option( 'js-css_version-id' ) === false ) // Nothing yet saved
        update_option( 'js-css_version-id', '1' );

    $version =  get_option( 'js-css_version-id');
    if (!empty($version) ) {

        if ( strpos( $src, 'ver=' ) )
            // use the WP function add_query_arg()
            // to set the ver parameter in
            $src = add_query_arg( 'ver', $version, $src );
        return esc_url( $src );
    }
}
add_action('init', 'css_js_versioning');
function css_js_versioning() {
    add_filter( 'style_loader_src', 'set_custom_ver_css_js', 9999 ); 	// css files versioning
    add_filter( 'script_loader_src', 'set_custom_ver_css_js', 9999 ); // js files versioning
}
/**  ENDING FUNCTION  **/



?>




