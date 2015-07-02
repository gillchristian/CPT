# CPT

Helper class to create Custom Pots Types in WordPress. 
I use [Inflector][1] class to capitalize and pluralize the CPT name.

## <i class="icon-pencil"></i> how to use it
Make sure to include the source folder in your theme folder. On your functions file ```include_once``` the cpt file.
```
include_once('cpt/src/cpt.php');
```
###CPT
#### register
The example below creates a custom post type called Book.

```
$book = new CPT('book');
$book->add_post_type();
```
#### unregister
to unregister a custom post type leave only the next two lines and reload your site once, then you can delete these lines also.
```
$book = new CPT('book');
$book->unregister_post_type();
```
####adding more options
```cpt_args_options($options)``` and ```cpt_labes_options($options)``` receive a relational array of options to customize the custom post type declaration. Make sure to define the options before you ```add_post_type```. 

In the example we make the Book custom post type private and change its menu icon.
```
$book->cpt_args_options(array(
     'menu_icon' => 'dashicons-book-alt',
     'public'    => false
    ));
```
for [more information][2] about  the custom post type register options go to the [WordPress Codex][3].

[here][4] are some more icons, from the WordPress icons font, you can use to customize the menu icon of the custom post type.

----------

###taxonomies
####add a taxonomy
to add a taxonomy use the ```add_taxonomy($par_name, $hierarchical)``` function.
#####parameters
```$par_name```: string, the taxonomy name

```$hierarchical```: boolean, if true the taxonomy is hierarchical like a category, if false the taxonomy is not hierarchical like a tag. default: ```true```
####adding more options
```taxonomy_args_options($options)``` and ```taxonomy_labels_options($options)``` work the same as the custom post type options.

you can find more informaton about registering taxonomies [here][5].


[1]:https://github.com/medio/Inflector
[2]:https://codex.wordpress.org/Function_Reference/register_post_type
[3]:https://codex.wordpress.org/
[4]:https://developer.wordpress.org/resource/dashicons/
[5]:https://codex.wordpress.org/Function_Reference/register_taxonomy
