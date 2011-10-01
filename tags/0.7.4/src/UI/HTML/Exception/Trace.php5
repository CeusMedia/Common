<?php
/**
 *	Visualisation of Exception Stack Trace.
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Visualisation of Exception Stack Trace.
 *	@category		cmClasses
 *	@package		UI.HTML.Exception
 *	@uses			Alg_Text_Trimmer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.1
 *	@version		$Id$
 */
class UI_HTML_Exception_Trace
{
	protected $exception;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Exception	$exception		Exception
	 *	@return		void
	 */
	public function __construct( Exception $exception )
	{
		$this->exception	= $exception;
	}

	/**
	 *	Prints exception view.
	 *	@access		public
	 *	@param		Exception	$exception		Exception
	 *	@return		void
	 */
	public function display()
	{
		print self::render( $this->exception );
	}

	/**
	 *	Renders exception trace HTML code.
	 *	@access		private
	 *	@param		Exception	$exception		Exception
	 *	@return		string
	 */
	public function render()
	{
		$i	= 0;
		$j	= 0;
		$list	= array();
		foreach( $this->exception->getTrace() as $key => $trace )
		{
			$step	= self::renderTraceStep( $trace, $i++, $j );
			if( !$step )
				continue;
			$list[]	= UI_HTML_Tag::create( 'li', $step );
			$j++;
		}
		return UI_HTML_Tag::create( 'ol', implode( $list ), array( 'class' => 'trace' ) );
	}

	/**
	 *	Renders an argument.
	 *	@access		protected
	 *	@static
	 *	@param		array		$argument		Array to render
	 *	@return		string
	 */
	protected static function renderArgument( $argument )
	{
		switch( gettype( $argument ) )
		{
			case 'NULL':																			//  handle NULL
				return '<em>NULL</em>';
			case 'boolean':																			//  handle boolean
				return $argument ? "<em>TRUE</em>" : "<em>FALSE</em>";
			case 'array':																			//  handle array
				return self::renderArgumentArray( $argument );
			case 'object':																			//  handle object
				return get_class( $argument );
			default:																				//  handle integer/double/float/real/resource/string
				return self::secureString( (string) $argument );
		}
	}

	/**
	 *	Renders an argument array.
	 *	@access		protected
	 *	@static
	 *	@param		array		$array			Array to render
	 *	@return		string
	 */
	protected static function renderArgumentArray( $array )
	{
		$list	= array();
		foreach( $array as $key => $value )
		{
			$type	= self::renderArgumentType( $value );
			$string	= self::renderArgument( $value );
			$list[]	= UI_HTML_Tag::create( 'dt', $type." ".$key );
			$list[]	= UI_HTML_Tag::create( 'dd', $string );
		}
		$list	= UI_HTML_Tag::create( 'dl', implode( $list ) );
		$block	= UI_HTML_Tag::create( 'blockquote', $list );
		return '{'.$block.'}';
	}

	/**
	 *	Renders formatted argument type.
	 *	@access		protected
	 *	@static
	 *	@param		string		$argument		Argument to render type for
	 *	@return		string
	 */
	protected static function renderArgumentType( $argument )
	{
		$type	= ucFirst( strtolower( gettype( $argument ) ) );
		return UI_HTML_Tag::create( 'span', $type, array( 'class' => 'type' ) );
	}

	/**
	 *	Builds HTML Code of one Trace Step.
	 *	@access		private
	 *	@static
	 *	@param		array		$trace		Trace Step Data
	 *	@param		int			$i			Trace Step Number
	 *	@return		string
	 */
	private static function renderTraceStep( $trace, $i, $j )
	{
		if( $j == 0 )
			if( isset( $trace['function'] ) )
				if( in_array( $trace['function'], array( "eval", "throwException" ) ) )				//  Exception was thrown using throwException
					return "";

		$content	= "";
		if( isset( $trace["file"] ) )
			$content	.= self::trimRootPath( $trace["file"] )."(".$trace["line"]."): ";
		if( array_key_exists( "class", $trace ) && array_key_exists( "type", $trace ) )
			$content	.= $trace["class"].$trace["type"];
		if( array_key_exists( "function", $trace ) )
		{
			$block	= NULL;
			if( array_key_exists( "args", $trace ) && count( $trace['args'] ) )
			{
				$argList	= array();
				foreach( $trace["args"] as $argument )
				{
					$type	= self::renderArgumentType( $argument );
					$string	= self::renderArgument( $argument );
					$argList[]	= UI_HTML_Tag::create( 'dt', $type );
					$argList[]	= UI_HTML_Tag::create( 'dd', $string );
				}
				$argList	= UI_HTML_Tag::create( 'dl', implode( $argList ) );
				$block		= UI_HTML_Tag::create( 'blockquote', $argList );
			}
			$content	.= $trace["function"]."(".$block.')';
		}
#		else
#			die( print_m( $trace ) );
#			$content	.= $trace["function"]."(".$block.')';
		return $content;
	}

	/**
	 *	Applies filters on content string to avoid injections.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			String to secure
	 *	@param		integer		$maxLength		Number of characters to show at most
	 *	@param		string		$mask			Mask to show for cutted content
	 *	@return		string
	 */
	protected static function secureString( $string, $maxLength = 0, $mask = '&hellip;' )
	{
		if( $maxLength && strlen( $string ) > $maxLength )
			$value	= Alg_Text_Trimmer::trimCentric( $string, $maxLength, $mask );
//		$string	= addslashes( $string );
		$string	= htmlentities( $string );
		return $string;
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