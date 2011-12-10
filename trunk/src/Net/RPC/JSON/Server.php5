<?php
class Net_RPC_JSON_Server
{
	protected $methods;

	public function handle( ADT_List_Dictionary $request )
	{
		if( $request->has( 'method' ) && $request->get( 'method' ) )
		{
			$buffer		= new UI_OutputBuffer();
			$methodName	= $request->get( 'method' );
			$arguments	= $request->get( 'arguments' );
			if( !is_array( $arguments ) )
				$arguments	= array();
			$arguments	= array_values( $arguments );
			try{
				if( !array_key_exists( $methodName, $this->methods ) )
					throw new InvalidArgumentException( 'Method "'.$methodName.'" is not available' );
				$procedure	= $this->methods[$methodName];
				$className	= $procedure['className'];
				$class		= new ReflectionClass( $className );
				$object		= $class->newInstance();
				$method		= $class->getMethod( $methodName );
				foreach( $method->getParameters() as $argument )
				{
					if( !$argument->allowsNull() && !count( $arguments ) )
						throw new InvalidArgumentException( 'Parameter "'.$argument->name.'" is missing' );
					$list[$argument->name] = array_shift( $arguments );
				}
				$time1		= microtime( TRUE );
				$result		= $method->invokeArgs( $object, $list );
				$time2		= microtime( TRUE );
				$data		= array(
					'status'		=> 'data',
					'data'			=> $result,
					'timeMethod'	=> round( $time2 - $time1, 3 )									//  duration of method call in seconds with max 3 decimal places
				);
			}
			catch( Exception $e ){
				$data		= array(
					'status'		=> 'error',
					'data'			=> $e->getMessage(),
					'timeMethod'	=> 0															//  duration of method call
				);
				if( $e instanceof Exception_Serializable)
					$data['serial']	= serialize( $e );
			}
			$data['stdout']		= $buffer->has() ? $buffer->get( TRUE ) : NULL;
			$response	= new Net_HTTP_Request_Response;
			$response->write( json_encode( $data ) );
			$response->send();
			return TRUE;
		}
		return FALSE;
	}
	
	public function setClass( $className )
	{
		$methodList	= get_class_methods( $className );
		foreach( $methodList as $methodName )
		{
			$this->methods[$methodName]	= array(
				'className'	=> $className,
				'argsMap'	=> array(),
			);
		}
	}
}
?>