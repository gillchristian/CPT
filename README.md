# CPT

Helper classes to create Custom Pots Types and Taxon in WordPress. 
I use [Inflector][1] class to capitalize and pluralize the CPT name.

## <i class="icon-pencil"></i> how to use it
Make sure to include the source folder in your theme folder. On your functions file ```include_once``` the cpt file.
```php
include_once('cpt/src/cpt.php');
```
###CPT

####register
to add a post type use the ```Post_Type``` class. The example below creates a custom post type called Book.

```php
$book = new Post_Type('book');
$book->register_post_type();
```

####unregister
```php
$book->unregister_post_type();
```

####adding more options
```set_args($options)``` and ```set_labes($options)``` receive a relational array of options to customize the custom post type declaration. Make sure to set the options before you ```add_post_type```. 

In the example we make the Book custom post type private and change its menu icon.
```php
$book->set_args(array(
     'menu_icon' => 'dashicons-book-alt',
     'public'    => false
    ));
```
for [more information][2] about  the custom post type register options go to the [WordPress Codex][3].

[here][4] are some more icons, from the WordPress icons font, you can use to customize the menu icon of the custom post type.

----------

###taxonomies

####add a taxonomy

to add a taxonomy use the ```Taxonomy``` class. The example below creates a Writter tag-like (not hierarchical) Taxonomy and a Genre category-like (hierarchical) Taxonomy. And binds them to the ```book``` Post Type.

```php
$writter = new Taxonomy('writter');

$writter->register_taxonomy('book');

$genre = new Taxonomy('genre');

$genre->register_taxonomy('book', true);
```

####adding more options
```set_args($options)``` and ```set_labels($options)``` work the same as the custom post type options setters.

you can find more informaton about registering taxonomies [here][5].


[1]:https://github.com/medio/Inflector
[2]:https://codex.wordpress.org/Function_Reference/register_post_type
[3]:https://codex.wordpress.org/
[4]:https://developer.wordpress.org/resource/dashicons/
[5]:https://codex.wordpress.org/Function_Reference/register_taxonomy
