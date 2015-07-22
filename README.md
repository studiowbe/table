# html
A helper package for generating HTML tables

## Usage
### Basic usage

```php
//Create a table
$table = new \Studiow\Table\Table(["class" => "widefat"]);

//Create some columns
$table->createColumn("post_id", "ID", function($post) {
            return $post->ID;
        })
        ->createColumn("post_title", "Title", function($post) {
            return $post->post_title;
        });
//Add a row, which be just about anything. Probably most of the time it will be an array or an object
$table->addRow($post);

//Add multiple rows at the same time using an array of rows
$table->addRows($posts);

//render the table
echo (string) $table;
```

### Columns
Defining columns is where most of the fun happens. Most of the time you can use the factory method
```php
$table->createColumn("post_title", "Title", function($post) {
            return $post->post_title;
        });
```
is a shorthand for
```php
$table->addColumn(new \Studiow\Table\Column\DefaultColumn("post_title", "Title", function($post) {
    return $post->post_title;
}));
```
You can use your own special column types by implementing the \Studiow\Table\ColumnInterface interface.

### Column handlers
Column handlers can be either a string or a callable. When the handler is a callable, the value will be the result of the callback applied to the rowdata

When the handler is a string, the script will try to find a value. Consider this example:
```php
$table->addColumn(new \Studiow\Table\Column\DefaultColumn("post_title", "Title", 'post_title'));
```
When calculating the value we'll check first to see if $rowData is an array or arrayObject. If this is the case, well try to find $rowData['post_title].
If $rowData is an object, we'll check if $rowData has a public property called $post_data. Finally we'll try and call a method called getPostTitle on $rowData to get the value

## Known Issues and todo's
### Todo: provide better documentation and tests
There are a lot of possibilities with the package which are not documented and tested enough at the moment, will need a lot more examples

### Todo: support colgroup, tfoot etc.

### Todo: render to template engine(s)
Provide a way to extract the data from the table so that a template system can use it

### Standard warning about rendering HTML
If you find yourself rendering large pieces of HTML within a PHP script, you'd probably be better of using a template system.