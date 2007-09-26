<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.xml.dom.XML_DOM_Parser' );
/**
 *	Reader for RSS Feeds. RSS versions 0.91, 0.92, 1.0 and 2.0 are supported.
 *	@package	xml
 *	@subpackage	rss
 *	@extends	OptionObject
 *	@uses		XML_DOM_Parser
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		19.07.2005
 *	@version		0.4
 */
/**
 *	Reader for RSS Feeds. RSS versions 0.91, 0.92, 1.0 and 2.0 are supported.
 *	@package	xml
 *	@subpackage	rss
 *	@extends	OptionObject
 *	@uses		XML_DOM_Parser
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		19.07.2005
 *	@version		0.4
 */
class RSS_DOM_Parser extends OptionObject
{
	/**	@var	array			_items		RSS Feed items */
	var $_items = array();
	/**	@var	bool				_loaded		State of reading of RSS Feed */
	var $_loaded	= false;
	/**	@var	bool				_parsed		State of parsing of RSS Feed */
	var $_parsed	= false;
	/**	@var	XML_DOM_Node	_tree		Loaded XML Tree from RSS Feed */
	var $_tree;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	xml		RSS Feed XML string URL to parse
	 *	@param		bool		abort		Flag: break on Errors showing Messages
	 *	@param		bool		verbose		Flag: show Warnings
	 *	@return		void
	 */
	public function __construct( $xml = false, $abort = true, $verbose = true )
	{
		parent::__construct();
		$this->_items		= array();
		$this->_parser	= new XML_DOM_Parser();
		$this->_parsed	= false;
		if( $xml )
			$this->loadXML( $xml, $abort, $verbose );
	}

	/**
	 *	Reads RSS Feed XML String and builds tree of XML_DOM_Nodes.
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
			$this->_items		= array();
			$this->clearOptions();
			$this->_loaded	= true;
			return true;
		}
		if( $verbose )
			trigger_error( "RSS_DOM_Parser[loadXML]: RSS Document has not been loaded", E_USER_WARNING );
		return false;
	}
	
	/**
	 *	Parses loaded XML String and saves RSS Feed options and RSS Feed items.
	 *	@access		public
	 *	@return		void
	 */
	function parse()
	{
		if( $this->_loaded )
		{
			foreach( $this->_parser->getOptions() as $key => $value )
				$this->setOption( "xml_".$key, $value );
			if( $version = $this->_tree->getAttribute( "version" ) )
				$this->setOption( "rss_version", $version );
			if( $this->_tree->getNodeName() == "rss" )
				$this->_parseRSS();
			else if( strtolower( $this->_tree->getNodeName() ) == "rdf" )
				$this->_parseRDF();
			$this->_parsed	= true;
			return true;
		}
		trigger_error( "RSS_DOM_Parser[parse]: RSS Document has not been loaded yet", E_USER_WARNING );
		return false;
	}

	/**
	 *	Returns an array of all RSS Feed items.
	 *	@access		public
	 *	@return		array
	 */
	function getItems()
	{
		if( $this->_parsed )
			return $this->_items;
		else
			trigger_error( "RSS_DOM_Parser[getItems]: RSS Document has not been parsed yet.", E_USER_WARNING );
	}
	
	/**
	 *	Returns an array of all RSS Feed options.
	 *	@access		public
	 *	@return		array
	 */
	function getOptions()
	{
		if( $this->_parsed )
			return $this->_options;
		else
			trigger_error( "RSS_DOM_Parser[getOptions]: RSS Document has not been parsed yet.", E_USER_WARNING );
	}

	/**
	 *	Returns an array of all RSS Feed options.
	 *	@access		public
	 *	@return		array
	 */
	function getOption( $key)
	{
		if( $this->_parsed )
		{
			if( isset( $this->_options[$key] ) )
				return $this->_options[$key];
			return false;
		}
		else
			trigger_error( "RSS_DOM_Parser[getOption]: RSS Document has not been parsed yet.", E_USER_WARNING );
	}

	//  --  PRIVATE METHODS  --  //
	function _parseRDF()
	{
		$children = $this->_tree->getChildren();
		foreach( $children as $nr => $child )
		{
			$childname = $child->getNodeName();
			switch( $childname )
			{
				case 'channel':
					foreach( $child->getChildren() as $node )
					{
						$nodename = $node->getNodeName();
						$content	= $node->getContent();
						switch( $nodename )
						{
							case 'image':
								$a = array();
								foreach( $child->getChildren() as $node )
									$a[$node->getNodeName()]	= $node->getContent();
								$this->setOption( 'image', $a );
								break;
							default:
								if( $nodename == "pubDate" && $content )
									$this->setOption( 'timestamp', $this->_getDate( $content ) );
								else if( $nodename == "date" && $content )
									$this->setOption( 'timestamp', $this->_getDate( $this->_convertDcDateToPubDate( $content ) ) );
								$this->setOption( $nodename, $content );
								break;
						}
					}
					break;
				case 'item':
					$a = array();
					foreach( $child->getChildren() as $node )
					{
						$nodename = $node->getNodeName();
						$content	= $node->getContent();
						if( $nodename == "pubDate" && $content )
							$a['timestamp'] = $this->_getDate( $content );
						else if( $nodename == "date" && $content )
							$a['timestamp'] = $this->_getDate( $this->_convertDcDateToPubDate( $content ) );
						else
							$a[$nodename] = $content;
					}
					$this->_items[] = $a;
					break;
			}
		}
	}

	function _parseRSS()
	{
		foreach( $this->_tree->getChildren() as $feed )
		{
			$children = $feed->getChildren();
			foreach( $children as $nr => $child )
			{
				$childname = $child->getNodeName();
				switch( $childname )
				{
					case 'item':
						$a = array();
						foreach( $child->getChildren() as $node )
						{
							$nodename = $node->getNodeName();
							$content	= $node->getContent();
							if( $nodename == "pubDate" && $content )
								$a['timestamp'] = strtotime( $content );
							else if( $nodename == "date" && $content )
								$a['timestamp'] = $this->_getDate( $this->_convertDcDateToPubDate( $content ) );
							else
								$a[$nodename] = $content;
						}
						$this->_items[] = $a;
						break;
					case 'image':
						$a = array();
						foreach( $child->getChildren() as $node )
							$a[$node->getNodeName()]	= $node->getContent();
						$this->setOption( 'image', $a );
						break;
					default:
						$content	= $child->getContent();
						if( $childname == "pubDate" && $content )
							$this->setOption( 'timestamp', $this->_getDate( $content ) );
						else if( $childname == "date" && $content )
							$this->setOption( 'timestamp', $this->_getDate( $this->_convertDcDateToPubDate( $content ) ) );
						$this->setOption( $childname, $content );
						break;
				}
			}
		}
	}
	
	/**
	 *	Returns GNU Date from DC Date.
	 *	@access		private
	 *	@param		string
	 *	@return		string
	 */
	function _convertDcDateToPubDate( $date )
	{
		$pattern	= "/(.*)(\+|-)([0-9]{2}):([0-9]{2})/";
		$replace	= "\\1\\2\\3\\4";
		if( preg_match( $pattern, $date ) )
			$date	= preg_replace( $pattern, $replace, $date );
		$pattern	= "/(.+)T([0-9]+)(.+)/";
		$replace	= "\\1 \\2\\3";
		if( preg_match( $pattern, $date ) )
			$date	= preg_replace( $pattern, $replace, $date );
		return $date;
	}

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
}
?>