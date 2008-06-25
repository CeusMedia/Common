<?php
import( 'de.ceus-media.file.Reader' );
/**
 *	Validates XML.
 *	@package		xml
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Validates XML.
 *	@package		xml
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 *	@todo			Unit Test
 */
class XML_Validator
{
	/**	@var		array		$error		Array of Error Information */
	protected $error	= array();

	/**
	 *	Returns last error line.
	 *	@access		public
	 *	@return		int
	 */
	public function getErrorLine()
	{
		if( $this->error )
			return $this->error['line'];
		return -1;
	}
	
	/**
	 *	Returns last error message.
	 *	@access		public
	 *	@return		string
	 */
	public function getErrorMessage()
	{
		if( $this->error )
			return $this->error['message'];
		return "";
	}

	/**
	 *	Validates XML File.
	 *	@access		public
	 *	@return		bool
	 */
	public function validateFile( $fileName )
	{
		$xml = File_Reader::load( $fileName );
		return $this->validateXML( $xml );
	}

	/**
	 *	Validates XML URL.
	 *	@access		public
	 *	@return		bool
	 */
	public function validateUrl( $url)
	{
		$xml	= Net_Reader::readUrl( $url );
		return $this->validateXML( $xml );
	}

	/**
	 *	Validates XML File.
	 *	@access		public
	 *	@param		string		$xml		XML String to validate
	 *	@return		bool
	 */
	public function validate( $xml )
	{
		$parser	= xml_parser_create();
		$dummy	= create_function( '', '' );
		xml_set_element_handler( $parser, $dummy, $dummy );
		xml_set_character_data_handler( $parser, $dummy );
		if( !xml_parse( $parser, $xml ) )
		{
			$msg	= "%s at line %d";
			$error	= xml_error_string( xml_get_error_code( $parser ) );
			$line	= xml_get_current_line_number( $parser );
			$this->error['message']	= sprintf( $msg, $error, $line );
			$this->error['line']	= $line;
			$this->error['xml']		= $xml;
			xml_parser_free( $parser );
			return FALSE;
		}
		xml_parser_free( $parser );
		return TRUE;
	}
}
?>