<?php
import( 'de.ceus-media.xml.dom.XML_DOM_ObjectDeserializer' );
import( 'de.ceus-media.xml.dom.XML_DOM_FileReader' );
/**
 *	Deserializer for a XML File into a Data Object.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		XML_DOM_ObjectDeserializer
 *	@uses			XML_DOM_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.1
 */
/**
 *	Deserializer for a XML File into a Data Object.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		XML_DOM_ObjectDeserializer
 *	@uses			XML_DOM_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.1
 */
class XML_DOM_ObjectFileDeserializer extends XML_DOM_ObjectDeserializer
{
	/**
	 *	Constructor.
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Builds Object from XML File of a serialized Object.
	 *	@param		string		filename		XML File of a serialized Object
	 *	@param		bool			abort		Flag: break on Errors showing Messages
	 *	@param		bool			verbose		Flag: show Warnings
	 *	@return		mixed
	 */
	function deserialize( $filename, $abort = true, $verbose = true )
	{
		$xfr		= new XML_DOM_FileReader();
		if( $xfr->loadFile( $filename, $abort, $verbose ) )
		{
			$tree	= $xfr->parse();
			$class	= $tree->getAttribute( 'class' );
			$object	= new $class();
			$this->_deserializeVarsRec( $tree->getChildren(), $object );
			return $object;
		}
		return false;
	}
}
?>