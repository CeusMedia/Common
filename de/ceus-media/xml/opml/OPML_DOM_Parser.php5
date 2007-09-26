<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.xml.dom.XML_DOM_Parser' );
/**
 *	Parser for OPML Files.
 *	@package		xml
 *	@subpackage		opml
 *	@uses			OptionObject
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.02.2006
 *	@version		0.1
 */
/**
 *	Parser for OPML Files.
 *	@package		xml
 *	@subpackage		opml
 *	@uses			OptionObject
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.02.2006
 *	@version		0.1
 */
class OPML_DOM_Parser
{
	/**	@var	OptionObject		_headers			Object containing Headers of OPML Document */
	var $_headers;
	/**	@var	bool				_loaded			State of reading of OPML Document */
	var $_loaded	= false;
	/**	@var	array			_option_keys		Array of supported Headers */
	var $_option_keys	= array(
		"title",
		"dateCreated",
		"dateModified",
		"ownerName",
		"ownerEmail",
		"expansionState",
		"vertScrollState",
		"windowTop",
		"windowLeft",
		"windowBottom",
		"windowRight",
		);
	/**	@var	array			_outlines			Array of Outlines */
	var $_outlines = array();
	/**	@var	bool				_parsed			State of parsing of OPML Document */
	var $_parsed	= false;
	/**	@var	XML_DOM_Node	_tree			Loaded XML Tree from OPML Document */
	var $_tree;
		
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	xml			XML string of OPML Document to parse
	 *	@param		bool		abort		Flag: break on Errors showing Messages
	 *	@param		bool		verbose		Flag: show Warnings
	 *	@return		void
	 */
	public function __construct( $xml = false, $abort = true, $verbose = true )
	{
		$this->_headers	= new OptionObject();
		$this->_outlines	= array();
		$this->_parser	= new XML_DOM_Parser();
		$this->_parsed	= false;
		if( $xml )
			$this->loadXML( $xml, $abort, $verbose );
	}

	/**
	 *	Reads  XML String of OPML Document and builds tree of XML_DOM_Nodes.
	 *	@access		public
	 *	@param		string	xml			RSS Feed XML string URL to parse
	 *	@param		bool		abort		Flag: break on Errors showing Messages
	 *	@param		bool		verbose		Flag: show Warnings
	 *	@return		bool
	 */
	function loadXML( $xml, $abort = true, $verbose = true )
	{
		$this->_parsed	= false;
		$this->_loaded	= false;
		if( $this->_parser->loadXML( $xml, $abort ) )
		{
			$this->_tree		= $this->_parser->parse();
			$this->_outlines	= array();
			$this->_headers->clearOptions();
			$this->_loaded	= true;
			return true;
		}
		if( $verbose )
			trigger_error( "OPML_DOM_Parser[loadXML]: OPML Document has not been loaded", E_USER_WARNING );
		return false;
	}
	
	/**
	 *	Parses loaded XML String of OPML Document and Headers and Outlines.
	 *	@access		public
	 *	@return		void
	 */
	function parse()
	{
		if( $this->_loaded )
		{
			foreach( $this->_parser->getOptions() as $key => $value )
				$this->_headers->setOption( "xml_".$key, $value );
			if( $version = $this->_tree->getAttribute( "version" ) )
				$this->_headers->setOption( "opml_version", $version );

			foreach( $this->_tree->getChildren() as $area )
			{
				$areaname	= $area->getNodeName();
				switch( $areaname )
				{
					case "head":
						$children = $area->getChildren();
						foreach( $children as $nr => $child )
						{
							$childname	= $child->getNodeName();
							$content		= $child->getContent();
							switch( $childname )
							{
								case 'dateCreated':
									$content	= $this->_getDate( $content );
									break;
								case 'dateModified':
									$content	= $this->_getDate( $content );
									break;
								default:
									break;
							}
							$this->_headers->setOption( "opml_".$childname, $content );
						}
						break;
					case "body":
						$this->_parseOutlines( $area, $this->_outlines );
						break;
					default:
						break;
				}
			}
			$this->_parsed	= true;
			return true;
		}
		trigger_error( "OPML_DOM_Parser[parse]: OPML Document has not been loaded yet", E_USER_WARNING );
		return false;
	}

	/**
	 *	Returns an array of all Outlines of OPML Document.
	 *	@access		public
	 *	@return		array
	 */
	function getOutlines()
	{
		if( $this->_parsed )
			return $this->_outlines;
		else
			trigger_error( "OPML_DOM_Parser[getOutlines]: OPML Document has not been parsed yet.", E_USER_WARNING );
	}
	
	function getOutlineTree()
	{
		if( $this->_parsed )
		{
			$areas	= $this->_tree->getChildren();
			foreach( $areas as $area )
				if( $area->getNodeName() == "body" )
					return $area;
		}
		else
			trigger_error( "OPML_DOM_Parser[getOutlines]: OPML Document has not been parsed yet.", E_USER_WARNING );
	}
	
	/**
	 *	Returns an array of all Headers of OPML Document.
	 *	@access		public
	 *	@return		array
	 */
	function getOptions()
	{
		if( $this->_parsed )
			return $this->_headers->getOptions();
		else
			trigger_error( "OPML_DOM_Parser[getOptions]: OPML Document has not been parsed yet.", E_USER_WARNING );
	}

	/**
	 *	Return the value of an options of OPML Document.
	 *	@access		public
	 *	@return		array
	 */
	function getOption( $key)
	{
		if( $this->_parsed )
		{
			if( NULL !== $this->_headers->getOption( $key ) )
				return $this->_headers->getOption( $key );
			return false;
		}
		else
			trigger_error( "OPML_DOM_Parser[getOption]: OPML Document has not been parsed yet.", E_USER_WARNING );
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Returns timestamp from GNU Date.
	 *	@access		private
	 *	@param		string
	 *	@return		string
	 */
	function _getDate( $date )
	{
		$timestamp	= strtotime( $date );
		if( $timestamp > 0 )
			return $timestamp;
		return false;
	}

	/**
	 *	Parses Outlines recursive.
	 *	@access		private
	 *	@return		void
	 */
	function _parseOutlines( $node, &$array )
	{
		$outlines = $node->getChildren();
		foreach( $outlines as $outline )
		{
			$data	= array();
			foreach( $outline->getAttributes() as $key => $value )
				$data[$key]	= $value;
			if( $outline->hasChildren() )
				$this->_parseOutlines( $outline, $data['outlines'] );
			else
				$data['outlines']	= array();
			$array[]	= $data;
		}
	}
}
?>