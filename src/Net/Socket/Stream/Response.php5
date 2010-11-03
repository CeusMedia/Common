<?php
/**
 *	@package		APL.IO.Socket.Stream
 */
/**
 *	@package		APL.IO.Socket.Stream
 */
class Net_Socket_Stream_Response
{
	public $format		= NULL;
	public $contents	= array();

	public function __construct( $format = NULL, $content = NULL )
	{
		if( !is_null( $format ) )
			$this->setFormat( $format );
		if( !is_null( $content ) )
			$this->addContent( $content );
	}

	public function addContent( $content )
	{
		switch( gettype( $content ) )
		{
			case 'integer':
			case 'float':
			case 'real':
			case 'double':
			case 'string':
				$this->contents[]	= $content;
				break;
			case 'array':
				foreach( $content as $block )
					if( trim( $block ) )
						$this->addContent( $block );
				break;
			case 'object':
				throw new Exception( 'StreamSocketResponse does not support Objects as content, yet' );
			default:
				throw new Exception( 'unknown' );
		}
	}

	public function render()
	{
		switch( $this->format )
		{
			case 'json':
				return json_encode( $this->contents );
			case 'php':
				return serialize( $this->contents );
			case 'wddx':
				return wddx_serialize_value( $this->contents );
			case 'xml':
				import( 'de.ceus-media.xml.Element' );
				import( 'de.ceus-media.xml.dom.Formater' );
				$root	= new XML_Element( "<response/>" );
				$this->addArrayToXmlNode( $root, $this->contents, "item" );
				$xml	= $root->asXml();
				$xml	= XML_DOM_Formater::format( $xml );
				return $xml;
			default:
				return implode( "\n", $this->contents );
		}
	}

	public function setFormat( $format )
	{
		$this->format	= $format;
	}




	/**
	 *	Converts a Data Array to a XML Structure and appends it to the given SimpleXMLElement.
	 *	@access		protected
	 *	@param		XML_Element		$xmlNode		XML Node to append to
	 *	@param		array			$dataArray		Array to append
	 *	@param		string			$lastParent		Recursion: Outer Node Name for Integer Values
	 *	@return		void
	 */
	protected function addArrayToXmlNode( &$xmlNode, $dataArray, $lastParent = "" )
	{
		if( !( is_string( $lastParent ) && $lastParent ) )
			$lastParent	= "item";
		foreach( $dataArray as $key => $value )
		{
			if( is_array( $value ) )
			{
				if( is_int( $key ) )
				{
					$child	=& $xmlNode->addChild( "set" );
					$this->addArrayToXmlNode( $child, $value, "items" );
					continue;
				}
				$child	=& $xmlNode->addChild( $key );
				$this->addArrayToXmlNode( $child, $value, $key );
				continue;
			}
			else if( is_int( $key ) )
			{
				if( $lastParent )
					$key	= $this->getSingular( $lastParent );
				else
					$key	= "item";
			}
			$xmlNode->addChild( $key, str_replace( "&", "&amp;", $value ) );
		}
	}

	/**
	 *	Returns Singular of a Word.
	 *	@access		public
	 *	@param		string			$words			Word in Plural
	 *	@return		string
	 */
	protected function getSingular( $word )
	{
		$word	= preg_replace( '@ies$@', "y", $word );
		$word	= preg_replace( '@(([s|x|h])e)?s$@', "\\2", $word );
		return $word;
	}
}
?>