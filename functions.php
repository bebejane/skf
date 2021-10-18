<?php

// LOAD BONES CORE
require_once "library/bones.php";

// USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
require_once "library/cpts/news.php";
require_once "library/cpts/exhibitions.php";
require_once "library/cpts/activities.php";
require_once "library/cpts/lotteries.php";
require_once "library/cpts/gallery.php";
require_once "library/cpts/newsletter.php";

// CUSTOMIZE THE WORDPRESS ADMIN
// require_once( 'library/admin.php' );

/*********************
LAUNCH BONES
*********************/

function bones_ahoy()
{
	// let's get language support going, if you need it
	load_theme_textdomain("bonestheme", get_template_directory() . "/library/translation");

	// launching operation cleanup
	add_action("init", "bones_head_cleanup");
	// A better title
	add_filter("wp_title", "rw_title", 10, 3);
	// remove WP version from RSS
	add_filter("the_generator", "bones_rss_version");
	// remove pesky injected css for recent comments widget
	add_filter("wp_head", "bones_remove_wp_widget_recent_comments_style", 1);
	// clean up comment styles in the head
	add_action("wp_head", "bones_remove_recent_comments_style", 1);
	// clean up gallery output in wp
	add_filter("gallery_style", "bones_gallery_style");

	// enqueue base scripts and styles
	add_action("wp_enqueue_scripts", "bones_scripts_and_styles", 999);
	// ie conditional wrapper

	// launching this stuff after theme setup
	bones_theme_support();

	// adding sidebars to Wordpress (these are created in functions.php)
	//add_action( 'widgets_init', 'bones_register_sidebars' );

	// cleaning up random code around images
	add_filter("the_content", "bones_filter_ptags_on_images");
	// cleaning up excerpt
	add_filter("excerpt_more", "bones_excerpt_more");

	add_filter("tiny_mce_before_init", "override_mp6_tinymce_styles");
	function override_mp6_tinymce_styles($mce_init)
	{
		// make sure we don't override other custom <code>content_css</code> files
		$content_css = trailingslashit(get_template_directory_uri()) . "library/css/editor-style.css";
		if (isset($mce_init["content_css"])) {
			$content_css_new = $mce_init["content_css"] . "," . $content_css;
		}

		$mce_init["content_css"] = $content_css_new;

		return $mce_init;
	}
} /* end bones ahoy */

// let's get this party started
add_action("after_setup_theme", "bones_ahoy");

/************* OEMBED SIZE OPTIONS *************/

if (!isset($content_width)) {
	$content_width = 640;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size("Admin thumb", 60, 60, true);
add_image_size("Large Retina", 2280, 2280);

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter("image_size_names_choose", "bones_custom_image_sizes");

function bones_custom_image_sizes($sizes)
{
	return array_merge($sizes, [
		//'bones-thumb-600' => __('600px by 150px'),
		//'bones-thumb-300' => __('300px by 100px'),
	]);
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

// Remove <p> on Category Description
remove_filter("term_description", "wpautop");

// Ta bort länkar kring bilder
add_filter("the_content", "attachment_image_link_remove_filter");
function attachment_image_link_remove_filter($content)
{
	$content = preg_replace(["{<a(.*?)(wp-att|wp-content/uploads)[^>]*><img}", '{ wp-image-[0-9]*" /></a>}'], ["<img", '" />'], $content);
	return $content;
}

function clear_br($content)
{
	return str_replace("<br />", "<br clear='none'/>", $content);
}
add_filter("the_content", "clear_br");

//ADD CATEGORY NAME TO BODY class
add_filter("body_class", "add_category_to_single");
function add_category_to_single($classes)
{
	if (is_single()) {
		global $post;
		foreach (get_the_category($post->ID) as $category) {
			// add category slug to the $classes array
			$classes[] = $category->category_nicename;
		}
	}
	// return the $classes array
	return $classes;
}

// OPTIONS PAGE
add_action("acf/init", "my_acf_op_init");
function my_acf_op_init()
{
	// Check function exists.
	if (function_exists("acf_add_options_page")) {
		// Register options page.
		$option_page = acf_add_options_page([
			"page_title" => __("Inställningar"),
			"menu_title" => __("Inställningar"),
			"menu_slug" => "theme-general-settings",
			"capability" => "edit_posts",
			"redirect" => false,
		]);
	}
}
/**
 * Responsive Image Helper Function
 *
 * @param string $image_id the id of the image (from ACF or similar)
 * @param string $image_size the size of the thumbnail image or custom image size
 * @param string $max_width the max width this image will be shown to build the sizes attribute
 */

function ar_responsive_image($image_id, $image_size, $max_width)
{
	// check the image ID is not blank
	if ($image_id != "") {
		// set the default src image size
		$image_src = wp_get_attachment_image_url($image_id, $image_size);

		// set the srcset with various image sizes
		$image_srcset = wp_get_attachment_image_srcset($image_id, $image_size);

		// generate the markup for the responsive image
		echo 'src="' . $image_src . '" srcset="' . $image_srcset . '" sizes="(max-width: ' . $max_width . ") 100vw, " . $max_width . '"';
	}
}

// SRCSET
add_theme_support("post-thumbnails");

/**
 * Add Post Type & Post Name to Body Class
 * For example: page-about-us
 */
add_filter("body_class", "add_post_class");
function add_post_class($classes)
{
	global $post;
	if (isset($post)) {
		$classes[] = $post->post_type . "-" . $post->post_name;
	}
	return $classes;
}

// Make the search to index custom
/**
 * Extend WordPress search to include custom fields
 * http://adambalee.com
 *
 * Join posts and postmeta tables
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */
function cf_search_join($join)
{
	global $wpdb;
	if (is_search()) {
		$join .= " LEFT JOIN " . $wpdb->postmeta . " ON " . $wpdb->posts . ".ID = " . $wpdb->postmeta . ".post_id ";
	}
	return $join;
}
add_filter("posts_join", "cf_search_join");

/**
 * Modify the search query with posts_where
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
function cf_search_where($where)
{
	global $pagenow, $wpdb;
	if (is_search()) {
		$where = preg_replace("/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/", "(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where);
	}
	return $where;
}
add_filter("posts_where", "cf_search_where");

/**
 * Prevent duplicates
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
 */
function cf_search_distinct($where)
{
	global $wpdb;
	if (is_search()) {
		return "DISTINCT";
	}
	return $where;
}
add_filter("posts_distinct", "cf_search_distinct");

//Remove Comments
// First, this will disable support for comments and trackbacks in post types
function df_disable_comments_post_types_support()
{
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if (post_type_supports($post_type, "comments")) {
			remove_post_type_support($post_type, "comments");
			remove_post_type_support($post_type, "trackbacks");
		}
	}
}
# https://keithgreer.uk/wordpress-code-completely-disable-comments-using-functions-php

add_action("admin_init", "df_disable_comments_post_types_support");

// Then close any comments open comments on the front-end just in case
function df_disable_comments_status()
{
	return false;
}
add_filter("comments_open", "df_disable_comments_status", 20, 2);
add_filter("pings_open", "df_disable_comments_status", 20, 2);

// Finally, hide any existing comments that are on the site.
function df_disable_comments_hide_existing_comments($comments)
{
	$comments = [];
	return $comments;
}
add_filter("comments_array", "df_disable_comments_hide_existing_comments", 10, 2);

//Disable comments
add_action("admin_init", function () {
	// Redirect any user trying to access comments page
	global $pagenow;

	if ($pagenow === "edit-comments.php") {
		wp_redirect(admin_url());
		exit();
	}

	// Remove comments metabox from dashboard
	remove_meta_box("dashboard_recent_comments", "dashboard", "normal");

	// Disable support for comments and trackbacks in post types
	foreach (get_post_types() as $post_type) {
		if (post_type_supports($post_type, "comments")) {
			remove_post_type_support($post_type, "comments");
			remove_post_type_support($post_type, "trackbacks");
		}
	}
});

// Close comments on the front-end
add_filter("comments_open", "__return_false", 20, 2);
add_filter("pings_open", "__return_false", 20, 2);

// Hide existing comments
add_filter("comments_array", "__return_empty_array", 10, 2);

// Remove comments page in menu
add_action("admin_menu", function () {
	remove_menu_page("edit-comments.php");
});

// Remove comments links from admin bar
add_action("init", function () {
	if (is_admin_bar_showing()) {
		remove_action("admin_bar_menu", "wp_admin_bar_comments_menu", 60);
	}
});

// Remove standard posts from admin menu
function post_remove()
{
	remove_menu_page("edit.php");
}
add_action("admin_menu", "post_remove");

// Custom toolbar for ACF text fields
add_filter("acf/fields/wysiwyg/toolbars", "my_toolbars");
function my_toolbars($toolbars)
{
	// Uncomment to view format of $toolbars
	/*
	echo '< pre >';
		print_r($toolbars);
	echo '< /pre >';
	die;
	*/

	// Add a new toolbar called "Very Simple"
	// - this toolbar has only 1 row of buttons
	$toolbars["Very Simple"] = [];
	$toolbars["Very Simple"][1] = ["bold,italic,bullist,link,unlink,removeformat,undo,redo,custom"];

	// Edit the "Full" toolbar and remove 'code'
	// - delet from array code from http://stackoverflow.com/questions/7225070/php-array-delete-by-value-not-key
	if (($key = array_search("code", $toolbars["Full"][2])) !== false) {
		unset($toolbars["Full"][2][$key]);
	}

	// remove the 'Basic' toolbar completely
	unset($toolbars["Basic"]);

	// return $toolbars - IMPORTANT!
	return $toolbars;
}

//Remove page attributes for other than admin

function remove_page_attribute_meta_box()
{
	if (!is_super_admin()) {
		remove_meta_box("pageparentdiv", "page", "normal");
	}
}
add_action("admin_menu", "remove_page_attribute_meta_box");

//Hide specific pages from other than admin
function jp_exclude_pages_from_admin($query)
{
	if (!is_admin()) {
		return $query;
	}

	global $pagenow, $post_type;

	if (!current_user_can("administrator") && $pagenow == "edit.php" && $post_type == "page") {
		$query->query_vars["post__not_in"] = ["14", "17", "22"];
	} // Enter your page IDs here
}
add_filter("parse_query", "jp_exclude_pages_from_admin");

//Custom admin for editors
function remove_menus()
{
	// get current login user's role
	$roles = wp_get_current_user()->roles;

	// test role
	if (!in_array("editor", $roles)) {
		return;
	}

	//remove menu from site backend.
	remove_menu_page("index.php"); //Dashboard
	remove_menu_page("edit-comments.php"); //Comments
	remove_menu_page("tools.php"); //Tools
	remove_menu_page("options-general.php"); //Settings
}
add_action("admin_menu", "remove_menus", 100);

//Landscape or portrait

function r0bsc0tt_single_image_filter($size)
{
	$tn_id = get_post_thumbnail_id($post->ID);
	$img = wp_get_attachment_image_src($tn_id, $size);
	$width = $img[1];
	$height = $img[2];
	if (has_post_thumbnail($post->ID)) {
		if ($width > $height) { ?>
			<?php the_post_thumbnail($size, ["class" => "attachment-" . $size . " size-" . $size . " landscape"]);} else { ?>
			<?php the_post_thumbnail($size, ["class" => "attachment-" . $size . " size-" . $size . " portrait"]);}
	}
}

// Clear dashboard

function remove_dashboard_widgets()
{
	global $wp_meta_boxes;

	unset($wp_meta_boxes["dashboard"]["side"]["core"]["dashboard_quick_press"]);
	unset($wp_meta_boxes["dashboard"]["normal"]["core"]["dashboard_incoming_links"]);
	unset($wp_meta_boxes["dashboard"]["normal"]["core"]["dashboard_right_now"]);
	unset($wp_meta_boxes["dashboard"]["normal"]["core"]["dashboard_plugins"]);
	unset($wp_meta_boxes["dashboard"]["normal"]["core"]["dashboard_recent_drafts"]);
	unset($wp_meta_boxes["dashboard"]["normal"]["core"]["dashboard_recent_comments"]);
	unset($wp_meta_boxes["dashboard"]["side"]["core"]["dashboard_primary"]);
	unset($wp_meta_boxes["dashboard"]["side"]["core"]["dashboard_secondary"]);
}

add_action("wp_dashboard_setup", "remove_dashboard_widgets");

// Lookup WPForms form ids
if (function_exists("wpforms")) {
	$forms = wpforms()->form->get();
	$participate_form_index = array_key_first(array_filter($forms, function($f){ return $f->post_title == 'Medlemsansökan'; }));
	$member_form_index = array_key_first(array_filter($forms, function($f){ return $f->post_title == 'Anmälan'; }));
	
	$participate_id = is_numeric($participate_form_index) ? $forms[$participate_form_index]->ID : null;
	$member_id = is_numeric($member_form_index) ? $forms[$member_form_index]->ID : null;
}

// Debug helper
function debug($obj)
{
	error_log(print_r($obj, true));
}

/* DON'T DELETE THIS CLOSING TAG */

?>
