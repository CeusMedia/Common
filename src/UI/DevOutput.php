<?php
/**
 *	Output Methods for Developement.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Output Methods for Developement.
 *	@category		Library
 *	@package		CeusMedia_Common_UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class UI_DevOutput
{
	public const CHANNEL_AUTO		= 'auto';
	public const CHANNEL_HTML		= 'html';
	public const CHANNEL_TEXT		= 'text';
	public const CHANNEL_CONSOLE	= 'text';

	public const CHANNELS			= array(
		self::CHANNEL_AUTO,
		self::CHANNEL_HTML,
		self::CHANNEL_TEXT,
		self::CHANNEL_CONSOLE
	);

	public $channel;

	public static $defaultChannel	= self::CHANNEL_AUTO;

	public static $channelSettings	= array(
		self::CHANNEL_HTML	=> array(
			// Sign for Line Break
			'lineBreak'			=> "<br/>",
			// Sign for Spaces
			'indentSign'		=> "&nbsp;",
			// Sign for opening Notes
			'noteOpen'			=> "<em>",
			// Sign for closing Notes
			'noteClose'			=> "</em>",
			// Sign for opening boolean values and null
			'booleanOpen'		=> "<em>",
			// Sign for closing boolean values and null
			'booleanClose'		=> "</em>",
			// Sign for opening Highlights
			'highlightOpen'		=> "<b>",
			// Sign for closing Highlights
			'highlightClose'	=> "</b>",
			// Factor of Spaces for Indents
			'indentFactor'		=> 6,
			'stringTrimMask'	=> '&hellip;',
			'stringMaxLength'	=> 1500
		),
		self::CHANNEL_TEXT	=> array(
			// Sign for Line Break
			'lineBreak'			=> PHP_EOL,
			// Sign for Spaces
			'indentSign'		=> " ",
			// Sign for opening Notes
			'noteOpen'			=> "'",
			// Sign for closing Notes
			'noteClose'			=> "'",
			// Sign for opening boolean values and null
			'booleanOpen'		=> "",
			// Sign for closing boolean values and null
			'booleanClose'		=> "",
			// Sign for opening Highlights
			'highlightOpen'		=> "",
			// Sign for closing Highlights
			'highlightClose'	=> "",
			// Factor of Spaces for Indents
			'indentFactor'		=> 2,
			'stringTrimMask'	=> '...',
			'stringMaxLength'	=> 50
		)
	);

	protected $settings;

	/**
	 *	Constructur.
	 *	@access		public
	 *	@param		string		$channel		Selector for Channel of Output
	 *	@return		void
	 */
	public function __construct( $channel = NULL )
	{
		$channel	= $channel ? $channel : self::$defaultChannel;
		$this->setChannel( $channel );
	}

	public function getSettings(): array
	{
		return self::$channelSettings[$this->channel];
	}

	/**
	 *	Returns whitespaces.
	 *	@access		public
	 *	@param		int			$offset		amount of space
	 *	@param		string		$sign		Space Sign
	 *	@param		int			$factor		Space Factor
	 *	@return		string
	 */
	public function indentSign( $offset, $sign = NULL, $factor = NULL )
	{
		extract( $this->getSettings() );
		$sign	= $sign ? $sign : $indentSign;
		$factor	= $factor ? $factor : $indentFactor;
		return str_repeat( $sign, $offset * $factor );
	}

	/**
	 *	Prints out an Array.
	 *	@access		public
	 *	@param		array		$array		Array variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string		$key		Element Key Name
	 *	@param		string		$sign		Space Sign
	 *	@param		int			$factor		Space Factor
	 *	@return		void
	 */
	public function printArray( $array, $offset = 0, $key = NULL, $sign = NULL, $factor = NULL )
	{
		if( is_array( $array ) )
		{
			extract( $this->getSettings() );
			$space = $this->indentSign( $offset, $sign, $factor );
			if( $key !== NULL )
				echo $space."[A] ".$key.$lineBreak;
			foreach( $array as $key => $value )
			{
				if( is_array( $value ) && count( $value ) )
					$this->printArray( $value, $offset + 1, $key, $sign, $factor );
				else
					$this->printMixed( $value, $offset + 1, $key, $sign, $factor );
			}
		}
		else
			$this->printMixed( $array, $offset, $key, $sign, $factor );
	}

	/**
	 *	Prints out a boolean variable.
	 *	@access		public
	 *	@param		bool		$bool		boolean variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string		$key		Element Key Name
	 *	@param		string		$sign		Space Sign
	 *	@param		int			$factor		Space Factor
	 *	@return		void
	 */
	public function printBoolean( $bool, $offset = 0, $key = NULL, $sign = NULL, $factor = NULL )
	{
		if( is_bool( $bool ) )
		{
			extract( $this->getSettings() );
			$key = ( $key !== NULL ) ? $key." => " : "";
			$space = $this->indentSign( $offset, $sign, $factor );
			echo $space."[B] ".$key.$booleanOpen.( $bool ? "TRUE" : "FALSE" ).$booleanClose.$lineBreak;
		}
		else
			$this->printMixed( $bool, $offset, $key, $sign, $factor );
	}

	/**
	 *	Prints out an Double variable.
	 *	@access		public
	 *	@param		double		$double		double variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string		$key		Element Key Name
	 *	@param		string		$sign		Space Sign
	 *	@param		int			$factor		Space Factor
	 *	@return		void
	 */
	public function printDouble( $double, $offset = 0, $key = NULL, $sign = NULL, $factor = NULL )
	{
		return $this->printFloat( $double, $offset, $key, $sign,$factor );
	}

	/**
	 *	Prints out an Float variable.
	 *	@access		public
	 *	@param		float		$float		float variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string		$key		Element Key Name
	 *	@param		string		$sign		Space Sign
	 *	@param		int			$factor		Space Factor
	 *	@return		void
	 */
	public function printFloat( $float, $offset = 0, $key = NULL, $sign = NULL, $factor = NULL )
	{
		if( is_float( $float ) )
		{
			extract( $this->getSettings() );
			$key = ( $key !== NULL ) ? $key." => " : "";
			$space = $this->indentSign( $offset, $sign, $factor );
			echo $space."[F] ".$key.$float.$lineBreak;
		}
		else
			$this->printMixed( $float, $offset, $key, $sign, $factor );
	}

	/**
	 *	Prints out an Integer variable.
	 *	@access		public
	 *	@param		int			$integer	Integer variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string		$key		Element Key Name
	 *	@param		string		$sign		Space Sign
	 *	@param		int			$factor		Space Factor
	 *	@return		void
	 */
	public function printInteger( $integer, $offset = 0, $key = NULL, $sign = NULL, $factor = NULL )
	{
		if( is_int( $integer ) )
		{
			extract( $this->getSettings() );
			$key = ( $key !== NULL ) ? $key." => " : "";
			$space = $this->indentSign( $offset, $sign, $factor );
			echo $space."[I] ".$key.$integer.$lineBreak;
		}
		else
			$this->printMixed( $integer, $offset, $key, $sign, $factor );
	}

	/**
	 *	Prints out a variable as JSON.
	 *	@access		public
	 *	@param		mixed		$mixed		variable of every kind to print out
	 *	@param		string		$sign		Space Sign
	 *	@param		int			$factor		Space Factor
	 *	@param		boolean		$return		Flag: Return output instead of printing it
	 *	@return		void
	 */
	public function printJson( $mixed, $sign = NULL, $factor = NULL, $return = FALSE )
	{
		if( $return )
			ob_start();
		extract( $this->getSettings() );
		$o	= new UI_DevOutput();
		echo $lineBreak;
		$space	= $this->indentSign( 1, $sign, $factor );
		$json	= ADT_JSON_Formater::format( $mixed );
		$json	= str_replace( "\n", $lineBreak, $json );
		$json	= str_replace( "  ", $space, $json );
		echo $json;
		if( $return )
			return ob_get_clean();
	}

	/**
	 *	Prints out a variable by getting Type and using a suitable Method.
	 *	@access		public
	 *	@param		mixed		$mixed		variable of every kind to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string		$key		Element Key Name
	 *	@param		string		$sign		Space Sign
	 *	@param		int			$factor		Space Factor
	 *	@param		boolean		$return		Flag: Return output instead of printing it
	 *	@return		void
	 */
	public function printMixed( $mixed, $offset = 0, $key = NULL, $sign = NULL, $factor = NULL, $return = FALSE )
	{
		if( $return )
			ob_start();
		if( is_object( $mixed ) || gettype( $mixed ) == "object" )
			$this->printObject( $mixed, $offset, $key, $sign, $factor );
		else if( is_array( $mixed ) )
			$this->printArray( $mixed, $offset, $key, $sign, $factor );
		else if( is_string( $mixed ) )
			$this->printString( $mixed, $offset, $key, $sign, $factor );
		else if( is_int($mixed ) )
			$this->printInteger( $mixed, $offset, $key, $sign, $factor );
		else if( is_float($mixed ) )
			$this->printFloat( $mixed, $offset, $key, $sign, $factor );
		else if( is_double( $mixed ) )
			$this->printDouble( $mixed, $offset, $key, $sign, $factor );
		else if( is_resource( $mixed ) )
			$this->printResource( $mixed, $offset, $key, $sign, $factor );
		else if( is_bool($mixed ) )
			$this->printBoolean( $mixed, $offset, $key, $sign, $factor );
		else if( $mixed === NULL )
			$this->printNull( $mixed, $offset, $key, $sign, $factor );
		if( $return )
			return ob_get_clean();
	}

	/**
	 *	Prints out NULL.
	 *	@access		public
	 *	@param		NULL		$null		boolean variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string		$key		Element Key Name
	 *	@param		string		$sign		Space Sign
	 *	@param		int			$factor		Space Factor
	 *	@return		void
	 */
	public function printNull( $null, $offset = 0, $key = NULL, $sign = NULL, $factor = NULL )
	{
		if( $null === NULL )
		{
			extract( $this->getSettings() );
			$key = ( $key !== NULL ) ? $key." => " : "";
			$space = $this->indentSign( $offset, $sign, $factor );
			echo $space."[N] ".$key.$booleanOpen."NULL".$booleanClose.$lineBreak;
		}
		else
			$this->printMixed( $null, $offset, $key, $sign, $factor );
	}

	/**
	 *	Prints out a Object.
	 *	@access		public
	 *	@param		mixed		$object		Object variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string		$key		Element Key Name
	 *	@param		string		$sign		Space Sign
	 *	@param		int			$factor		Space Factor
	 *	@return		void
	 */
	public function printObject( $object, $offset = 0, $key = NULL, $sign = NULL, $factor = NULL )
	{
		if( is_object( $object ) || gettype( $object ) == "object" )
		{
			extract( $this->getSettings() );
			$ins_key	= ( $key !== NULL ) ? $key." -> " : "";
			$space		= $this->indentSign( $offset, $sign, $factor );
			echo $space."[O] ".$ins_key."".$highlightOpen.get_class( $object ).$highlightClose.$lineBreak;
			$vars		= get_object_vars( $object );
			foreach( $vars as $key => $value )
			{
				if( is_object( $value ) )
					$this->printObject( $value, $offset + 1, $key, $sign, $factor );
				else if( is_array( $value ) )
					$this->printArray( $value, $offset + 1, $key, $sign, $factor );
				else
					$this->printMixed( $value, $offset + 1, $key, $sign, $factor );
			}
		}
		else
			$this->printMixed( $object, $offset, $key, $sign, $factor );
	}

	/**
	 *	Prints out a Resource.
	 *	@access		public
	 *	@param		mixed		$object		Object variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string		$key		Element Key Name
	 *	@param		string		$sign		Space Sign
	 *	@param		int			$factor		Space Factor
	 *	@return		void
	 */
	public function printResource( $resource, $offset = 0, $key = NULL, $sign = NULL, $factor = NULL )
	{
		if( is_resource( $resource ) )
		{
			extract( $this->getSettings() );
			$key	= ( $key !== NULL ) ? $key." => " : "";
			$space	= $this->indentSign( $offset, $sign, $factor );
			echo $space."[R] ".$key.$resource.$lineBreak;
		}
		else
			$this->printMixed( $object, $offset, $key, $sign, $factor );
	}

	/**
	 *	Prints out a String variable.
	 *	@access		public
	 *	@param		string		$string		String variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string		$key		Element Key Name
	 *	@param		string		$sign		Space Sign
	 *	@param		int			$factor		Space Factor
	 *	@return		void
	 */
	public function printString( $string, $offset = 0, $key = NULL, $sign = NULL, $factor = NULL )
	{
		if( is_string( $string ) )
		{
			extract( $this->getSettings() );
			$key = ( $key !== NULL ) ? $key." => " : "";
			$space = $this->indentSign( $offset, $sign, $factor );
			if( $lineBreak != "\n" )
				$string	= htmlspecialchars( $string );
			if( strlen( $string > $stringMaxLength ) )
				$string	= Alg_Text_Trimmer::trimCentric( $string, $stringMaxLength, $stringTrimMask );
			echo $space."[S] ".$key.$string.$lineBreak;
		}
		else
			$this->printMixed( $string, $offset, $key, $sign, $factor );
	}

	/**
	 *	Prints out a String and Parameters.
	 *	@access		public
	 *	@param		string		$text		String to print out
	 *	@param		array		$parameters	Associative Array of Parameters to append
	 *	@return		void
	 */
	public function remark( $text, $parameters = array() )
	{
		$param	= "";
		if( is_array( $parameters ) && count( $parameters ) )
		{
			$param	= array();
			foreach( $parameters as $key => $value )
			{
				if( is_int( $key ) )
					$param[]	= $value;
				else
					$param[]	= $key." -> ".$value;
			}
			$param	= ": ".implode( " | ", $param );
		}
		echo $text.$param;
	}

	public function getChannel()
	{
		return $this->channel;
	}

	/**
	 *	Sets output channel type.
	 *	Auto mode assumes HTML at first and will fall back to Console if detected.
	 *	@access		public
	 *	@param		string		$channel		Type of channel (auto, console, html);
	 *	@return		void
	 *	@throws		OutOfRangeException			if an invalid channel type is to be set
	 */
	public function setChannel( $channel = NULL )
	{
		if( !is_string( $channel ) )
			$channel	= self::CHANNEL_AUTO;
		$channel	= strtolower( $channel );
		if( !in_array( $channel, self::CHANNELS ) )
			throw new OutOfRangeException( 'Channel type "'.$channel.'" is not supported' );
		if( $channel === self::CHANNEL_AUTO ){
			$channel	= self::CHANNEL_HTML;
			if( getEnv( 'PROMPT' ) || getEnv( 'SHELL' ) || $channel == "console" )
				$channel	= self::CHANNEL_TEXT;
		}
		$this->channel	= $channel;
	}

	public static function setDefaultChannel( $channel )
	{
		if( !in_array( $channel, self::CHANNELS ) )
			throw new OutOfRangeException( 'Channel type "'.$channel.'" is not supported' );
		self::$defaultChannel	= $channel;
	}

	public function showDOM( $node, $offset = 0 )
	{
	//	remark( $node->nodeType." [".$node->nodeName."]" );
	//	remark( $node->nodeValue );
		$o	= str_repeat( "&nbsp;", $offset * 2 );
		switch( $node->nodeType )
		{
			case XML_ELEMENT_NODE:
				remark( $o."[".$node->nodeName."]" );
				foreach( $node->attributes as $map )
					$this->showDOM( $map, $offset+1 );
				foreach( $node->childNodes as $child )
					$this->showDOM( $child, $offset+1 );
				break;
			case XML_ATTRIBUTE_NODE:
				remark( $o.$node->nodeName."->".$node->textContent );
				break;
			case XML_TEXT_NODE:
				if(!(trim($node->nodeValue) == ""))
					remark( $o."#".$node->nodeValue );
				break;
		}
	}
}

/**
 *	Prints out Code formatted with Tag CODE
 *	@access		public
 *	@param		string		$string	Code to print out
 *	@return		void
 */
function code( $string )
{
	echo "<code>".$string."</code>";
}

/**
 *	Prints given content only if flag CM_SHOW_DEV is on (or if forced).
 *	@access		public
 *	@param		string		$content	Dev Info to show
 *	@param		bool		$force		Flag: force display
 *	@return		void
 */
function dev( $content, $force = FALSE, $flagKey = 'CM_SHOW_DEV' )
{
	if( !( !$force && !( defined( $flagKey ) && constant( $flagKey ) ) ) )
		echo $content;
}

/**
 *	Prints out any variable with print_r in xmp
 *	@access		public
 *	@param		mixed		$variable	Variable to print dump of
 *	@param		boolean		$return		Flag: Return output instead of printing it
 *	@return		void
 */
function dump( $variable, $return = FALSE )
{
	ob_start();
	print_r( $variable );
	if( $return )
		ob_start();
	xmp( ob_get_clean() );
	if( $return )
		return ob_get_clean();
}

/**
 *	Prints out Code formatted with Tag "pre".
 *	@access		public
 *	@param		string		$string		Code to print out
 *	@return		mixed		String for Dump Mode or void
 */
function pre( $string, $dump = FALSE )
{
	ob_start();
	echo "<pre>".htmlentities( $string, ENT_QUOTES, 'UTF-8' )."</pre>";
	return $dump ? ob_get_clean() : print( ob_get_clean() );
}

/**
 *	Global function for UI_DevOutput::printJson.
 *	@access		public
 *	@param		mixed		$mixed		variable to print out
 *	@param		string		$sign		Space Sign
 *	@param		int			$factor		Space Factor
 *	@param		boolean		$return		Flag: Return output instead of printing it
 *	@return		void
 */
function print_j( $mixed, $sign = NULL, $factor = NULL, $return = FALSE )
{
	$o		= new UI_DevOutput();
	$break	= UI_DevOutput::$channelSettings[$o->channel]['lineBreak'];
	if( $return )
		return $o->printJson( $mixed, $sign, $factor, TRUE );
	echo $break;
	$o->printJson( $mixed, 0, $sign, $factor );
}

/**
 *	Global function for UI_DevOutput::printMixed.
 *	@access		public
 *	@param		mixed		$mixed		variable to print out
 *	@param		string		$sign		Space Sign
 *	@param		int			$factor		Space Factor
 *	@param		boolean		$return		Flag: Return output instead of printing it
 *	@return		void
 */
function print_m( $mixed, $sign = NULL, $factor = NULL, $return = FALSE, $channel = NULL )
{
	$o		= new UI_DevOutput();
	if( $channel )
		$o->setChannel( $channel );
	$break	= UI_DevOutput::$channelSettings[$o->channel]['lineBreak'];
	if( $return )
		return $break.$o->printMixed( $mixed, 0, NULL, $sign, $factor, $return );
	echo $break;
	$o->printMixed( $mixed, 0, NULL, $sign, $factor, $return );
}

/**
 *	Prints out all global registered variables with UI_DevOutput::print_m
 *	@access		public
 *	@param		string		$sign		Space Sign
 *	@param		int			$factor		Space Factor
 *	@return		void
 */
function print_globals( $sign = NULL, $factor = NULL )
{
	$globals	= $GLOBALS;
	unset( $globals['GLOBALS'] );
	print_m( $globals, $sign, $factor );
}

/**
 *	Prints out a String after Line Break.
 *	@access		public
 *	@param		string		$text		String to print out
 *	@param		array		$parameters	Associative Array of Parameters to append
 *	@param		bool		$break		Flag: break Line before Print
 *	@return		void
 */
function remark( $text = "", $parameters = array(), $break = TRUE )
{
	$o = new UI_DevOutput();
	if( $break )
		echo UI_DevOutput::$channelSettings[$o->channel]['lineBreak'];
	$o->remark( $text, $parameters );
}

/**
 *	Prints out a variable with UI_DevOutput::print_m
 *	@access		public
 *	@param		mixed		$mixed		variable to print out
 *	@param		string		$sign		Space Sign
 *	@param		int			$factor		Space Factor
 *	@return		void
 */
function show( $mixed, $sign = NULL, $factor = NULL )
{
	print_m( $mixed, $sign, $factor );
}

function showDOM( $node )
{
	$o = new UI_DevOutput();
	$o->showDOM( $node );
}

/**
 *	Prints out Code formatted with Tag XMP
 *	@access		public
 *	@param		string		$string		Code to print out
 *	@return		mixed		String for Dump Mode or void
 */
function xmp( $string, $dump = FALSE )
{
	$dev	= new UI_DevOutput();
	if( $dump )
		ob_start();
	if( $dev->channel === UI_DevOutput::CHANNEL_TEXT )
		echo $string."\n";
	else
		echo "<xmp>".$string."</xmp>";
	if( $dump )
		return ob_get_clean();
}
