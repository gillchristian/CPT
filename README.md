# CPT
Helper class to create Custom Pots Types in WordPress. 
I use [Inflector][1] class to capitalize and pluralize the CPT name.

## <i class="icon-pencil"></i> how to use it
Make sure to include the source folder in your theme folder. On your functions file ```include_once``` the cpt file.
```
include_once('cpt/src/cpt.php');
```

#### add a CPT
The example below creates a custom post type called Book.

```
$book = new CPT('book');
$book->add_post_type();
```
#### unregister a CPT

```
$book->unregister_post_type();
```
####adding more options
```cpt_args_options()``` and ```cpt_labes_options()``` allow to pass options to the custom post type declaration, like the example bellow, you can pass any valid option with the desired value in a relational array
```
$book->cpt_args_options(array(
     'menu_icon' => 'dashicons-book-alt',
     'public'    => true,
    ));
```
for [more information][2] about  the custom post type register options go to the [WordPress Codex][3].

[here][4] are some more icons, from the WordPress icons font, you can use to customize the menu icon of the custom post type.
## <i class="icon-list"></i> to do
- Taxonomies creation.

[1]:https://github.com/medio/Inflector
[2]:https://codex.wordpress.org/Function_Reference/register_post_type
[3]:https://codex.wordpress.org/
[4]:https://developer.wordpress.org/resource/dashicons/
