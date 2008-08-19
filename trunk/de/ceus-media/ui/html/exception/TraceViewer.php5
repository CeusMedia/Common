<?php
/**
 *	Visualisation of Exception Stack Trace.
 *	@package		ui.html.exception
 *	@author			Romain Boisnard
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.04.2008
 *	@version		0.1
 */
/**
 *	Visualisation of Exception Stack Trace.
 *	@package		ui.html.exception
 *	@author			Romain Boisnard
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.04.2008
 *	@version		0.1
 */
class UI_HTML_Exception_TraceViewer
{
	/**
	 *	Constructor, prints Exception Trace.
	 *	Break Modes:
	 *	0	show every Trace Step in one Line
	 *	1	break Function Call after File Name
	 *	2	break on every Argument
	 *	@access		public
	 *	@param		Exception	$exception		Exception
	 *	@param		int			$breakMode		Mode of Line Breaks (0-one line|1-break line|2-break arguments)
	 *	@return		void
	 */
	public function __construct( $e, $breakMode = 2 )
	{
		print( $this->buildTrace( $e, $breakMode ) );
	}

	/**
	 *	Builds Trace HTML Code from an Exception.
	 *	Break Modes:
	 *	0	show every Trace Step in one Line
	 *	1	break Function Call after File Name
	 *	2	break on every Argument
	 *	@access		private
	 *	@param		Exception	$exception		Exception
	 *	@param		int			$breakMode		Mode of Line Breaks (0-one line|1-break line|2-break arguments)
	 *	@return		string
	 */
	public static function buildTrace( Exception $exception, $breakMode = 0 )
	{
		$content	= '<p style="font-family: monospace;"><span style="font-weight: bold; color: #000000;">An exception was thrown.<br/></span>';
		$content	.= "Type: ".get_class( $exception )."<br/>";
		$content	.= "Message: ".$exception->getMessage()."<br/>";
		$content	.= "Code: ".$exception->getCode()."<br/>";
		$content	.= '<span style="color: #0000FF;">';
		$i	= 0;
		$j	= 0;
		foreach( $exception->getTrace() as $key => $trace )
		{
			$step	= self::buildTraceStep( $trace, $i++, $j, $breakMode );
			if( $step )
			{
				$content	.= $step;
				$j++;
			}
		}
		$content	.= "#$j {main}<br/>";
		$content	.= "</span></p>";
		return $content;
	}
	
	/**
	 *	Builds HTML Code of one Trace Step.
	 *	@access		private
	 *	@param		array		$trace		Trace Step Data
	 *	@param		int			$i			Trace Step Number
	 *	@param		int			$breakMode		Mode of Line Breaks (0-one line|1-break line|2-break arguments)
	 *	@return		string
	 */
	private static function buildTraceStep( $trace, $i, $j, $breakMode = 0 )
	{
		if( $j == 0 )
			if( !isset( $trace['class'] ) && isset( $trace['function'] ) )
				if( in_array( $trace['function'], array( "eval", "throwException" ) ) )		//  Exception was thrown using throwException
					return "";

		$indent		= " ";
		$break		= "";
		if( $breakMode == 2 )
		{
			$indent		= str_repeat( "&nbsp;", 2 + strlen( $j ) );
			$break		= "<br/>".$indent;
		}
		$funcBreak	= $break;
		if( $breakMode == 1 )
			$funcBreak	= "<br/>";

		$content	= "#$j ";
		if( isset( $trace["file"] ) )
			$content	.= $trace["file"]."(".$trace["line"]."): ".$funcBreak;
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
						$arg	= ucFirst( $type ).": ";
						switch( $type )
						{
							case 'boolean':
								$arg	.= $type ? "TRUE" : "FALSE";
								break;
							case 'integer':
							case 'double':
							case 'float':
								if( settype( $value, "string" ) )
									$arg	.= strlen( $value ) <= 78 ? $value : substr( $value, 0, 75 )."...";
								else
									$arg	.= $type == "integer" ? "? integer ?" : "? double or float ?";
								break;
							case 'string':
								$arg	.= strlen( $value ) <= 78 ? '"'.$value.'"' : '"'.substr( $value, 0, 75 ).'..."';
								break;
							case 'array':
								$arg	.= "Array";#self::convertArrayToString( $argument, $breakMode );
								break;
							case 'object':
								$arg	.= get_class( $argument );
								break;
							case 'NULL':
								break;
							case 'resource':
								$arg	.= (string) $value;
							default:
								$arg	.= (string) $value;
								break;
						}
						$argList[]	= $arg;
					}
					$argBreak	= $breakMode ? $break.$indent.$indent : " ";
					$arguments	= implode( ",".$argBreak, $argList );
					$content	.= $argBreak.$arguments.$break;
				}
			}			
			$content	.= ")<br/>";
		}
		return $content;
	}
	
	/**
	 *	Converts Array to String.
	 *	@access		private
	 *	@param		array		$array			Array to convert to String
	 *	@return		string
	 */
	private static function convertArrayToString( $array, $breakMode )
	{
		foreach( $array as $key => $value )
		{
			if( is_array( $value ) )
				$value	= self::convertArrayToString( $value );
			$list[]	= $key.":".$value;
		}
#		if( $breakMode == 2 )
#		{
#			$indent	= str_repeat( "&nbsp;", 3 );
#			return "(<br/>".$indent.$indent.$indent.$indent.implode( ",<br/>".$indent.$indent.$indent.$indent, $list )."<br/>".$indent.$indent.$indent.")";
#		}
		return "(".implode( ", ", $list ).")";
	}
}
?>