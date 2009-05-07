<?php
import( 'de.ceus-media.net.service.Client' );
import( 'de.ceus-media.StopWatch' );
/**
 *	@package		net.service
 *	@todo			Code Docu
 *	@todo			Unit Test
 */
class Net_Service_Caller
{
	protected $calls	= array();
	
	public function __construct( $url )
	{
		$this->client	= new Net_Service_Client( $url );
	}

	public function __call( $key, $arguments )
	{
		$watch		= new StopWatch();
		$arguments	= $this->buildArgumentsForRequest( $arguments );
		$response	= $this->client->get( $key, "php", $arguments );
		$this->calls[]	= array(
			'service'	=> $key,
			'arguments'	=> $arguments,
			'response'	=> $response,
			'time'		=> $watch->stop( 6, 0 ),
		);
		$result		= @unserialize( $response );
		if( $result && is_object( $result ) && is_a( $result, 'Exception' ) )
			throw $result;		
		return $response;
	}
	
	protected function buildArgumentsForRequest( $arguments )
	{
		if( $arguments )
		{
			if( count( $arguments ) == 1 && is_array( $arguments[0] ) )
				return $arguments[0];
			else
				return array( 'argumentsGivenByServiceCaller' => serialize( $arguments ) );
		}
		return array();
	}
	
	public function getCalls()
	{
		return $this->calls;
	}
}
?>