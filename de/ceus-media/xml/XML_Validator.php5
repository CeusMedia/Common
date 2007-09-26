<?php
/**
 *	Validates XML.
 *	@package		xml
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Validates XML.
 *	@package		xml
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class XML_Validator
{
	/**	@var	resource		_xml			Resource of XML Parser */
	var $_xml;
	/**	@var	array		_last		Last node while parsing */
	var $_last	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Returns last error message.
	 *	@access		public
	 *	@return		string
	 */
	function getErrorMessage()
	{
		return $this->_last['error_message'];
	}
	
	/**
	 *	Returns last error line.
	 *	@access		public
	 *	@return		int
	 */
	function getErrorLine()
	{
		return $this->_last['error_line'];
	}
	
	/**
	 *	Returns XML as HTML List with marked error.
	 *	@access		public
	 *	@return		string
	 */
	function getXML()
	{
		$code = "";
		if( $this->_last['xml'] )
		{	
			$lines = explode( "\n", htmlentities( $this->_last['xml'] ) );
			$code = "<ul style='list-style-type: decimal'>";
			for( $i=0; $i<count($lines); $i++ )
				if( $line = trim( $lines[$i]) )
					$code .= ( $i == $this->_last['error_line'] - 1 ) ? "<li><b>".$line."</b></li>" : "<li>".$line."</li>";
			$code .= "</ul>";
		}
		return $code;
	}

	/**
	 *	Validates XML File.
	 *	@access		public
	 *	@return		bool
	 */
	function validateFile( $filename )
	{
		$file = new File( $filename );
		if( $xml = $file->readString() )
			return $this->validateXML( $xml );
	}

	/**
	 *	Validates XML URL.
	 *	@access		public
	 *	@return		bool
	 */
	function validateUrl( $url)
	{
		if( $xml = implode( "", @file( $url ) ) )
			return $this->validateXML( $xml );
	}

	/**
	 *	Validates XML File.
	 *	@access		public
	 *	@return		bool
	 */
	function validateXML( $xml )
	{
		$this->_xml	= xml_parser_create();
		xml_set_object ($this->_xml, $this);
		xml_set_element_handler ($this->_xml, '_tag_open', '_tag_close');
		xml_set_character_data_handler ($this->_xml, '_cdata');
		if (!xml_parse ($this->_xml, $xml))
		{
			$msg	= "XML error: %s at line %d";
			$error	= xml_error_string(xml_get_error_code($this->_xml));
			$line		= xml_get_current_line_number($this->_xml);
			$this->_last['error_message']	= "<br>".sprintf( $msg, $error, $line );
			$this->_last['error_line']		= $line;
			$this->_last['xml']			= $xml;
			xml_parser_free($this->_xml);
			return false;
		}
		xml_parser_free($this->_xml);
		return true;
	}

	/**
	 *	Callback function for character data.
	 *	@access		private
	 *	@param		resource		parser		Resource of XML Parser
	 *	@param		string		cdata		Data of parsed tag
	 *	@return		void
	 */
	function _cdata($parser, $cdata)
	{
	}

	/**
	 *	Callback function for opening tags.
	 *	@access		private
	 *	@param		resource		parser		Resource of XML Parser
	 *	@param		string		tag			Name of parsed tag 
	 *	@param		array		attributes	Array of parsed attributes
	 *	@return		void
	 */
	function _tag_open($parser, $tag, $attributes)
	{
	}

	/**
	 *	Callback function for closing tags.
	 *	@access		private
	 *	@param		resource		parser		Resource of XML Parser
	 *	@param		string		tag			Name of parsed tag
	 *	@return		void
	 */
	function _tag_close($parser, $tag)
	{
	}
}
?>