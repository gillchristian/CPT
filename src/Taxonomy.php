<?php
/**
* Helper class to leverage Custon Taxonomy creation on WordPress
*
* Check out https://github.com/gillchristian/CPT for examples on how to use it.
*
* @author Christian Gill
* @license GNU Lesser General Public License
* @since 0.1.0
* @version 0.3.0
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
