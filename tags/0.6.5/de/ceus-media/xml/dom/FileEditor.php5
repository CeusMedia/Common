<?php
/**
 *	Editor for XML Files.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		xml.dom
 *	@uses			XML_DOM_FileReader
 *	@uses			XML_DOM_FileWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			10.05.2008
 *	@version		0.6
 */
import( 'de.ceus-media.xml.dom.FileReader' );
import( 'de.ceus-media.xml.dom.FileWriter' );
/**
 *	Editor for XML Files.
 *	Every Method is working with a Node Path, which is a bit like XPath but without Attribute Selectors.
 *  You can address Nodes with same Node Names with an Index, eg. "node[2]]. Please remember that this Index will start with 0.
 *	To focus on the second Node named 'test' within a Node named 'parent' the Node Path would be "mother/test[1]"
 *	@package		xml.dom
 *	@uses			XML_DOM_FileReader
 *	@uses			XML_DOM_FileWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			10.05.2008
 *	@version		0.6
 */
class XML_DOM_FileEditor
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of XML File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
		$this->xmlTree	= XML_DOM_FileReader::load( $fileName );
	}
	
	/**
	 *	Adds a new Node Attribute to an existing Node.
	 *	@access		public
	 *	@param		string		$nodePath		Path to existring Node in XML Tree
	 *	@param		string		$name			Name of new Node
	 *	@param		string		$content		Cotnent of new Node
	 *	@param		array		$attributes		Array of Attribute of new Content
	 *	@return		bool
	 */
	public function addNode( $nodePath, $name, $content = "", $attributes = array() )
	{
		$branch	= $this->getNode( $nodePath );
		$node	= new XML_DOM_Node( $name, $content, $attributes );
		$branch->addChild( $node );
		return (bool) $this->write();
	}
	
	/**
	 *	Modifies a Node Attribute by its Path and Attribute Key.
	 *	@access		public
	 *	@param		string		$nodePath		Path to Node in XML Tree
	 *	@param		string		$key			Attribute Key
	 *	@return		bool
	 */
	public function editNodeAttribute( $nodePath, $key, $value )
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
	public function editNodeContent( $nodePath, $content )
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
	 *	@return		bool
	 */
	protected function getNode( $nodePath )
	{
		$pathNodes	= explode( "/", $nodePath );
		$xmlNode	=& $this->xmlTree;
		while( $pathNodes )
		{
			$pathNode	= trim( array_shift( $pathNodes ) );
			$matches	= array();
			if( preg_match_all( "@^(.*)\[([0-9]+)\]$@", $pathNode, $matches ) )
			{
				$pathNode	= $matches[1][0];
				$itemNumber	= $matches[2][0];
				$nodes		= $xmlNode->getChildren( $pathNode );
				if( !isset( $nodes[$itemNumber] ) )
					throw new InvalidArgumentException( 'Node not existing.' );
				$xmlNode	=& $nodes[$itemNumber];
				continue;
			}
			$xmlNode	=& $xmlNode->getChild( $pathNode );
			continue;
		}
		return $xmlNode;
	}
	
	/**
	 *	Removes a Node by its Path.
	 *	@access		public
	 *	@param		string		$nodePath		Path to Node in XML Tree
	 *	@return		bool
	 */
	public function removeNode( $nodePath )
	{
		$pathNodes	= explode( "/", $nodePath );
		$nodeName	= array_pop( $pathNodes );
		$nodePath	= implode( "/", $pathNodes );
		$nodeNumber	= 0;
		$branch		= $this->getNode( $nodePath );
		if( preg_match_all( "@^(.*)\[([0-9]+)\]$@", $nodeName, $matches ) )
		{
			$nodeName	= $matches[1][0];
			$nodeNumber	= $matches[2][0];
		}
		$nodes		=& $branch->getChildren();
		$index		= -1;
		for( $i=0; $i<count( $nodes ); $i++ )
		{
			if( !$nodeName || $nodes[$i]->getNodeName() == $nodeName )
			{
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
	public function removeNodeAttribute( $nodePath, $key )
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
	protected function write()
	{
		return XML_DOM_FileWriter::save( $this->fileName, $this->xmlTree );
	}
}
?>