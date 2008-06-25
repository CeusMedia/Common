<?php
/**
 *	Builds HTML Form Components.
 *	@package		ui.html
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Builds HTML Form Components.
 *	@package		ui.html
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class UI_HTML_FormElements
{

	//  --  DEVELOPMENT  --  //
	public static function RadioGroup( $name, $options, $class = FALSE, $disabled = FALSE )
	{
		$radios	= array();
		foreach( $options as $value => $label )
		{
			if( (string) $value == '_selected' )
				continue;
			$selected = false;
			if( array_key_exists( '_selected', $options ) )
				$selected	= $value == $options['_selected'];
			$radio		= UI_HTML_Elements::Radio( $name, $value, $selected, $class, $disabled );
			$content	= '<span class="radiolabel"><span class="radio">'.$radio.'</span><span class="label"><label for="'.$name."_".$value.'">'.$label.'</label></span></span>';
			$radios[]	= $content;
		}
		$group	= implode( "", $radios );
		return $group;
	}

	/**
	 *	Erstellt HTML-Code einer CheckBox mit Label.
	 *	@access		public
	 *	@param		string		$checkbox		HTML-Code einer CheckBox
	 *	@param		string		$text			Text der Beschriftung
	 *	@param		string		$class			CSS-Class der Beschriftung
	 *	@param		string		$label			ID der Beschriftung
	 *	@param		string		$icons			HTML-Code der Icons vor der CheckBox
	 *	@return		string
	 *	@todo		Gui_Elements::CheckLabel: Icons einbaun
	 */
/*	public static function CheckLabel( $checkbox, $text, $class, $label, $icons = false)
	{
		$ins_label = $label?" id='fld_'.$label.''":"'; 
		$ins_class	= $class ? ' class="'.$class.'"" : '';
		$ins_text = $this->Label( $label, $text);
		if( is_array( $icons))
		{
			foreach( $icons as $icon) $icons_ .= '<td>'.$icon.'</td>';
			$icons =  $icons_;
		}
		$ins_box = '<table cellpadding=0 cellspacing=0><tr>'.$icons.'<td>'.$checkbox.'</td></tr></table>';
		$code = '<td class='field' '.$ins_label.'><table cellpadding=0 cellspacing=0><tr><td'.$ins_class.'>'.$ins_box.'</td>'.$ins_text.'</tr></table></td>';
		return $code;
	}*/

	/**
	 *	@todo:	Signature Documenation
	 */
	public static function CheckButton( $name, $value, $text, $class = FALSE )
	{
		$ins_class = ( $class ? $class."_" : "" ).( $value ? "set" : "unset" );
		$code = '
		<input id="chkbut_'.$name.'" type="submit" class="'.$ins_class.'" value="'.$text.'" onClick="switchCheckButton(\''.$name.'\', \''.( $class ? $class."_" : "" ).'\');" onFocus="this.blur()"/>
		<input id="'.$name.'" type="hidden" name="'.$name.'" value="'.$value.'"/>';
		return $code;
	}




	//  --  STABLE  --  //
	/**
	 *	Erstellt HTML-Code eines Buttons.
	 *	@access		public
	 *	@param		string		$name 			Name des Formular-Elementes
	 *	@param		string		$value 			Beschriftung des Buttons
	 *	@param		string		$class			CSS-Class der Beschriftung
	 *	@param		string		$confirm 			Nachricht der Bestätigung
	 *	@patam		string		$disabled			Ausgrauen des Buttons
	 *	@return		string
	 */
	public static function Button( $name, $value, $class = 'but', $confirm = false, $disabled = false )
	{
		$ins_class	= $class ? ' class="'.$class.'"' : '';
		$ins_type	= ' type="submit"';
		$ins_name	= ' name="'.$name.'"';
		$ins_value	= ' value="1"';
		$ins_disabled	= $disabled ? ' disabled="disabled"' : "";
		$ins_confirm	= $confirm ? ' onClick="return confirm(\''.$confirm.'\')"' : "";
		$code		= '<button'.$ins_type.$ins_name.$ins_value.$ins_class.$ins_confirm.$ins_disabled.'><span>'.$value.'</span></button>';
		return $code;
	}

	/**
	 *	Erstellt HTML-Code einer CheckBox.
	 *	@access		public
	 *	@param		string		$name 			Name des Formular-Elementes
	 *	@param		string		$value 			Wert der CheckBox
	 *	@param		bool		$checked			aktueller Zustand (0-off | 1-on)
	 *	@param		string		$class 			CSS Style Klasse
	 *	@param		int			$disabled 		Ausgrauen der CheckBox
	 *	@return		string
	 */
	public static function CheckBox( $name, $value, $checked = false, $class = false, $disabled = false)
	{
		$ins_type	= ' type="checkbox"';
		$ins_id		= ' id="'.$name.'"';
		$ins_name	= ' name="'.$name.'"';
		$ins_value	= ' value="'.$value.'"';
		$ins_class	= $class ? ' class="'.$class.'"' : '';
		$ins_checked	= $checked ? ' checked="checked"' : '';
		$ins_disabled	= '';
		if( $disabled )
		{
			$ins_disabled = ' disabled';
			if( is_string( $disabled ) )
				$ins_disabled = ' disabled onclick="alert(\''.$disabled.'\');"';
		}
		$code = '<input'.$ins_id.$ins_class.$ins_type.$ins_name.$ins_value.$ins_checked.$ins_disabled.'/>';
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code eines Datei-Feldes (Upload).
	 *	@access		public
	 *	@param		string		$name			Name des Eingabefeldes
	 *	@param		string		$class			CSS-Klasse des Eingabefeldes (in|inbit|inshort|inlong)
	 *	@param		string		$disabled			Deaktiveren des Eingabefeldes
	 *	@param		bool		$readonly		Eingabefeld ist nur lesbar
	 *	@param		int			$tabindex		Tabulatur-Index
	 *	@param		int			$maxlength		maximale Länge
	 *	@return		string
	 */
	public static function File( $name, $value = "", $class = "in", $disabled = false, $readonly = false, $tabindex = false, $maxlength = false )
	{
		$ins_id			= ' id="'.$name.'"';
		$ins_class		= $class ? ' class="'.$class.'"' : "";
		$ins_type		= ' type="file"';
		$ins_name		= ' name="'.$name.'"';
		$ins_value		= ' value="'.$value.'"';
		$ins_readonly	= $readonly ? ' readonly' : "";	
		$ins_tabindex	= $tabindex ? ' tabindex="'.$tabindex.'"' : "";
		$ins_maxlength	= $maxlength ? ' maxlength="'.$maxlength.'"' : "";
		$ins_disabled	= $ins_readonly = $ins_tabindex = $ins_maxlength = "";
		if( $disabled )
		{
			$ins_disabled = ' disabled';
			if( is_string( $disabled ) )
				$ins_disabled = ' readonly onclick="alert(\''.$disabled.'\');"';
		}
		$code = '<input'.$ins_id.$ins_class.$ins_type.$ins_name.$ins_value.$ins_disabled.$ins_readonly.$ins_tabindex.$ins_maxlength.'/>';
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code eines post-Formulars.
	 *	@access		public
	 *	@param		string		$id				ID des Formulars
	 *	@param		string		$action			URL der Aktion
	 *	@param		string		$target			Zielframe der Aktion
	 *	@param		string		$enctype		Encryption-Typ, für Uploads
	 *	@param		string		$on_submit		JavaScript vor dem Versenden des Formulars
	 *	@return		string
	 */
	public static function Form( $id = "", $action = "", $target = false, $enctype = false, $on_submit = "" )
	{
		$ins_id		= ' id="form_'.$id.'"';
		$ins_method	= ' method="post"';
		$ins_action	= ' action="'.str_replace( "&", "&amp;", $action ).'"';
		$ins_enctype	= $enctype ? ' enctype="'.$enctype.'"' : "";
		$ins_submit	= $on_submit ? ' onSubmit="'.$on_submit.'"' : "";
		$code = '<form'.$ins_id.$ins_method.$ins_action.$ins_enctype.$ins_submit.'>';
//		$code .= UI_HTML_Elements::HiddenField( 'timestamp", time() );
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code eines Eingabefeldes.
	 *	Eingabe-Validierung mit JavaScript.
	 *	@access		public
	 *	@param		string		$name			Name des Eingabefeldes
	 *	@param		string		$value			Wert des Eingabefeldes
	 *	@param		string		$class			CSS-Klasse des Eingabefeldes (in|inbit|inshort|inlong)
	 *	@param		string		$disabled		Deaktiveren des Eingabefeldes
	 *	@param		bool		$readonly		Eingabefeld ist nur lesbar
	 *	@param		int			$tabindex		Tabulatur-Index
	 *	@param		int			$maxlength		maximale Länge
	 *	@param		string		$validator		Validator-Klasse für JavaScript UI.validateInput.js
	 *	@return		string
	 */
	public static function Input( $name, $value = "", $class = "in", $disabled = false, $readonly = false, $tabindex = false, $maxlength = false, $validator = "" )
	{
		$ins_id			= ' id="'.$name.'"';
		$ins_class		= $class ? ' class="'.$class.'"' : "";
		$ins_type		= ' type="text"';
		$ins_name		= ' name="'.$name.'"';
		$ins_value		= ' value="'.addslashes( $value ).'"';
		$ins_readonly	= $readonly ? ' readonly' : "";	
		$ins_tabindex	= $tabindex ? ' tabindex="'.$tabindex.'"' : "";
		$ins_maxlength	= $maxlength ? ' maxlength="'.$maxlength.'"' : "";
		$ins_disabled 	= "";
		$ins_validator	= $validator ? ' onKeyup="allowOnly(this, \''.$validator.'\');"' : "";	
		if( $disabled )
		{
			if( is_string( $disabled ) )
				$ins_disabled = ' readonly onclick="alert(\''.$disabled.'\');"';
			else
				$ins_disabled = ' disabled';
		}
		$code = '<input'.$ins_id.$ins_class.$ins_type.$ins_name.$ins_value.$ins_disabled.$ins_readonly.$ins_tabindex.$ins_maxlength.$ins_validator.'/>';
		return $code;
	}

	/**
	 *	Erezeugt HTML-Code eines versteckten Eingabefeldes mit einem Wert.
	 *	@access		public
	 *	@param		string		$class			CSS-Klasse
	 *	@return 		string
	 */
	public static function HiddenField( $name, $value )
	{
		$code = '<input type="hidden" name="'.$name.'" value="'.$value.'"/>';
		return $code;
	}

	/**
	 *	Erstellt HTML-Code eines Buttons.
	 *	@access		public
	 *	@param		string		$title 			Beschriftung des Buttons
	 *	@param		string		$url			URL to request
	 *	@param		string		$class			CSS-Class der Beschriftung
	 *	@param		string		$confirm 		Nachricht der Bestätigung
	 *	@patam		string		$disabled		Ausgrauen des Buttons
	 *	@return		string
	 */
	public static function LinkButton( $title, $url, $class = 'but', $confirm = false, $disabled = false)
	{
		$ins_class		= $class ? ' class="'.$class.'"' : "";
		$ins_type		= ' type="button"';
		$ins_value		= ' value="'.$title.'"';
		$ins_id			= ' id="button_'.md5( $title ).'"';
		$ins_disabled	= $disabled ? ' disabled="disabled"' : "";
		$action			= 'document.location.href=\''.$url.'\';';
		if( $confirm )
			$action	= 'if( confirm(\''.$confirm.'\') ){'.$action.'};';
		$ins_action	= ' onclick="'.$action.'return false;"';
/*		$code		= '<input'.$ins_class.$ins_type.$ins_value.$ins_action.$ins_disabled.' onfocus="this.blur();"/>';*/
		$code		= '<button'.$ins_id.$ins_class.$ins_type.$ins_action.$ins_disabled.'><span>'.$title.'</span></button>';
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code einer Option für eine SelectBox.
	 *	@access		public
	 *	@param		string		$key			Schlüssel der Option
	 *	@param		string		$value			Anzeigewert der Option
	 *	@param		string		$selected			Auswahlstatus der Option
	 *	@param		string		$disabled			Ausgrauen der Option
	 *	@param		string		$color			Hintergrundfarge der Option
	 *	@return		string
	 */
	public static function Option( $key, $value, $selected = false, $disabled = false, $color = "" )
	{
		$ins_disabled = $disabled ? " disabled" : "";
		$code = "";
//		echo '<br>'.$key.' => '.$value.' ['.($key != '_selected").'|'.((string)$key != '_groupname").']';
		
		if( (string) $key != "_selected" && (string) $key != "_groupname" )
		{
			$ins_selected = $selected ? ' selected="selected"' : "";
//			$color = $color?" selected":"';
			$code = '<option value="'.$key.'"'.$ins_selected.$ins_disabled.'>'.htmlspecialchars( $value ).'</option>';
		}
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code einer Optionen-Gruppe für eine SelectBox.
	 *	@access		public
	 *	@param		string		$group			Name der Optionen-Gruppe
	 *	@param		string		$options 			Array mit Optionen
	 *	@param		string		$selected			Auswahlstatus der Option
	 *	@param		string		$code			HTML-Code zum Anhängen
	 *	@return		string
	 */
	public static function OptionGroup( $group, $options, $selected = false, $code = "" )
	{
		$code = "";
		if( $group )
			$code .= '<optgroup label="'.$group.'">';
		$code .= UI_HTML_Elements::Options( $options, $selected, false );
		if( $group )
			$code .= '</optgroup>';
		return $code;
	}

	/**
	 *	Erstellt HTML-Code der Optionen für eine SelectBox aus einem Array.
	 *	@access		public
	 *	@param		array		$options 			Array mit Optionen
	 *	@param		string		$selected			selektiertes Element
	 *	@return		string
	 */
	public static function Options( $options, $selected = false )
	{
		$code = "";
		if( isset( $options[0] ) && is_array( $options[0] ) )
		{
			foreach( $options as $option_group )
				if( is_array( $option_group ) )
					$code .= UI_HTML_Elements::OptionGroup( $option_group['_groupname'], $option_group, $options['_selected'] );
		}
		else
		{
			foreach( $options as $key => $value )
			{
				if( is_array( $selected ) )
					$code .= UI_HTML_Elements::Option( $key, $value, in_array( (string)$key, $selected ) );
				else
					$code .= UI_HTML_Elements::Option( $key, $value, ( (string)$selected == (string)$key) );
			}
		}
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code eines Passwort-Eingabefeldes.
	 *	@access		public
	 *	@param		string		$name			Name des Eingabefeldes
	 *	@param		string		$class			CSS-Klasse des Eingabefeldes (in|inbit|inshort|inlong)
	 *	@param		string		$disabled		Deaktiveren des Eingabefeldes
	 *	@param		bool		$readonly		Eingabefeld ist nur lesbar
	 *	@param		int			$tabindex		Tabulatur-Index
	 *	@param		int			$maxlength		maximale Länge
	 *	@return		string
	 */
	public static function Password( $name, $class = "in", $disabled = false, $readonly = false, $tabindex = false, $maxlength = false )
	{
		$ins_id			= ' id="'.$name.'"';
		$ins_class		= $class ? ' class="'.$class.'"' : "";
		$ins_type		= ' type="password"';
		$ins_name		= ' name="'.$name.'"';
		$ins_readonly	= $readonly ? ' readonly' : "";	
		$ins_tabindex	= $tabindex ? ' tabindex="'.$tabindex.'"' : "";
		$ins_maxlength	= $maxlength ? ' maxlength="'.$maxlength.'"' : "";
		$ins_disabled 	= "";
		if( $disabled )
		{
			$ins_disabled = ' disabled';
			if( is_string( $disabled ) )
				$ins_disabled = ' readonly onclick="alert(\''.$disabled.'\');"';
		}
		$code = '<input'.$ins_id.$ins_class.$ins_type.$ins_name.$ins_disabled.$ins_readonly.$ins_tabindex.$ins_maxlength.'/>';
		return $code;
	}

	/**
	 *	Erstellt HTML-Code für RadioButtons.
	 *	@access		public
	 *	@param		string		$name 			Name des Formular-Elementes
	 *	@param		string		$value 			Wert des RadionButtons
	 *	@param		string		$checked 		Auswahl-Status
	 *	@param		string		$class			CSS-Klasse des RadioButtons
	 *	@param		bool			$disabled 		Deaktivieren des RadioButtons
	 *	@return		string
	 */ 
	public static function Radio( $name, $value, $checked = false, $class = 'radio', $disabled = false )
	{
		$ins_id		= ' id="'.$name.'_'.$value.'"';
		$ins_type	= ' type="radio"';
		$ins_name	= ' name="'.$name.'"';
		$ins_value	= ' value="'.$value.'"';
		$ins_class	= $class ? ' class="'.$class.'"' : "";
		$ins_checked	= $checked ? ' checked="checked"' : "";
		$ins_disabled	= $disabled ? ' disabled="disabled"' : "";
		$code = '<input'.$ins_class.$ins_type.$ins_id.$ins_name.$ins_value.$ins_checked.$ins_disabled.'/>';
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code eines RadioLabels.
	 *	@access		public
	 *	@param		string		$name			Name des RadioButtons
	 *	@param		string		$label			Inhalt des Beschriftungsfeldes
	 *	@param		string		$value			Wert des RadioButtons
	 *	@param		string		$checked 		Auswahl-Status
	 *	@param		string		$class			CSS-Klasse des RadioButtons
	 *	@param		string		$disabled			Deaktivieren des RadioButtons
	 *	@return		string
	 */
	public static function RadioLabel( $name, $label, $value, $checked = false, $class = 'radio', $disabled = false )
	{
		$radio		= UI_HTML_Elements::Radio( $name, $value, $checked, $class, $disabled );
		$field		= UI_HTML_Elements::Field( '', $radio );
		$label		= UI_HTML_Elements::Label( '', $label, $class );
		$content	= '<tr>'.$field.$label.'</tr>';
		$code		= UI_HTML_Elements::Table( $content, false, false );
		return $code;
	}

	/**
	 *	Erstellt HTML-Code eines Buttons to reset current Formular.
	 *	@access		public
	 *	@param		string		$title	 		Beschriftung des Buttons
	 *	@param		string		$class			CSS-Class der Beschriftung
	 *	@param		string		$action			JavaScript-Aufruf bei Click
	 *	@return		string
	 *	@todo		BETA PROOVE !!!
	 */
	public static function ResetButton( $title, $class = 'but', $action = false )
	{
		$action			= $action ? $action : 'this.form.reset()';
		$ins_class		= $class ? ' class="'.$class.'"' : "";
		$ins_type		= ' type="button"';
		$ins_onclick	= ' onClick="'.$action.'; this.blur(); return false;"';
		$code			= '<button'.$ins_class.$ins_type.$ins_onclick.'>'.$title.'</button>';
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code eines Auswahlfeldes.
	 *	@access		public
	 *	@param		string		$name			Name des Auswahlfeldes
	 *	@param		mixed		$options			Auswahloptionen als String oder Array
	 *	@param		string		$class			CSS-Klasse des Auswahlfeldes
	 *	@param		string		$disabled			Deaktiveren des Auswahlfeldes
	 *	@param		string		$submit			Formular-ID bei Veränderung ausführen
	 *	@param		string		$focus			Focus Element on Change
	 *	@param		string		$change			JavaScript to execute on Change
	 *	@return		string
	 */
	public static function Select( $name, $options, $class = 'sel', $disabled = false, $submit = false, $focus = false, $change = "" )
	{
		$ins_disabled	= '';
		$ins_change		= '';
		$ins_multiple	= '';
		$ins_submit		= '';
		$ins_focus		= '';
		if( is_array( $options ) )
		{
			if( isset( $options['_selected'] ) )
				$options = UI_HTML_Elements::Options( $options, $options['_selected'] );
			else
				$options = UI_HTML_Elements::Options( $options );
		}
		if( $focus || $submit || $change )
		{
			if( $focus )
				$ins_focus= 'document.'.$focus.'.focus();';
			if( $submit )
				$ins_submit = 'document.getElementById(\'form_'.$submit.'\').submit();';
			$ins_change = ' onchange="'.$ins_focus.$ins_submit.$change.'"';
		}
		if( $disabled )
			$ins_disabled = is_string( $disabled ) ? ' readonly onClick="alert(\''.$disabled.'\');"' : ' disabled';
		if( substr( $name, -2 ) == "[]" )
			$ins_multiple = ' multiple';
		$ins_class	= $class ? ' class="'.$class.'"' : "";
		$ins_name	= ' name="'.$name.'"';
		$ins_id		= ' id="'.$name.'"';
		$code = '<select'.$ins_id.$ins_class.$ins_name.$ins_change.$ins_disabled.$ins_multiple.'>'.$options.'</select>';
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code eines Textfeldes.
	 *	@access		public
	 *	@param		string		$name			Name des Textfeldes
	 *	@param		string		$value			Inhalt des Textfeldes
	 *	@param		string		$class			CSS-Klasse des Textfeldes (xx|xm|xs|mx|mm|ms|sx|sm|ss)
	 *	@param		string		$disabled		Deaktiveren des Textfeldes
	 *	@param		string		$validator		Validator-Klasse für JavaScript UI.validateInput.js
	 *	@return		string
	 */
	public static function Textarea( $name, $value, $class, $disabled = false, $validator = false )
	{
		$ins_id			= ' id="'.$name.'"';
		$ins_disabled	= '';
		$ins_name		= ' name="'.$name.'"';
		$ins_class		= $class ? ' class="'.$class.'"' : "";
		$ins_validator	= $validator ? ' onKeyUp="allowOnly( this, \''.$validator.'\');"' : "";
		if( $disabled )
		{
			$ins_disabled = ' readonly';
			if( is_string( $disabled ) )
				$ins_disabled = ' readonly onclick="alert(\''.$disabled.'\');"';
		}
		$code = '<textarea'.$ins_id.$ins_name.$ins_class.$ins_disabled.$ins_validator.' rows="" cols="">'.$value.'</textarea>';
		return $code;
	}
}
?>