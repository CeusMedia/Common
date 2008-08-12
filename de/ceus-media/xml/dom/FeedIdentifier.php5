<?php
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.xml.dom.Parser' );
/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *	@package		xml.dom
 *	@uses			File
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.01.2006
 *	@version		0.6
 */
/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *	@package		xml.dom
 *	@uses			File
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.01.2006
 *	@version		0.6
 */
class XML_DOM_FeedIdentifier
{
	/**	@var	string		$type			Type of Feed */
	protected $type	= "";
	/**	@var	string		$version		Version of Feed Type */
	protected $version	= "";
	
	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		string
	 */
	public function getType()
	{
		return $this->type;
	}
	
	/**
	 *	Returns identified Version of Feed Type.
	 *	@access		public
	 *	@return		string
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 *	Identifies Feed from XML.
	 *	@access		public
	 *	@param		string		$xml		XML of Feed
	 *	@return		string
	 */
	public function identify( $xml )
	{
		$parser	= new XML_DOM_Parser();
		$tree	= $parser->parse( $xml );
		return $this->identifyFromTree( $tree );
	}

	/**
	 *	Identifies Feed from a File.
	 *	@access		public
	 *	@param		string		$fileName	XML File of Feed
	 *	@return		string
	 */
	public function identifyFromFile( $fileName )
	{
		$file	= new File_Reader( $fileName );
		$xml	= $file->readString();
		return $this->identify( $xml );
	}

	/**
	 *	Identifies Feed from XML Tree.
	 *	@access		public
	 *	@param		XML_DOM_Node	$tree	XML Tree of Feed
	 *	@return		string
	 */
	public function identifyFromTree( $tree )
	{
		$this->type		= "";
		$this->version	= "";
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
			$this->type		= $type;
			$this->version	= $version;
			return $type."/".$version;
		}
		return false;
	}
}
?>