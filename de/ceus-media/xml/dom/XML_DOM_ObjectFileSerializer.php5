<?php
import( 'de.ceus-media.xml.dom.XML_DOM_ObjectSerializer' );
import( 'de.ceus-media.xml.dom.XML_DOM_FileWriter' );
/**
 *	Serializer for Data Object into a XML File.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		XML_DOM_ObjectSerializer
 *	@uses			XML_DOM_FileWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.1
 */
/**
 *	Serializer for Data Object into a XML File.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		XML_DOM_ObjectSerializer
 *	@uses			XML_DOM_FileWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.1
 */
class XML_DOM_ObjectFileSerializer extends XML_DOM_ObjectSerializer
{
	/**
	 *	Constructor.
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Writes XML String from an Object to a File.
	 *	@param		mixed		object	Object to serialize
	 *	@param		string		filename	XML File to write to
	 *	@return		void
	 */
	function serialize( $object, $filename )
	{
		$root	= $this->serializeToTree( $object );
		$writer	= new XML_DOM_FileWriter( $filename );
		$serial	= $writer->write( $root );
	}
}
?>