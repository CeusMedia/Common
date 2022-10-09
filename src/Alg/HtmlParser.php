<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Parser for HTML Documents.
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
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg;

use DOMDocument;
use DOMElement;
use DomXPath;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Parser for HTML Documents.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			implement getErrors() and hide $errors;
 */
class HtmlParser
{
	/** @var		string|NULL		$errors				DOM Document from HTML */
	public ?string $errors			= NULL;

	/** @var		DOMDocument		$document			DOM Document from HTML */
	protected DOMDocument $document;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
#		DOMDocument::setIdAttribute( 'id', TRUE );
		$this->document = new DOMDocument();
	}

	/**
	 *	Returns List of Attributes from a DOM Element.
	 *	@access		public
	 *	@param		DOMElement		$element			DOM Element
	 *	@return		array
	 */
	public function getAttributesFromElement( DOMElement $element ): array
	{
		$list	= [];
		foreach( $element->attributes as $key => $value )
			$list[$key]	= $value->textContent;
		return $list;
	}

	/**
	 *	Returns Description of HTML Document or throws Exception.
	 *	@access		public
	 *	@param		bool			$throwException		Flag: throw Exception if not found, otherwise return empty String
	 *	@return		string
	 *	@throws		RuntimeException
	 */
	public function getDescription( bool $throwException = TRUE ): string
	{
		$tags	= $this->getMetaTags( TRUE );
		if( isset( $tags['description'] ) )
			return $tags['description'];
		if( isset( $tags['dc.description'] ) )
			return $tags['dc.description'];
		if( $throwException )
			throw new RuntimeException( 'No Description Meta Tag set.' );
		return "";
	}

	/**
	 *	Returns current DOM Document.
	 *	@access		public
	 *	@return		DOMDocument
	 */
	public function getDocument(): DOMDocument
	{
		return $this->document;
	}

	/**
	 *	Returns Favorite Icon URL or throws Exception.
	 *	@access		public
	 *	@param		bool			$throwException		Flag: throw Exception if not found, otherwise return empty String
	 *	@return		string
	 *	@throws		RuntimeException
	 */
	public function getFavoriteIcon( bool $throwException = TRUE ): string
	{
		$values	= [
			'apple-touch-icon',
			'APPLE-TOUCH-ICON',
			'shortcut icon',
			'SHORTCUT ICON',
			'icon',
			'ICON',
		];
		foreach( $values as $value ){
			$tags	= $this->getTags( 'link', 'rel', $value );
			if( count( $tags ) )
				return $tags[0]->getAttribute( 'href' );
		}
		if( $throwException )
			throw new RuntimeException( 'No Favorite Icon Link Tag found.' );
		return "";
	}

	/**
	 *	Returns List of JavaScript Blocks.
	 *	@access		public
	 *	@return		array
	 */
	public function getJavaScripts(): array
	{
		$list	= [];
		$query	= "//script[not(@src)]";
		$tags	= $this->getTagsByXPath( $query );
		foreach( $tags as $tag )
			$list[]	= $tag->textContent;
		return $list;
	}

	/**
	 *	Returns List of CSS Style Sheet URLs.
	 *	@access		public
	 *	@return		array
	 */
	public function getJavaScriptUrls(): array
	{
		return $this->getTagsByXPath( "//script/@src" );
	}

	/**
	 *	Returns List of Keywords or throws Exception.
	 *	@access		public
	 *	@param		bool			$throwException		Flag: throw Exception if not found, otherwise return empty String
	 *	@return		array
	 *	@throws		RuntimeException
	 */
	public function getKeywords( bool $throwException = TRUE ): array
	{
		$list	= [];
		$tags	= $this->getMetaTags( TRUE );
		if( isset( $tags['keywords'] ) )
		{
			$words	= explode( ",", $tags['keywords'] );
			foreach( $words as $word )
				$list[]	= trim( $word );
			return $list;
		}
		if( $throwException )
			throw new RuntimeException( 'No Favorite Icon Link Tag found.' );
		return $list;
	}

	/**
	 *	Returns Language of HTML Document or throws Exception.
	 *	@access		public
	 *	@param		bool			$throwException		Flag: throw Exception if not found, otherwise return empty String
	 *	@return		string
	 *	@throws		RuntimeException
	 */
	public function getLanguage( bool $throwException = TRUE ): string
	{
		$tags	= $this->getMetaTags( TRUE );
		if( isset( $tags['content-language'] ) )
			return $tags['content-language'];
		if( $throwException )
			throw new RuntimeException( 'No Language Meta Tag set.' );
		return "";
	}

	/**
	 *	Returns Array of set Meta Tags.
	 *	@access		public
	 *	@return		array
	 */
	public function getMetaTags( bool $lowerCaseKeys = FALSE ): array
	{
		$list	= [];
		$tags	= $this->document->getElementsByTagName( "meta" );
		foreach( $tags as $tag ){
			if( !$tag->hasAttribute( 'content' ) )
				continue;
			$content	= $tag->getAttribute( 'content' );
			$key		= $tag->hasAttribute( 'name' ) ? "name" : "http-equiv";
			$name		= $tag->getAttribute( $key );
			if( $lowerCaseKeys )
				$name	= strtolower( $name );
			$list[$name]	= trim( $content );
		}
		return $list;
	}

	/**
	 *	Returns List of Style Definition Blocks.
	 *	@access		public
	 *	@return		array
	 */
	public function getStyles(): array
	{
		$list	= [];
		$tags	= $this->getTagsByXPath( "//style" );
		foreach( $tags as $tag )
			$list[]	= $tag->textContent;
		return $list;
	}

	/**
	 *	Returns List of CSS Style Sheet URLs.
	 *	@access		public
	 *	@return		array
	 */
	public function getStyleSheetUrls(): array
	{
		return $this->getTagsByXPath( "//link[@rel='stylesheet']/@href" );
	}

	/**
	 *	Returns HTML Tag by its ID or throws Exception.
	 *	@access		public
	 *	@param		string			$id					ID of Tag to return
	 *	@param		bool			$throwException		Flag: throw Exception if not found, otherwise return empty String
	 *	@return		DOMElement|NULL
	 */
	public function getTagById( string $id, bool $throwException = TRUE ): ?DOMElement
	{
		$tags	= $this->getTagsByXPath( "//*[@id = '$id']" );
		if( $tags )
			return $tags[0];
		if( $throwException )
			throw new RuntimeException( 'No Tag with ID "'.$id.'" found.' );
		return NULL;
	}

	/**
	 *	Returns List of HTML Tags with Tag Name, existing Attribute Key or exact Attribute Value.
	 *	@access		public
	 *	@param		string|NULL		$tagName			Tag Name of Tags to return
	 *	@param		string|NULL		$attributeKey		Attribute Key
	 *	@param		string|NULL		$attributeValue		Attribute Value
	 *	@param		string			$attributeOperator	Attribute Operator (=|!=)
	 *	@return		array
	 *	@throws		InvalidArgumentException
	 */
	public function getTags( ?string $tagName = NULL, ?string $attributeKey = NULL, ?string $attributeValue = NULL, string $attributeOperator = "=" ): array
	{
		$query	= $tagName ? "//".$tagName : "//*";
		if( $attributeKey ){
			$attributeValue	= $attributeValue ? $attributeOperator."'".addslashes( $attributeValue )."'" : "";
			$query	.= "[@".$attributeKey.$attributeValue."]";
		}
		return $this->getTagsByXPath( $query );
	}

	/**
	 *	Returns List of HTML Tags by Node Name.
	 *	@access		public
	 *	@param		string			$key				Attribute Key
	 *	@param		string|NULL		$value				Attribute Value
	 *	@param		string			$operator			Attribute Operator (=|!=)
	 *	@return		array
	 */
	public function getTagsByAttribute( string $key, ?string $value = NULL, string $operator = "=" ): array
	{
		return $this->getTags( "*", $key, $value, $operator );
	}

	/**
	 *	Returns List of HTML Tags by Tag Name.
	 *	@access		public
	 *	@param		string			$tagName			Tag Name of Tags to return
	 *	@return		array
	 */
	public function getTagsByTagName( string $tagName ): array
	{
		$list	= [];
		$nodes	= $this->document->getElementsByTagName( $tagName );
		foreach( $nodes as $node )
			$list[]	= $node;
		return $list;
	}

	/**
	 *	Returns List of HTML Tags by Node Name.
	 *	@access		public
	 *	@param		string			$query				XPath Query
	 *	@return		array
	 */
	public function getTagsByXPath( string $query ): array
	{
		$list	= [];
		$xpath	= new DomXPath( $this->document );
		$nodes	= $xpath->query( $query );
		foreach( $nodes as $node ){
			if( preg_match( "#/@[a-z]+$#i", $query ) )
				$node	= $node->textContent;
			$list[]	= $node;
		}
		return $list;
	}

	/**
	 *	Returns Title of HTML Document or throws Exception.
	 *	@access		public
	 *	@param		bool			$throwException		Flag: throw Exception if not found, otherwise return empty String
	 *	@return		string
	 *	@throws		RuntimeException
	 */
	public function getTitle( bool $throwException = TRUE ): string
	{
		$nodes	= $this->document->getElementsByTagName( "title" );
		if( $nodes->length )
			return $nodes->item(0)->textContent;
		$tags	= $this->getMetaTags( TRUE );
		if( isset( $tags['dc.title'] ) )
			return $tags['dc.title'];
		if( $throwException )
			throw new RuntimeException( 'Neither Title Tag not Title Meta Tag found.' );
		return "";
	}

	/**
	 *	Indicates whether an HTML Tag is existing by its ID.
	 *	@access		public
	 *	@param		string			$id					ID of Tag to return
	 *	@return		bool
	 */
	public function hasTagById( string $id ): bool
	{
		$xpath	= new DomXPath( $this->document );
		$query	= "//*[@id = '$id']";
		$nodes	= $xpath->query( $query );
		return (bool) $nodes->length;
	}

	/**
	 *	Creates DOM Document and reads HTML String.
	 *	@access		public
	 *	@param		string			$string				HTML String
	 *	@return		self
	 */
	public function parseHtml( string $string ): self
	{
		$this->document = new DOMDocument();
		ob_start();
		$this->document->loadHTML( $string );
		$content	= ob_get_clean();
		if( $content )
			$this->errors	= $content;
		return $this;
	}

	/**
	 *	Loads HTML File and prepares DOM Document.
	 *	@access		public
	 *	@param		string			$fileName			File Name of HTML Document
	 *	@return		self
	 */
	public function parseHtmlFile( string $fileName ): self
	{
		$html	= file_get_contents( $fileName );
		$this->parseHtml( $html );
		return $this;
	}
}
