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

	/**
   * CPT Name
   *
   * @var string
   * @access public
   */
	public $name;

	/**
	 * CPT Plural Name
	 *
	 * @var string
	 * @access public
	 */
	public $plural;

	/**
	 * CPT Uppercased Name
	 *
	 * @var string
	 * @access public
	 */
	public $title;

	/**
	 * CPT Uppercased Plural Name
	 *
	 * @var string
	 * @access public
	 */
	public $pluralTitle;

	/**
   * CPT Slug
   *
   * @var string
   * @access public
   */
	public $slug;

	/**
	 * Holds the Arguments the CPT is registered with
	 *
	 * @var array
	 * @access public
	 */
	public $args = array();

	/**
	 * Holds the Labels the CPT is registered with
	 *
	 * @var array
	 * @access public
	 */
	public $labels = array();

	/**
	 * Constructor
	 *
	 * @param {string} singular name
	 * @param {string} plural name
	 * @param {string} CPT slug
	 */

	function __construct($name, $plural = false, $slug = false){

		$this->name = $name;
		$this->plural = $plural ? $plural : Inflector::pluralize($name);
		$this->title = Inflector::titleize($name);
		$this->pluralTitle = Inflector::titleize($this->plural);

		$this->slug = $slug ? $slug : Inflector::underscore($name);
	}

	/**
	 * Adds the action hook for registering a post type.
	 *
	 * By default is created not hierarchical, you can change that by
	 * by calling {PostType object}->setArgs( array('hierarchical' => false,) ).
	 */
	public function register(){
		add_action( 'init', array($this, 'newPostType') );
	}

	/**
	 * Generates the $args array and registers the post type
	 *
	 * Callback passed to WordPress.
	 * DO NOT EXECUTE THIS METHOD!
	 */
	public function newPostType() {
		$args = CptProvider::postArgs($this->slug, $this->name, $this->plural, $this->title, $this->pluralTitle);

		register_post_type( $this->slug , $this->mergeArgs($args) );
	}

	/**
	 * Unregister the CPT
	 */
	public function unregister(){
		global $wp_post_types;
		if ( isset( $wp_post_types[ $this->slug ] ) )
			unset( $wp_post_types[ $this->slug ] );
	}

	/**
	 * Set $args
	 *
	 * @param {$options: array}
	 */
	public function setArgs ($options) {
		$this->args = $options;
	}

	/**
	 * Set $labels
	 *
	 * @param {$labels} labels
	 */
	public function setLabels ($labels) {
		$this->labels = $labels;
	}

	/**
	 * Merge default parameters with the custom ones
	 *
	 * @return {array} mixed parameters and labesl for the registration
	 */
	public function mergeArgs($args){
		$args['labels'] = array_merge($args['labels'], $this->labels);
		$args = array_merge($args, $this->args);

		return $args;
	}

}

/**
 * Register taxonomies
 *
 */

class Taxonomy extends PostType{

	/**
	 * Whether or not the Taxonomy is created hierarchical
	 *
	 * @var bool
	 * @access public
	 */
	public $hierarchical;

	/**
	 * The post types the Taxonomy is registered to
	 *
	 * @var string|array
	 * @access public
	 */
	public $postTypes;

	/**
	 * Adds the action hook for registering a Taxonomy.
	 *
	 * By default is created not hierarchical, you can change that by
	 * passing true on the second argument to $this->register.
	 *
	 * @param {string|array} post type/s to register the Taxonomy to
	 * @param {boolean} hierarchical
	 */
	public function register($postTypes, $hierarchical = false){
		$this->postTypes = $postTypes;
		$this->hierarchical = $hierarchical;
		add_action( 'init', array($this, 'newTaxonomy') );
	}

	/**
	 * Generates the $args array and registers the taxonomy
	 *
	 * Callback passed to WordPress.
	 * DO NOT EXECUTE THIS METHOD!
	 */
	public function newTaxonomy() {
		$args = $this->hierarchical ?
							CptProvider::categoryArgs($this->slug, $this->name, $this->plural, $this->title, $this->pluralTitle) :
							CptProvider::tagArgs($this->slug, $this->name, $this->plural, $this->title, $this->pluralTitle);

    register_taxonomy( $this->slug, $this->postTypes, $this->mergeArgs($args) );
	}

}

/**
 * CPT Provider Class
 *
 * Methods return the defaults CPT and Taxonomies $args arrays
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
