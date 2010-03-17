<?php
/**
 *	Reader for PEAR Package Description Files in XML.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		xml.dom.pear
 *	@uses			File_Reader
 *	@author			Christian Würker
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.10.2008
 *	@version		0.1
 */
import( 'de.ceus-media.file.Reader' );
/**
 *	Reader for PEAR Package Description Files in XML.
 *	@category		cmClasses
 *	@package		xml.dom.pear
 *	@uses			File_Reader
 *	@author			Christian Würker
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.10.2008
 *	@version		0.1
 */
class XML_DOM_PEAR_PackageReader
{
	/**
	 *	Reads Package XML File and returns found Information as Array.
	 *	@access		public
	 *	@param		string		$fileName		Name of Package XML File
	 *	@return		array
	 */
	public function getPackageDataFromXmlFile( $fileName )
	{
		$package	= array(
			'name'			=> NULL,
			'summary'		=> NULL,
			'description'	=> NULL,
			'maintainers'	=> array(),
			'release'		=> array(),
			'changes'		=> array(),
		);

		$xml	= File_Reader::load( $fileName );
		$doc	= new DOMDocument();
		$doc->preserveWhiteSpace	= FALSE;
		$doc->validateOnParse		= !TRUE;
		$doc->loadXml( $xml );
		foreach( $doc->childNodes as $node )
		{
			if( $node->nodeType == 1 )
			{
				$root	= $node;
				break;
			}
		}
		if( !$root )
			throw Exception( 'No root node found.' );
		
		foreach( $root->childNodes as $node )
		{
			$nodeName	= strToLower( $node->nodeName );
			switch( $nodeName )
			{
				case 'maintainers':
					foreach( $node->childNodes as $maintainer )
						$package['maintainers'][]	= $this->readMaintainer( $maintainer );
					break;
				case 'release':
					$package['releases'][]	= $this->readRelease( $node );
					break;
				case 'changelog':
					foreach( $node->childNodes as $release )
						$package['changes'][]	= $this->readRelease( $release );
					break;
				default:
					$package[$nodeName]	= $this->getNodeValue( $node );
					break;
			}
		}
		return $package;
	}
	
	/**
	 *	Reads a Maintainer Block and returns an Array.
	 *	@access		protected
	 *	@param		DOMNode		$domNode		DOM Node of Maintainer Block
	 *	@return		array
	 */
	private function readMaintainer( $domNode )
	{
		$maintainer	= array();
		foreach( $domNode->childNodes as $node )
			$maintainer[$node->nodeName]	= $this->getNodeValue( $node );
		return $maintainer;
	}

	/**
	 *	Reads a Release Block and returns an Array.
	 *	@access		protected
	 *	@param		DOMNode		$domNode		DOM Node of Release Block
	 *	@return		array
	 */
	private function readRelease( $domNode )
	{
		$release	= array();
		foreach( $domNode->childNodes as $node )
		{
			$nodeName	= $node->nodeName;
			switch( $nodeName )
			{
				case 'deps':
					foreach( $node->childNodes as $dep )
						$release['dependencies'][]	= $this->getNodeValue( $dep );
					break;
				case 'filelist':
					foreach( $node->childNodes as $file )
						$release['files'][]	= $this->getNodeAttributes( $file );
					break;
				default:
					$release[$nodeName]	= $this->getNodeValue( $node );
					break;
			}
		}
		return $release;
	}

	/**
	 *	Returns all Attributes of a DOM Node as Array.
	 *	@access		protected
	 *	@param		DOMNode		$domNode		DOM Node with Attributes
	 *	@return		array
	 */
	private function getNodeAttributes( $domNode )
	{
		$attributes	= array();
		foreach( $domNode->attributes as $attribute )
			$attributes[$attribute->name]	= $attribute->value;
		return $attributes;
	}

	/**
	 *	Returns the Text Value of a DOM Node.
	 *	@access		protected
	 *	@param		DOMNode		$domNode		DOM Node with Attributes
	 *	@return		string
	 */
	private function getNodeValue( $domNode )
	{
		if( !( $domNode->nodeType == 1 && $domNode->childNodes->length > 0 ) )
			return NULL;
		return $domNode->childNodes->item(0)->nodeValue;
	}
}
?>