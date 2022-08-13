<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Editor for XML Files.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	Every Method is working with a Node Path, which is a bit like XPath but without Attribute Selectors.
 *  You can address Nodes with same Node Names with an Index, eg. "node[2]]. Please remember that this Index will start with 0.
 *	To focus on the second Node named 'test' within a Node named 'parent' the Node Path would be "mother/test[1]"
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\DOM;

use InvalidArgumentException;


/**
 *	Editor for XML Files.
 *	Every Method is working with a Node Path, which is a bit like XPath but without Attribute Selectors.
 *  You can address Nodes with same Node Names with an Index, eg. "node[2]]. Please remember that this Index will start with 0.
 *	To focus on the second Node named 'test' within a Node named 'parent' the Node Path would be "mother/test[1]"
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class FileEditor
{
	/** @var		string		$fileName		File Name of XML File */
	protected $fileName;

	/** @var		Node		$xmlTree		... */
	protected $xmlTree;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of XML File
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->fileName	= $fileName;
		$this->xmlTree	= FileReader::load( $fileName );
	}

	/**
	 *	Adds a new Node Attribute to an existing Node.
	 *	@access		public
	 *	@param		string		$nodePath		Path to existing Node in XML Tree
	 *	@param		string		$name			Name of new Node
	 *	@param		string		$content		Content of new Node
	 *	@param		array		$attributes		Array of Attribute of new Content
	 *	@return		bool
	 */
	public function addNode( string $nodePath, string $name, string $content = '', array $attributes = array() ): bool
	{
		$branch	= $this->getNode( $nodePath );
		$node	= new Node( $name, $content, $attributes );
		$branch->addChild( $node );
		return (bool) $this->write();
	}

	/**
	 *	Modifies a Node Attribute by its Path and Attribute Key.
	 *	@access		public
	 *	@param		string		$nodePath		Path to Node in XML Tree
	 *	@param		string		$key			Attribute Key
	 *	@param		mixed		$value			Attribute Value
	 *	@return		bool
	 */
	public function editNodeAttribute( string $nodePath, string $key, $value ): bool
	{
		$node	= $this->getNode( $nodePath );
		if( $node->setAttribute( $key, $value ) )
			return (bool) $this->write();
		return FALSE;
	}

	/**
	 *	Modifies a Node Content by its Path.
	 *	@access		public
	 *	@param		string		$nodePath		Path to Node in XML Tree
	 *	@param		string		$content		Content to set to Node
	 *	@return		bool
	 */
	public function editNodeContent( string $nodePath, string $content ): bool
	{
		$node	= $this->getNode( $nodePath );
		if( $node->setContent( $content ) )
			return (bool) $this->write();
		return FALSE;
	}

	/**
	 *	Returns Node Object for a Node Path.
	 *	@access		public
	 *	@param		string		$nodePath		Path to Node in XML Tree
	 *	@return		Node
	 */
	protected function getNode( string $nodePath ): Node
	{
		$pathNodes	= explode( "/", $nodePath );
		$xmlNode	=& $this->xmlTree;
		while( $pathNodes ){
			$pathNode	= trim( array_shift( $pathNodes ) );
			$matches	= array();
			if( preg_match_all( "@^(.*)\[(\d+)\]$@", $pathNode, $matches ) ){
				$pathNode	= $matches[1][0];
				$itemNumber	= $matches[2][0];
				$nodes		= $xmlNode->getChildren( $pathNode );
				if( !isset( $nodes[$itemNumber] ) )
					throw new InvalidArgumentException( 'Node not existing.' );
				$xmlNode	=& $nodes[$itemNumber];
				continue;
			}
			$xmlNode	=& $xmlNode->getChild( $pathNode );
		}
		return $xmlNode;
	}

	/**
	 *	Removes a Node by its Path.
	 *	@access		public
	 *	@param		string		$nodePath		Path to Node in XML Tree
	 *	@return		bool
	 */
	public function removeNode( string $nodePath ): bool
	{
		$pathNodes	= explode( "/", $nodePath );
		$nodeName	= array_pop( $pathNodes );
		$nodePath	= implode( "/", $pathNodes );
		$nodeNumber	= 0;
		$branch		= $this->getNode( $nodePath );
		if( preg_match_all( "@^(.*)\[(\d+)\]$@", $nodeName, $matches ) ){
			$nodeName	= $matches[1][0];
			$nodeNumber	= $matches[2][0];
		}
		$nodes		=& $branch->getChildren();
		$index		= -1;
		for( $i=0; $i<count( $nodes ); $i++ ){
			if( !$nodeName || $nodes[$i]->getNodeName() == $nodeName ){
				$index++;
				if( $index != $nodeNumber )
					continue;
				unset( $nodes[$i] );
				return (bool) $this->write();
			}
		}
		throw new InvalidArgumentException( 'Node not found.' );
	}

	/**
	 *	Removes a Node Attribute by its Path and Attribute Key.
	 *	@access		public
	 *	@param		string		$nodePath		Path to Node in XML Tree
	 *	@param		string		$key			Attribute Key
	 *	@return		bool
	 */
	public function removeNodeAttribute( string $nodePath, string $key ): bool
	{
		$node	= $this->getNode( $nodePath );
		if( $node->removeAttribute( $key ) )
			return (bool) $this->write();
		return FALSE;
	}

	/**
	 *	Writes changes XML Tree to File and returns Number of written Bytes.
	 *	@access		protected
	 *	@return		int
	 */
	protected function write(): int
	{
		return FileWriter::save( $this->fileName, $this->xmlTree );
	}
}
