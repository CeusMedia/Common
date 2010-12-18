<?php
/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		XML.DOM
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			24.01.2006
 *	@version		$Id$
 */
/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *	@category		cmClasses
 *	@package		XML.DOM
 *	@uses			File
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			24.01.2006
 *	@version		$Id$
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