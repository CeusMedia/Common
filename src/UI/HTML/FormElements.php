<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Builder for HTML Form Components.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\HTML;

/**
 *	Builder for HTML Form Components.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class FormElements
{
	/**
	 *	Adds Disabled Attributes directly to Attributes Array, inserts JavaScript Alert if String given.
	 *	@access		protected
	 *	@param		array			$attributes		Reference to Attributes Array
	 *	@param		string|NULL		$disabled		Bool or String, String will be set in mit JavaScript Alert
	 *	@return		void
	 */
	protected static function addDisabledAttributes( array &$attributes, ?string $disabled = NULL )
	{
		$attributes['readonly']	= 'readonly';
		$attributes['onclick']	= $disabled ? "alert('".$disabled."');" : 'disabled';
	}

	/**
	 *	Adds Readonly Attributes directly to Attributes Array, inserts JavaScript Alert if String given.
	 *	@access		protected
	 *	@param		array				$attributes		Reference to Attributes Array
	 *	@param		string|bool|NULL	$readOnly		Bool or String, String will be set in with JavaScript Alert
	 *	@return		void
	 */
	protected static function addReadonlyAttributes( array &$attributes, $readOnly = NULL )
	{
		$attributes['readonly']	= "readonly";
		if( is_string( $readOnly ) )
			$attributes['onclick']	= "alert('".$readOnly."');";
	}

	//  --  STABLE  --  //
	/**
	 *	Builds HTML Code for a Button to submit a Form.
	 *	@access		public
	 *	@static
	 *	@param		string			$name 			Button Name
	 *	@param		string			$label 			Button Label
	 *	@param		string|NULL		$class			CSS Class
	 *	@param		string|NULL		$confirm 		Confirmation Message
	 *	@param		string|NULL		$disabled		Button is not usable, JavaScript Alert if String is given
	 *	@param		string|NULL		$title			Titel text on mouse hover
	 *	@return		string
	 */
	public static function Button( string $name, string $label, ?string $class = NULL, ?string $confirm = NULL, ?string $disabled = NULL, ?string $title = NULL ): string
	{
		$attributes	= array(
			'type'		=> "submit",
			'name'		=> $name,
			'value'		=> 1,
			'class'		=> $class,
			'onclick'	=> $confirm ? "return confirm('".$confirm."');" : NULL,
			'title'		=> $title,
		);
		if( $disabled )
			self::addDisabledAttributes( $attributes, $disabled );
		return Tag::create( "button", Tag::create( "span", $label ), $attributes );
	}

	/**
	 *	Builds HTML Code for a Checkbox.
	 *	@access		public
	 *	@static
	 *	@param		string				$name 			Field Name
	 *	@param		string|int|float	$value 			Field Value if checked
	 *	@param		bool				$checked		Field State
	 *	@param		string|NULL			$class 			CSS Class
	 *	@param		string|bool|NULL	$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@return		string
	 */
	public static function Checkbox( string $name, $value, bool $checked = NULL, ?string $class = NULL, $readOnly = NULL ): string
	{
		$attributes	= array(
			'id'		=> $name,
			'type'		=> "checkbox",
			'name'		=> $name,
			'value'		=> (string) $value,
			'class'		=> $class,
			'checked'	=> $checked ? "checked" : NULL,
			'disabled'	=> $readOnly && !is_string( $readOnly ) ? "disabled" : NULL,
		);
		if( $readOnly )
			self::addReadonlyAttributes( $attributes, $readOnly );
		return Tag::create( "input", NULL, $attributes );
	}

	/**
	 *	Builds HTML Code for a File Upload Field.
	 *	@access		public
	 *	@static
	 *	@param		string				$name			Field Name
	 *	@param		string|int|float	$value			Field Value
	 *	@param		string|NULL			$class			CSS Class (xl|l|m|s|xs)
	 *	@param		string|bool|NULL	$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@param		int|NULL			$tabIndex		Tabbing Order
	 *	@param		int|NULL			$maxLength		Maximum Length
	 *	@return		string
	 */
	public static function File( string $name, $value = "", ?string $class = NULL, $readOnly = NULL, ?int $tabIndex = NULL, ?int $maxLength = NULL ): string
	{
		$attributes	= array(
			'id'		=> $name,
			'type'		=> "file",
			'name'		=> $name,
			'value'		=> (string) $value,
			'class'		=> $class,
			'tabindex'	=> $tabIndex,
			'maxlength'	=> $maxLength,
		);
		if( $readOnly )
			self::addReadonlyAttributes( $attributes, $readOnly );
		return Tag::create( "input", NULL, $attributes );
	}

	/**
	 *	Builds HTML Code for a Form using POST.
	 *	@access		public
	 *	@static
	 *	@param		string|NULL		$name			Form Name, also used for ID with Prefix 'form_'
	 *	@param		string|NULL		$action			Form Action, mostly a URL
	 *	@param		string|NULL		$target			Target Frage of Action
	 *	@param		string|NULL		$enctype		Encryption Type, needs to be 'multipart/form-data' for File Uploads
	 *	@param		string|NULL		$onSubmit 		JavaScript to execute before Form is submitted, Validation is possible
	 *	@return		string
	 */
	public static function Form( ?string $name = NULL, ?string $action = NULL, ?string $target = NULL, ?string $enctype = NULL, ?string $onSubmit = NULL ): string
	{
		$attributes	= array(
			'id'		=> $name ? "form_".$name : NULL,
			'name'		=> $name,
			'action'	=> $action ? str_replace( "&", "&amp;", $action ) : NULL,
			'target'	=> $target,
			'method'	=> "post",
			'enctype'	=> $enctype,
			'onsubmit'	=> $onSubmit,
		);
		$form	= Tag::create( "form", NULL, $attributes );
		return preg_replace( "@/>$@", ">", $form );
	}

	/**
	 *	Builds HTML Code for a hidden Input Field. It is not advised to work with hidden Fields.
	 *	@access		public
	 *	@static
	 *	@param		string				$name			Field Name
	 *	@param		string|int|float	$value			Field Value
	 *	@return 	string
	 */
	public static function HiddenField( string $name, $value ): string
	{
		$attributes	= array(
			'id'		=> $name,
			'type'		=> "hidden",
			'name'		=> $name,
			'value'		=> (string) $value,
		);
		return Tag::create( "input", NULL, $attributes );
	}

	/**
	 *	Builds HTML Code for an Input Field. Validation is possible using Validator Classes from UI.validateInput.js.
	 *	@access		public
	 *	@static
	 *	@param		string					$name			Field Name
	 *	@param		string|int|float|NULL	$value			Field Value
	 *	@param		string|NULL				$class			CSS Class (xl|l|m|s|xs)
	 *	@param		string|bool|NULL		$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@param		int|NULL				$tabIndex		Tabbing Order
	 *	@param		int|NULL				$maxLength		Maximum Length
	 *	@param		string|NULL				$validator		Validator Class (using UI.validateInput.js)
	 *	@return		string
	 */
	public static function Input( string $name, $value = NULL, ?string $class = NULL, $readOnly = NULL, ?int $tabIndex = NULL, ?int $maxLength = NULL, ?string $validator = NULL ): string
	{
		$attributes	= array(
			'id'		=> $name,
			'type'		=> "text",
			'name'		=> $name,
			'value'		=> (string) $value,
			'class'		=> $class,
			'tabindex'	=> $tabIndex,
			'maxlength'	=> $maxLength,
			'onkeyup'	=> $validator	? "allowOnly(this,'".$validator."');" : NULL,
		);
		if( $readOnly )
			self::addReadonlyAttributes( $attributes, $readOnly );
		return Tag::create( "input", NULL, $attributes );
	}

	/**
	 *	Builds HTML Code for a Field Label.
	 *	@access		public
	 *	@static
	 *	@param		string		$inputId		ID of Field to reference
	 *	@param		string			$label			Label Text
	 *	@param		string|NULL		$class			CSS Class
	 *	@return		string
	 */
	public static function Label( string $inputId, string $label, ?string $class = NULL ): string
	{
		return Tag::create( "label", $label, [
			'for'		=> $inputId,
			'class'		=> $class,
		] );
	}

	/**
	 *	Builds HTML Code for a Button behaving like a Link.
	 *	@access		public
	 *	@static
	 *	@param		string			$url			URL to request
	 *	@param		string			$label			Button Label, also used for ID with Prefix 'button_' and MD5 Hash
	 *	@param		string|NULL		$class			CSS Class
	 *	@param		string|NULL		$confirm 		Confirmation Message
	 *	@param		string|NULL		$disabled		Button is not usable, JavaScript Alert if String is given
	 *	@param		string|NULL		$title			Title text on mouse hove
	 *	@return		string
	 */
	public static function LinkButton( string $url, string $label, ?string $class = NULL, ?string $confirm = NULL, ?string $disabled = NULL, ?string $title = NULL ): string
	{
		$action			= "document.location.href='".$url."';";
		$attributes	= array(
			'id'		=> "button_".md5( $label ),
			'type'		=> "button",
			'class'		=> $class,
			'onclick'	=> $confirm	? "if(confirm('".$confirm."')){".$action."};" : $action,
			'title'		=> $title,
		);
		if( $disabled )
			self::addDisabledAttributes( $attributes, $disabled );
		return Tag::create( "button", Tag::create( "span", $label ), $attributes );
	}

	/**
	 *	Builds HTML Code for an Option for a Select.
	 *	@access		public
	 *	@static
	 *	@param		string|int|float	$value			Option Value
	 *	@param		string				$label			Option Label
	 *	@param		bool				$selected		Option State
	 *	@param		bool				$disabled		Option is not selectable
	 *	@param		string|NULL			$class			CSS Class
	 *	@return		string
	 */
	public static function Option( $value, string $label, bool $selected = FALSE, bool $disabled = FALSE, ?string $class = NULL ): string
	{
		if( !( $value != "_selected" && $value != "_groupname" ) )
			return "";
		$attributes	= array(
			'value'		=> $value,
			'selected'	=> $selected ? "selected" : NULL,
			'disabled'	=> $disabled ? "disabled" : NULL,
			'class'		=> $class,
		);
		return Tag::create( "option", htmlspecialchars( $label ), $attributes );
	}

	/**
	 *	Builds HTML Code for an Option Group for a Select.
	 *	@access		public
	 *	@static
	 *	@param		string				$label			Group Label
	 *	@param		array				$options 		Array of Options
	 *	@param		string|array|NULL		$selected		Value of selected Option
	 *	@return		string
	 */
	public static function OptionGroup( string $label, array $options, $selected = NULL ): string
	{
		$attributes	= ['label' => $label];
		$options	= self::Options( $options, $selected );
		return Tag::create( "optgroup", $options, $attributes );
	}

	/**
	 *	Builds HTML Code for Options for a Select.
	 *	@access		public
	 *	@static
	 *	@param		array				$options 			Array of Options
	 *	@param		string|array|NULL	$selected			Value of selected Option
	 *	@return		string
	 */
	public static function Options( array $options, $selected = NULL ): string
	{
		$list		= [];
		foreach( $options as $key => $value){
			if( (string) $key != "_selected" && is_array( $value ) ){
				foreach( $options as $groupLabel => $groupOptions ){
					if( !is_array( $groupOptions ) )
						continue;
					if( (string) $groupLabel == "_selected" )
						continue;
					$groupName	= $groupOptions['_groupname'] ?? $groupLabel;
					$select		= $options['_selected'] ?? $selected;
					$list[]		= self::OptionGroup( $groupName, $groupOptions, $select );
				}
				return implode( "", $list );
			}
		}
		foreach( $options as $value => $label ){
			$isSelected	= is_array( $selected ) ? in_array( $value, $selected ) : (string) $selected == (string) $value;
			$list[]		= self::Option( $value, $label, $isSelected );
		}
		return implode( "", $list );
	}

	/**
	 *	Builds HTML Code for a Password Field.
	 *	@access		public
	 *	@static
	 *	@param		string				$name			Field Name
	 *	@param		string|NULL			$class			CSS Class (xl|l|m|s|xs)
	 *	@param		string|bool|NULL	$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@param		int|NULL			$tabIndex		Tabbing Order
	 *	@param		int|NULL			$maxLength		Maximum Length
	 *	@return		string
	 */
	public static function Password( string $name, ?string $class = NULL, $readOnly = NULL, ?int $tabIndex = NULL, ?int $maxLength = NULL ): string
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
		return Tag::create( "input", NULL, $attributes );
	}

	/**
	 *	Builds HTML Code for Radio Buttons.
	 *	@access		public
	 *	@static
	 *	@param		string				$name			Field Name
	 *	@param		string|int|float	$value			Field Value if checked
	 *	@param		boolean				$checked		Field State
	 *	@param		string|NULL			$class			CSS Class
	 *	@param		string|bool|NULL	$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@return		string
	 */
	public static function Radio( string $name, $value, bool $checked = FALSE, ?string $class = NULL, $readOnly = NULL ): string
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
		return Tag::create( "input", NULL, $attributes );
	}

	/**
	 *	Builds HTML for a Group of Radio Buttons, behaving like a Select.
	 *	@access		public
	 *	@static
	 *	@param		string				$name			Field Name
	 *	@param		array				$options		Array of Options
	 *	@param		string|NULL			$class			CSS Class
	 *	@param		string|bool|NULL	$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@return		string
	 */
	public static function RadioGroup( string $name, array $options, ?string $class = NULL, $readOnly = NULL ): string
	{
		$radios	= [];
		foreach( $options as $value => $label ){
			if( (string) $value == '_selected' )
				continue;
			$selected	= isset( $options['_selected'] ) ? (string) $value == (string) $options['_selected'] : NULL;
			$radio		= self::Radio( $name, $value, $selected, $class, $readOnly );
			$spanRadio	= Tag::create( "span", $radio, ['class' => 'radio'] );
			$label		= Tag::create( "label", $label, ['for' => $name."_".$value] );
			$spanLabel	= Tag::create( "span", $label, ['class' => 'label'] );
			$content	= Tag::create( "span", $spanRadio.$spanLabel, ['class' => 'radiolabel'] );
			$radios[]	= $content;
		}
		return implode( "", $radios );
	}

	/**
	 *	Builds HTML Code for a Button to reset the current Form.
	 *	@access		public
	 *	@static
	 *	@param		string			$label	 		Button Label
	 *	@param		string|NULL		$class			CSS Class
	 *	@param		string|NULL		$confirm 		Confirmation Message
	 *	@param		string|NULL		$disabled		Button is not usable, JavaScript Alert if String is given
	 *	@param		string|NULL		$title			Title text on mouse hover
	 *	@return		string
	 */
	public static function ResetButton( string $label, ?string $class = NULL, ?string $confirm = NULL, ?string $disabled = NULL, ?string $title = NULL ): string
	{
		$attributes	= array(
			'type'		=> "reset",
			'class'		=> $class,
			'onclick'	=> $confirm		? "return confirm('".$confirm."');" : NULL,
			'title'		=> $title,
		);
		if( $disabled )
			self::addReadonlyAttributes( $attributes, $disabled );
		return Tag::create( "button", $label, $attributes );
	}

	/**
	 *	Builds HTML Code for a Select.
	 *	@access		public
	 *	@static
	 *	@param		string				$name			Field Name
	 *	@param		string|array		$options		Array of String of Options
	 *	@param		string|NULL			$class			CSS Class (xl|l|m|s|xs)
	 *	@param		string|bool|NULL	$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@param		string|NULL			$submit			ID of Form to submit on Change
	 *	@param		string|NULL			$focus			ID of Element to focus on Change
	 *	@param		string|NULL			$change			JavaScript to execute on Change
	 *	@return		string
	 */
	public static function Select( string $name, $options, ?string $class = NULL, $readOnly = NULL, ?string $submit = NULL, ?string $focus = NULL, ?string $change = NULL ): string
	{
		if( is_array( $options ) ){
			$selected	= $options['_selected'] ?? NULL;
			$options	= self::Options( $options, $selected );
		}
		$focus	= $focus	? "document.getElementById('".$focus."').focus();" : NULL;
		$submit	= $submit	? "document.getElementById('form_".$submit."').submit();" : NULL;
		$attributes	= array(
			'id'		=> str_replace( "[]", "", $name ),
			'name'		=> $name,
			'class'		=> $class,
			'multiple'	=> substr( trim( $name ), -2 ) == "[]"	? "multiple" : NULL,
			'onchange'	=> $focus.$submit.( $change ? $focus.$submit.$change : ''),
		);
		if( $readOnly ){
			$attributes['readonly']		= "readonly";
			if( is_string( $readOnly ) && strlen( trim( $readOnly ) ) )
				$attributes['onmousedown']		= "alert('".htmlentities( $readOnly, ENT_QUOTES, 'UTF-8' )."'); return false;";
			else
				self::addDisabledAttributes( $attributes, TRUE );
		}
		return Tag::create( "select", $options, $attributes );
	}

	/**
	 *	Builds HTML Code for a Textarea.
	 *	@access		public
	 *	@static
	 *	@param		string				$name			Field Name
	 *	@param		string|NULL			$content		Field Content
	 *	@param		string|NULL			$class			CSS Class (ll|lm|ls|ml|mm|ms|sl|sm|ss)
	 *	@param		string|bool|NULL	$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@param		string|NULL			$validator		Validator Class (using UI.validateInput.js)
	 *	@return		string
	 */
	public static function Textarea( string $name, ?string $content = NULL, ?string $class = NULL, $readOnly = NULL, ?string $validator = NULL ): string
	{
		$attributes	= array(
			'id'		=> $name,
			'name'		=> $name,
			'class'		=> $class,
			'onkeyup'	=> $validator	? "allowOnly(this,'".$validator."');" : NULL,
		);
		if( $readOnly )
			self::addReadonlyAttributes( $attributes, $readOnly );
		return Tag::create( "textarea", (string) $content, $attributes );
	}
}
