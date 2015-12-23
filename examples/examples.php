<?php
/**
 *
 * Custom Post Types and Taxonomies definition examples
 *
 */
include_once 'cpt/src/cpt.php';
/**
 *
 * CPT with only support for Featured Images to use as slides
 *
 */
$slides = new PostType('slide');

$slides->set_args(array(
  'menu_icon'  => 'dashicons-slides',
	'supports'   => array( 'title', 'page-attributes', 'thumbnail'  )
));

$slides->register();

/**
 *
 * FAQ Questions CPT
 *
 * We add the defaul Tags Taxonomy to it  
 *
 * Already registered taxonomies can be added to a Post Type through the $args
 */
$faq = new PostType('question');

$faq->set_args(array(
	'menu_icon'  => 'dashicons-editor-help',
	'supports' 	 => array( 'title', 'page-attributes', 'editor' ),
  'taxonomies' => array('post_tag')
));

$faq->register();

/**
 * Destinations CPT
 *
 */
$destinations = new PostType('destination');

$destinations->set_args(array(
    'menu_icon' => 'dashicons-palmtree',
));

$destinations->register();

/**
 * Type Taxonomy
 *
 * Note that we pass true as the second parametter to make it hierarchical
 * the default parametter is false.
 *
 * We are not only registering the Taxonomy to the Destination Post Type
 * but also to FAQ and to the default Posts
 *
 * You can access the slug as a property of the PostType/Taxonomy object
 * It is generated from the $name propertie passed to the constructor,
 * using the Inflector::underscore function which:
 *
 *    * Convert any "CamelCased" or "ordinary Word" into an
 *    * "underscored_word".
 */
$type = new Taxonomy('type');

$registerTo = array(
	$destinations->slug,
	$faq->slug,
	'post'
);

$type->register($registerTo, true);