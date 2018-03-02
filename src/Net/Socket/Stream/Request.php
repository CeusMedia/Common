<?php
/**
 *	@category		Library
 *	@package		CeusMedia_Common_Net_Socket_Stream
 */
/**
 *	@category		Library
 *	@package		CeusMedia_Common_Net_Socket_Stream
 */
class Net_Socket_Stream_Request
{
	public $data		= NULL;
	public $format		= 'json';

	public function __construct( $format = NULL, $data = NULL )
	{
		if( !is_null( $format ) )
			$this->setFormat( $format );
		if( !is_null( $data ) )
			$this->setData( $data );
	}

	public function addHeader( $header )
	{
		$this->headers[]	= $header;
	}

	public function setData( $data )
	{
		$this->data	= $data;
	}

	public function setFormat( $format )
	{
		$this->format = $format;
	}

	public function setHeaders( $headers )
	{
		$this->headers	= array();
		foreach( $headers as $header )
			$this->addHeader( $header );
	}

	public function setResponseFormat( $format )
	{
		$this->format	= $format;
	}

	public function render()
	{
		switch( $this->format )
		{
			case Net_Socket_Stream_Server::FORMAT_PHP:
			//	@todo		implement
				break;

		}
	}
}
?>
