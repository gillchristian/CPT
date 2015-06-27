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

class CPT
{
	public $type;
 	private $plural;
	private $title;
	private $plural_title;

	//Constructor
 
	function __construct($par_name){
		
		$this->type = $par_name;
		$this->plural = Inflector::pluralize($par_name);
		$this->title = Inflector::titleize($par_name);
		$this->plural_title = Inflector::titleize($this->plural);
	}

	//Functions

	//Post type register
	
	public function add_post_type(){	

		add_action('init', 'custom_post_register'); 
		$this->custom_post_register();
		
	}

	private function custom_post_register() {

		$name = $this->type;
		$plural = $this->plural;
		$title = $this->title;
		$plural_title = $this->plural_title;

	    $labels = array(
			'name'               => _x( $plural_title, 'post type general name'),
			'singular_name'      => _x( $title, 'post type singular name'),
			'menu_name'          => _x( $plural_title, 'admin menu'),
			'name_admin_bar'     => _x( $title, 'add new on admin bar'),
			'add_new'            => _x( 'Add New', $name),
			'add_new_item'       => __( "Add New $title"),
			'new_item'           => __( "New $title"),
			'edit_item'          => __( "Edit $title"),
			'view_item'          => __( "View $title"),
			'all_items'          => __( "All $plural_title"),
			'search_items'       => __( "Search $plural_title"),
			'parent_item_colon'  => __( "Parent $plural_title:"),
			'not_found'          => __( "No $plural found."),
			'not_found_in_trash' => __( "No $plural found in Trash.")
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $name ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail')
		);

	    register_post_type( $name , $args );
	}

	// Unregister post type
	
	public function unregister_post_type(){
	
		global $wp_post_types;
		
		if ( isset( $wp_post_types[ $this->type ] ) ) unset( $wp_post_types[ $this->type ] );
	}

}