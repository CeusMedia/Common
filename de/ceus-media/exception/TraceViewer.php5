<?php
/**
 *	Visualisation of Exception Stack Trace.
 *	@package		exception
 *	@author			Romain Boisnard
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.04.2008
 *	@version		0.1
 */
/**
 *	Visualisation of Exception Stack Trace.
 *	@package		exception
 *	@author			Romain Boisnard
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.04.2008
 *	@version		0.1
 */
class Exception_TraceViewer
{
	/**
	 *	Constructor, prints Exception Trace.
	 *	@access		public
	 *	@param		Exception	$exception	Exception
	 *	@return		void
	 */
	public function __construct( $e )
	{
		print( $this->buildTrace( $e ) );
	}

	/**
	 *	Builds Trace HTML Code from an Exception.
	 *	@access		private
	 *	@param		Exception	$exception	Exception
	 *	@return		string
	 */
	public static function buildTrace( Exception $exception )
	{
		$content	= "<p style=\"font-family: monospace; border: solid 1px #000000\"><span style=\"font-weight: bold; color: #000000;\">An exception was thrown :<br/></span>";
		$content	.= "Exception code : ".$exception->getCode()."<br/>";
		$content	.= "Exception message : ".$exception->getMessage()."<br/>";
		$content	.= "<span style=\"color: #0000FF;\">";
		$i = 0;
		foreach( $exception->getTrace() as $key => $trace )
			$content	.= self::buildTraceStep( $trace, $i++ );
		$content	.= "#$i {main}<br/>";
		$content	.= "</span></p>";
		return $content;
	}
	
	/**
	 *	Builds HTML Code of one Trace Step.
	 *	@access		private
	 *	@param		array		$trace		Trace Step Data
	 *	@param		int			$i			Trace Step Number
	 *	@return		string
	 */
	private static function buildTraceStep( $trace, $i )
	{
		$indent		= str_repeat( "&nbsp;", 2 + strlen( $i ) );
		$content	= "#$i ".$trace["file"]."(".$trace["line"]."): <br/>";
		if( array_key_exists( "class", $trace ) && array_key_exists( "type", $trace ) )
			$content	.= $indent.$trace["class"].$trace["type"];
		if( array_key_exists( "function", $trace ) )
		{
			$content	.= $trace["function"]."(";
			if( array_key_exists( "args", $trace ) )
			{
				if( count( $trace['args'] ) )
				{
					$argList	= array();
					foreach( $trace["args"] as $argument )
					{
						$type	= gettype( $argument );
						$value	= $argument;
						if( $type == "boolean" )
							$argList[] = $type ? "true" : "false";
						else if( $type == "integer" || $type == "double")
						{
							if( settype( $value, "string" ) )
								$argList[] = strlen( $value ) <= 80 ? $value : substr( $value, 0, 17 )."...";
							else
								$argList[] = $type == "integer" ? "? integer ?" : "? double or float ?";
						}
						else if( $type == "string" )
							$argList[] = strlen( $value ) <= 78 ? '"'.$value.'"' : '"'.substr( $value, 0, 15 ).'..."';
						else if( $type == "array" )
							$argList[] = "Array: ".self::convertArrayToString( $argument );
						else if( $type == "object" )
							$argList[] = "Object: ".get_class( $argument );
						else if( $type == "resource" )
							$argList[] = "Resource";
						else if( $type == "NULL" )
							$argList[] = "null";
						else if( $type == "unknown type" )
							$argList[] = "? unknown type ?";
					}
					$content	.= "<br/>".$indent.$indent.implode( ",<br/>".$indent.$indent, $argList )."<br/>".$indent;
				}
			}			
			$content	.= ")<br/>";
		}
		return $content;
	}
	
	/**
	 *	Converts Array to String.
	 *	@access		private
	 *	@param		array		$array		Array to convert to String
	 *	@return		string
	 */
	private static function convertArrayToString( $array )
	{
		foreach( $array as $key => $value )
		{
			if( is_array( $value ) )
				$value	= self::convertArrayToString( $value );
			$list[]	= $key.":".$value;
		}
		return "(".implode( ", ", $list ).")";
	}
}
?>