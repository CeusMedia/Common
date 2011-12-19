<?php
class Net_RPC_JSON_Server
{
	protected $procedures;
	
	protected $log;

	public function __construct(){}
	
	protected function evaluateArguments( $procedureName, $arguments ){
		if( empty( $this->procedures[$procedureName] ) )
			throw new InvalidArgumentException( 'Procedure "'.$procedureName.'" is not available' );
		$index		= array_keys( $this->procedures[$procedureName]['parameters'] );
		$defined	= array_values( $this->procedures[$procedureName]['parameters'] );
		for( $i=count( $defined )-1; $i>=0; $i--){
			if( !$defined[$i]['optional'] && count( $arguments ) < $i + 1 )
				throw new InvalidArgumentException( 'Argument for parameter "'.$index[$i].'" is missing' );	
		}
		return $arguments;
	}
	
	public function handle( ADT_List_Dictionary $request )
	{
		if( $request->has( 'method' ) && $request->get( 'method' ) )
		{
			$buffer		= new UI_OutputBuffer();
			$procedureName	= $request->get( 'method' );
			$arguments	= $request->get( 'arguments' );
			if( !is_array( $arguments ) )
				$arguments	= array();

			try{
				if( !array_key_exists( $procedureName, $this->procedures ) )
					throw new InvalidArgumentException( 'Procedure "'.$procedureName.'" is not available' );
				$procedure	= $this->procedures[$procedureName];
				$className	= $procedure['className'];
				$arguments	= $this->evaluateArguments( $procedureName, $arguments );
				$time1		= microtime( TRUE );
				$class		= new ReflectionClass( $className );
				$object		= $class->newInstance();
				$procedure	= $class->getMethod( $procedureName );
				$result		= $procedure->invokeArgs( $object, $arguments );
				$time2		= microtime( TRUE );
				$data		= array(
					'status'		=> 'data',
					'data'			=> $result,
					'timeProcedure'	=> round( $time2 - $time1, 3 )									//  duration of procedure call in seconds with max 3 decimal places
				);
			}
			catch( Exception $e ){
				$data		= array(
					'status'		=> 'error',
					'data'			=> $e->getMessage(),
					'timeProcedure'	=> 0															//  duration of procedure call
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
		$procedureList	= get_class_methods( $className );
		foreach( $procedureList as $procedureName ){
			
			$parameters	= array();
			$procedure	= new ReflectionMethod( $className, $procedureName );
			foreach( $procedure->getParameters() as $parameter )
				$parameters[$parameter->name]	= array(
					'optional'	=> $parameter->isOptional(),
					'default'	=> $parameter->isOptional() ? $parameter->getDefaultValue() : NULL
				);

			
			$this->procedures[$procedureName]	= array(
				'className'		=> $className,
				'parameters'	=> $parameters,
			);
		}
	}
}
?>