<?php
/**
	Admin Settings Page Utility Class
	Version: 1.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://www.a3non.org/go/enlighterjs
	License: MIT X11-License
	
	Copyright (c) 2013, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
if (!defined('ENLIGHTER_INIT')) die('DIRECT ACCESS PROHIBITED');


class Enlighter_SettingsUtil{
	
	// stores the plugin config
	private $_config;
	
	// stores options prefix
	private $_optionsPrefix;
	
	public function __construct($config, $prefix){
		// store local plugin config
		$this->_config = $config;
		
		$this->_optionsPrefix = $prefix;
	}
	
	/**
	 * Generates a checkbox based on the settings-name
	 * @param unknown_type $title
	 * @param unknown_type $optionName
	 */
	public function displayCheckbox($title, $optionName){
		?>
<!-- SETTING [<?php echo $optionName ?>] -->		
<table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e($title); ?></th>
        <td>
		<?php
	if ($this->_config[$optionName]){ 
		$checked = ' checked="checked" '; 
	}
	echo '<input '.$checked.' name="'.$this->_optionsPrefix.$optionName.'" type="checkbox" value="1" />';
?>        
        </td>
        </tr>
</table>		
		<?php 
	}
	
	/**
	 * Generates a selectform  based on settings-name
	 * @param String $title
	 * @param String $optionName
	 * @param Array $values
	 */
	public function displaySelect($title, $optionName, $values){
		?>
<!-- SETTING [<?php echo $optionName ?>] -->			
<table class="form-table">
	<tr valign="top">
	<th scope="row"><?php _e($title); ?></th>
        <td>
        <select name="<?php echo $this->_optionsPrefix.$optionName ?>" id="<?php echo $this->_optionsPrefix.$optionName ?>">
		<?php
		
		foreach ($values as $key=>$value){
			$selected = ($this->_config[$optionName] == $value) ? 'selected="selected"' : '';
			echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
		}
		?> 
        </select>       
        </td>
        </tr>
</table>
		<?php
	}
	
	/**
	 * Generates a input-form
	 * @param String $title
	 * @param String $optionName
	 * @param String $label
	 */
	public function displayInput($title, $optionName, $label, $cssClass='', $rowOnly=false){
		
	if (!$rowOnly){
		echo '<table class="form-table">';
	}
	?>
	<tr valign="top">
		<th scope="row"><?php _e($title); ?></th>
		<td>
		<input id="<?php echo $this->_optionsPrefix.$optionName; ?>" name="<?php echo $this->_optionsPrefix.$optionName;?>" type="text" value="<?php echo $this->_config[$optionName] ?>" class="text <?php echo $cssClass; ?>" />
		<label for="<?php echo $this->_optionsPrefix.$optionName; ?>"><?php _e($label); ?></label>
        </td>
	</tr>
	    
	<?php	
	if (!$rowOnly){
		echo '</table>';
	}
	}
	
}