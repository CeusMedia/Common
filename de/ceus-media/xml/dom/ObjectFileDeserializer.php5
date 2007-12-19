<?php
import( 'de.ceus-media.xml.dom.ObjectDeserializer' );
import( 'de.ceus-media.file.Reader' );
/**
 *	Deserializer for a XML File into a Data Object.
 *	@package		xml.dom
 *	@extends		XML_DOM_ObjectDeserializer
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.6
 */
/**
 *	Deserializer for a XML File into a Data Object.
 *	@package		xml.dom
 *	@extends		XML_DOM_ObjectDeserializer
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.6
 */
class XML_DOM_ObjectFileDeserializer extends XML_DOM_ObjectDeserializer
{
	/**
	 *	Builds Object from XML File of a serialized Object.
	 *	@param		string		$fileName		XML File of a serialized Object
	 *	@return		Object
	 */
	public static function deserialize( $fileName )
	{
		$reader	= new File_Reader( $fileName );
		$xml	= $reader->readString( $fileName );
		return parent::deserialize( $xml ); 
	}
}
?>