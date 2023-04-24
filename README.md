![RichBuilds.com Components](/src/richbuilds_logo.png)
![ArrayOf Logo](/src/array_of_logo.png)

# ArrayOf

A PHP 8.1 Typesafe array buy [RichBuilds](https://www.richbuilds.com).

Example usage:

```php
use Richbuilds/ArrayOf;

$array = new ArrayOf(User::class);

$array[] = new User(); // works

$array[] = new stdObj(); // fails InvalidArgumentException
```
$array->set() syntax:
```php
// Setting a single element in the array
$array->set('key', 'value');

// Setting multiple elements in the array
$array->set(['key1' => 'value1', 'key2' => 'value2']);

// Chaining set method
$array->set('key1', 'value1')->set('key2', 'value2');
```