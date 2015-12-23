<?php
/**
* Helper class to leverage Custom Post Types creation on WordPress
*
* Check out https://github.com/gillchristian/CPT for examples on how to use it.
*
* @author Christian Gill
* @license GNU Lesser General Public License
* @since 0.0.1
* @version 0.3.0
*/

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
