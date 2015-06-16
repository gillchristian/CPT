<?php
/**
* Easily create Custom Post Types for WP using CPT.
*
* Check out https://github.com/gillchristian/CPT for examples on how to use it.
*
* @author Christian Gill
* @license GNU Lesser General Public License
*/

class CPT
{
	private $type;
 	private $plural;
	private $title;
	private $plural_title;

	private $tax;
 	private $tax_plural;
	private $tax_title;
	private $tax_plural_title;

	//Constructor
 
	function __construct($par_name, $par_plural, $par_title, $par_plural_title){
		
		$this->type = $par_name;
		$this->plural = $par_plural;
		$this->title = $par_title;
		$this->plural_title = $par_plural_title;

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

	//Custom taxonomies register - hierarchical/not hierarchical

	public function add_taxonomy($tax_name, $tax_plural, $tax_title, $tax_plural_title, $type){	

		$this->tax = $tax_name;
		$this->tax_plural = $tax_plural;
		$this->tax_title = $tax_title;
		$this->tax_plural_title = $tax_plural_title;

		if ($type) {
			// hierarchical
			add_action( 'init', 'custom_category_register');
			$this->custom_category_register();
			# code...
		}
		else {
			// not hierarchical
			add_action( 'init', 'custom_tag_register');
			$this->custom_tag_register();
		}
	}
	

	function custom_category_register() {

		$name = $this->tax;
		$plural = $this->tax_plural;
		$title = $this->tax_title;
		$plural_title = $this->tax_plural_title;

		$labels = array(
			'name'              => _x( $plural_title, 'taxonomy general name' ),
			'singular_name'     => _x( $title, 'taxonomy singular name' ),
			'search_items'      => __( 'Search '.$plural_title ),
			'all_items'         => __( 'All '.$plural_title ),
			'parent_item'       => __( 'Parent '.$title ),
			'parent_item_colon' => __( 'Parent '.$title.':' ),
			'edit_item'         => __( 'Edit '.$title ),
			'update_item'       => __( 'Update '.$title ),
			'add_new_item'      => __( 'Add New '.$title ),
			'new_item_name'     => __( 'New '.$title.' Name' ),
			'menu_name'         => __( $title ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $name ),
		);

		register_taxonomy( $name, array( $this->type ), $args );

	}

	function custom_tag_register() {

		$name = $this->tax;
		$plural = $this->tax_plural;
		$title = $this->tax_title;
		$plural_title = $this->tax_plural_title;

		$labels = array(
			'name'                       => _x( $plural_title, 'taxonomy general name' ),
			'singular_name'              => _x( $name, 'taxonomy singular name' ),
			'search_items'               => __( 'Search '.$plural_title ),
			'popular_items'              => __( 'Popular '.$plural_title ),
			'all_items'                  => __( 'All '.$plural_title ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit '.$name),
			'update_item'                => __( 'Update '.$name),
			'add_new_item'               => __( 'Add New '.$name),
			'new_item_name'              => __( 'New '.$name.' Name' ),
			'separate_items_with_commas' => __( 'Separate '.$plural.' with commas' ),
			'add_or_remove_items'        => __( 'Add or remove '.$plural ),
			'choose_from_most_used'      => __( 'Choose from the most used '.$plural ),
			'not_found'                  => __( 'No '.$plural.' found.' ),
			'menu_name'                  => __( $plural_title ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => $name ),
		);

		register_taxonomy( $name, array( $this->type ), $args );
	}

}