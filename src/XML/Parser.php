<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Parses XML String and returns Array or Object Structure.
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
 *	@category		Library
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML;

use CeusMedia\Common\XML\DOM\Node;
use RuntimeException;
use XMLParser;

/**
 *	Parses XML String and returns Array or Object Structure.
 *	@category		Library
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			implement reading from stream + Unit Test
 */
class Parser
{
	/**	@var		array			$last			Last Node while parsing */
	protected array $last			= [];

	/**	@var		array			$options		Map of parser options */
	protected array $options		= [
		XML_OPTION_CASE_FOLDING		=> 0,
	];

	/**
	 *	Returns an Array Structure from XML String.
	 *	@access		public
	 *	@param		string		$xml
	 *	@return		array
	 *	@throws		RuntimeException
	 */
	public function toArray( string $xml ): array
	{
		$data	= [];
		$parser = $this->createParser();
		xml_set_element_handler( $parser, [$this, 'handleTagOpenForArray'], [$this, 'handleTagCloseForArray'] );
		xml_set_character_data_handler( $parser, [$this, 'handleCDataForArray'] );
		return $this->parse( $data, $parser, $xml );
	}

	/**
	 *	Returns an Object Tree as XML_DOM_Node from XML String.
	 *	@access		public
	 *	@param		string		$xml
	 *	@return		Node
	 *	@throws		RuntimeException
	 */
	public function toObject( string $xml ): Node
	{
		$data	= new Node( "root" );
		$parser = $this->createParser();
		xml_set_element_handler( $parser, [$this, 'handleTagOpenForObject'], [$this, 'handleTagCloseForObject'] );
		xml_set_character_data_handler( $parser, [$this, 'handleCDataForObject'] );
		$object = $this->parse( $data, $parser, $xml );
		return $object->getChildByIndex( 0 );
	}

	/**
	 *	@access		protected
	 *	@return		XMLParser
	 */
	protected function createParser(): XMLParser
	{
		$parser		= xml_parser_create();
		xml_set_object( $parser, $this );
		foreach( $this->options as $key => $value )
			xml_parser_set_option( $parser, $key, $value );
		return $parser;
	}

	/**
	 *	Callback Method for Character Data.
	 *	@access		protected
	 *	@param		resource	$parser		Resource of XML Parser
	 *	@param		string		$cdata		Data of parsed tag
	 *	@return		void
	 *	@noinspection PhpUnusedParameterInspection
	 */
	protected function handleCDataForArray( $parser, string $cdata ): void
	{
		if( strlen( ltrim( $cdata ) ) > 0 ){
			$pointer	= count( $this->last ) - 2;
			$index		= count( $this->last[$pointer] ) - 1;
			$content	= str_replace( '\n', "\n", trim( $cdata ) );
			$this->last[$pointer][$index]['content']	.= $content;
		}
	}

	/**
	 *	Callback Method for Character Data.
	 *	@access		protected
	 *	@param		resource	$parser		Resource of XML Parser
	 *	@param		string		$cdata		Data of parsed tag
	 *	@return		void
	 *	@noinspection PhpUnusedParameterInspection
	 */
	protected function handleCDataForObject( $parser, string $cdata ): void
	{
		if( strlen( ltrim( $cdata ) ) <= 0 )
			return;
		$pointer	= count( $this->last ) - 2;
		$index		= count( $this->last[$pointer]->getChildren() ) - 1;
		$parent		=& $this->last[$pointer];
		$node		= $parent->getChildByIndex( $index );
		$content	= $node->getContent();
		$content	.= str_replace( '\n', "\n", trim( $cdata ) );
		$node->setContent( $content );
	}

	/**
	 *	@access		protected
	 *	@param		XMLParser		$parser
	 *	@return		void
	 *	@throws		RuntimeException
	 */
	protected function handleError( XMLParser $parser ): void
	{
		$msg	= "XML error: %s at line %d";
		$error	= xml_error_string( xml_get_error_code( $parser ) );
		$line	= xml_get_current_line_number( $parser );
		throw new RuntimeException( sprintf( $msg, $error, $line ) );
	}

	/**
	 *	Callback Method for closing Tags on Array Collection.
	 *	@access		protected
	 *	@param		resource	$parser		Resource of XML Parser
	 *	@param		string		$tag		Name of parsed tag
	 *	@return		void
	 *	@noinspection PhpUnusedParameterInspection
	 */
	protected function handleTagCloseForArray( $parser, string $tag ): void
	{
		array_pop( $this->last );
	}

	/**
	 *	Callback Method for closing Tags on Object Collection.
	 *	@access		protected
	 *	@param		resource	$parser		Resource of XML Parser
	 *	@param		string		$tag		Name of parsed tag
	 *	@return		void
	 *	@noinspection PhpUnusedParameterInspection
	 */
	protected function handleTagCloseForObject( $parser, string $tag ): void
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
	 *	@noinspection PhpUnusedParameterInspection
	 */
	protected function handleTagOpenForArray( $parser, string $tag, array $attributes ): void
	{
		$count	= count( $this->last ) - 1;
		$this->last[$count][]	= [
			"nodeName"		=> $tag,
			"attributes"	=> $attributes,
			"content"		=> '',
			"children"		=> [],
		];
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
	 *	@noinspection PhpUnusedParameterInspection
	 */
	protected function handleTagOpenForObject( $parser, string $tag, array $attributes ): void
	{
		$count		= count( $this->last ) - 1;
		$parentNode	=& $this->last[$count];
		$childNode	= new Node( $tag, '', $attributes );
		$parentNode->addChild( $childNode );
		$this->last[]	=& $childNode;
	}

	/**
	 *	@access		protected
	 *	@param		Node|array		$data
	 *	@param		XMLParser		$parser
	 *	@param		string $xml
	 *	@return		array|mixed
	 *	@throws		RuntimeException
	 */
	protected function parse( $data, XMLParser $parser, string $xml )
	{
		$this->last = [&$data];
		if( xml_parse( $parser, $xml ) !== 1 )
			$this->handleError( $parser );
		xml_parser_free( $parser );
		return $data;
	}
}
