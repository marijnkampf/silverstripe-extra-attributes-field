<?php

class DropdownAttributesField extends DropdownField {
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

				$optionsAttributes = $this->getOptionAttributes($value);

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

	public function Field($properties = array()) {
		$properties = $this->getOptionsAttributes($properties);

		$obj = ($properties) ? $this->customise($properties) : $this;
		$this->extend('onBeforeRender', $this);
		return $obj->renderWith($this->getTemplates());
	}
}