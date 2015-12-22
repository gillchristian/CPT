<?php
/**
* Easily create Custom Post Types for WP using CPT.
*
* Check out https://github.com/gillchristian/CPT for examples on how to use it.
*
* @author Christian Gill
* @license GNU Lesser General Public License
* @since 0.0.1
* @version 0.2.0
*/

include_once('inflector.php');

class PostType {

	// cpt properties
	public $name;
	public $plural;
	public $title;
	public $plural_title;

	public $slug;

	// arrays properties for custom post type options
	public $args = array();
	public $labels = array();

	/**
	 * Constructor
	 *
	 * @param {string} singular name
	 */

	function __construct($name){

		$this->name = $name;
		$this->plural = Inflector::pluralize($name);
		$this->title = Inflector::titleize($name);
		$this->plural_title = Inflector::titleize($this->plural);

		$this->slug = Inflector::underscore($name);
	}

	/**
	 * Adds the action hook for registering a post type.
	 */
	public function register(){
		add_action('init', array($this, 'new_post_type' ));
	}

	/**
	 * Generates the $args array and registers the post type
	 *
	 * Callback passed to WordPress.
	 * By default is created not hierarchical, you can do $this->set_args to change it to hierarchical.
	 */
	public function new_post_type() {
		$args = cptProvider::cpt_args($this->slug, $this->name, $this->plural, $this->title, $this->plural_title);

		$args['labels'] = array_merge($args['labels'], $this->labels);
		$args = array_merge($args, $this->args);

		register( $this->slug , $args );
	}

	/**
	 * Unregister post type
	 */
	public function unregister(){
		global $wp_post_types;
		if ( isset( $wp_post_types[ $this->slug ] ) ) unset( $wp_post_types[ $this->slug ] );
	}

	/**
	 * Set $args
	 *
	 * @param {$options: array}
	 */
	public function set_args ($options) {
		$this->args = $options;
	}

	/**
	 * Set $labels
	 *
	 * @param {$labels} labels
	 */
	public function set_labels ($labels) {
		$this->labels = $labels;
	}

}

/**
 * Register taxonomies
 *
 */

class Taxonomy extends PostType{

	public $hierarchical;
	public $post_types;

	/**
	 * Adds the action hook for registering a Taxonomy.
	 *
	 * @param {string|array} post type/s
	 * @param {boolean} hierarchical
	 */
	public function register($post_types, $hierarchical = false){
		$this->post_types = $post_types;
		$this->hierarchical = $hierarchical;
		add_action('init', array($this, 'new_taxonomy'));
	}

	/**
	 * Generates the $args array and registers the taxonomy
	 *
	 * Callback passed to WordPress.
	 * By default is created not hierarchical, you can change that by passing true to $this->register().
	 */
	public function new_taxonomy() {

		$args = $this->hierarchical ?
							cptProvider::category_args($this->slug, $this->name, $this->plural, $this->title, $this->plural_title) :
							cptProvider::tag_args($this->slug, $this->name, $this->plural, $this->title, $this->plural_title);

		$args['labels'] = array_merge($args['labels'], $this->labels);
		$args = array_merge($args, $this->args);

    register( $this->slug, $this->post_types, $args );
	}

}

/**
 * CPT Provider Class
 *
 * Methods return the defaults CPT and Taxonomies $args arrays
 */
class cptProvider {

	/**
	 * Getter for the WP Theme Text Domain
	 *
	 * @access public
	 * @static
	 * @return {string} Actual theme text domain
	 */
	static function getTextDomain(){
		return wp_get_theme()->get('TextDomain ');
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
	static function cpt_args($slug, $singular, $plural, $name, $plural_name) {
		$domain = self::getTextDomain();

		$labels = array(
			'name'               => sprintf( _x( '%s', 'post type general name', $domain ), $plural_name ),
			'singular_name'      => sprintf( _x( '%s', 'post type singular name', $domain ), $name ),
			'menu_name'          => sprintf( _x( '%s', 'admin menu name', $domain ), $plural_name ),
			'name_admin_bar'     => sprintf( _x( '%s', 'add new on admin bar', $domain ), $name ),
			'add_new'            => _x( 'Add New', 'add new menu option', $domain ),
			'add_new_item'       => sprintf( _x( 'Add New %s', 'add new single view', $domain ), $name ),
			'new_item'           => sprintf( __( 'New %s', $domain ), $name ),
			'edit_item'          => sprintf( __( 'Edit %s', $domain ), $name ),
			'view_item'          => sprintf( __( 'View %s', $domain ), $name ),
			'all_items'          => sprintf( __( 'All %s', $domain ), $plural ),
			'search_items'       => sprintf( __( 'Search %s', $domain ), $plural ),
			'parent_item_colon'  => sprintf( __( 'Parent %s:', $domain ), $plural ),
			'not_found'          => sprintf( __( 'No %s found.', $domain ), $plural_name ),
			'not_found_in_trash' => sprintf( __( 'No %s found in Trash.', $domain ), $plural_name )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => sprintf( _x( '%s post type.', 'description', $domain ), $name ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $slug ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
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

	static function category_args($slug, $singular, $plural_slug, $name, $plural_name){
		$domain = self::getTextDomain();

		$labels = array(
			'name'              			=> sprintf( _x( '%s', 'taxonomy general name', $domain ), $plural_name ),
			'singular_name'           => sprintf( _x( '%s', 'taxonomy singular name', $domain ), $name ),
			'search_items'            => sprintf( __( 'Search %s', $domain ) , $plural_name ),
			'popular_items'           => sprintf( __( 'Popular %s', $domain ), $plural_name ),
			'all_items'               => sprintf( __( 'All %s', $domain ), $plural_name ),
			'parent_item'       			=> sprintf( __( 'Parent %s', $domain ), $name ),
			'parent_item_colon' 			=> sprintf( __( 'Parent %s:', $domain ), $name),
			'edit_item'               => sprintf( __( 'Edit %s', $domain ), $name ),
			'update_item'             => sprintf( __( 'Update %s', $domain ), $name ),
			'add_new_item'            => sprintf( __( 'Add New %s', $domain ), $name ),
			'new_item_name'           => sprintf( __( 'New %s Name', $domain ), $name ),
			'add_or_remove_items'     => sprintf( __( 'Add or remove %s', $domain ), $plural ),
			'choose_from_most_used' 	=> sprintf( __( 'Choose from the most used %s', $domain ), $plural ),
			'not_found'               => sprintf( __( 'No %s found.', $domain ), $plural ),
			'menu_name'               => sprintf( _x( '%s', 'menu name', $domain ), $plural_name ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $slug ),
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

	static function tag_args($slug, $singular, $plural, $name, $plural_name){
		$domain = self::getTextDomain();

		$labels = array(
			'name'                       => sprintf( _x( '%s', 'taxonomy general name', $domain ), $plural_name ),
			'singular_name'              => sprintf( _x( '%s', 'taxonomy singular name', $domain ), $name ),
			'search_items'               => sprintf( __( 'Search %s', $domain ) , $plural_name ),
			'popular_items'              => sprintf( __( 'Popular %s', $domain ), $plural_name ),
			'all_items'                  => sprintf( __( 'All %s', $domain ), $plural_name ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => sprintf( __( 'Edit %s', $domain ), $name ),
			'update_item'                => sprintf( __( 'Update %s', $domain ), $name ),
			'add_new_item'               => sprintf( __( 'Add New %s', $domain ), $name ),
			'new_item_name'              => sprintf( __( 'New %s Name', $domain ), $name ),
			'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', $domain ), $plural ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', $domain ), $plural ),
			'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', $domain ), $plural ),
			'not_found'                  => sprintf( __( 'No %s found.', $domain ), $plural ),
			'menu_name'                  => sprintf( _x( '%s', 'menu name', $domain ), $plural_name ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => $slug ),
		);
		return $args;
	}
}
