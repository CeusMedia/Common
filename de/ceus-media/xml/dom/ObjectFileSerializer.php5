<?php
import( 'de.ceus-media.xml.dom.ObjectSerializer' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Serializer for Data Object into a XML File.
 *	@package		xml.dom
 *	@extends		XML_DOM_ObjectSerializer
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.6
 */
/**
 *	Serializer for Data Object into a XML File.
 *	@package		xml.dom
 *	@extends		XML_DOM_ObjectSerializer
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.6
 */
class XML_DOM_ObjectFileSerializer extends XML_DOM_ObjectSerializer
{
	/**
	 *	Writes XML String from an Object to a File.
	 *	@param		mixed		$object			Object to serialize
	 *	@param		string		$fileName		XML File to write to
	 *	@return		void
	 */
	public static function serialize( $object, $fileName )
	{
		$serial	= parent::serialize( $object );
		$file	= new File_Writer( $fileName );
		return $file->writeString( $serial );
	}
}
?>