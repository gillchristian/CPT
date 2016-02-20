# CPT

[![PRs Welcome](https://img.shields.io/badge/prs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)

Helper classes to create Custom Pots Types and Taxonomies in WordPress.

I use [Inflector][1] class to capitalize and pluralize the CPT name.

## <i class="icon-pencil"></i> how to use it
Make sure to include the source folder in your theme's folder. On your functions file `include_once` or `require_once` the `cpt.inc.php` file, it will load all the required files.
```php
include_once 'CPT/src/cpt.inc.php';
```
##post types

###register
To add a post type use the `PostType` class. The example below creates a custom post type called Book.

```php
$book = new PostType('book');
$book->register();
```

###unregister
```php
$book->unregister();
```

###adding more options
`setArgs($options)` and `setLabels($options)` receive a relational array of options to customize the custom post type declaration.

In the example we make the Book custom post type private and change its menu icon.
```php
$book->setArgs(array(
     'menu_icon' => 'dashicons-book-alt',
     'public'    => false
    ));
```
For [more information][2] about the custom post type register options go to the [WordPress Codex][3].

[here][4] are some more icons, from the WordPress icons font, you can use to customize the menu icon of the custom post type.

----------

##taxonomies

###add a taxonomy

To add a taxonomy use the `Taxonomy` class. The example below creates a Writer tag-like (not hierarchical) Taxonomy and a Genre category-like (hierarchical) Taxonomy. And binds them to the `book` Post Type we already created.

```php
$writer = new Taxonomy('writer');

$writer->register('book');

$genre = new Taxonomy('genre');

$genre->register('book', true); // set 2nd parameter to true to make the taxonomy hierarchical
```

###adding more options
`setArgs($options)` and `setLabels($options)` work the same as the custom post type options setters.

You can find more information about registering taxonomies [here][5].

Also check the other examples on the _examples_ folder.

##to-do

- Make it Autoload ready.
- Change to a different inflector and support pluralization for other languages. [ICanBoogie/Inflector](6)
- Add testing.

##done

- Add support for internationalization.

##contributing

Feel free to submit issues, pull requests, or suggestions.

You can also so reach me on twitter ([@gillchristian](7)) if you like it and/or are using it :smile:

[1]:https://github.com/medio/Inflector
[2]:https://codex.wordpress.org/Function_Reference/register_post_type
[3]:https://codex.wordpress.org/
[4]:https://developer.wordpress.org/resource/dashicons/
[5]:https://codex.wordpress.org/Function_Reference/register_taxonomy
[6]:https://github.com/ICanBoogie/Inflector
[7]:https://twitter.com/gillchristian
