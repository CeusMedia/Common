<?php
import( 'de.ceus-media.file.File' );
import( 'de.ceus-media.xml.dom.XML_DOM_Parser' );
/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *	@package		xml
 *	@subpackage		dom
 *	@uses			File
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.01.2006
 *	@version		0.1
 */
/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *	@package		xml
 *	@subpackage		dom
 *	@uses			File
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.01.2006
 *	@version		0.1
 */
class XML_DOM_FeedIdentifier
{
	/**	@var	string	_type		Type of Feed */
	var $_type	= "";
	/**	@var	string	_version		Version of Feed Type */
	var $_version	= "";
	
	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		string
	 */
	function getType()
	{
		return $this->_type;
	}
	
	/**
	 *	Returns identified Version of Feed Type.
	 *	@access		public
	 *	@return		string
	 */
	function getVersion()
	{
		return $this->_version;
	}

	/**
	 *	Identifies Feed from XML.
	 *	@access		public
	 *	@param		string	xml		XML of Feed
	 *	@return		string
	 */
	function identify( $xml )
	{
		$xdp	= new XML_DOM_Parser( $xml );
		$tree	= $xdp->parse();
		return $this->identifyFromTree( $tree );
	}

	/**
	 *	Identifies Feed from a File.
	 *	@access		public
	 *	@param		string	filename		XML File of Feed
	 *	@return		string
	 */
	function identifyFromFile( $filename )
	{
		$file	= new File( $filename );
		$xml	= $file->readString();
		return $this->identify( $xml );
	}

	/**
	 *	Identifies Feed from XML Tree.
	 *	@access		public
	 *	@param		XML_DOM_Node	tree		XML Tree of Feed
	 *	@return		string
	 */
	function identifyFromTree( $tree )
	{
		$this->_type		= "";
		$this->_version	= "";
		$nodename	= strtolower( $tree->getNodeName() );
		switch( $nodename )
		{
			case 'feed':
				$type	= "ATOM";
				$version	= $tree->getAttribute( 'version' );
				break;
			case 'rss':
				$type	= "RSS";
				$version	= $tree->getAttribute( 'version' );
				break;
			case 'rdf:rdf':
				$type	= "RSS";
				$version	= "1.0";
				break;
		}
		if( $type && $version )
		{
			$this->_type		= $type;
			$this->_version	= $version;
			return $type."/".$version;
		}
		return false;
	}
}
?>