<?php
/**
* Easily create Custom Post Types for WP using CPT.
*
* Check out https://github.com/gillchristian/CPT for examples on how to use it.
*
* @author Christian Gill
* @license GNU Lesser General Public License
*/

include_once('inflector.php');

class Post_Type {

	// cpt properties
	public $slug;
 	public $plural;
	public $title;
	public $plural_title;

	// arrays properties for custom post type options
	public $args = array();
	public $labels = array();

	/**
	 * Constructor
	 *
	 * @param {string: $slug}
	 */

	function __construct($slug){

		$this->slug = $slug;
		$this->plural = Inflector::pluralize($slug);
		$this->title = Inflector::titleize($slug);
		$this->plural_title = Inflector::titleize($this->plural);
		//die($this->plural_title);
	}

	/**
	 * Adds the action hook for registering a post type.
	 */
	public function register_post_type(){
		add_action('init', array($this, 'new_post_type' ));
	}

	/**
	 * Generates the $args array and registers the post type
	 *
	 * Callback passed to WordPress.
	 * By default is created not hierarchical, you can do $this->set_args to change it to hierarchical.
	 */
	public function new_post_type() {
		$args = cptProvider::cpt_args($this->slug, $this->plural, $this->title, $this->plural_title);

		$args['labels'] = array_merge($args['labels'], $this->labels);
		$args = array_merge($args, $this->args);

		register_post_type( $this->slug , $args );
	}

	/**
	 * Unregisters a Post Type
	 */
	public function unregister(){
		global $wp_post_types;
		if ( isset( $wp_post_types[ $this->slug ] ) ) unset( $wp_post_types[ $this->slug ] );
	}

	/**
	 * Set $args
	 * @param {$options: array}
	 */
	public function set_args ($options) {
		$this->args = $options;
	}

	/**
	 * Set $labels
	 * @param {$options: array}
	 */
	public function set_labels ($options) {
		$this->labels = $options;
	}

}

/**
 * Register taxonomies
 *
 */

class Taxonomy extends Post_Type{

	public $hierarchical;
	public $post_types;

	/**
	 * Adds the action hook for registering a Taxonomy.
	 *
	 * @param {string|array: $post_types} the post types to bind the taxonomy to.
	 * @param {boolean: $hierarchical = false} determinates whether the taxonomy is hierarchical or not.
	 */
	public function register_taxonomy($post_types, $hierarchical = false){
		$this->post_types = $post_types;
		$this->hierarchical = $hierarchical;
		add_action('init', array($this, 'new_taxonomy'));
	}

	/**
	 * Generates the $args array and registers the taxonomy
	 *
	 * Callback passed to WordPress.
	 * By default is created not hierarchical, you can change that by passing true to $this->register_taxonomy.
	 */
	public function new_taxonomy() {

		$args = $this->hierarchical ?
							cptProvider::category_args($this->slug, $this->plural, $this->title, $this->plural_title) :
							cptProvider::tag_args($this->slug, $this->plural, $this->title, $this->plural_title);

		$args['labels'] = array_merge($args['labels'], $this->labels);
		$args = array_merge($args, $this->args);

    register_taxonomy( $this->slug, $this->post_types, $args );
	}

}

/**
 * CPT Provider Class
 *
 * Methods return the defaults CPT and Taxonomies $args arrays
 */
class cptProvider {

	/**
	 * Custom Post Type arguments
	 *
	 * @param {string: $slug} Slug of the CPT
	 * @param {string: $plural_slug} Plural slug
	 * @param {string: $name} Name of the  CPT
	 * @param {string: $plural_slug} Plural Name
	 *
	 * @return {array: $args} default $args with the injected parameters
	 */
	static function cpt_args($slug, $plural_slug, $name, $plural_name) {
		$labels = array(
			'name'               => _x( $plural_name, 'post type general name', 'your-plugin-textdomain' ),
			'singular_name'      => _x( $name, 'post type singular name', 'your-plugin-textdomain' ),
			'menu_name'          => _x( $plural_name, 'admin menu', 'your-plugin-textdomain' ),
			'name_admin_bar'     => _x( $name, 'add new on admin bar', 'your-plugin-textdomain' ),
			'add_new'            => _x( 'Add New', $slug, 'your-plugin-textdomain' ),
			'add_new_item'       => __( 'Add New '.$name, 'your-plugin-textdomain' ),
			'new_item'           => __( 'New '.$name, 'your-plugin-textdomain' ),
			'edit_item'          => __( 'Edit '.$name, 'your-plugin-textdomain' ),
			'view_item'          => __( 'View '.$name, 'your-plugin-textdomain' ),
			'all_items'          => __( 'All '.$plural_slug, 'your-plugin-textdomain' ),
			'search_items'       => __( 'Search '.$plural_slug, 'your-plugin-textdomain' ),
			'parent_item_colon'  => __( 'Parent '.$plural_slug.':', 'your-plugin-textdomain' ),
			'not_found'          => __( 'No '.$plural_name.' found.', 'your-plugin-textdomain' ),
			'not_found_in_trash' => __( 'No '.$plural_name.' found in Trash.', 'your-plugin-textdomain' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( $name.' post type.', 'your-plugin-textdomain' ),
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
	 * @param {string: $slug} Slug of the Taxonomy
	 * @param {string: $plural_slug} Plural slug
	 * @param {string: $name} Name of the  Taxonomy
	 * @param {string: $plural_slug} Plural Name
	 *
	 * @return {array: $args} default $args with the injected parameters
	 */

	static function category_args($slug, $plural_slug, $name, $plural_name){
		$labels = array(
			'name'              => _x( $plural_name, 'taxonomy general name' ),
			'singular_name'     => _x( $name, 'taxonomy singular name' ),
			'search_items'      => __( 'Search '.$plural_name ),
			'all_items'         => __( 'All '.$plural_name ),
			'parent_item'       => __( 'Parent '.$name ),
			'parent_item_colon' => __( 'Parent '.$name.':' ),
			'edit_item'         => __( 'Edit '.$name ),
			'update_item'       => __( 'Update '.$name ),
			'add_new_item'      => __( 'Add New '.$name ),
			'new_item_name'     => __( 'New '.$name.' Name' ),
			'menu_name'         => __( $name ),
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
	 * @param {string: $slug} Slug of the Taxonomy
	 * @param {string: $plural_slug} Plural slug
	 * @param {string: $name} Name of the  Taxonomy
	 * @param {string: $plural_slug} Plural Name
	 *
	 * @return {array: $args} default $args with the injected parameters
	 */

	static function tag_args($slug, $plural_slug, $name, $plural_name){
		$labels = array(
			'name'                       => _x( $plural_name, 'taxonomy general name' ),
			'singular_name'              => _x( $name, 'taxonomy singular name' ),
			'search_items'               => __( 'Search '.$plural_name ),
			'popular_items'              => __( 'Popular '.$plural_name ),
			'all_items'                  => __( 'All '.$plural_name ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit '.$name ),
			'update_item'                => __( 'Update '.$name ),
			'add_new_item'               => __( 'Add New '.$name ),
			'new_item_name'              => __( 'New '.$name.' Name' ),
			'separate_items_with_commas' => __( 'Separate '.$plural_slug.' with commas' ),
			'add_or_remove_items'        => __( 'Add or remove '.$plural_slug ),
			'choose_from_most_used'      => __( 'Choose from the most used '.$plural_slug ),
			'not_found'                  => __( 'No '.$plural_slug.' found.' ),
			'menu_name'                  => __( $plural_name ),
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
