Dropdown Attributes Field module
================================

Description
-----------
Extension of SilverStripe DropdownField to allow attributes for options of dropdown field.

Maintainer Contact
------------------
Marijn Kampf 
<marijn (at) exadium (dot) com>

Sponsored by
------------
Exadium Web Development and Online Marketing. Visit http://www.exadium.com for more information.

Installation
------------
````
composer require "exadium/dropdown-attributes-field":"*"
````

Usage
-----
````
$field = DropdownAttributesField::create(
	'Count',
	'Count',
	array(
		'1' => 'One',
		'2' => 'Two',
		'3' => 'Three'
	)
)->setOptionsAttributes(
	'data-bats',
	array(
		'1' => 'batty',
		'2' => 'batty batty',
		'3' => 'batty batty batty',
	)
);
````

Generates code like
````
<div id="Count" class="control-group form-group">
	<label for="Form_Form_Count">Count</label>
	<select name="Count" class="dropdownattributes form-control" id="Form_CountingForm_Count">
	<option value="1" data-bats="batty">One</option>
	<option value="2" data-bats="batty batty">Two</option>
	<option value="3" data-bats="batty batty batty">Three</option>
</select>
</div>
````

Or you can load from a database field map:

````
$field = DropdownAttributesField::create('PaperTypeID', 'Paper Type', PaperType::get()->map('ID', 'Name'))->setOptionsAttributes('data-calc', PaperType::get()->map('ID', 'Price'))
````

Requirements
------------
SilverStripe 3.1