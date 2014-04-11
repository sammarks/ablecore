# Able Core

Able Core is a set of modifications for Drupal to enhance the development process. Able Core
implements a "no-boilerplate" philosophy. That is, all the defaults are abstracted away and only
modifications of the defaults are required to be represented in the code. Able Core provides
several OO-based implementations of standard Drupal configuration arrays (for example, things like
`hook_menu` and `hook_theme`) so that developers don't have to constantly refer to the Drupal API
documentation when creating a website.

Able Core introduces syntactic sugar wherever it can and has the following ultimate goals:

- Make Drupal easier to integrate with IDEs and code introspection (IDEs should be able to inform
	the developer of the correct keys to use when configuring things like `hook_menu` so that
	developers don't constantly have to refer to the API documentation)
- Do more with less characters. Arrays in PHP (depending on how they're formatted) take up a lot of
	space. It's a lot easier to interpret a `hook_menu` definition if every menu item is on one
	line (with the line not introducing horizontal scrolling, because that's just as difficult to
	read) than if every definition (out of a possible 50 or so) had 5 lines.
- Introduce sensible defaults, on top of the defaults already in place.
- Make it possible to abstract as much of the configuration to code as possible. Features is great,
	but it adds a _lot_ of boilerplate code that isn't really necessary. Content types should be
	able to be defined in 30 lines, not 400.
- Add some convenience utilities so that modules can be OO-based more easily. Classes like `Batch`
	and `FormBase` are introduced so that instead of having a collection of functions representing
	a form or a batch operation, those functions can all be contained in one class.

Basically, you shouldn't have to write thousands of lines of code to get a decent site setup with
Drupal.

Able Core is sponsored by [Able Engine,](http://ableengine.com/) a web services company located in
Lexington, Kentucky.

## File Structure

Able Core employs specific naming conventions. Therefore, in order to use Able Core to its fullest,
you'll probably want to follow these conventions:

All PHP logic goes in the module and all HTML, CSS and Javascript goes in the theme. The CSS,
Javascript and HTML all live in their own parts of the theme.

### Modules

	module_name/
		preprocessors/
			theme-hook-name.php
			other-theme-hook.php
		callbacks/
			page-callback-a.php
			page-callback-b.php
		helpers/
			misc-file-1.inc
			misc-file-2.inc
		hooks/
			block.inc
			token.inc
		module_name.info
		module_name.module

#### Footnotes

- `module_name` - This should always be in the following format: `sitename_subname`. It should
	**never** be the same name as the profile (or theme) because this causes large problems with
	Drupal (various hooks conflict with each other) and is against the convention recommended by
	Drupal documentation.
	- The primary module for the project should be called `sitename_core`.
	- All modules for a project should start with the profile (or site) name and be in the same
		package in the module configuration (the `.info` file).
	- Only use underscores and lowercase letters for modules. This is a Drupal convention.
- `preprocessors` - This folder is where all theme preprocessors go. This is the convention used by
	Able Core. Each preprocessor file should have the same name as the theme and have one function
	`preprocessor_THEMEHOOK(&$variables)`.
- `callbacks` - This folder is for all callbacks registered with Drupal through `hook_menu`. These
	files should have a name that identifies them with their respective page.
- `helpers` - Files that contain helper functions go in this folder. Any file ending in `.inc` will
	automatically be included by Able Core. *Not Implemented Yet.*
- `hooks` - Files in this folder are similar to the `helpers` folder. These files must end in
	`.inc` and by convention must have the same name as the module they contain hooks for. In the
	future, Able Core will have "smart" functionality so that when a specific module is looking for
	hooks, it loads the respective file.
- `module_name.module` - This file contains generic Drupal hooks. Really, it should only contain
	Drupal core hooks.
	- Block hooks go in `hooks/block.inc`

### Themes

While the standards set below are *highly* recommended, they are completely optional.

	theme_name/
		themes/ (not templates)
			node/ (the module name)
				node.tpl.php
				node--type.tpl.php
				node--type--display.tpl.php (node--type--teaser.tpl.php)
			core/
				html.tpl.php
				page.tpl.php
			module_name/
				theme-name.tpl.php
				other-theme-name.tpl.php
		styles/
			default.[scss|less|sass]
			core/
				layout.[scss|less|sass] (layout under SMACSS)
				base.[scss|less|sass] (base under SMACSS)
			themes/ (modules under SMACSS)
				theme-name.[scss|less|sass]
				other-theme-name.[scss|less|sass]
				subfolder/ (... these can be organized into subfolders.)
			vendor/
				third-party.[scss|css|less|sass]
				other-third-party.[scss|css|less|sass]
		scripts/
			default.js
			themes/
				theme-name.js
				other-theme-name.js
			vendor/
				equalize.js
		theme_name.info

#### Footnotes

- `theme_name` - This is never to be the same name as the module or profile. This name always
	starts with the profile (or site name).
- `themes` - Traditionally, this folder is named `templates`. However, that name does 
	not agree with the Drupal component it deals with (themes). Therefore, the folder has been 
	renamed so that it better defines which Drupal component it's talking about.
	- This folder is split up into subfolders for each module it has a theme override for.
		For example, node templates would go inside the `node` folder because they deal
		with the node module. Core templates (really only `html.tpl.php` and `page.tpl.php`)
		go inside the `core` folder.
- `styles` - Any styles for a website go in this folder. The structure of the CSS loosely
	reflects a combination of SMACSS and the structure under the `themes` folder. Any files
	in the root of this directory are to be included in the theme's `.info` file to be parsed
	by the LESS (or SASS) compiler.
	- `default.less` - This is the only file inside the `styles` root, and therefore the
		only file included by Drupal. This file is meant to contain only include statements
		pointing to other LESS files.
	- `core` - These styles represent the base styles for the theme. Really, the only two
		files that belong in this folder are `layout.less` and `base.less`
		- `layout.less` - This file creates the layout for the website. This is typically
			where a grid system is included and put to use.
		- `base.less` - This file contains all the base styles. Typically, this file only
			contains styles for tags. Classes and IDs are not intended to be included in
			this file.
	- `themes` - This folder is to be organized in the same way the `themes` folder underneath
		the theme root is organized. Each `.less` file in this folder corresponds with a file
		in the `themes` directory underneath the theme root.
	- `vendor` - Any third party CSS or LESS goes in this folder.
- `scripts` - Any scripts for the website go in this folder. The naming and structuring schemes
	here are exactly the same as in the `styles` folder.
	- `default.js` - This file is meant to contain any global JS. There shouldn't be a lot of
		Javascript in this file as most of our Javascript code is tied with a specific theme
		hook.
- `template.php` - **This file is not meant to be used.** This file should not be used to prepare
	contents for a specific page. If something changes across several pages of a website,
	put it in a block and not in `template.php`. Using `template.php` breaks the convention
	of "all logic goes in the module and all UI goes in the theme."

## Usage in Modules

One of the major ideas introduced by Able Core is to create modules that drive the custom aspects
of any Drupal site. This idea was initally discovered in the [White House Petitions Repository.](https://github.com/whitehouse/petitions)

Looking through the source code for the repositories, I noticed something really nasty about
Drupal's configuration syntax for things like `hook_menu` and `hook_theme`. It consisted of
nasty PHP arrays. The code was not readable (especially if many declarations were made in the
same file), and the module file went on for miles.

Because of htat, several helpers classes were introduced to collapse the basic configuration for
these files into one-liners. Creating a custom menu entry for `hook_menu` is as simple as
the following:

	<?php
	
		function mymodule_menu()
		{
			return AbleCore\Modules\PathManager::init()
				->define('this/is/a/custom/path', 'controllerFile@index', 'Page Title')
				->fin();
		}
		
	?>

That way, multiple configuration options can be passed into a single block of code. This idea
is very similar to the way routing works in the top MVC frameworks (yes, Drupal is not an MVC).
The same style of code is used for some of the other manager classes in Able Core.

Let's say you want the URL `test/url` to go to the `action_test_url` function in the `callbacks/test_callback.php` file. Here's what your code would look like:

	<?php
	
		function mymodule_menu()
		{
			return AbleCore\Modules\PathManager::init()
				->define('test/url', 'test_callback@test_url', 'Test URL Page')
				->fin();
		}
		
	?>

Here's the breakdown of arguments:

- **path** - The path the action should respond to. This path can have multiple arguments, as
	demonstrated below.
- **callback** - The file and function to call when the path is reached.
- **title** - The title of the page.
- ... and more ... - Consult the documentation in the code for the complete list of arguments.

### Using Arguments

Drupal's menu function allows for arguments. Drupal uses percent (%) signs to designate wildcards.
Here are some examples of wildcarded paths:

- `path/%/with/%/arguments` will match the following:
	- `path/1/with/2/arguments`
	- `path/test/with/moretest/arguments`
- `path%` will match the following:
	- `pathtest`
	- `pathsecondtest/thirdtest`
- `%` *should* match any path. It's not recommended to do this, for obvious reasons.

Arguments are automatically stored in variables and passed as arguments to the callback function.
For example, `path/%/with/%/arguments` will yield the following callback function:

	<?php
	
		function action_page($argument_a, $argument_b)
		{
			// $argument_a is the first %
			// $argument_b is the second %
		}
		
	?>

Therefore, going to `path/1/with/2/arguments` will call the callback like so:

	<?php
	
		action_page(1, 2);
		
	?>
