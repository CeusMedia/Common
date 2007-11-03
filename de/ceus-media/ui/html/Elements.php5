<?php
/**
 *	Erzeugt HTML-Bausteine fuer Tabellen und Formulare.
 *	@desc			diverse Methoden zur einheitlichen und dynamischen Erstelltung von Formularen und Tabellen.
 *	@package		ui
 *	@subpackage		html
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Erzeugt HTML-Bausteine fuer Tabellen und Formulare.
 *	@desc			diverse Methoden zur einheitlichen und dynamischen Erstelltung von Formularen und Tabellen.
 *	@package		ui
 *	@subpackage		html
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 *	@todo			Code Documentation
 */
class Elements
{
//	public function __construct( ) {}

	public static function Preview( $html, $url, $title, $zoom = false )
	{
		$id	= uniqid( "" );
		$class	= $zoom ? "preview_zoom" : "preview";
		$ins_zoom	= "";
		if( $zoom )
			$ins_zoom	= "
  <a href=\"#\" onclick=\"ImagePreview.zoomIn('img_".$id."');\">[+]</a>&nbsp;
  <a href=\"#\" onclick=\"ImagePreview.zoomOut('img_".$id."');\">[-]</a><br/>";
		$code	= "
<span onclick=\"ImagePreview.change('div_".$id."');\">".$html."</span>
<div id=\"div_".$id."\" class=\"".$class."\">".$ins_zoom."
  <img id=\"img_".$id."\" class=\"".$class."\" src=\"".$url."\" alt=\"".$title."\" title=\"".$title."\" onclick=\"ImagePreview.hide('div_".$id."');\"/>
</div>";
		return $code;
	}


	//  --  DEVELOPMENT  --  //

	public static function RadioGroup( $name, $options, $class = "", $disabled = false )
	{
		$radios	= array();
		foreach( $options as $value => $label )
		{
			if( (string)$value == "_selected" )
				continue;
			$selected = false;
			if( array_key_exists( '_selected', $options ) )
				$selected	= $value == $options['_selected'];
			$radio		= Elements::Radio( $name, $value, $selected, $class, $disabled );
			$content	= "<span class='radiolabel'><span class='radio'>".$radio."</span><span class='label'><label for='".$name."_".$value."'>".$label."</label></span></span>";
			$radios[]	= $content;
		}
		$group	= implode( "", $radios );
		return $group;
	}

	/**
	 *	@param		string		$	$type		Type of Document (html|xhtml)
	 *	@param		string		$	$
	 */ 
	public static function Document( $type, $namespace )
	{
	
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
		$ins_label = $label?" id='fld_".$label."'":""; 
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_text = $this->Label( $label, $text);
		if( is_array( $icons))
		{
			foreach( $icons as $icon) $icons_ .= "<td>".$icon."</td>";
			$icons =  $icons_;
		}
		$ins_box = "<table cellpadding=0 cellspacing=0><tr>".$icons."<td>".$checkbox."</td></tr></table>";
		$code = "<td class='field' ".$ins_label."><table cellpadding=0 cellspacing=0><tr><td".$ins_class.">".$ins_box."</td>".$ins_text."</tr></table></td>";
		return $code;
	}*/
	/**
	 *	@todo	Signature Documentation
	 */
	public static function FoldingArea( $name, $content, $state = false, $class = false )
	{
		$ins_state	= " style=\"display: ".( $state ? "block" : "none" )."\"";
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$code		= "<div id='fa_".$name."' ".$ins_class.$ins_state.">".$content."</div>";
		return $code;
	}

	/**
	 *	@todo	Signature Documentation
	 */
	public static function FoldingButton( $name, $value, $text, $class )
	{
		$onClick = "switchFoldingButton('".$name."', '".( $class ? $class."_" : "" )."'); switchFoldingArea('".$name."'); return false;";
		$ins_class = ( $class ? $class."_" : "" ).( $value ? "set" : "unset" );
	/*	$code = "
		<button id='chkbut_".$name."' class='".$ins_class."' onClick=\"".$onClick."\" onFocus='this.blur()'>".$text."</button>
		<input id='".$name."' type='hidden' name='".$name."' value='".$value."'>";
	*/	$code = "
		<input type='button' id='chkbut_".$name."' class='".$ins_class."' onClick=\"".$onClick."\" onFocus='this.blur()' value='".$text."'/>
		<input id='".$name."' type='hidden' name='".$name."' value='".$value."'/>";
		return $code;
	}

	/**
	 *	@todo:	Signature Documenation
	 */
	public static function CheckButton( $name, $value, $text, $class = "" )
	{
		$ins_class = ( $class ? $class."_" : "" ).( $value ? "set" : "unset" );
		$code = "
		<input id='chkbut_".$name."' type='submit' class='".$ins_class."' value='".$text."' onClick=\"switchCheckButton('".$name."', '".( $class?$class."_":"" )."');\" onFocus='this.blur()'/>
		<input id='".$name."' type='hidden' name='".$name."' value='".$value."'/>";
		return $code;
	}

	/**
	 *	@todo:	Signature Documenation
	 */
	public static function CheckTable( $id, $class = 'panel', $width = "100%", $border = 0, $spacing = 0 )
	{
		$ins_id		= " id=\"chktbl_".$id."\"";
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_border	= $border ? " border=\"".$border."\"" : "";
		$ins_width	= " width=\"".$width."\"";
		$ins_spacing	= " cellspacing=\"".$spacing."\"";
		$code = "<table".$ins_id.$ins_class.$ins_width.$ins_border.$ins_spacing.">";
		return $code;
	}
	
	/**
	 *	@todo:	Signature Documenation
	 */
	public static function CheckTableEnd( $id )
	{
		$code	= "</table><script>ct.recallStatus('".$id."');</script>";
		return $code;
	}

	/**
	 *	@todo:	Signature Documenation
	 */
	public static function Anchor( $name )
	{
		$code	= "<a name='".$name."'></a>";
		return $code;
	}




	//  --  BETA / TEST  --  //
	/**
	 *	Erzeugt HTML Box for Hover über HTML-Elementen.
	 *	@access		public
	 *	@param		string		$html		HTML of linked Element
	 *	@param		string		$text		Text within HelpHover
	 *	@param		string		$class		CSS Class of HelpHover
	 */
	public static function HelpHover( $html, $text, $class = 'helptext' )
	{
		$id	= uniqid( "hhItem" );
		$code	= "<span class=\"hover\" id=\"".$id."\">".$html."</span><div class=\"".$class."\" id=\"".$id."Help\">".$text."</div>";
		return $code;
	}

	/**
	 *	Erzeugt HTML Box for ToolTip über HTML-Elementen.
	 *	@access		public
	 *	@param		string		$html		HTML of linked Element
	 *	@param		string		$text		Text within ToolTip
	 *	@param		string		$class		CSS Class of ToolTip
	 */
	public static function ToolTip( $html, $text, $class = 'container' )
	{
		$id		= uniqid( "ttItem" );
		$text	= str_replace( "  ", "<br/>", $text );
		$tip		= "
<div id='".$id."' class='tooltip' onClick=\"ToolTip.hide('".$id."');\">
  <div class='".$class."'>
    <div class='head'></div>
    <div class='tip'>".$text."</div>
    <div class='foot'></div>
  </div>
</div>";
		$code	= "<span class='tooltip' onMouseOver=\"ToolTip.show('".$id."');\" onMouseOut=\"ToolTip.hide('".$id."');\">".$html."</span>".$tip;
		return $code;
	}

	//  --  STABLE  --  //
	/**
	 *	Erzeugt HTML-Code eines Acronyms.
	 *	@access		public
	 *	@param		string		$text			Text des Acronyms
	 *	@param		string		$description		Beschreibung des Acronyms
	 *	@param		string		$class			CSS-Klasse des Acronyms
	 *	@return		string
	 */
	public static function Acronym( $text, $description, $class = "" )
	{
		$ins_title		= " title=\"".$description."\"";
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$code		= "<acronym".$ins_title.$ins_class.">".$text."</acronym>";
		return $code;
	}

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
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_type	= " type=\"submit\"";
		$ins_name	= " name=\"".$name."\"";
		$ins_value	= " value=\"".$value."\"";
		$ins_disabled	= $disabled ? " disabled=\"disabled\"" : "";
		$ins_confirm	= $confirm ? " onClick=\"return confirm('".$confirm."')\"" : "";
		$code		= "<button".$ins_class.$ins_type.$ins_name.$ins_value.$ins_confirm.$ins_disabled.">".$value."</button>";
		return $code;
	}

	/**
	 *	Erstellt HTML-Code einer CheckBox.
	 *	@access		public
	 *	@param		string		$name 			Name des Formular-Elementes
	 *	@param		string		$value 			Wert der CheckBox
	 *	@param		bool			$checked			aktueller Zustand (0-off | 1-on)
	 *	@param		string		$class 			CSS Style Klasse
	 *	@param		int			$disabled 		Ausgrauen der CheckBox
	 *	@return		string
	 */
	public static function CheckBox( $name, $value, $checked = false, $class = false, $disabled = false)
	{
		$ins_type	= " type=\"checkbox\"";
		$ins_id		= " id=\"".$name."\"";
		$ins_name	= " name=\"".$name."\"";
		$ins_value	= " value=\"".$value."\"";
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_checked	= $checked ? " checked='checked'" : "";
		$ins_disabled	= "";
		if( $disabled )
		{
			if( is_string( $disabled ) )
				$ins_disabled = " disabled onclick=\"alert('".$disabled."');\"";
			else
				$ins_disabled = " disabled";
		}
		$code = "<input".$ins_id.$ins_class.$ins_type.$ins_name.$ins_value.$ins_checked.$ins_disabled."/>";
		return $code;
	}

	/**
	 *	Spaltenangaben in Prozent für eine Tabelle.
	 *	@access		public
	 *	@return		string
	 */
	public static function ColumnGroup()
	{
		$code	= "";
		$cols	= array();
		$args	= func_get_args( );
		if( is_array( $args[0] ) )
			$args	= $args[0];
		if( preg_match( "@,@", $args[0] ) )
			$args	= explode( ",", $args[0] );
		if( sizeof( $args ) )
		{
			foreach( $args as $arg )
				$cols[] = "<col width=\"".$arg."\"/>";
			$cols	= implode( "", $cols );
			$code	= "<colgroup>".$cols."</colgroup>";
		}
		return $code;	
	}
	
	/**
	 *	Erzeugt HTML-Code eines Eingabefeldes.
	 *	@access		public
	 *	@param		string		$field_id			interner Name des Eingabefeldes
	 *	@param		string		$field_element		HTML-Code des Eingabeelements
	 *	@param		string		$class			CSS-Klasse
	 *	@param		string		$suffix			Textausgabe hinter dem Eingabefeld
	 *	@param		string		$colspan			Anzahl der überstreckten Spalten
	 *	@return		string
	 */
	public static function Field( $field_id, $field_element, $class = "field", $suffix = "", $colspan = 1 )
	{
		$ins_id		= "";//$field_id ? " id=\"fld_".$field_id."\"" : "";
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_colspan	= ( $colspan > 1 ) ? " colspan=\"".$colspan."\"" : "";
		if( $suffix )
		{
			$code = "<td".$ins_colspan.">
			  <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			    <tr><td".$ins_id.$ins_class.">".$field_element."</td><td class=\"suffix\">".$suffix."</td></tr></table></td>";
		}
		else $code = "<td".$ins_class.$ins_colspan.$ins_id.">".$field_element."</td>";
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code eines Datei-Feldes (Upload).
	 *	@access		public
	 *	@param		string		$name			Name des Eingabefeldes
	 *	@param		string		$class			CSS-Klasse des Eingabefeldes (in|inbit|inshort|inlong)
	 *	@param		string		$disabled			Deaktiveren des Eingabefeldes
	 *	@param		bool			$readonly		Eingabefeld ist nur lesbar
	 *	@param		int			$tabindex		Tabulatur-Index
	 *	@param		int			$maxlength		maximale Länge
	 *	@return		string
	 */
	public static function File( $name, $value = '', $class = "in", $disabled = false, $readonly = false, $tabindex = false, $maxlength = false )
	{
		$ins_id			= " id=\"".$name."\"";
		$ins_class		= $class ? " class=\"".$class."\"" : "";
		$ins_type		= " type=\"file\"";
		$ins_name		= " name=\"".$name."\"";
		$ins_value		= " value=\"".$value."\"";
		$ins_readonly		= $readonly ? " readonly" : "";	
		$ins_tabindex		= $tabindex ? " tabindex=\"".$tabindex."\"" : "";
		$ins_maxlength	= $maxlength ? " maxlength=\"".$maxlength."\"" : "";
		$ins_disabled 		= "";
		$ins_disabled = $ins_readonly = $ins_tabindex = $ins_maxlength = "";
		if( $disabled )
		{
			if( is_string( $disabled ) )
				$ins_disabled = " readonly onclick=\"alert('".$disabled."');\"";
			else
				$ins_disabled = " disabled";
		}
		$code = "<input".$ins_id.$ins_class.$ins_type.$ins_name.$ins_value.$ins_disabled.$ins_readonly.$ins_tabindex.$ins_maxlength."/>";
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
	public static function Form( $id = "", $action = '', $target = false, $enctype = false, $on_submit = "" )
	{
		$ins_id		= " id=\"form_".$id."\"";
		$ins_method	= " method=\"post\"";
		$ins_action	= " action=\"".str_replace( "&", "&amp;", $action )."\"";
		$ins_enctype	= $enctype ? " enctype=\"".$enctype."\"" : "";
		$ins_submit	= $on_submit ? " onSubmit=\"".$on_submit."\"" : "";
		$code = "<form".$ins_id.$ins_method.$ins_action.$ins_enctype.$ins_submit.">";
//		$code .= Elements::HiddenField( "timestamp", time() );
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code einer Grafik.
	 *	@access		public
	 *	@param		string		$url				URL der Grafik
	 *	@param		string		$title			Alternativ-Text
	 *	@param		string		$class			CSS-Klasse des Eingabefeldes
	 *	@return		string
	 */
	public static function Image( $url, $title, $class = "", $width = false, $height = false )
	{
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_width	= $width ? " width=\"".$width."\"" : "";
		$ins_height	= $height ? " height=\"".$height."\"" : "";
		$ins_source	= " src=\"".$url."\"";
		$ins_title		= " alt=\"".$title."\" title=\"".$title."\"";
		$code		= "<img".$ins_class.$ins_source.$ins_title.$ins_width.$ins_height." hspace=\"0\" vspace=\"0\"/>";
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code eines Eingabefeldes.
	 *	Eingabe-Validierung mit JavaScript.
	 *	@access		public
	 *	@param		string		$name			Name des Eingabefeldes
	 *	@param		string		$value			Wert des Eingabefeldes
	 *	@param		string		$class			CSS-Klasse des Eingabefeldes (in|inbit|inshort|inlong)
	 *	@param		string		$disabled			Deaktiveren des Eingabefeldes
	 *	@param		bool			$readonly		Eingabefeld ist nur lesbar
	 *	@param		int			$tabindex		Tabulatur-Index
	 *	@param		int			$maxlength		maximale Länge
	 *	@param		string		$validator		Validator-Klasse für JavaScript UI.validateInput.js
	 *	@return		string
	 */
	public static function Input( $name, $value = '', $class = "in", $disabled = false, $readonly = false, $tabindex = false, $maxlength = false, $validator = "" )
	{
		$ins_id			= " id=\"".$name."\"";
		$ins_class		= $class ? " class=\"".$class."\"" : "";
		$ins_type		= " type=\"text\"";
		$ins_name		= " name=\"".$name."\"";
		$ins_value		= " value=\"".str_replace( '"', "'", $value )."\"";
		$ins_readonly		= $readonly ? " readonly" : "";	
		$ins_tabindex		= $tabindex ? " tabindex=\"".$tabindex."\"" : "";
		$ins_maxlength	= $maxlength ? " maxlength=\"".$maxlength."\"" : "";
		$ins_disabled 		= "";
		$ins_validator		= $validator ? " onKeyup=\"allowOnly(this, '".$validator."');\"" : "";	
		if( $disabled )
		{
			if( is_string( $disabled ) )
				$ins_disabled = " readonly onclick=\"alert('".$disabled."');\"";
			else
				$ins_disabled = " disabled";
		}
		$code = "<input".$ins_id.$ins_class.$ins_type.$ins_name.$ins_value.$ins_disabled.$ins_readonly.$ins_tabindex.$ins_maxlength.$ins_validator."/>";
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
		$code = "<input type=\"hidden\" name=\"".$name."\" value=\"".$value."\"/>";
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code einer Feldbeschriftung.
	 *	@access		public
	 *	@param		string		$label_name		interner Name des Beschrifungsfeldes
	 *	@param		string		$label_name		Inhalt des Beschriftungsfeldes
	 *	@param		string		$class			CSS-Klasse
	 *	@param		string		$icons			Array mit Icons vor den Eingabefeld
	 *	@param		string		$width			Weitenangabe
	 *	@return		string
	 */
	public static function Label( $label_name, $label_text, $class = 'label', $icons = array(), $width = "" )
	{
		if( !is_array( $icons ) )
		{
			if( $icons )
				$icons = array( $icons );
			else
				$icons = array();
		}
		if( sizeof( $icons ) && $label_name )
		{
			$ins_icons = "";
			foreach( $icons as $icon )
				if( trim( $icon ) )
					$ins_icons .= "<td>".$icon."</td>";
			$code = "<td".$ins_width.">
			<table cellpadding='0' cellspacing='0' border='0' width='100%'>
			  <tr>
				<td class='label' id='lbl_".$label_name."'><label for='".$label_name."'>".$label_text."</label></td>
				<td class='prefix' id='ico_".$label_name."' align='right' valign='middle'>
				  <table cellpadding='0' cellspacing='0' border='0'><tr>".$ins_icons."</tr></table></td>
			  </tr>
			</table>";
		}
		else
		{
			$ins_id		= $label_name ? " id=\"lbl_".$label_name."\"" : "";
			$ins_class	= $class ? " class=\"".$class."\"" : "";
			$label		= $label_name ? "<label for='".$label_name."'>".$label_text."</label>" : $label_text;
			$code = "<td".$ins_id.$ins_class.">".$label."</td>";		
		}
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code eines Links.
	 *	@access		public
	 *	@param		string		$url			URL des Links
	 *	@param		string		$name			Name des Links
	 *	@param		string		$class			CSS-Klasse des Links
	 *	@param		string		$target			Zielframe des Links
	 *	@param		string		$confirm		Bestätigungstext des Links
	 *	@param		int			$tabindex		Tabulatur-Index
	 *	@param		string		$key			Access Key (eindeutiger Buchstabe)
	 *	@return		string
	 */
	public static function Link( $url = "", $name, $class = false, $target = false, $confirm = false, $tabindex = false, $key = false )
	{
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_confirm	= $confirm ? " onClick=\"return confirm('".$confirm."')\"" : "";
		$ins_key	= $key ? " accesskey=\"".$key."\"" : "";
		$ins_target	= $target ? " target=\"".$target."\"" : "";
		$url = str_replace( '"', "'", $url );
		$url = str_replace( "&", "&amp;", $url );
		$ins_tabindex = $tabindex ? " tabindex=\"".$tabindex."\"" : "";
		$code = "<a href=\"".$url."\"".$ins_class.$ins_target.$ins_tabindex.$ins_key.$ins_confirm." onFocus=\"this.blur()\">".$name."</a>";
		$code = "<a href=\"".$url."\"".$ins_class.$ins_target.$ins_tabindex.$ins_key.$ins_confirm.">".$name."</a>";
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
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_type	= " type=\"button\"";
		$ins_value	= " value=\"".$title."\"";
		$ins_id		= " id=\"".md5( $title )."\"";
		$ins_disabled	= $disabled ? " disabled=\"disabled\"" : "";
		$action		= "document.location.href='".$url."';";
		if( $confirm )
			$action	= "if( confirm('".$confirm."') ){".$action."};";
		$ins_action	= " onclick=\"".$action."return false;\"";
/*		$code		= "<input".$ins_class.$ins_type.$ins_value.$ins_action.$ins_disabled." onfocus=\"this.blur();\"/>";*/
		$code		= "<button".$ins_id.$ins_class.$ins_type.$ins_action.$ins_disabled.">".$title."</button>";
		return $code;
	}

	/**
	 *	Build List Item.
	 *	@access		public
	 *	@param		string		$content			Content of List Item
	 *	@param		int			$level			Level of Indenting
	 *	@param		array		$attributes		Array of HTML Attributes
	 *	@return		string
	 */
	public static static function ListItem( $content, $level = 0, $attributes = array() )
	{
		$list	= array();
		$indent	= str_repeat( "  ", 2 * abs( (int)$level ) );
		foreach( $attributes as $key => $value )
			$list[]	= " ".$key."=\"".$value."\"";
		$attributes	= implode( "", $list );
		$code	= "  ".$indent."<li".$attributes.">".$content."</li>";
		return	$code;
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
//		echo "<br>".$key." => ".$value." [".($key != "_selected")."|".((string)$key != "_groupname")."]";
		
		if( (string)$key != "_selected" && (string)$key != "_groupname" )
		{
			$ins_selected = $selected ? " selected='selected'" : "";
//			$color = $color?" selected":"";
			$code = "<option value=\"".$key."\"".$ins_selected.$ins_disabled.">".htmlspecialchars( $value )."</option>";
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
			$code .= "<optgroup label='".$group."'>";
		$code .= Elements::Options( $options, $selected, false );
		if( $group )
			$code .= "</optgroup>";
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
					$code .= Elements::OptionGroup( $option_group['_groupname'], $option_group, $options['_selected'] );
		}
		else
		{
			foreach( $options as $key => $value )
			{
				if( is_array( $selected ) )
					$code .= Elements::Option( $key, $value, in_array( (string)$key, $selected ) );
				else
					$code .= Elements::Option( $key, $value, ( (string)$selected == (string)$key) );
			}
		}
		return $code;
	}

	/**
	 *	Build ordered List from List Items.
	 *	@access		public
	 *	@param		string		$content			Content of List Item
	 *	@param		int			$level			Level of Indenting
	 *	@param		array		$attributes		Array of HTML Attributes
	 *	@return		string
	 */
	static function orderedList( $items, $level = 0, $attributes = array() )
	{
		$content	= implode( "\n", $items );
		$list	= array();
		$indent	= str_repeat( "  ", 2 * abs( (int)$level ) );
		foreach( $attributes as $key => $value )
			$list[]	= " ".$key."=\"".$value."\"";
		$attributes	= implode( "", $list );
		$code	= $indent."<ol".$attributes.">\n".$content."\n".$indent."</ol>";
		return	$code;
	}

	/**
	 *	Erzeugt HTML-Code eines Passwort-Eingabefeldes.
	 *	@access		public
	 *	@param		string		$name			Name des Eingabefeldes
	 *	@param		string		$value			Wert des Eingabefeldes
	 *	@param		string		$class			CSS-Klasse des Eingabefeldes (in|inbit|inshort|inlong)
	 *	@param		string		$disabled			Deaktiveren des Eingabefeldes
	 *	@param		bool			$readonly		Eingabefeld ist nur lesbar
	 *	@param		int			$tabindex		Tabulatur-Index
	 *	@param		int			$maxlength		maximale Länge
	 *	@return		string
	 */
	public static function Password( $name, $value = '', $class = "in", $disabled = false, $readonly = false, $tabindex = false, $maxlength = false )
	{
		$ins_id			= " id=\"".$name."\"";
		$ins_class		= $class ? " class=\"".$class."\"" : "";
		$ins_type		= " type=\"password\"";
		$ins_name		= " name=\"".$name."\"";
		$ins_value		= " value=\"".$value."\"";
		$ins_readonly		= $readonly ? " readonly" : "";	
		$ins_tabindex		= $tabindex ? " tabindex=\"".$tabindex."\"" : "";
		$ins_maxlength	= $maxlength ? " maxlength=\"".$maxlength."\"" : "";
		$ins_disabled 		= "";
		if( $disabled )
		{
			if( is_string( $disabled ) )
				$ins_disabled = " readonly onclick=\"alert('".$disabled."');\"";
			else
				$ins_disabled = " disabled";
		}
		$code = "<input".$ins_id.$ins_class.$ins_type.$ins_name.$ins_value.$ins_disabled.$ins_readonly.$ins_tabindex.$ins_maxlength."/>";
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
		$ins_id		= " id=\"".$name."_".$value."\"";
		$ins_type	= " type=\"radio\"";
		$ins_name	= " name=\"".$name."\"";
		$ins_value	= " value=\"".$value."\"";
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_checked	= $checked ? " checked='checked'" : "";
		$ins_disabled	= $disabled ? " disabled='disabled'" : "";
		$code = "<input".$ins_class.$ins_type.$ins_id.$ins_name.$ins_value.$ins_checked.$ins_disabled."/>";
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
		$radio	= Elements::Radio( $name, $value, $checked, $class, $disabled );
		$field	= Elements::Field( '', $radio );
		$label	= Elements::Label( '', $label, $class );
		$content	= "<tr>".$field.$label."</tr>";
		$code	= Elements::Table( $content, false, false );
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
		$action		= $action ? $action : "this.form.reset()";
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_type	= " type=\"button\"";
		$ins_onclick	= " onClick=\"".$action."; this.blur(); return false;\"";
		$code		= "<button".$ins_class.$ins_type.$ins_onclick.">".$title."</button>";
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
		$ins_disabled	= "";
		$ins_change	= "";
		$ins_multiple	= "";
		$ins_submit	= "";
		$ins_focus	= "";
		if( is_array ($options ) )
		{
			if( isset( $options['_selected'] ) )
				$options = Elements::Options( $options, $options['_selected'] );
			else
				$options = Elements::Options( $options );
		}
		if( $focus || $submit || $change )
		{
			if( $focus )
				$ins_focus= "document.".$focus.".focus();";
			if( $submit )
				$ins_submit = "document.getElementById('form_".$submit."').submit();";
			$ins_change = " onchange=\"".$ins_focus.$ins_submit.$change."\"";
		}
		if( $disabled )
			$ins_disabled = is_string( $disabled )?" readonly onClick=\"alert('".$disabled."');\"":" disabled";
		if( substr( $name, -2 ) == "[]" )
			$ins_multiple = " multiple";
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_name	= " name=\"".$name."\"";
		$ins_id		= " id=\"".$name."\"";
		$code = "<select".$ins_id.$ins_class.$ins_name.$ins_change.$ins_disabled.$ins_multiple.">".$options."</select>";
		return $code;
	}

	/**
	 * Erzeugt HTML-Code einer horizontale und vertikale Trennzeile.
	 *	@access		public
	 *	@param		int			$colspan			Name des Formulars
	 *	@param		int			$rowspan		URL der Aktion
	 *	@param		int			$strength		Stärke der Linie
	 *	@param		string		$class			CSS-Klasse
	 *	@return		string
	 */
	public static function Separator( $colspan = 3, $rowspan = 1, $class = "inline" )
	{
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_colspan	= $colspan ? " colspan=\"".$colspan."\"" : "";
		$ins_rowspan	= $rowspan ? " rowspan=\"".$rowspan."\"" : "";
		$code = "<tr><td".$ins_colspan.$ins_rowspan.$ins_class."></td></tr>";
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code einer Tabelle.
	 *	@access		public
	 *	@param		string		$content			Inhalt der Tabelle
	 *	@param		string		$class 			CSS Style Klasse
	 *	@param		int			$width			Breite der Tabelle
	 *	@param		int			$border			Rahmendicke der Tabelle
	 *	@param		int			$padding			Innenabstand der Tabelle
	 *	@param		int			$spacing			Zellenabstand
	 *	@return		string
	 */
	public static function Table( $content, $class = "filledframe", $width = "100%", $border = 0, $padding = 0, $spacing = 0 )
	{
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_width	= $width ? " width=\"".$width."\"" : "";
		$ins_border	= $border ? " border=\"".$border."\"" : "";
		$ins_padding	= " cellpadding=\"".$padding."\"";
		$ins_spacing	= " cellspacing=\"".$spacing."\"";
		$code = "<table".$ins_class.$ins_width.$ins_border.$ins_padding.$ins_spacing.">".$content."</table>\n";
		return $code;
	}

	/**
	 *	Erzeugt eine Überschriftzeile für Tabellen als HTML-Code.
	 *	@access		public
	 *	@param		string		$caption 			Inhalt der Überschrift
	 *	@param		string		$class 			CSS Style Klasse
	 *	@param		string		$checktable_id	ID der CheckTable
	 *	@return		string
	 */
	public static function TableCaption( $caption, $class = '', $checktable_id = "" )
	{
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_check	= $checktable_id ? " onClick=\"ct.switchTable('".$checktable_id."');\"" : "";
		$code	= "<caption".$ins_class.$ins_check."><span>".$caption."</span></caption>";
		return $code;
	}

	/**
	 *	Erzeugt eine Überschriftzeile für Tabellen als HTML-Code.
	 *	@access		public
	 *	@param		string		$heading 		Inhalt der Überschrift
	 *	@param		int			$colspan 			Spaltenanzahl der Tabelle
	 *	@param		string		$class 			CSS Style Klasse
	 *	@return		string
	 */
	public static function TableHeading( $heading, $colspan = 3, $class = 'tabhead' )
	{
		$code = "";
		if( $heading )
		{
			$ins_class	= $class ? " class=\"".$class."\"" : "";
			$ins_colspan	= $colspan ? " colspan=\"".$colspan."\"" : "";
			$code = "  <tr><th".$ins_class.$ins_colspan.">".$heading."</th></tr>\n";
		}
		return $code;
	}

	/**
	 *	Erzeugt eine Überschriftzeile für Tabellen als HTML-Code.
	 *	@access		public
	 *	@param		array		$heads 		Inhalte der Überschriften
	 *	@param		string		$class 		CSS Style Klasse
	 *	@return		string
	 */
	public static function TableHeads( $heads, $class = '', $colspan = 0 )
	{
		$cols	= array();
		$class	= $class ? " class=\"".$class."\"" : "";
		$colspan	= $colspan ? " colspan=\"".$colspan."\"" : "";
		foreach( $heads as $head )
			$cols[]	= "<th".$class.$colspan.">".$head."</th>";
		$code	= "<tr>".implode( "", $cols )."</tr>";
		return $code;
	}
	
	/**
	 *	Erzeugt HTML-Code eines Textfeldes.
	 *	@access		public
	 *	@param		string		$name			Name des Textfeldes
	 *	@param		string		$value			Inhalt des Textfeldes
	 *	@param		string		$class			CSS-Klasse des Textfeldes (xx|xm|xs|mx|mm|ms|sx|sm|ss)
	 *	@param		string		$disabled			Deaktiveren des Textfeldes
	 *	@param		string		$validator		Validator-Klasse für JavaScript UI.validateInput.js
	 *	@return		string
	 */
	public static function Textarea( $name, $value, $class, $disabled = false, $validator = false )
	{
		$ins_id		= " id=\"".$name."\"";
		$ins_disabled	= "";
		$ins_name	= " name=\"".$name."\"";
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_validator	= $validator ? " onKeyUp=\"allowOnly( this, '".$validator."');\"" : "";
		if( $disabled )
		{
			if( is_string( $disabled ) )
				$ins_disabled = " readonly onclick=\"alert('".$disabled."');\"";
			else
				$ins_disabled = " readonly";
		}
		$code = "<textarea".$ins_id.$ins_name.$ins_class.$ins_disabled.$ins_validator." rows=\"\" cols=\"\">".$value."</textarea>";
		return $code;
	}

	/**
	 *	Build unordered List from List Items.
	 *	@access		public
	 *	@param		string		$content			Content of List Item
	 *	@param		int			$level			Level of Indenting
	 *	@param		array		$attributes		Array of HTML Attributes
	 *	@return		string
	 */
	public static function unorderedList( $items, $level = 0, $attributes = array() )
	{
		$content	= implode( "\n", $items );
		$list	= array();
		$indent	= str_repeat( "  ", 2 * abs( (int)$level ) );
		foreach( $attributes as $key => $value )
			$list[]	= " ".$key."=\"".$value."\"";
		$attributes	= implode( "", $list );
		$code	= $indent."<ul".$attributes.">\n".$content."\n".$indent."</ul>";
		return	$code;
	}
}
?>