<?php
	function conv_build_select($id, $options, $selected = null) {
		$html = '<select name="'.$id.'" id="'.$id.'">';

		foreach ($options as $value => $label) {
			$s = ($selected && $value == $selected) ? 'selected="selected"' : '';
			$html .= '<option value="'.$value.'" '.$s.'>'.$label.'</option>';
		}

		$html .= '</select>';
		return $html;
	}
?>
