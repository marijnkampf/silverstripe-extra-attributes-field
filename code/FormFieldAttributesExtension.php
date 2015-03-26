<?php

class FormFieldAttributesExtension extends DataExtension {
	/**
	 * @var boolean $extraData Associative or numeric array of extra attributes for dropdown items,
	 * with array key as the submitted field value, and the array value as an
	 * extra attribute included in the interface element.
	 */
	protected $optionsAttributes = array();

	/**
	 * Set attributes for options
	 *
	 * @param string $name Name of the attribute
	 * @param array $optionsAttributes
	 */
	public function setOptionsAttributes($name, $optionsAttributes) {
		foreach($optionsAttributes as $key => $value) {
			$this->setOptionAttribute($key, $name, $value);
		}
		return $this->owner;
	}

	/**
	 * Set attribute for single dropdown option. Assumes that object values are unique.
	 *
	 * @param string $key ID of the option
	 * @param string $name Name of the attribute
	 * @param string $value Select option by its value
	 */
	public function setOptionAttribute($key, $name, $value) {
		if (!isset($this->optionsAttributes[$key])) $this->optionsAttributes[$key] = array();
		$this->optionsAttributes[$key][$name] = $value;
		return $this->owner;
	}

	/**
	 * Get attribute(s) for single dropdown option.
	 *
	 * @param string $key ID of the option to return
	 */
	public function getOptionAttributes($key) {
		if (isset($this->optionsAttributes[$key])) return $this->optionsAttributes[$key];
		else return array();
	}

	public function updateGetOptions($options) {
		foreach($options as $option) {
			if ($option->hasField("Value") && isset($this->optionsAttributes[$option->getField("Value")])) {
				foreach($this->optionsAttributes[$option->getField("Value")] as $key => $value) {
					$option->setField($key, $value);
				}
				$option->setField('OptionAttributesHTML', $this->getOptionAttributesHTML($this->optionsAttributes[$option->getField("Value")]));
			}
		}
	}

	/**
	 * Get an HTML attribute added through {@link setOptionAttribute()}.
	 *
	 * @return string
	 */
/*	public function getOptionAttribute($name, $value) {
	Debug::Show($name);
		$optionAttrs = $this->owner->getOptionsAttributes();
		if(isset($optionAttrs[$name])) return $optionAttrs[$name];
	}*/

	/**
	 * Get a HTML attributes added through {@link setOptionAttribute()}.
	 *
	 * @return string
	 */
/*	public function getOptionAttributes($name) {
		$optionAttrs = $this->owner->getOptionsAttributes();
		if(isset($optionAttrs[$name])) return $optionAttrs[$name];
	}*/

	/**
	 * @param Array Custom attributes to process. Falls back to {@link getOptionsAttributes()}.
	 * If at least one argument is passed as a string, all arguments act as excludes by name.
	 * @return string HTML attributes, ready for insertion into an HTML tag
	 */
	public function getOptionAttributesHTML($attrs = null) {
		$exclude = (is_string($attrs)) ? func_get_args() : null;

		// Remove empty
		$attrs = array_filter((array)$attrs, function($v) {
			return ($v || $v === 0 || $v === '0');
		});

		// Remove excluded
		if($exclude) $attrs = array_diff_key($attrs, array_flip($exclude));

		// Create markkup
		$parts = array();
		foreach($attrs as $name => $value) {
			$parts[] = ($value === true) ? "{$name}=\"{$name}\"" : "{$name}=\"" . Convert::raw2att($value) . "\"";
		}

		return implode(' ', $parts);
	}
}