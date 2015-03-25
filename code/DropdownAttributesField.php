<?php

class DropdownAttributesField extends DropdownField {
	/**
	 * @var boolean $extraData Associative or numeric array of extra attributes for dropdown items,
	 * with array key as the submitted field value, and the array value as an
	 * extra attribute included in the interface element.
	 */
	protected $optionsAttributes;

	/**
	 * Set attributes for options
	 *
	 * @param string $title
	 * @param array $optionsAttributes
	 */
	public function setOptionsAttributes($name, $optionsAttributes) {
		$this->optionsAttributes[$name] = $optionsAttributes;
		return $this;
	}

	/**
	 * Set attribute for single dropdown option. Assumes that object values are unique.
	 *
	 * @param string $name Name of the extra attribute included in the interface element as data-$name
	 * @param string $value Select option by its value
	 * @param string $set Value of the attrbiute
	 */
	public function setOptionAttribute($name, $value, $set) {
		$this->optionsAttributes[$name][$value] = $set;
		return $this;
	}

	/**
	 * Get an HTML attribute added through {@link setOptionAttribute()}.
	 *
	 * @return string
	 */
	public function getOptionAttribute($name, $value) {
	Debug::Show($name);
		$optionAttrs = $this->getOptionsAttributes();
		if(isset($optionAttrs[$name])) return $optionAttrs[$name];
	}

	/**
	 * Get a HTML attributes added through {@link setOptionAttribute()}.
	 *
	 * @return string
	 */
	public function getOptionAttributes($name) {
		$optionAttrs = $this->getOptionsAttributes();
		if(isset($optionAttrs[$name])) return $optionAttrs[$name];
	}

	/**
	 * Get attributes for options, --data-key is made available as $dataKey in template.--
	 *
	 * @return array
	 */
	public function getOptionsAttributes($properties = array()) {
		$source = $this->getSource();
		$options = array();
		if($source) {
			// SQLMap needs this to add an empty value to the options
			if(is_object($source) && $this->emptyString) {
				$options[] = new ArrayData(array(
					'Value' => '',
					'Title' => $this->emptyString,
				));
			}

			foreach($source as $value => $title) {
				$selected = false;
				if($value === '' && ($this->value === '' || $this->value === null)) {
					$selected = true;
				} else {
					// check against value, fallback to a type check comparison when !value
					if($value) {
						$selected = ($value == $this->value);
					} else {
						$selected = ($value === $this->value) || (((string) $value) === ((string) $this->value));
					}

					$this->isSelected = $selected;
				}

				$disabled = false;
				if(in_array($value, $this->disabledItems) && $title != $this->emptyString ){
					$disabled = 'disabled';
				}

				$optionsAttributes = array();
				foreach($this->optionsAttributes as $key => $v) {
					$optionsAttributes[$key] = $v[$value];
/*
					if (strpos($key, '-') !== false) {
						$keys = explode('-', $key);
						$newKey = $keys[0];
						foreach($keys as $i => $k) {
							if ($i > 0) {
								$newKey .= ucfirst($k);
							}
						}
						$optionsAttributes[$newKey] = $v[$value];
					}
					*/
				}

				$options[] = new ArrayData(array_merge(
					array(
						'Title' => $title,
						'Value' => $value,
						'Selected' => $selected,
						'Disabled' => $disabled,
						'OptionAttributesHTML' => $this->getOptionAttributesHTML($optionsAttributes)
					),
					$optionsAttributes
				));
			}
		}

		$properties = array_merge($properties, array('Options' => new ArrayList($options)));
		return $properties;
	}

	/**
	 * @param Array Custom attributes to process. Falls back to {@link getOptionsAttributes()}.
	 * If at least one argument is passed as a string, all arguments act as excludes by name.
	 * @return string HTML attributes, ready for insertion into an HTML tag
	 */
	public function getOptionAttributesHTML($attrs = null) {
		$exclude = (is_string($attrs)) ? func_get_args() : null;

		if(!$attrs || is_string($attrs)) $attrs = $this->getOptionsAttributes();

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



	public function Field($properties = array()) {
		$properties = $this->getOptionsAttributes($properties);

		$obj = ($properties) ? $this->customise($properties) : $this;
		$this->extend('onBeforeRender', $this);
		return $obj->renderWith($this->getTemplates());
	}
}