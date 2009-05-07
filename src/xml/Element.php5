<?php
/**
 *	XML Element based on SimpleXMLElement with improved Attribute Handling.
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
 *	@package		xml
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			21.02.2008
 *	@version		0.6
 */
/**
 *	XML Element based on SimpleXMLElement with improved Attribute Handling.
 *	@package		xml
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			21.02.2008
 *	@version		0.6
 */
class XML_Element extends SimpleXMLElement
{
	/**
	 *	Returns count of Attributes.
	 *	@access		public
	 *	@return		int
	 */
	public function countAttributes()
	{
		return count( $this->getAttributeKeys() );
	}

	/**
	 *	Returns count of Children.
	 *	@access		public
	 *	@return		int
	 */
	public function countChildren()
	{
		$i = 0;
		foreach( $this->children() as $node )
			$i++;
		return $i;
	}

	/**
	 *	Returns the Value of an Attribute from its' Key.
	 *	@access		public
	 *	@param		string		$key		Key of Attribute
	 *	@return		bool
	 */
	public function getAttribute( $key, $nameSpace = "" )
	{
		$data	= $nameSpace ? $this->attributes( $nameSpace, 1 ) : $this->attributes();
		if( !isset( $data[$key] ) )
			throw new Exception( 'Attribute "'.$key.'" is not set.' );
		return (string) $data[$key];
	}

	/**
	 *	Returns List of Attribute Keys.
	 *	@access		public
	 *	@return		array
	 */
	public function getAttributeKeys( $nameSpace = "" )
	{
		$list	= array();
		$data	= $nameSpace ? $this->attributes( $nameSpace, 1 ) : $this->attributes();
		foreach( $data as $key => $value )
			$list[] = $key;
		return $list;
	}

	/**
	 *	Returns Array of Attributes.
	 *	@access		public
	 *	@return		array
	 */
	public function getAttributes()
	{
		$list	= array();
		foreach( $this->attributes() as $key => $value )
			$list[$key]	= (string) $value;
		return $list;
	}
	
	/**
	 *	Returns Text Value.
	 *	@access		public
	 *	@return		string
	 */
	public function getValue()
	{
		return (string) $this;
	}

	/**
	 *	Indicates whether an Attribute is existing by it's Key.
	 *	@access		public
	 *	@param		string		$key		Key of Attribute
	 *	@return		bool
	 */
	public function hasAttribute( $key, $nameSpace = ""  )
	{
		$keys	= $this->getAttributeKeys( $nameSpace );
		return in_array( $key, $keys );
	}

	/**
	 *	Writes current XML Element as XML File.
	 *	@access		public
	 *	@param		string		$fileName		File Name for XML File
	 *	@return		int
	 */
	public function asFile( $fileName )
	{
		import( 'de.ceus-media.file.Writer' );
		$xml	= $this->asXml();
		return File_Writer::save( $fileName, $xml );
	}
}
?>