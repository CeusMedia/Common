<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	...
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@todo			Code Doc
 */
class UI_HTML_FormElements
{
	/**
	 *	Erstellt HTML-Code einer CheckBox mit Label.
	 *	@access		public
	 *	@static
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
	 *	@static
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
	 *	Erzeugt HTML-Code eines RadioLabels.
	 *	@access		public
	 *	@static
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
}
?>