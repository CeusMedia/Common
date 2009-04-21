<?php
/**
 *	Parser for HTML Documents.
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
 *	@package		alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.08.2008
 *	@version 		0.2
 */
/**
 *	Parser for HTML Documents.
 *	@package		alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.08.2008
 *	@version 		0.2
 *	@todo			implement getErrors() and hide $errors;
 */
class Alg_HtmlParser
{
	/** @var		DOMDocument		$document			DOM Document from HTML */
	protected $document;
	/** @var		array			$errors				DOM Document from HTML */
	public $errors	= array();

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
	 *	Returns current DOM Document.
	 *	@access		public
	 *	@return		DOMDocument
	 */
	public function getDocument()
	{
		return $this->document;
	}

	/**
	 *	Returns List of Attributes from a DOM Element.
	 *	@access		public
	 *	@param		DOMElement		$element			DOM Element
	 *	@return		list
	 */
	public function getAttributesFromElement( $element )
	{
		$list	= array();
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
	public function getDescription( $throwException = TRUE )
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
	 *	Returns Favorite Icon URL or throws Exception.
	 *	@access		public
	 *	@param		bool			$throwException		Flag: throw Exception if not found, otherwise return empty String
	 *	@return		string
	 *	@throws		RuntimeException
	 */
	public function getFavoriteIcon( $throwException = TRUE )
	{
		$values	= array(
			'shortcut icon',
			'SHORTCUT ICON',
			'icon',
			'ICON',
		);
		foreach( $values as $value )
		{
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
	public function getJavaScripts()
	{
		$list	= array();
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
	public function getJavaScriptUrls()
	{
		$query	= "//script/@src";
		$tags	= $this->getTagsByXPath( $query );
		return $tags;
	}

	/**
	 *	Returns List of Key Words or throws Exception.
	 *	@access		public
	 *	@param		bool			$throwException		Flag: throw Exception if not found, otherwise return empty String
	 *	@return		array
	 *	@throws		RuntimeException
	 */
	public function getKeyWords( $throwException = TRUE )
	{
		$list	= array();
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
	public function getLanguage( $throwException = TRUE )
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
	public function getMetaTags( $lowerCaseKeys = FALSE )
	{
		$list	= array();
		$tags	= $this->document->getElementsByTagName( "meta" );
		foreach( $tags as $tag )
		{
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
	public function getStyles()
	{
		$list	= array();
		$query	= "//style";
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
	public function getStyleSheetUrls()
	{
		$query	= "//link[@rel='stylesheet']/@href";
		$tags	= $this->getTagsByXPath( $query );
		return $tags;
	}

	/**
	 *	Returns List of HTML Tags with Tag Name, existing Attribute Key or exact Attribute Value.
	 *	@access		public
	 *	@param		string			$tagName			Tag Name of Tags to return
	 *	@param		string			$attributeKey		Attribute Key
	 *	@param		string			$attributeValue		Attribute Value
	 *	@param		string			$attributeOperator	Attribute Operator (=|!=)
	 *	@return		array
	 *	@throws		InvalidArgumentException
	 */
	public function getTags( $tagName = NULL, $attributeKey = NULL, $attributeValue = NULL, $attributeOperator = "=" )
	{
		$query	= $tagName ? "//".$tagName : "//*";
		if( $attributeKey )
		{
			$attributeValue	= $attributeValue ? $attributeOperator."'".addslashes( $attributeValue )."'" : "";
			$query	.= "[@".$attributeKey.$attributeValue."]";
		}
		return $this->getTagsByXPath( $query );
	}

	/**
	 *	Returns List of HTML Tags by Node Name.
	 *	@access		public
	 *	@param		string			$key				Attribute Key
	 *	@param		string			$value				Attribute Value
	 *	@param		string			$operator			Attribute Operator (=|!=)
	 *	@return		array
	 */
	public function getTagsByAttribute( $key, $value = NULL, $operator = "=" )
	{
		return $this->getTags( "*", $key, $value, $operator );
	}

	/**
	 *	Returns HTML Tag by its ID or throws Exception.
	 *	@access		public
	 *	@param		string			$id					ID of Tag to return
	 *	@param		bool			$throwException		Flag: throw Exception if not found, otherwise return empty String
	 *	@return		DOMElement
	 */
	public function getTagById( $id, $throwException = TRUE )
	{
		$xpath	= new DomXPath( $this->document );
		$query	= "//*[@id = '$id']";
		$tags	= $this->getTagsByXPath( $query );
		if( $tags )
			return $tags[0];
		if( $throwException )
			throw new RuntimeException( 'No Tag with ID "'.$id.'" found.' );
		return NULL;
	}
	
	/**
	 *	Returns List of HTML Tags by Tag Name.
	 *	@access		public
	 *	@param		string			$tagName			Tag Name of Tags to return
	 *	@return		array
	 */
	public function getTagsByTagName( $tagName )
	{
		$list	= array();
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
	public function getTagsByXPath( $query )
	{
		$list	= array();
		$xpath	= new DomXPath( $this->document );
		$nodes	= $xpath->query( $query );
		foreach( $nodes as $node )
		{
			if( preg_match( "#/@[a-z]+$#i", $query ) )
				$node	= $node->textContent;
			$list[]	= $node;
		}
		return $list;
	}
	
	/**
	 *	Indicates whether a  HTML Tag is existing by its ID.
	 *	@access		public
	 *	@param		string			$id					ID of Tag to return
	 *	@return		bool
	 */
	public function hasTagById( $id )
	{
		$xpath	= new DomXPath( $this->document );
		$query	= "//*[@id = '$id']";
		$nodes	= $xpath->query( $query );
		return (bool) $nodes->length;
	}

	/**
	 *	Returns Title of HTML Document or throws Exception.
	 *	@access		public
	 *	@param		bool			$throwException		Flag: throw Exception if not found, otherwise return empty String
	 *	@return		string
	 *	@throws		RuntimeException
	 */
	public function getTitle( $throwException = TRUE )
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
	 *	Creates DOM Document and reads HTML String.
	 *	@access		public
	 *	@param		string			$string				HTML String
	 *	@return		void
	 */
	public function parseHtml( $string )
	{
		$this->document = new DOMDocument();
		ob_start();
		$this->document->loadHTML( $string );
		$content	= ob_get_clean();
		if( $content )
			$this->errors	= $content;
	}

	/**
	 *	Loads HTML File and prepares DOM Document.
	 *	@access		public
	 *	@param		string			$fileName			File Name of HTML Document
	 *	@return		void
	 */
	public function parseHtmlFile( $fileName )
	{
		$html	= file_get_contents( $fileName );
		$this->parseHtml( $html );
	}
}
?>