Extra Attributes Field module
================================

Description
-----------
Extension of SilverStripe form fields to allow adding attributes to child elements on dropdown and checkboxset fields.

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
composer require "exadium/extra-attributes-field":"*"
````

The module should work out of the box on a standard installation of SilverStripe. If you have Bootstrap Forms module installed by

\extra-attributes-field\templates\
\themes\bootstrap\templates

Usage
-----
````
public function getCMSFields() {
	$fields = parent::getCMSFields();

	$count = array('1' => 'One', '2' => 'Two', '3' => 'Three');
	$batties = array('1' => 'Batty', '2' => 'Batty batty', '3' => 'Batty batty batty');

	$fields->push(
		DropdownAttributesField::create('DropdownAttributesCount', 'DropdownAttributesField Count', $count)
			->setOptionsAttributes('data-bats', $batties)
	);

	$fields->push(
		CheckboxSetField::create('CheckCount', 'CheckboxSetField Count', $count)
			->setOptionsAttributes('data-bats', $batties)
	);

	return $fields;
}
````

Generates code like
````
<div id="DropdownAttributesCount" class="field dropdownattributes">
	<label class="left" for="Form_EditForm_DropdownAttributesCount">DropdownAttributesField Count</label>
	<div class="middleColumn">
		<select name="DropdownAttributesCount" class="dropdownattributes" id="Form_EditForm_DropdownAttributesCount">
			<option value="1" data-bats="Batty">One</option>
			<option value="2" data-bats="Batty batty">Two</option>
			<option value="3" data-bats="Batty batty batty">Three</option>
		</select>
	</div>
</div>

<div id="CheckCount" class="field optionset checkboxset">
	<label class="left">CheckboxSetField Count</label>
	<div class="middleColumn">
		<ul id="Form_EditForm_CheckCount" class="optionset checkboxset">
			<li class="odd val1">
				<input id="Form_EditForm_CheckCount_1" class="checkbox" name="CheckCount[1]" type="checkbox" value="1" data-bats="Batty">
				<label for="Form_EditForm_CheckCount_1">One</label>
			</li>
			<li class="even val2">
				<input id="Form_EditForm_CheckCount_2" class="checkbox" name="CheckCount[2]" type="checkbox" value="2" data-bats="Batty batty">
				<label for="Form_EditForm_CheckCount_2">Two</label>
			</li>
			<li class="odd val3">
				<input id="Form_EditForm_CheckCount_3" class="checkbox" name="CheckCount[3]" type="checkbox" value="3" data-bats="Batty batty batty">
				<label for="Form_EditForm_CheckCount_3">Three</label>
			</li>
		</ul>
	</div>
</div>
````

Or you can load from a database field map. Also shows adding multiple options.
````
$fields->push(
	DropdownAttributesField::create('Members', 'Members', Member::get()->map('ID', 'Name'))
		->setOptionsAttributes('data-email', Member::get()->map('ID', 'Email'))
		->setOptionsAttributes('data-firstname', Member::get()->map('ID', 'FirstName'))
);
````

Requirements
------------
SilverStripe 3.1