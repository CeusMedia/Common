<?php
/**
 *	Parses XML Data to Array.
 *	@package		xml
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Parses XML Data to Array.
 *	@package		xml
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class XML_Parser
{
	/**	@var	resource		_xml		Resource of XML Parser */
	var $_xml;
	/**	@var	array		_last	Last node while parsing */
	var $_last	= array();
	/**	@var	array		_array	Parsed XML Data as array */
	var $_array	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	xml		XML Data
	 *	@return		void
	 */
	public function __construct( $xml )
	{
		$this->_xml	= xml_parser_create();
		xml_set_object ($this->_xml, $this);
		xml_set_element_handler ($this->_xml, '_tag_open', '_tag_close');
		xml_set_character_data_handler ($this->_xml, '_cdata');
		$this->_last	= array (&$this->_array);
		if (!xml_parse ($this->_xml, $xml))
		{
			$msg	= "XML error: %s at line %d";
			$error	= xml_error_string(xml_get_error_code($this->_xml));
			$line		= xml_get_current_line_number($this->_xml);
			trigger_error( sprintf($msg, $error, $line), E_USER_ERROR );
		}
		xml_parser_free($this->_xml);
	}
	
	/**
	 *	Returns a representative array.
	 *	@access		public
	 *	@return		array
	 */
	function toArray ()
	{
		return $this->_array;
	}

	/**
	 *	Callback function for opening tags.
	 *	@access		private
	 *	@param		resource	parser		Resource of XML Parser
	 *	@param		string	tag			Name of parsed tag 
	 *	@param		array	attributes	Array of parsed attributes
	 *	@return		void
	 */
	function _tag_open($parser, $tag, $attributes)
	{
		$c = count($this->_last) - 1;
		$this->_last[$c][] = array(
			"tag"		=> $tag,
			"attributes"	=> $attributes,
			"content"		=> '',
			"children"		=> array()
			);
		$this->_last[] = &$this->_last[$c][count($this->_last[$c]) - 1]['children'];
	}

	/**
	 *	Callback function for closing tags.
	 *	@access		private
	 *	@param		resource	parser		Resource of XML Parser
	 *	@param		string	tag			Name of parsed tag
	 *	@return		void
	 */
	function _tag_close($parser, $tag)
	{
		array_pop($this->_last);
	}

	/**
	 *	Callback function for character data.
	 *	@access		private
	 *	@param		resource	parser		Resource of XML Parser
	 *	@param		string	cdata		Data of parsed tag
	 *	@return		void
	 */
	function _cdata($parser, $cdata)
	{
		if (strlen(ltrim($cdata)) > 0)
		{
			$p = count($this->_last) - 2;
			$this->_last[$p][count($this->_last[$p]) - 1]['content'] .= str_replace('\n', "\n", trim($cdata));
		}
	}
}
?>