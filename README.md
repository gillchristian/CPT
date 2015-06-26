## CPT
Helper class to create Custom Pots Types in WordPress. 
I use [Inflector](https://github.com/medio/Inflector) class to capitalize and pluralize the CPT name.

### <i class="icon-pencil"></i> how to use it
The example below creates a custom post type called Book. Make sure to include the cpt file in your functions file in the theme folder.

```
include_once('cpt.php');

$book = new CPT('book');
$book->add_post_type();
```
### <i class="icon-list"></i> to do
- Unregister post type.
- Add taxonomies.
- Change menu icon.
