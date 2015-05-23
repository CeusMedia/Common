<?php
/**
 *	Visualisation of Exception Stack Trace.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		UI.HTML.Exception
 *	@author			Romain Boisnard
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.04.2008
 *	@version		$Id$
 */
/**
 *	Visualisation of Exception Stack Trace.
 *	@category		cmClasses
 *	@package		UI.HTML.Exception
 *	@author			Romain Boisnard
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.04.2008
 *	@version		$Id$
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

	protected static function blockquote( $content )
	{
		return UI_HTML_Tag::create( 'blockquote', $content, array( 'style' => 'margin: 0px 30px' ) );
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
		$content	= 'Type: '.get_class( $exception ).'<br/>';
		$content	.= 'Message: '.$exception->getMessage().'<br/>';
		$content	.= 'Code: '.$exception->getCode()."<br/>";
		$content	.= 'File: '.self::trimRootPath( $exception->getFile() ).'<br/>';
		$content	.= 'Line: '.$exception->getLine().'<br/>';
		$content	.= 'Trace:<br/><span style="color: #0000FF;">';
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
		$content	.= '#'.$j.' {main}<br/></span>';
		if( method_exists( $exception, 'getPrevious' ) && $exception->getPrevious() )
		{
			$view	= self::buildTrace( $exception->getPrevious(), $breakMode );
			$block	= UI_HTML_Tag::create( 'blockquote', $view );
			$content	.= 'Previous: '.$block.'<br/>';
		}
		return UI_HTML_Tag::create( 'p', $content, array( 'style' => "font-family: monospace" ) );
		 $content;
	}
	
	/**
	 *	Builds HTML Code of one Trace Step.
	 *	@access		private
	 *	@static
	 *	@param		array		$trace		Trace Step Data
	 *	@param		int			$i			Trace Step Number
	 *	@param		int			$breakMode		Mode of Line Breaks (0-one line|1-break line|2-break arguments)
	 *	@return		string
	 */
	private static function buildTraceStep( $trace, $i, $j, $breakMode = 0 )
	{
		if( $j == 0 )
			if( isset( $trace['function'] ) )
				if( in_array( $trace['function'], array( "eval", "throwException" ) ) )		//  Exception was thrown using throwException
					return "";

		$indent		= " ";
		$break		= "";
		if( $breakMode == 2 )
		{
			$indent		= str_repeat( "&nbsp;", 2 + strlen( $j ) );
			$break		= "<br/>".$indent;
		}
		if( $breakMode == 3 )
		{
			$break		= "";#_2_<br/>";
		}
		$funcBreak	= $break;
		if( $breakMode == 1 )
			$funcBreak	= "<br/>";

		$content	= "#$j ";
		if( isset( $trace["file"] ) )
			$content	.= self::trimRootPath( $trace["file"] )."(".$trace["line"]."): ".$funcBreak;
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
						$type	= ucFirst( gettype( $argument ) );
						$string	= self::convertArgumentToString( $argument, $breakMode );
						$argList[]	= $type.": ".$string;
					}
					$argBreak	= $breakMode ? $break.$indent.$indent : " ";
					$arguments	= implode( ",".$argBreak, $argList );
					if( $breakMode == 3 )
					{
						$arguments	= self::blockquote( implode( ",<br/>", $argList ) );
					}
					$content	.= $argBreak.$arguments.$break;
				}
			}
			$content	.= ")<br/>";
		}
		return $content;
	}

	protected static function convertArgumentToString( $argument, $breakMode, $level = 0 )
	{
		$type	= gettype( $argument );
		$value	= $argument;
		switch( $type )
		{
			case 'boolean':
				return $type ? "TRUE" : "FALSE";
			case 'integer':
			case 'double':
			case 'float':
			case 'real':
				return htmlentities( (string) $value );
			case 'string':
				if( strlen( $value ) > 70 )
					$value	= Alg_Text_Trimmer::trimCentric( $value, 70, '...' );
				return '"'.htmlentities($value ).'"';
			case 'array':
				return self::convertArrayToString( $argument, $breakMode, $level );
			case 'object':
				return get_class( $argument );
			case 'NULL':
				break;
			case 'resource':
				return htmlentities( (string) $value );
			default:
				return htmlentities( (string) $value );
		}
	}

	/**
	 *	Converts Array to String.
	 *	@access		protected
	 *	@static
	 *	@param		array		$array			Array to convert to String
	 *	@return		string
	 */
	protected static function convertArrayToString( $array, $breakMode, $level = 1 )
	{
		$list = array();
		foreach( $array as $key => $value )
		{
			$string	= self::convertArgumentToString( $value, $breakMode, $level+1 );
			$list[]	= $key.":".$string;
		}
		$block	= implode( ", ", $list );
		if( $breakMode == 2 )
		{
			$level	= str_repeat( "&nbsp;", 3 * $level );
			$indent	= str_repeat( "&nbsp;", 3 );
			$list	= implode( ",<br/>".$level.$indent.$indent.$indent.$indent, $list );
			$block	= "<br/>".$level.$indent.$indent.$indent.$indent.$list."<br/>".$level.$indent.$indent.$indent;
		}
		if( $breakMode == 3 )
		{
			$block	= self::blockquote( implode( ',<br/>', $list ) );
		}
		return '{'.$block.'}';
	}

	/**
	 *	Removes Document Root in File Names.
	 *	@access		protected
	 *	@static
	 *	@param		string		$fileName		File Name to clear
	 *	@return		string
	 */
	protected static function trimRootPath( $fileName )
	{
		$rootPath	= isset( $_SERVER['DOCUMENT_ROOT'] ) ? $_SERVER['DOCUMENT_ROOT'] : "";
		if( !$rootPath || !$fileName )
			return;
		$fileName	= str_replace( '\\', "/", $fileName );
		$cut		= substr( $fileName, 0, strlen( $rootPath ) );
		if( $cut == $rootPath )
			$fileName	= substr( $fileName, strlen( $rootPath ) );
		return $fileName;
	}
}
?>