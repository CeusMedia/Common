<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.xml.dom.Parser' );
/**
 *	Reader for ATOM Feeds.
 *	@package		xml
 *	@subpackage		atom
 *	@extends		ADT_OptionObject
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.01.2006
 *	@version		0.1
 */
/**
 *	Reader for ATOM Feeds.
 *	@package		xml
 *	@subpackage		atom
 *	@extends		ADT_OptionObject
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.01.2006
 *	@version		0.1
 */
class ATOM_DOM_Parser extends ADT_OptionObject
{
	/**	@var	array			_entries		ATOM Feed entries */
	var $_entries = array();
	/**	@var	bool				_loaded		State of reading of ATOM Feed */
	var $_loaded	= false;
	/**	@var	bool				_parsed		State of parsing of ATOM Feed */
	var $_parsed	= false;
	/**	@var	XML_DOM_Node	_tree		Loaded XML Tree from ATOM Feed */
	var $_tree;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	xml			ATOM Feed XML string URL to parse
	 *	@param		bool		abort		Flag: break on Errors showing Messages
	 *	@param		bool		verbose		Flag: show Warnings
	 *	@return		void
	 */
	public function __construct( $xml = false, $abort = true, $verbose = true )
	{
		$this->_entries	= array();
		$this->_parser	= new XML_DOM_Parser();
		$this->_parsed	= false;
		if( $xml )
			$this->loadXML( $xml, $abort, $verbose );
	}

	/**
	 *	Reads ATOM Feed XML String and builds tree of XML_DOM_Nodes.
	 *	@access		public
	 *	@param		string	xml			ATOM Feed XML string URL to parse
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
			$this->_entries	= array();
			$this->clearOptions();
			$this->_loaded	= true;
			return true;
		}
		if( $verbose )
			trigger_error( "ATOM_DOM_Parser[loadXML]: ATOM Document has not been loaded", E_USER_WARNING );
		return false;
	}

	/**
	 *	Parses loaded XML String and saves ATOM Feed options and ATOM Feed entries.
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
				$this->setOption( "atom_version", $version );

			foreach( $this->_tree->getChildren() as $feed )
			{
				$nodename = $feed->getNodeName();
				switch( $nodename )
				{
					case 'title':
						$content	= $feed->getContent();
						$this->setOption( 'title', $content );
						break;
					case 'id':
						$content	= $feed->getContent();
						$this->setOption( 'id', $content );
						break;
					case 'updated':
						$content	= $feed->getContent();
						$content	= $this->_getDate( $this->_convertDcDateToPubDate ( $content ) );
						$this->setOption( 'updated', $content );
						break;
					case 'modified':
						$content	= $feed->getContent();
						$content	= $this->_getDate( $this->_convertDcDateToPubDate ( $content ) );
						$this->setOption( 'date', $content );
						break;
					case 'link':
						$content	= $feed->getAttribute( 'href' );
						$this->setOption( 'link', $content );
						break;
					case 'tagline':
						$content	= $feed->getContent();
						$this->setOption( 'tagline', $content );
						break;
					case 'entry':
						$children	= $feed->getChildren();
						foreach( $children as $nr => $child )
						{
							$node	= $child->getNodeName();
							$content	= $child->getContent();
							switch( $node )
							{
								case 'author':
									$list	= array();
									$authors	= $child->getChildren();
									foreach( $authors as $author )
										$list[]	= $author->getContent();
									$content	= implode( ",", $list );
									break;
								case 'link':
									$content	= $child->getAttribute( 'href' );
									break;
								case 'updated':
									$content	= $this->_getDate( $this->_convertDcDateToPubDate ( $content ));
									break;
								case 'created':
									$content	= $this->_getDate( $this->_convertDcDateToPubDate ( $content ) );
									break;
								case 'issued':
									$content	= $this->_getDate( $this->_convertDcDateToPubDate ( $content ) );
									break;
								case 'modified':
									$content	= $this->_getDate( $this->_convertDcDateToPubDate ( $content ) );
									break;
								default:
									break;
							}
							$a[$node]	= $content;
						}
						$this->_entries[] = $a;
						break;
				}			
			}
			$this->_parsed	= true;
			return true;
		}
		trigger_error( "ATOM_DOM_Parser[parse]: ATOM Document has not been loaded yet", E_USER_WARNING );
		return false;
	}

	/**
	 *	Returns an array of all ATOM Feed entries.
	 *	@access		public
	 *	@return		array
	 */
	function getEntries()
	{
		if( $this->_parsed )
			return $this->_entries;
		else
			trigger_error( "Document has not been parsed yet.", E_USER_WARNING );
	}
	
	/**
	 *	Returns an array of all ATOM Feed options.
	 *	@access		public
	 *	@return		array
	 */
	function getOptions()
	{
		if( $this->_parsed )
			return $this->_options;
		else
			trigger_error( "Document has not been parsed yet.", E_USER_WARNING );
	}

	/**
	 *	Returns an array of all ATOM Feed options.
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
			trigger_error( "Document has not been parsed yet.", E_USER_WARNING );
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