# CPT
Helper class to create Custom Pots Types in WordPress. 
I use [Inflector](https://github.com/medio/Inflector) class to capitalize and pluralize the CPT name.

## <i class="icon-pencil"></i> how to use it
Make sure to include the source folder in your theme folder. On your functions file include_once the cpt file.
```
include_once('cpt/src/cpt.php');
```

#### add a CPT
The example below creates a custom post type called Book.

```
$book = new CPT('book');
$book->add_post_type();
```
#### unregister CPT
```
$book->unregister_post_type();
```

## <i class="icon-list"></i> to do
- Taxonomies creation.
- Change menu icon.
