<?php
/**
 *	Parser for HTML Documents.
 *	@package		alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@date			04.08.2008
 *	@version 		0.1
 */
/**
 *	Parser for HTML Documents.
 *	@package		alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@date			04.08.2008
 *	@version 		0.1
 */
class Alg_HtmlParser
{
	/** @var		DOMDocument		$document		DOM Document from HTML */
	protected $document;
	/** @var		array			$errors			DOM Document from HTML */
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
	 *	Returns Language of HTML Document.
	 *	@access		public
	 *	@return		string
	 *	@throws		RuntimeException
	 */
	public function getLanguage()
	{
		$tags	= $this->getMetaTags();
		if( !isset( $tags['Content-Language'] ) )
			throw new RuntimeException( 'No Language set.' );
		return $tags['Content-Language'];
	}
	
	/**
	 *	Returns Array of set Meta Tags.
	 *	@access		public
	 *	@return		array
	 */
	public function getMetaTags()
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
			$parts		= explode( "-", $name );
			for( $i=0; $i<count( $parts ); $i++ )
				$parts[$i]	= ucFirst( strtolower( $parts[$i] ) );
			$name		= implode( "-", $parts );
			$list[$name]	= $content;
		}
		return $list;
	}
	
	/**
	 *	Returns List of HTML Tags by Node Name.
	 *	@access		public
	 *	@param		string			$key			Attribute Key
	 *	@param		string			$value			Attribute Value
	 *	@return		array
	 */
	public function getTagsByAttribute( $key, $value = NULL )
	{
		$list	= array();
		$value	= addslashes( $value );
		$xpath	= new DomXPath( $this->document );
		$query	= $value ? "//*[@".$key." = '".$value."']" : "//*[@".$key."]";
		$nodes	= $xpath->query( $query );
		foreach( $nodes as $node )
			$list[]	= $node;
		return $list;
	}

	/**
	 *	Returns HTML Tag by its ID.
	 *	@access		public
	 *	@param		string			$id				ID of Tag to return
	 *	@return		DOMElement
	 */
	public function getTagById( $id )
	{
		$xpath	= new DomXPath( $this->document );
		$query	= "//*[@id = '$id']";
		$nodes	= $xpath->query( $query );
		if( !$nodes->length )
			throw new RuntimeException( 'No Tag with ID "'.$id.'" found.' );
		return $nodes->item( 0 );
	}
	
	/**
	 *	Returns List of HTML Tags by Tag Name.
	 *	@access		public
	 *	@param		string			$tagName		Tag Name of Tags to return
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
	 *	@param		string			$query			XPath Query
	 *	@return		array
	 */
	public function getTagsByXPath( $query )
	{
		$list	= array();
		$value	= addslashes( $value );
		$xpath	= new DomXPath( $this->document );
		$nodes	= $xpath->query( $query );
		foreach( $nodes as $node )
			$list[]	= $node;
		return $list;
	}
	
	/**
	 *	Indicates whether a  HTML Tag is existing by its ID.
	 *	@access		public
	 *	@param		string			$id				ID of Tag to return
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
	 *	Returns Title of HTML Document.
	 *	@access		public
	 *	@return		string
	 *	@throws		RuntimeException
	 */
	public function getTitle()
	{
		$nodes	= $this->document->getElementsByTagName( "title" );
		if( !$nodes->length )
			throw new RuntimeException( 'No Title Tag found.' );
		return $nodes->item(0)->textContent;
	}
	
	/**
	 *	Creates DOM Document and reads HTML String.
	 *	@access		public
	 *	@param		string			$string			HTML String
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
	 *	@param		string			$fileName		File Name of HTML Document
	 *	@return		void
	 */
	public function parseHtmlFile( $fileName )
	{
		$html	= file_get_contents( $fileName );
		$this->parseHtml( $html );
	}
}
?>