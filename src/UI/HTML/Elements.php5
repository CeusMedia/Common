<?php
/**
 *	Builds HTML Components.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Builds HTML Components.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@extends		UI_HTML_FormElements
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class UI_HTML_Elements extends UI_HTML_FormElements
{
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

	public static function CheckboxLabel( $name, $value, $checked, $text, $class = 'checklabel' )
	{
		$checkBox	= self::CheckBox( $name, $value, $checked );
		$checkSpan	= UI_HTML_Tag::create( "span", $checkBox, array( 'class' => "checkbox" ) );
		$label		= UI_HTML_Tag::create( "label", $text, array( 'for' => $name ) );
		$span		= UI_HTML_Tag::create( "span", $checkSpan.$label, array( 'class' => $class ) );
		return $span;
		
	}

	/**
	 *	Builds HTML Code for a CheckBox with Label.
	 *	@access		public
	 *	@static
	 *	@param		string		$checkbox		HTML Code einer CheckBox
	 *	@param		string		$text			Text der Beschriftung
	 *	@param		string		$class			CSS Class
	 *	@param		string		$label			ID der Beschriftung
	 *	@param		string		$icons			HTML Code der Icons vor der CheckBox
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
	 *	@static
	 *	@todo		Signature Documenation
	 */
	public static function CheckButton( $name, $value, $text, $class = FALSE )
	{
		$ins_class = ( $class ? $class."_" : "" ).( $value ? "set" : "unset" );
		$code = '
		<input id="chkbut_'.$name.'" type="submit" class="'.$ins_class.'" value="'.$text.'" onClick="switchCheckButton(\''.$name.'\', \''.( $class ? $class."_" : "" ).'\');" onFocus="this.blur()"/>
		<input id="'.$name.'" type="hidden" name="'.$name.'" value="'.$value.'"/>';
		return $code;
	}



	//  --  DEVELOPMENT  --  //
	/**
	 *	Builds HTML Code for a Radio Button with a Label.
	 *	@access		public
	 *	@static
	 *	@param		string		$name			Field Name
	 *	@param		string		$label			Field Label
	 *	@param		string		$value			Field Value
	 *	@param		string		$checked 		Auswahl-Status
	 *	@param		string		$class			CSS Class
	 *	@param		mixed		$readOnly		Field is not writable, JavaScript Alert if String is given
	 *	@return		string
	 */
	public static function RadioLabel( $name, $label, $value, $checked = NULL, $class = NULL, $readOnly = NULL )
	{
		$radio		= UI_HTML_Elements::Radio( $name, $value, $checked, $class, $readOnly );
		$field		= UI_HTML_Elements::FieldCell( '', $radio );
		$label		= UI_HTML_Elements::LabelCell( '', $label, $class );
		$content	= '<tr>'.$field.$label.'</tr>';
		$code		= UI_HTML_Elements::Table( $content, false, false );
		return $code;
	}

	public static function MailLink( $address, $label, $class = "mail", $crypt = TRUE )
	{
		if( $crypt )
		{
			$crypt	= $address;
			$crypt	= str_replace( "@", " (at) ", $crypt );
			$crypt	= str_replace( ".", " [dot] ", $crypt );

			return '<span class="'.$class.'"><span class="mailAddress">'.$crypt.'</span><span class="mailName">'.$label.'</span></span>';
		}
		else
			return self::Link( "mailto:".$address, $label, $class );
	}

	/**
	 *	@static
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
	 *	@static
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
	 *	@static
	 *	@todo		Signature Documenation
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
	 *	@static
	 *	@todo		Signature Documenation
	 */
	public static function CheckTableEnd( $id )
	{
		$code	= "</table><script>ct.recallStatus('".$id."');</script>";
		return $code;
	}

	/**
	 *	@static
	 *	@todo		Signature Documenation
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
	 *	@static
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
	 *	@static
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
	 *	@static
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
	 *	Spaltenangaben in Prozent für eine Tabelle.
	 *	@access		public
	 *	@static
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
	 *	@static
	 *	@param		string		$field_id			interner Name des Eingabefeldes
	 *	@param		string		$field_element		HTML-Code des Eingabeelements
	 *	@param		string		$class			CSS-Klasse
	 *	@param		string		$suffix			Textausgabe hinter dem Eingabefeld
	 *	@param		string		$colspan			Anzahl der überstreckten Spalten
	 *	@return		string
	 */
	public static function Field( $field_id, $field_element, $class = "field", $suffix = "", $colspan = 1 )
	{
		$ins_id		= $field_id ? " id=\"fld_".$field_id."\"" : "";
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

	public static function Heading( $label, $level, $class = NULL )
	{
		return UI_HTML_Tag::create( 'h'.$level, $label, array( 'class' => $class ) );
	}

	/**
	 *	Erzeugt HTML-Code einer Grafik.
	 *	@access		public
	 *	@static
	 *	@param		string		$url				URL der Grafik
	 *	@param		string		$title			Alternativ-Text
	 *	@param		string		$class			CSS-Klasse des Eingabefeldes
	 *	@return		string
	 */
	public static function Image( $url, $title, $class = "", $width = NULL, $height = NULL )
	{
		$attributes	= array(
			'src'		=> $url,
			'class'		=> $class		? $class : NULL,
			'width'		=> $width		? $width : NULL,
			'height'	=> $height		? $height : NULL,
			'alt'		=> $title		? $title : NULL,
			'title'		=> $title		? $title : NULL,
			'hspace'	=> 0,
			'vspace'	=> 0,
		);
		$code	= UI_HTML_Tag::create( "img", NULL, $attributes );
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code einer Feldbeschriftung.
	 *	@access		public
	 *	@static
	 *	@param		string		$label_name		interner Name des Beschrifungsfeldes
	 *	@param		string		$label_name		Inhalt des Beschriftungsfeldes
	 *	@param		string		$class			CSS-Klasse
	 *	@param		string		$icons			Array mit Icons vor den Eingabefeld
	 *	@param		string		$width			Weitenangabe
	 *	@return		string
	 */
	public static function Label( $label_name, $label_text, $class = 'label', $icons = array() )
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
			$code		= "<td".$ins_id.$ins_class.">".$label."</td>";		
		}
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code eines Links.
	 *	@access		public
	 *	@static
	 *	@param		string		$url			URL des Links
	 *	@param		string		$name			Name des Links
	 *	@param		string		$class			CSS-Klasse des Links
	 *	@param		string		$target			Zielframe des Links
	 *	@param		string		$confirm		Bestätigungstext des Links
	 *	@param		int			$tabindex		Tabulatur-Index
	 *	@param		string		$key			Access Key (eindeutiger Buchstabe)
	 *	@param		bool		$relation		Relation (nofollow,licence,...)
	 *	@return		string
	 */
	public static function Link( $url, $name, $class = NULL, $target = NULL, $confirm = NULL, $tabindex = NULL, $key = NULL, $relation = NULL )
	{
		$url = str_replace( '"', "'", $url );
		$url = str_replace( "&", "&amp;", $url );
		$attributes	= array(
			'href'		=> $url,
			'class'		=> $class		? $class : NULL,
			'accesskey'	=> $key			? $key : NULL,
			'tabindex'	=> $tabindex	? $tabindex : NULL,
			'target'	=> $target		? $target : NULL,
			'rel'		=> $relation	? $relation : NULL,
			'onclick'	=> $confirm		? "return confirm('".$confirm."')" : NULL,
		);
		$link	= UI_HTML_Tag::create( "a", $name, $attributes );
		return $link;
	}

	/**
	 *	Build List Item.
	 *	@access		public
	 *	@static
	 *	@param		string		$content		Content of List Item
	 *	@param		int			$level			Level of Indenting
	 *	@param		array		$attributes		Array of HTML Attributes
	 *	@return		string
	 */
	public static function ListItem( $content, $level = 0, $attributes = array() )
	{
		$depth	= 2 * abs( (int) $level ) + 1;
		$indent	= str_repeat( "  ", $depth );
		$tag	= UI_HTML_Tag::create( "li", $content, $attributes );
		$code	= $indent.$tag;
		return $code;
	}

	/**
	 *	Build ordered List from List Items.
	 *	@access		public
	 *	@static
	 *	@param		string		$content			Content of List Item
	 *	@param		int			$level			Level of Indenting
	 *	@param		array		$attributes		Array of HTML Attributes
	 *	@return		string
	 */
	public static function orderedList( $items, $level = 0, $attributes = array() )
	{
		$content	= "\n".implode( "\n", $items )."\n";
		$indent		= str_repeat( "	", 2 * abs( (int) $level ) );
		$tag		= UI_HTML_Tag::create( "ol", $content, $attributes );
		$code		= $indent.$tag;
		return $code;
	}

	/**
	 * Erzeugt HTML-Code einer horizontale und vertikale Trennzeile.
	 *	@access		public
	 *	@static
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
	 *	@static
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
	 *	@static
	 *	@param		string		$caption 			Inhalt der Überschrift
	 *	@param		string		$class 			CSS Style Klasse
	 *	@param		string		$checktable_id	ID der CheckTable
	 *	@return		string
	 */
	public static function TableCaption( $caption, $class = '', $checktable_id = "" )
	{
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_check	= $checktable_id ? " onClick=\"ct.switchTable('".$checktable_id."');\"" : "";
		$span		= UI_HTML_Tag::create( "span", $caption );
		$code		= "<caption".$ins_class.$ins_check.">".$span."</caption>";
		return $code;
	}

	/**
	 *	Erzeugt eine Überschriftzeile für Tabellen als HTML-Code.
	 *	@access		public
	 *	@static
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
	 *	@static
	 *	@param		array		$heads 		Inhalte der Überschriften
	 *	@param		string		$class 		CSS Style Klasse
	 *	@return		string
	 */
	public static function TableHeads( $heads, $class = '', $colspan = 0 )
	{
		$cols		= array();
		$class		= $class ? " class=\"".$class."\"" : "";
		$colspan	= $colspan ? " colspan=\"".$colspan."\"" : "";
		foreach( $heads as $head )
			$cols[]	= "<th".$class.$colspan.">".$head."</th>";
		$code	= "<tr>".implode( "", $cols )."</tr>";
		return $code;
	}

	/**
	 *	Build unordered List from List Items.
	 *	@access		public
	 *	@static
	 *	@param		string		$content			Content of List Item
	 *	@param		int			$level			Level of Indenting
	 *	@param		array		$attributes		Array of HTML Attributes
	 *	@return		string
	 */
	public static function unorderedList( $items, $level = 0, $attributes = array() )
	{
		$depth1		= 2 * abs( (int) $level );
		$depth2		= $level ? 2 * abs( (int) $level - 1 ) + 1 : 0;
		$indent1	= str_repeat( "  ", $depth1 );
		$indent2	= str_repeat( "  ", $depth2 );
		$content	= "\n".implode( "\n", $items )."\n".$indent1;
		$tag		= UI_HTML_Tag::create( "ul", $content, $attributes );
		$code		= $indent1.$tag."\n".$indent2;
		return $code;
	}
}
?>
