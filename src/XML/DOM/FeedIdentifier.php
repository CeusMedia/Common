<?php
/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			24.01.2006
 */

namespace CeusMedia\Common\XML\DOM;

/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@uses			File
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			24.01.2006
 */
class FeedIdentifier
{
	/**	@var	string		$type			Type of Feed */
	protected $type		= "";
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
		$parser	= new Parser();
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
		$file	= new \FS_File_Reader( $fileName );
		$xml	= $file->readString();
		return $this->identify( $xml );
	}

	/**
	 *	Identifies Feed from XML Tree.
	 *	@access		public
	 *	@param		Node		$tree	XML Tree of Feed
	 *	@return		string
	 */
	public function identifyFromTree( $tree )
	{
		$this->type		= "";
		$this->version	= "";
		$nodename		= strtolower( $tree->getNodeName() );
		$type			= '';
		$version		= '';
		switch( $nodename )
		{
			case 'feed':
				$type		= "ATOM";
				$version	= $tree->getAttribute( 'version' );
				break;
			case 'rss':
				$type		= "RSS";
				$version	= $tree->getAttribute( 'version' );
				break;
			case 'rdf:rdf':
				$type		= "RSS";
				$version	= "1.0";
				break;
		}
		if( $type && $version )
		{
			$this->type		= $type;
			$this->version	= $version;
			return $type."/".$version;
		}
		return FALSE;
	}
}
