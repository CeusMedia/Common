<?php
import( 'de.ceus-media.ui.html.Elements' );
/**
 *	Builds HTML Components for AJAX Applications.
 *	@package		ui.html
 *	@extends		UI_HTML_Elements
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Builds HTML Components for AJAX Applications.
 *	@package		ui.html
 *	@extends		UI_HTML_Elements
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 *	@todo			Finish Implementation
 *	@todo			Code Documentation
 */
class UI_HTML_AjaxElements extends UI_HTML_Elements
{
//	public function __construct( ) {}

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
	public function Button( $name, $value, $class = 'but', $confirm = false, $disabled = false)
	{
		$ins_class	= $class ? " class=\"".$class."\"" : "";
		$ins_disabled	= $disabled ? " disabled=\"disabled\"" : "";
		$ins_confirm	= $confirm ? " if( !confirm('".$confirm."') ) return false;" : "";
		$code		= "<button onClick=\"".$ins_confirm."submitFormular(this.form.action+'&".$name."=true',this.form);\"".$ins_class.$ins_confirm.$ins_disabled.">".$value."</button>";
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code eines post-Formulars.
	 *	@access		public
	 *	@param		string		$id				ID des Formulars
	 *	@param		string		$action			URL der Aktion
	 *	@param		string		$target			Zielframe der Aktion
	 *	@param		string		$enctype			Encryption-Typ, für Uploads
	 *	@param		string		$on_submit		JavaScript vor dem Versenden des Formulars
	 *	@return		string
	 */
	public function Form( $id = "", $action = '', $target = false, $enctype = false, $on_submit = "" )
	{
		$ins_id		= " id=\"form_".$id."\"";
		$ins_action	= " action=\"".$action."\"";
		$ins_submit	= " onSubmit=\"return false;\"";
		$code = "<form".$ins_id.$ins_action.$ins_enctype.$ins_submit.">";
		return $code;
	}

	/**
	 *	Erzeugt HTML-Code eines Links.
	 *	@access		public
	 *	@param		string		$url				URL des Links
	 *	@param		string		$name			Name des Links
	 *	@param		string		$class			CSS-Klasse des Links
	 *	@param		string		$target			Zielframe des Links
	 *	@param		string		$confirm			Bestätigungstext des Links
	 *	@param		int			$tabindex		Tabulatur-Index
	 *	@return		string
	 */
	public function Link( $url = "", $name, $class = false, $target = false, $confirm = false, $tabindex = false )
	{
		if( $target || substr_count( $url, "://" ) )
			return UI_HTML_Elements::Link( $url, $name, $class, $target, $confirm, $tabindex );
		else
		{
			$ins_class	= $class ? " class=\"".$class."\"" : "";
			$ins_confirm	= $confirm ? "if( !confirm('".$confirm."') ) return false;" : "";
			$ins_tabindex = $tabindex ? " tabindex=\"".$tabindex."\"" : "";
			$code = "<a href=\"#\" onMouseDown=\"".$ins_confirm."loadPage('".$url."');\"".$ins_class.$ins_tabindex.">".$name."</a>";
			return $code;
		}
	}
}
?>