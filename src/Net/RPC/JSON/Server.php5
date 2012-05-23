<?php
/**
 *	...
 *
 *	Copyright (c) 2011-2012 Christian Würker (ceusmedia.com)
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
 *	@category		cmClasses
 *	@package		Net.RPC.JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.5
 *	@version		$Id$
 */
/**
 *	...
 *
 *	@category		cmClasses
 *	@package		Net.RPC.JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.5
 *	@version		$Id$
 */
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