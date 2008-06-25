<?php
import( 'de.ceus-media.xml.dom.SyntaxValidator' );
/**
 *	Formats a XML String or recodes it to another Character Set.
 *	@package		xml.dom
 *	@uses			XML_DOM_SyntaxValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.05.2008
 *	@version		0.6
 */
/**
 *	Formats untidy XML or recodes to another Character Set.
 *	@package		xml.dom
 *	@uses			XML_DOM_SyntaxValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.05.2008
 *	@version		0.6
 *	@todo			Unit Test
 */
class XML_DOM_Formater
{
	/**
	 *	Formats a XML String with Line Breaks and Indention and returns it.
	 *	@access		public
	 *	@param		string		$xml		XML String to format
	 *	@return		string
	 */
	public static function format( $xml  )
	{
		$validator	= new XML_DOM_SyntaxValidator();
		if( !$validator->validate( $xml ) )
			throw new InvalidArgumentException( 'XML String is not valid XML.' ); 

		$document	= DOMDocument::loadXml( $xml );
		$document->formatOutput = TRUE;
		return $document->saveXml();
	}

	/**
	 *	Recodes a XML String to another Character Set.
	 *	@access		public
	 *	@param		string		$xml		XML String to format
	 *	@param		string		$encodeTo	Character Set to encode to
	 *	@see		http://www.iana.org/assignments/character-sets
	 *	@return		string
	 */
	public static function recode( $xml, $encodeTo = "UTF-8" )
	{
		$validator	= new XML_DOM_SyntaxValidator();
		if( !$validator->validate( $xml ) )
			throw new InvalidArgumentException( 'XML String is not valid XML.' ); 

		$encodeTo	= strtoupper( $encodeTo );
		$document	= DOMDocument::loadXml( $xml );
		$encoding	= strtoupper( $document->actualEncoding );
		remark( "Encoding: ".$encoding );
		if( $encoding == $encodeTo )
			return $xml;
		
		$pattern		= '@<\?(.*) encoding=(\'|")'.$encoding.'(\'|")(.*)\?>@i';
		$replacement	= '<?\\1 encoding="'.$encodeTo.'"\\4?>';
		$xml	= iconv( $encoding, $encodeTo, $xml );
		$xml	= preg_replace( $pattern, $replacement, $xml );
		return $xml;
	}
}
?>