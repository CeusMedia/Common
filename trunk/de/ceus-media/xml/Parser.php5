<?php
/**
 *	Parses XML String and returns Array or Object Structure.
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
 *	@package		xml
 *	@uses			XML_DOM_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
import( 'de.ceus-media.xml.dom.Node' );
/**
 *	Parses XML String and returns Array or Object Structure.
 *	@package		xml
 *	@uses			XML_DOM_Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 *	@todo			Unit Test
 */
class XML_Parser
{
	/**	@var		resource		$xml		Resource of XML Parser */
	protected $xml;
	/**	@var		array			$last		Last Node while parsing */
	protected $last	= array();
	/**	@var		mixed			$data		Parsed XML Data as Array or Object Structure */
	protected $data	= array();

	/**
	 *	Callback Method for Character Data.
	 *	@access		protected
	 *	@param		resource	parser		Resource of XML Parser
	 *	@param		string	cdata		Data of parsed tag
	 *	@return		void
	 */
	protected function handleCDataForArray( $parser, $cdata )
	{
		if( strlen( ltrim( $cdata ) ) > 0 )
		{
			$pointer	= count( $this->last ) - 2;
			$index		= count( $this->last[$pointer] ) - 1;
			$content	= str_replace( '\n', "\n", trim( $cdata ) );
			$this->last[$pointer][$index]['content']	.= $content;
		}
	}

	/**
	 *	Callback Method for Character Data.
	 *	@access		protected
	 *	@param		resource	parser		Resource of XML Parser
	 *	@param		string	cdata		Data of parsed tag
	 *	@return		void
	 */
	protected function handleCDataForObject( $parser, $cdata )
	{
		if( strlen( ltrim( $cdata ) ) <= 0 )
			return;
		$pointer		= count( $this->last ) - 2;
		$index		= count( $this->last[$pointer]->getChildren() ) - 1;
		$parent		=& $this->last[$pointer];
		$node		= $parent->getChildByIndex( $index );
		$content	= $node->getContent();
		$content	.= str_replace( '\n', "\n", trim( $cdata ) );
		$node->setContent( $content );
	}

	/**
	 *	Callback Method for closing Tags on Array Collection.
	 *	@access		protected
	 *	@param		resource	$parser		Resource of XML Parser
	 *	@param		string		$tag		Name of parsed tag
	 *	@return		void
	 */
	protected function handleTagCloseForArray( $parser, $tag )
	{
		array_pop( $this->last );
	}

	/**
	 *	Callback Method for closing Tags on Object Collection.
	 *	@access		protected
	 *	@param		resource	$parser		Resource of XML Parser
	 *	@param		string		$tag		Name of parsed tag
	 *	@return		void
	 */
	protected function handleTagCloseForObject( $parser, $tag )
	{
		array_pop( $this->last );
	}

	/**
	 *	Callback Method for opening Tags on Array Collection.
	 *	@access		protected
	 *	@param		resource	$parser		Resource of XML Parser
	 *	@param		string		$tag		Name of parsed Tag 
	 *	@param		array		$attributes	Array of parsed Attributes
	 *	@return		void
	 */
	protected function handleTagOpenForArray( $parser, $tag, $attributes )
	{
		$count	= count( $this->last ) - 1;
		$this->last[$count][]	= array(
			"tag"			=> $tag,
			"attributes"	=> $attributes,
			"content"		=> '',
			"children"		=> array()
		);
		$index	= count( $this->last[$count] ) - 1;
		$this->last[]	= &$this->last[$count][$index]['children'];
	}

	/**
	 *	Callback Method for opening Tags on Object Collection.
	 *	@access		protected
	 *	@param		resource	$parser		Resource of XML Parser
	 *	@param		string		$tag		Name of parsed Tag 
	 *	@param		array		$attributes	Array of parsed Attributes
	 *	@return		void
	 */
	protected function handleTagOpenForObject( $parser, $tag, $attributes )
	{
		$count		= count( $this->last ) - 1;
		$parentNode	=& $this->last[$count];
		$childNode	=& new XML_DOM_Node(
			$tag,
			"",
			$attributes
		); 
		$parentNode->addChild( $childNode );
		$this->last[]	=& $childNode;
	}

	/**
	 *	Returns an Array Structure from XML String.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray( $xml )
	{
		$this->data	= array();
		$this->xml	= xml_parser_create();
		xml_set_object( $this->xml, $this );
		xml_set_element_handler( $this->xml, 'handleTagOpenForArray', 'handleTagCloseForArray' );
		xml_set_character_data_handler( $this->xml, 'handleCDataForArray' );
		$this->last	= array( &$this->data );
		if( !xml_parse( $this->xml, $xml ) )
		{
			$msg	= "XML error: %s at line %d";
			$error	= xml_error_string( xml_get_error_code( $this->xml ) );
			$line	= xml_get_current_line_number( $this->xml );
			throw new RuntimeException( sprintf( $msg, $error, $line ) );
		}
		xml_parser_free( $this->xml );
		return $this->data;
	}

	/**
	 *	Returns an Object Tree as XML_DOM_Node from XML String.
	 *	@access		public
	 *	@return		XML_DOM_Node
	 */
	public function toObject( $xml )
	{
		import( 'de.ceus-media.xml.dom.Node' );
		$this->data	= new XML_DOM_Node( "root" );
		$this->xml	= xml_parser_create();
		xml_set_object( $this->xml, $this );
		xml_set_element_handler( $this->xml, 'handleTagOpenForObject', 'handleTagCloseForObject' );
		xml_set_character_data_handler( $this->xml, 'handleCDataForObject' );
		$this->last	= array( &$this->data );
		if( !xml_parse( $this->xml, $xml ) )
		{
			$msg	= "XML error: %s at line %d";
			$error	= xml_error_string( xml_get_error_code( $this->xml ) );
			$line	= xml_get_current_line_number( $this->xml );
			throw new RuntimeException( sprintf( $msg, $error, $line ) );
		}
		xml_parser_free( $this->xml );
		return $this->data->getChildByIndex( 0 );
	}
}
?>