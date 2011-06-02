<?php
/**
 *	@package		APL.IO.Socket.Stream
 */
/**
 *	@package		APL.IO.Socket.Stream
 */
class Net_Socket_Stream_Package
{
	public $serial		= NULL;
	public $contents	= array();
	public $format		= NULL;

	public function __construct( $format = NULL )
	{
		if( !is_null( $format ) )
			$this->setFormat( $format );
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

	public function setData( $data )
	{
		switch( gettype( $data ) )
		{
			case 'integer':
			case 'float':
			case 'real':
			case 'double':
			case 'string':
			case 'array':
			case NULL:
				$this->data	= $data;
				break;
			case 'object':
				throw new Exception( 'StreamSocketResponse does not support Objects as content, yet' );
			default:
				throw new Exception( 'unknown' );
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

	public function toSerial( $format = NULL )
	{
		$format	= $format ? $format : $this->format;
		switch( $format )
		{
			case 'json':
				return $format.':'.json_encode( $this->data, JSON_FORCE_OBJECT );
			case 'php':
				return $format.':'.serialize( $this->data );
			case 'wddx':
				return $format.':'.wddx_serialize_value( $this->data );
			case 'xml':
				import( 'de.ceus-media.xml.Element' );
				import( 'de.ceus-media.xml.dom.Formater' );
				$root	= new XML_Element( "<response/>" );
				$this->addArrayToXmlNode( $root, $this->data, "item" );
				$xml	= $root->asXml();
				$xml	= XML_DOM_Formater::format( $xml );
				return $format.':'.$xml;
			default:
				return $format.':'.implode( "\n", $this->data );
		}

	}

	public function setFormat( $format )
	{
		$this->format = $format;
	}

	public function getData()
	{
		return $this->data;
	}

	public function fromSerial( $serial ){
		$parts	= preg_split( '/:/', $serial, 2 );
		switch( $parts[0] ){
			case 'php':
				$this->data	= unserialize( $parts[1] );
				break;
			case 'json':
				$this->data	= json_decode( $parts[1] );
				break;
		}
	}
}
?>