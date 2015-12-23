<?php
/**
* CPT Provider Class
*
* Methods return the defaults CPT and Taxonomies $args arrays
*
* Check out https://github.com/gillchristian/CPT for examples on how to use it.
*
* @author Christian Gill
* @license GNU Lesser General Public License
* @since 0.2.0
* @version 0.3.0
*/

class CptProvider {

	/**
	 * Getter for the WP Theme Text Domain
	 *
	 * @access public
	 * @static
	 * @return {string} Actual theme text domain
	 */
	static function getTextDomain(){
		return wp_get_theme()->get('TextDomain');
	}

	/**
	 * Custom Post Type arguments
	 *
	 * @access public
	 * @static
	 * @param {string} slug
	 * @param {string} singular name
	 * @param {string} plural name
	 * @param {string} capitalized name
	 * @param {string} capitalized plural name
	 *
	 * @return {array} default $args with the injected parameters
	 */
	static function postArgs($slug, $singular, $plural, $name, $pluralName) {
		$domain = self::getTextDomain();

		$labels = array(
			'name'               => sprintf( _x('%s', 'post type general name', $domain), $pluralName ),
			'singular_name'      => sprintf( _x('%s', 'post type singular name', $domain), $name ),
			'menu_name'          => sprintf( _x('%s', 'admin menu name', $domain), $pluralName ),
			'name_admin_bar'     => sprintf( _x('%s', 'add new on admin bar', $domain), $name ),
			'add_new'            => _x('Add New', 'add new menu option', $domain),
			'add_new_item'       => sprintf( _x('Add New %s', 'add new single view', $domain), $name ),
			'new_item'           => sprintf( __('New %s', $domain), $name ),
			'edit_item'          => sprintf( __('Edit %s', $domain), $name ),
			'view_item'          => sprintf( __('View %s', $domain), $name ),
			'all_items'          => sprintf( __('All %s', $domain), $plural ),
			'search_items'       => sprintf( __('Search %s', $domain), $plural ),
			'parent_item_colon'  => sprintf( __('Parent %s:', $domain), $plural ),
			'not_found'          => sprintf( __('No %s found.', $domain), $pluralName ),
			'not_found_in_trash' => sprintf( __('No %s found in Trash.', $domain), $pluralName )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => sprintf( _x('%s post type.', 'description', $domain), $name ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array('slug' => $slug),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
		);
		return $args;
	}

	/**
	 * Custom Hierarchical Taxonomy Arguments
	 *
	 * @access public
	 * @static
	 * @param {string} slug
	 * @param {string} singular name
	 * @param {string} plural name
	 * @param {string} capitalized name
	 * @param {string} capitalized plural name
	 *
	 * @return {array} default $args with the injected parameters
	 */

	static function categoryArgs($slug, $singular, $plural_slug, $name, $pluralName){
		$domain = self::getTextDomain();

		$labels = array(
			'name'              			=> sprintf( _x('%s', 'taxonomy general name', $domain), $pluralName ),
			'singular_name'           => sprintf( _x('%s', 'taxonomy singular name', $domain), $name ),
			'search_items'            => sprintf( __('Search %s', $domain) , $pluralName ),
			'popular_items'           => sprintf( __('Popular %s', $domain), $pluralName ),
			'all_items'               => sprintf( __('All %s', $domain), $pluralName ),
			'parent_item'       			=> sprintf( __('Parent %s', $domain), $name ),
			'parent_item_colon' 			=> sprintf( __('Parent %s:', $domain), $name),
			'edit_item'               => sprintf( __('Edit %s', $domain), $name ),
			'update_item'             => sprintf( __('Update %s', $domain), $name ),
			'add_new_item'            => sprintf( __('Add New %s', $domain), $name ),
			'new_item_name'           => sprintf( __('New %s Name', $domain), $name ),
			'add_or_remove_items'     => sprintf( __('Add or remove %s', $domain), $plural ),
			'choose_from_most_used' 	=> sprintf( __('Choose from the most used %s', $domain), $plural ),
			'not_found'               => sprintf( __('No %s found.', $domain), $plural ),
			'menu_name'               => sprintf( _x('%s', 'menu name', $domain), $pluralName ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array('slug' => $slug),
		);
		return $args;
	}

	/**
	 * Custom Not Hierarchical Taxonomy Arguments
	 *
	 * @access public
	 * @static
	 * @param {string} slug
	 * @param {string} singular name
	 * @param {string} plural name
	 * @param {string} capitalized name
	 * @param {string} capitalized plural name
	 *
	 * @return {array} default $args with the injected parameters
	 */

	static function tagArgs($slug, $singular, $plural, $name, $pluralName){
		$domain = self::getTextDomain();

		$labels = array(
			'name'                       => sprintf( _x('%s', 'taxonomy general name', $domain), $pluralName ),
			'singular_name'              => sprintf( _x('%s', 'taxonomy singular name', $domain), $name ),
			'search_items'               => sprintf( __('Search %s', $domain) , $pluralName ),
			'popular_items'              => sprintf( __('Popular %s', $domain), $pluralName ),
			'all_items'                  => sprintf( __('All %s', $domain), $pluralName ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => sprintf( __('Edit %s', $domain), $name ),
			'update_item'                => sprintf( __('Update %s', $domain), $name ),
			'add_new_item'               => sprintf( __('Add New %s', $domain), $name ),
			'new_item_name'              => sprintf( __('New %s Name', $domain), $name ),
			'separate_items_with_commas' => sprintf( __('Separate %s with commas', $domain), $plural ),
			'add_or_remove_items'        => sprintf( __('Add or remove %s', $domain), $plural ),
			'choose_from_most_used'      => sprintf( __('Choose from the most used %s', $domain), $plural ),
			'not_found'                  => sprintf( __('No %s found.', $domain), $plural ),
			'menu_name'                  => sprintf( _x('%s', 'menu name', $domain), $pluralName ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array('slug' => $slug),
		);
		return $args;
	}
}
