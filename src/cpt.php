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

class CPT {

	// cpt properties
	public $type;
 	private $plural;
	private $title;
	private $plural_title;

	// arrays properties for custom post type options
	private $args = [];
	private $labels = [];

	// taxonomies properties
	private $tax_name;
	private $tax_plural;
	private $tax_title;
	private $tax_plural_title;

	// arrays properties for taxonomies options
	private $tax_args = [];
	private $tax_labels = [];

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

		add_action('init', array($this, 'custom_post_register' ));
		$this->custom_post_register();
		
	}

	public function custom_post_register() {

		$name = $this->type;
		$plural = $this->plural;
		$title = $this->title;
		$plural_title = $this->plural_title;

	    $labels_aux = array(
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

	    $labels = array_merge($labels_aux, $this->labels);
	    $this->labels = [];

		$args_aux = array(
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

	    $args = array_merge($args_aux, $this->args);
	    $this->args = [];

	    register_post_type( $name , $args );
	}

	// Unregister post type
	
	public function unregister_post_type(){
	
		global $wp_post_types;
		
		if ( isset( $wp_post_types[ $this->type ] ) ) unset( $wp_post_types[ $this->type ] );
	}

	// Changes the $args options of the cpt
	public function cpt_args_options ($options) {
		
		$this->args = [];
	    $this->args = array_merge($options);
	}

	// Changes the $labels options of the cpt
	public function cpt_labels_options ($options) {
		
		$this->labels = [];
	    $this->labels = array_merge($options);
	}

	// Taxonomy register
	
	public function add_taxonomy($par_name, $hierarchical = true){

		$this->tax_name = $par_name;
		$this->tax_plural = Inflector::pluralize($par_name);
		$this->tax_title = Inflector::titleize($par_name);
		$this->tax_plural_title = Inflector::titleize($this->tax_plural);
		$this->tax_hierarchical = $hierarchical;

		add_action('init', array($this, 'taxonomy_register')); 
		$this->taxonomy_register();
		
	}

	public function taxonomy_register() {

	    $name = $this->tax_name;
	    $plural = $this->tax_plural;
	    $title = $this->tax_title;
	    $plural_title = $this->tax_plural_title;
	    $post_type = $this->type;

	    if ( $this->tax_hierarchical ) {
			    
		    $labels_aux = array(
		        'name'              => _x( $plural_title, 'taxonomy general name' ),
		        'singular_name'     => _x( $title, 'taxonomy singular name' ),
		        'search_items'      => __( 'Search '.$plural_title ),
		        'all_items'         => __( 'All '.$plural_title ),
		        'parent_item'       => __( 'Parent '.$title ),
		        'parent_item_colon' => __( 'Parent '.$title.':' ),
		        'edit_item'         => __( 'Edit '.$title ),
		        'update_item'       => __( 'Update '.$title ),
		        'add_new_item'      => __( 'Add new '.$title ),
		        'new_item_name'     => __( 'New '.$title.' name'),
		        'menu_name'         => __( $title ),
		    );

	    	$labels = array_merge($labels_aux, $this->tax_labels);
		    $this->tax_labels = [];

		    $args_aux = array(
		        'hierarchical'      => true,
		        'labels'            => $labels,
		        'show_ui'           => true,
		        'show_admin_column' => true,
		        'query_var'         => true,
		        'rewrite'           => array( 'slug' => $name ),
		    );

	    	$args = array_merge($args_aux, $this->tax_args);
		    $this->tax_args = [];
	    } 
	    else {
	    		
			$labels_aux = array(
				'name'                       => _x( $plural_title, 'taxonomy general name' ),
				'singular_name'              => _x( $name, 'taxonomy singular name' ),
				'search_items'               => __( 'Search '.$plural_title ),
				'popular_items'              => __( 'Popular '.$plural_title ),
				'all_items'                  => __( 'All '.$plural_title ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => __( 'Edit '.$name),
				'update_item'                => __( 'Update '.$name),
				'add_new_item'               => __( 'Add new '.$name),
				'new_item_name'              => __( 'New '.$name.' name'),
				'separate_items_with_commas' => __( 'Separate '.$plural.' with commas' ),
				'add_or_remove_items'        => __( 'Add or remove '.$plural ),
				'choose_from_most_used'      => __( 'Choose from the most used '.$plural),
				'not_found'                  => __( 'No '.$plural.' found' ),
				'menu_name'                  => __( $plural_title )
			);

	    	$labels = array_merge($labels_aux, $this->tax_labels);
		    $this->tax_labels = [];

			$args_aux = array(
				'hierarchical'          => false,
				'labels'                => $labels,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'rewrite'               => array( 'slug' => $name ),
			);
			
	    	$args = array_merge($args_aux, $this->tax_args);
		    $this->tax_args = [];
	    }

	    register_taxonomy( $name, array($post_type), $args );
	}

	// Changes the $args options of the taxonomy
	public function taxonomy_args_options ($options) {
		
		$this->tax_args = [];
    	$this->tax_args = array_merge($options);
	}

	// Changes the $labels options of taxonomy
	public function taxonomy_labels_options ($options) {
		
		$this->tax_labels = [];
    	$this->tax_labels = array_merge($options);
	}

}