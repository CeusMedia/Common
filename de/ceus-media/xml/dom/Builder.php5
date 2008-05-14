<?php
/**
 *	Builder for XML Strings with DOM.
 *	@package		xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Builder for XML Strings with DOM.
 *	@package		xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class XML_DOM_Builder
{
	/**	@var	DOMDocument			$document		DOM Document */
	protected $document;
	
	/**
	 *	Builds XML and returns XML as string.
	 *	@access		public
	 *	@param		XML_DOM_Node	$tree			XML Tree
	 *	@param		string			$encoding		Encoding Type
	 *	@return		string
	 */
	public function build( $tree, $encoding = "utf-8" )
	{
		$this->document = new DOMDocument( "1.0", $encoding );
		$this->document->formatOutput = true;
		$root = $this->document->createElement( $tree->getNodename() );
		$root = $this->document->appendChild( $root );
		$this->buildRecursive( $root, $tree, $encoding );
		$xml	= $this->document->saveXML();
		return $xml;
	}

	/**
	 *	Writes XML Tree to XML File recursive.
	 *	@access		protected
	 *	@param		DOMElement		$root		DOM Element
	 *	@param		XML_DOM_Node	$tree		Parent XML Node
	 *	@return		void
	 */
	protected function buildRecursive( $root, $tree, $encoding )
	{
		foreach( $tree->getAttributes() as $key => $value )
		{
			$value	= addslashes( $value );
			if( $encoding == "utf-8" && utf8_encode( utf8_decode( $value ) ) != $value )
				$value	= utf8_encode( $value );
			$root->setAttribute( $key, $value );
		}
		if( $tree->hasChildren() )
		{
			$children =& $tree->getChildren();
			foreach( $children as $child )
			{
				$element = $this->document->createElement( $child->getNodename() );
				$this->buildRecursive( $element, $child, $encoding );
				$element = $root->appendChild( $element );
			}
		}
		else if( $tree->hasContent() )
		{
			$text	= (string) $tree->getContent();
			$text	= addslashes( $text );
			if( $encoding == "utf-8" && utf8_encode( utf8_decode( $text ) ) != $text )
				$text	= utf8_encode( $text );
			$text	= $this->document->createTextNode( $text );
			$text	= $root->appendChild( $text );
		}
	}
}
?>