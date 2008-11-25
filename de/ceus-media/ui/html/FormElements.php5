<?php
import( 'de.ceus-media.ui.html.Tag' );
/**
 *	Builder for HTML Form Components.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@package		ui.html
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Builder for HTML Form Components.
 *	@package		ui.html
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class UI_HTML_FormElements
{
	/**
	 *	Adds Readonly Attributes directly to Attributes Array, inserts JavaScript Alert if String given.
	 *	@access		public
	 *	@param		array		$attributes		Reference to Attributes Array
	 *	@param		mixed		$readOnly		Bool or String, String will be set in mit JavaScript Alert
	 *	@return		void
	 */
	private function addReadOnlyAttributes( &$attributes, $readOnly )
	{
		$attributes['readonly']	= "readonly";
		if( is_string( $readOnly ) )
			$attributes['onclick']	= "alert('".$readOnly."');";
	}

	/**
	 *	Builds HTML for a Group of Radio Buttons, behaving like a Select.
	 *	@access		public
	 *	@param		string		$name			Field Name
	 *	@param		array		$options		Array of Options
	 *	@param		string		$class			CSS Class
	 *	@param		mixed		$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@return		string
	 */
	public static function RadioGroup( $name, $options, $class = NULL, $readOnly = NULL )
	{
		$radios	= array();
		foreach( $options as $value => $label )
		{
			if( (string) $value == '_selected' )
				continue;
			$selected	= isset( $options['_selected'] ) ? (string) $value == (string) $options['_selected'] : NULL;
			$radio		= self::Radio( $name, $value, $selected, $class, $readOnly );
			$spanRadio	= UI_HTML_Tag::create( "span", $radio, array( 'class' => 'radio' ) );
			$label		= UI_HTML_Tag::create( "label", $label, array( 'for' => $name."_".$value ) );
			$spanLabel	= UI_HTML_Tag::create( "span", $label, array( 'class' => 'label' ) );
			$content	= UI_HTML_Tag::create( "span", $spanRadio.$spanLabel, array( 'class' => 'radiolabel' ) );
			$radios[]	= $content;
		}
		$group	= implode( "", $radios );
		return $group;
	}

	//  --  STABLE  --  //
	/**
	 *	Builds HTML Code for a Button to submit a Form.
	 *	@access		public
	 *	@param		string		$name 			Button Name
	 *	@param		string		$label 			Button Label
	 *	@param		string		$class			CSS Class
	 *	@param		string		$confirm 		Confirmation Message
	 *	@param		mixed		$disabled		Button is not pressable, JavaScript Alert if String is given
	 *	@return		string
	 */
	public static function Button( $name, $label, $class = NULL, $confirm = NULL, $disabled = NULL)
	{
		$attributes	= array(
			'type'		=> "submit",
			'name'		=> $name,
			'value'		=> 1,
			'class'		=> $class,
			'onclick'	=> $confirm		? "return confirm('".$confirm."');" : NULL,
		);
		if( $disabled )
			self::addReadonlyAttributes( $attributes, $disabled);
		return UI_HTML_Tag::create( "button", UI_HTML_Tag::create( "span", (string) $label ), $attributes );
	}

	/**
	 *	Builds HTML Code for a Checkbox.
	 *	@access		public
	 *	@param		string		$name 			Field Name
	 *	@param		string		$value 			Field Value if checked
	 *	@param		bool		$checked		Field State
	 *	@param		string		$class 			CSS Class
	 *	@param		mixed		$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@return		string
	 */
	public static function Checkbox( $name, $value, $checked = NULL, $class = NULL, $readOnly = NULL )
	{
		$attributes	= array(
			'id'		=> $name,
			'type'		=> "checkbox",
			'name'		=> $name,
			'value'		=> $value,
			'class'		=> $class,
			'checked'	=> $checked		? "checked"		: NULL,
			'disabled'	=> $readOnly	? "disabled"	: NULL,
		);
		if( $readOnly )
			self::addReadonlyAttributes( $attributes, $readOnly );
		return UI_HTML_Tag::create( "input", NULL, $attributes );
	}

	/**
	 *	Builds HTML Code for a File Upload Field.
	 *	@access		public
	 *	@param		string		$name			Field Name
	 *	@param		string		$class			CSS Class (xl|l|m|s|xs)
	 *	@param		mixed		$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@param		int			$tabIndex		Tabbing Order
	 *	@param		int			$maxLength		Maximum Length
	 *	@return		string
	 */
	public static function File( $name, $value = "", $class = NULL, $readOnly = NULL, $tabIndex = NULL, $maxLength = NULL )
	{
		$attributes	= array(
			'id'		=> $name,
			'type'		=> "file",
			'name'		=> $name,
			'value'		=> $value,
			'class'		=> $class,
			'tabindex'	=> $tabIndex,
			'maxlength'	=> $maxLength,
		);
		if( $readOnly )
			self::addReadOnlyAttributes( $attributes, $readOnly );
		return UI_HTML_Tag::create( "input", NULL, $attributes );
	}
	
	/**
	 *	Builds HTML Code for a Form using POST.
	 *	@access		public
	 *	@param		string		$name			Form Name, also used for ID with Prefix 'form_'
	 *	@param		string		$action			Form Action, mostly an URL
	 *	@param		string		$target			Target Frage of Action
	 *	@param		string		$enctype		Encryption Type, needs to be 'multipart/form-data' for File Uploads
	 *	@param		string		$onSubmit 		JavaScript to execute before Form is submitted, Validation is possible
	 *	@return		string
	 */
	public static function Form( $name = NULL, $action = NULL, $target = NULL, $enctype = NULL, $onSubmit = NULL )
	{
		$attributes	= array(
			'id'		=> $name		? "form_".$name : NULL,
			'name'		=> $name,
			'action'	=> $action		? str_replace( "&", "&amp;", $action ) : NULL,
			'target'	=> $target,
			'method'	=> "post",
			'enctype'	=> $enctype,
			'onsubmit'	=> $onSubmit,
		);
		$form	= UI_HTML_Tag::create( "form", NULL, $attributes );
		return preg_replace( "@/>$@", ">", $form );
	}

	/**
	 *	Builds HTML Code for an Input Field. Validation is possible using Validator Classes from UI.validateInput.js.
	 *	@access		public
	 *	@param		string		$name			Field Name
	 *	@param		string		$value			Field Value
	 *	@param		string		$class			CSS Class (xl|l|m|s|xs)
	 *	@param		mixed		$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@param		int			$tabIndex		Tabbing Order
	 *	@param		int			$maxLength		Maximum Length
	 *	@param		string		$validator		Validator Class (using UI.validateInput.js)
	 *	@return		string
	 */
	public static function Input( $name, $value = NULL, $class = NULL, $readOnly = NULL, $tabIndex = NULL, $maxLength = NULL, $validator = NULL )
	{
		$attributes	= array(
			'id'		=> $name,
			'type'		=> "text",
			'name'		=> $name,
			'value'		=> $value,
			'class'		=> $class,
			'tabindex'	=> $tabIndex,
			'maxlength'	=> $maxLength,
			'onkeyup'	=> $validator	? "allowOnly(this,'".$validator."');" : NULL,
		);
		if( $readOnly )
			self::addReadOnlyAttributes( $attributes, $readOnly );
		return UI_HTML_Tag::create( "input", NULL, $attributes );
	}

	/**
	 *	Builds HTML Code for a hidden Input Field. It is not advised to work with hidden Fields.
	 *	@access		public
	 *	@param		string		$name			Field Name
	 *	@param		string		$value			Field Value
	 *	@return 	string
	 */
	public static function HiddenField( $name, $value )
	{
		$attributes	= array(
			'id'		=> $name,
			'type'		=> "hidden",
			'name'		=> $name,
			'value'		=> $value,
		);
		return UI_HTML_Tag::create( "input", NULL, $attributes );
	}

	/**
	 *	Builds HTML Code for a Field Label.
	 *	@access		public
	 *	@param		string		$id				ID of Field to reference
	 *	@param		string		$label			Label Text
	 *	@param		string		$class			CSS Class
	 *	@return		string
	 */
	public static function Label( $id, $label, $class = NULL )
	{
		$attributes	= array(
			'for'		=> $id,
			'class'		=> $class ? $class : NULL,
		);
		return UI_HTML_Tag::create( "label", $label, $attributes );
	}

	/**
	 *	Builds HTML Code for a Button behaving like a Link.
	 *	@access		public
	 *	@param		string		$label			Button Label, also used for ID with Prefix 'button_' and MD5 Hash
	 *	@param		string		$url			URL to request
	 *	@param		string		$class			CSS Class
	 *	@param		string		$confirm 		Confirmation Message
	 *	@param		mixed		$disabled		Button is not pressable, JavaScript Alert if String is given
	 *	@return		string
	 */
	public static function LinkButton( $url, $label, $class = NULL, $confirm = NULL, $disabled = NULL )
	{
		$action			= "document.location.href='".$url."';";
		$attributes	= array(
			'id'		=> "button_".md5( $label ),
			'type'		=> "button",
			'class'		=> $class,
			'disabled'	=> $disabled	? "disabled" : NULL,
			'onclick'	=> $confirm		? "if(confirm('".$confirm."')){".$action."};" : $action,
		);
		if( $disabled )
			self::addReadOnlyAttributes( $attributes, $disabled );
		return UI_HTML_Tag::create( "button", UI_HTML_Tag::create( "span", $label ), $attributes );
	}

	/**
	 *	Builds HTML Code for an Option for a Select.
	 *	@access		public
	 *	@param		string		$value			Option Value
	 *	@param		string		$label			Option Label
	 *	@param		bool		$selected		Option State
	 *	@param		string		$disabled		Option is not selectable
	 *	@param		string		$class			CSS Class
	 *	@return		string
	 */
	public static function Option( $value, $label, $selected = NULL, $disabled = NULL, $class = NULL )
	{
		if( !( $value != "_selected" && $value != "_groupname" ) )
			return "";
		$attributes	= array(
			'value'		=> $value,
			'selected'	=> $selected	? "selected" : NULL,
			'disabled'	=> $disabled	? "disabled" : NULL,
			'class'		=> $class,
		);
		return UI_HTML_Tag::create( "option", htmlspecialchars( $label ), $attributes );
	}

	/**
	 *	Builds HTML Code for an Option Group for a Select.
	 *	@access		public
	 *	@param		string		$label			Group Label
	 *	@param		string		$options 		Array of Options
	 *	@param		string		$selected		Value of selected Option
	 *	@return		string
	 */
	public static function OptionGroup( $label, $options, $selected = NULL )
	{
		$attributes	= array( 'label' => $label );
		$options	= self::Options( $options, $selected );
		return UI_HTML_Tag::create( "optgroup", $options, $attributes );
	}

	/**
	 *	Builds HTML Code for Options for a Select.
	 *	@access		public
	 *	@param		array		$options 			Array of Options
	 *	@param		string		$selected			Value of selected Option
	 *	@return		string
	 */
	public static function Options( $options, $selected = NULL )
	{
		$list		= array();
		foreach( $options as $key => $value)
		{
			if( (string) $key != "_selected" && is_array( $value ) )
			{
				foreach( $options as $groupLabel => $groupOptions )
				{
					if( !is_array( $groupOptions ) )
						continue;
					if( (string) $groupLabel == "_selected" )
						continue;
					$groupName	= isset( $groupOptions['_groupname'] ) ? $groupOptions['_groupname'] : $groupLabel;
					$select		= isset( $options['_selected'] ) ? $options['_selected'] : $selected;
					$list[]		= self::OptionGroup( $groupName, $groupOptions, $select );
				}
				return implode( "", $list );
			}
		}
		foreach( $options as $value => $label )
		{
			$value		= (string) $value;
			$isSelected	= is_array( $selected ) ? in_array( $value, $selected ) : (string) $selected == $value; 
			$list[]		= self::Option( $value, $label, $isSelected );
		}
		return implode( "", $list );
	}

	/**
	 *	Builds HTML Code for a Password Field.
	 *	@access		public
	 *	@param		string		$name			Field Name
	 *	@param		string		$class			CSS Class (xl|l|m|s|xs)
	 *	@param		mixed		$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@param		int			$tabIndex		Tabbing Order
	 *	@param		int			$maxLength		Maximum Length
	 *	@return		string
	 */
	public static function Password( $name, $class = NULL, $readOnly = NULL, $tabIndex = NULL, $maxLength = NULL )
	{
		$attributes	= array(
			'id'		=> $name,
			'type'		=> "password",
			'name'		=> $name,
			'class'		=> $class,
			'tabindex'	=> $tabIndex,
			'maxlength'	=> $maxLength,
		);
		if( $readOnly )
			self::addReadonlyAttributes( $attributes, $readOnly );
		return UI_HTML_Tag::create( "input", NULL, $attributes );
	}

	/**
	 *	Builds HTML Code for Radio Buttons.
	 *	@access		public
	 *	@param		string		$name 			Field Name
	 *	@param		string		$value 			Field Value if checked
	 *	@param		string		$checked 		Field State
	 *	@param		string		$class			CSS Class
	 *	@param		mixed		$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@return		string
	 */ 
	public static function Radio( $name, $value, $checked = NULL, $class = NULL, $readOnly = NULL )
	{
		$attributes	= array(
			'id'		=> $name.'_'.$value,
			'type'		=> "radio",
			'name'		=> $name,
			'value'		=> $value,
			'class'		=> $class,
			'checked'	=> $checked		? "checked" : NULL,
			'disabled'	=> $readOnly	? "disabled" : NULL,
		);
		if( $readOnly )
			self::addReadonlyAttributes( $attributes, $readOnly );
		return UI_HTML_Tag::create( "input", NULL, $attributes );
	}

	/**
	 *	Builds HTML Code for a Button to reset the current Form.
	 *	@access		public
	 *	@param		string		$label	 		Button Label
	 *	@param		string		$class			CSS Class
	 *	@param		string		$confirm 		Confirmation Message
	 *	@param		mixed		$disabled		Button is not pressable, JavaScript Alert if String is given
	 *	@return		string
	 */
	public static function ResetButton( $label, $class = NULL, $confirm = NULL, $disabled = NULL )
	{
		$attributes	= array(
			'type'		=> "reset",
			'class'		=> $class,
			'onclick'	=> $confirm		? "return confirm('".$confirm."');" : NULL,
		);
		if( $disabled )
			self::addReadOnlyAttributes( $attributes, $disabled );
		return UI_HTML_Tag::create( "button", $label, $attributes );
	}

	/**
	 *	Builds HTML Code for a Select.
	 *	@access		public
	 *	@param		string		$name			Field Name
	 *	@param		mixed		$options		Array of String of Options
	 *	@param		string		$class			CSS Class (xl|l|m|s|xs)
	 *	@param		mixed		$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@param		string		$submit			ID of Form to submit on Change
	 *	@param		string		$focus			ID of Element to focus on Change
	 *	@param		string		$change			JavaScript to execute on Change
	 *	@return		string
	 */
	public static function Select( $name, $options, $class = NULL, $readOnly = NULL, $submit = NULL, $focus = NULL, $change = NULL )
	{
		if( is_array( $options ) )
		{
			$selected	= isset( $options['_selected'] ) ? $options['_selected'] : NULL;
			$options	= self::Options( $options, $selected );
		}
		$focus	= $focus	? "document.getElementById('".$focus."').focus();" : NULL;
		$submit	= $submit	? "document.getElementById('form_".$submit."').submit();" : NULL;
		$attributes	= array(
			'id'		=> str_replace( "[]", "", $name ),
			'name'		=> $name,
			'class'		=> $class,
			'multiple'	=> substr( trim( $name ), -2 ) == "[]"	? "multiple" : NULL,
			'onchange'	=> $focus.$submit.$change ? $focus.$submit.$change : NULL,
		);
		if( $readOnly )
			self::addReadonlyAttributes( $attributes, $readOnly );
		return UI_HTML_Tag::create( "select", $options, $attributes );
	}

	/**
	 *	Builds HTML Code for a Textarea.
	 *	@access		public
	 *	@param		string		$name			Field Name
	 *	@param		string		$content		Field Content
	 *	@param		string		$class			CSS Class (ll|lm|ls|ml|mm|ms|sl|sm|ss)
	 *	@param		mixed		$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@param		string		$validator		Validator Class (using UI.validateInput.js)
	 *	@return		string
	 */
	public static function Textarea( $name, $content, $class = NULL, $readOnly = NULL, $validator = NULL )
	{
		$attributes	= array(
			'id'		=> $name,
			'name'		=> $name,
			'class'		=> $class,
			'onkeyup'	=> $validator	? "allowOnly(this,'".$validator."');" : NULL,
		);
		if( $readOnly )
			self::addReadonlyAttributes( $attributes, $readOnly );
		return UI_HTML_Tag::create( "textarea", (string) $content, $attributes );
	}
}
?>
