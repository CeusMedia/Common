<?php
/**
 *	Visualisation of Exception Stack Trace.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		exception
 *	@author			Romain Boisnard
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.04.2008
 *	@version		0.1
 */
/**
 *	Visualisation of Exception Stack Trace.
 *	@category		cmClasses
 *	@package		exception
 *	@author			Romain Boisnard
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.04.2008
 *	@version		0.1
 */
class Exception_TraceViewer
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
	public function __construct( $e, $breakMode )
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
	 *	@static
	 *	@param		Exception	$exception		Exception
	 *	@param		int			$breakMode		Mode of Line Breaks (0-one line|1-break line|2-break arguments)
	 *	@return		string
	 */
	public static function buildTrace( Exception $exception, $breakMode = 0 )
	{
		$content	= '<p style="font-family: monospace;"><span style="font-weight: bold; color: #000000;">An exception was thrown :<br/></span>';
		$content	.= "Type: ".get_class( $exception )."<br/>";
		$content	.= "Message: ".$exception->getMessage()."<br/>";
		$content	.= "Code: ".$exception->getCode()."<br/>";
		$content	.= '<span style="color: #0000FF;">';
		$i = 0;
		foreach( $exception->getTrace() as $key => $trace )
			$content	.= self::buildTraceStep( $trace, $i++, $breakMode );
		$content	.= "#$i {main}<br/>";
		$content	.= "</span></p>";
		return $content;
	}
	
	/**
	 *	Builds HTML Code of one Trace Step.
	 *	@access		private
	 *	@static
	 *	@param		array		$trace			Trace Step Data
	 *	@param		int			$i				Trace Step Number
	 *	@param		int			$breakMode		Mode of Line Breaks (0-one line|1-break line|2-break arguments)
	 *	@return		string
	 */
	private static function buildTraceStep( $trace, $i, $breakMode = 0 )
	{
		$indent		= " ";
		$break		= "";
		if( $breakMode == 2 )
		{
			$indent		= str_repeat( "&nbsp;", 2 + strlen( $i ) );
			$break		= "<br/>".$indent;
		}
		$funcBreak	= $break;
		if( $breakMode == 1 )
			$funcBreak	= "<br/>";

		$content	= "#$i ".$trace["file"]."(".$trace["line"]."): ".$funcBreak;
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
								$arg	.= self::convertArrayToString( $argument );
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
					$argBreak	= $breakMode ? $break.$indent : " ";
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
	 *	@static
	 *	@param		array		$array			Array to convert to String
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