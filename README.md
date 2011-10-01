ModuleFilemanager for ExpressionEngine
==================

This project allows you to invoke the built-in EE File manager within any of your EE modules.

## Usage

```php
$this->ModuleFilemanager = new ModuleFilemanager();

$str = "
	$(document).ready(function() {
		$.ee_filebrowser(); // initialize the filebrowser
		$.ee_filebrowser.add_trigger($('.some_click_area'), function(a){
			// Handle upload (variables available include a.thumb, a.name, a.directory, a.dimensions, a.is_image)

			// Reset the filebrowser
			$.ee_filebrowser.reset();
		}
	});
";

$this->ModuleFilemanager->output($str);
```

That's it! ModuleFilemanager will automatically print the necessary javascript when the page is rendered. Now you're in business.

`$.ee_filebrowser` passes an object containing details about the file uploaded or selected from the file manager when the tigger is invoked. They are:

-`a.thumb` string. The URI of the thumbnail for the file.
-`a.name` string. The name of the file.
-`a.directory` string. The path to the directory where the file is stored.
-`a.is_image` boolean. Whether or not the file is an image.

These parameters come care of EE's built-in file manager, so your results may vary. You should consult the EE documentation for more details. Oh, that's right... it's not in their docs. Bummer.

## License

This project is licensed under the [MIT License](http://www.opensource.org/licenses/mit-license.php) Copyright (c) 2011 Cody Phillips