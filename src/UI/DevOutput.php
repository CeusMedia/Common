<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Output Methods for Developement.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
namespace CeusMedia\Common\UI;

use CeusMedia\Common\ADT\JSON\Formater as JsonFormater;
use CeusMedia\Common\Alg\Text\Trimmer as TextTrimmer;
use OutOfRangeException;

/**
 *	Output Methods for Development.
 *	@category		Library
 *	@package		CeusMedia_Common_UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class DevOutput
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
	 *	Constructor.
	 *	@access		public
	 *	@param		string|NULL		$channel		Selector for Channel of Output
	 *	@return		void
	 */
	public function __construct( string $channel = NULL )
	{
		$channel	= $channel ?? self::$defaultChannel;
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
	 *	@param		string|NULL	$sign		Space Sign
	 *	@param		int|NULL	$factor		Space Factor
	 *	@return		string
	 */
	public function indentSign( int $offset, ?string $sign = NULL, ?int $factor = NULL ): string
	{
		$settings	= (object) $this->getSettings();
		$sign		= $sign ?? $settings->indentSign;
		$factor		= $factor ?? $settings->indentFactor;
		return str_repeat( $sign, $offset * $factor );
	}

	/**
	 *	Prints out an Array.
	 *	@access		public
	 *	@param		array		$array		Array variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string|NULL	$key		Element Key Name
	 *	@param		string|NULL	$sign		Space Sign
	 *	@param		int|NULL	$factor		Space Factor
	 *	@return		void
	 */
	public function printArray( $array, int $offset = 0, ?string $key = NULL, ?string $sign = NULL, ?int $factor = NULL )
	{
		if( is_array( $array ) ){
			extract( $this->getSettings() );
			$space = $this->indentSign( $offset, $sign, $factor );
			if( $key !== NULL )
				echo $space."[A] ".$key.$lineBreak;
			foreach( $array as $key => $value ){
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
	 *	@param		string|NULL	$key		Element Key Name
	 *	@param		string|NULL	$sign		Space Sign
	 *	@param		int|NULL	$factor		Space Factor
	 *	@return		void
	 */
	public function printBoolean( $bool, int $offset = 0, ?string $key = NULL, ?string $sign = NULL, ?int $factor = NULL )
	{
		if( is_bool( $bool ) ){
			extract( $this->getSettings() );
			$key = ( $key !== NULL ) ? $key." => " : "";
			$space = $this->indentSign( $offset, $sign, $factor );
			echo $space."[B] ".$key.$booleanOpen.( $bool ? "TRUE" : "FALSE" ).$booleanClose.$lineBreak;
		}
		else
			$this->printMixed( $bool, $offset, $key, $sign, $factor );
	}

	/**
	 *	Prints out a Double variable.
	 *	@access		public
	 *	@param		double		$double		double variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string|NULL	$key		Element Key Name
	 *	@param		string|NULL	$sign		Space Sign
	 *	@param		int|NULL	$factor		Space Factor
	 *	@return		void
	 */
	public function printDouble( $double, int $offset = 0, ?string $key = NULL, ?string $sign = NULL, ?int $factor = NULL )
	{
		$this->printFloat( $double, $offset, $key, $sign,$factor );
	}

	/**
	 *	Prints out an Float variable.
	 *	@access		public
	 *	@param		float		$float		float variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string|NULL	$key		Element Key Name
	 *	@param		string|NULL	$sign		Space Sign
	 *	@param		int|NULL	$factor		Space Factor
	 *	@return		void
	 */
	public function printFloat( $float, int $offset = 0, ?string $key = NULL, ?string $sign = NULL, ?int $factor = NULL )
	{
		if( is_float( $float ) ){
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
	 *	@param		string|NULL	$key		Element Key Name
	 *	@param		string|NULL	$sign		Space Sign
	 *	@param		int|NULL	$factor		Space Factor
	 *	@return		void
	 */
	public function printInteger( $integer, int $offset = 0, ?string $key = NULL, ?string $sign = NULL, ?int $factor = NULL )
	{
		if( is_int( $integer ) ){
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
	 *	@return		string|NULL
	 */
	public function printJson( $mixed, ?string $sign = NULL, ?int $factor = NULL, bool $return = FALSE ): string
	{
		if( $return )
			ob_start();
		extract( $this->getSettings() );
		$o	= new DevOutput();
		echo $lineBreak;
		$space	= $this->indentSign( 1, $sign, $factor );
		$json	= JsonFormater::format( $mixed );
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
	 *	@param		string|NULL	$key		Element Key Name
	 *	@param		string|NULL	$sign		Space Sign
	 *	@param		int|NULL	$factor		Space Factor
	 *	@param		boolean		$return		Flag: Return output instead of printing it
	 *	@return		string|NULL
	 */
	public function printMixed( $mixed, int $offset = 0, ?string $key = NULL, ?string $sign = NULL, ?int $factor = NULL, bool $return = FALSE ): ?string
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
		return NULL;
	}

	/**
	 *	Prints out NULL.
	 *	@access		public
	 *	@param		NULL		$null		boolean variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string|NULL	$key		Element Key Name
	 *	@param		string|NULL	$sign		Space Sign
	 *	@param		int|NULL	$factor		Space Factor
	 *	@return		void
	 */
	public function printNull( $null, int $offset = 0, ?string $key = NULL, ?string $sign = NULL, ?int $factor = NULL )
	{
		if( $null === NULL ){
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
	 *	@param		string|NULL	$key		Element Key Name
	 *	@param		string|NULL	$sign		Space Sign
	 *	@param		int|NULL	$factor		Space Factor
	 *	@return		void
	 */
	public function printObject( $object, int $offset = 0, ?string $key = NULL, ?string $sign = NULL, ?int $factor = NULL )
	{
		if( is_object( $object ) || gettype( $object ) === "object" ){
			extract( $this->getSettings() );
			$ins_key	= ( $key !== NULL ) ? $key." -> " : "";
			$space		= $this->indentSign( $offset, $sign, $factor );
			echo $space."[O] ".$ins_key."".$highlightOpen.get_class( $object ).$highlightClose.$lineBreak;
			$vars		= get_object_vars( $object );
			foreach( $vars as $key => $value ){
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
	 *	@param		mixed		$resource	Object variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string|NULL	$key		Element Key Name
	 *	@param		string|NULL	$sign		Space Sign
	 *	@param		int|NULL	$factor		Space Factor
	 *	@return		void
	 */
	public function printResource( $resource, int $offset = 0, ?string $key = NULL, ?string $sign = NULL, ?int $factor = NULL )
	{
		if( is_resource( $resource ) ){
			extract( $this->getSettings() );
			$key	= ( $key !== NULL ) ? $key." => " : "";
			$space	= $this->indentSign( $offset, $sign, $factor );
			echo $space."[R] ".$key.$resource.$lineBreak;
		}
		else
			$this->printMixed( $resource, $offset, $key, $sign, $factor );
	}

	/**
	 *	Prints out a String variable.
	 *	@access		public
	 *	@param		string		$string		String variable to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string|NULL	$key		Element Key Name
	 *	@param		string|NULL	$sign		Space Sign
	 *	@param		int|NULL	$factor		Space Factor
	 *	@return		void
	 */
	public function printString( $string, int $offset = 0, ?string $key = NULL, ?string $sign = NULL, ?int $factor = NULL )
	{
		if( is_string( $string ) ){
			extract( $this->getSettings() );
			$key = ( $key !== NULL ) ? $key." => " : "";
			$space = $this->indentSign( $offset, $sign, $factor );
			if( $lineBreak !== "\n" )
				$string	= htmlspecialchars( $string );
			if( strlen( $string > $stringMaxLength ) )
				$string	= TextTrimmer::trimCentric( $string, $stringMaxLength, $stringTrimMask );
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
	public function remark( string $text, array $parameters = [] )
	{
		$param	= "";
		if( is_array( $parameters ) && count( $parameters ) ){
			$param	= [];
			foreach( $parameters as $key => $value ){
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
	 *	@param		string|NULL		$channel		Type of channel (auto, console, html);
	 *	@return		self
	 *	@throws		OutOfRangeException			if an invalid channel type is to be set
	 */
	public function setChannel( string $channel ): self
	{
		$channel	= strtolower( $channel );
		if( !in_array( $channel, self::CHANNELS ) )
			throw new OutOfRangeException( 'Channel type "'.$channel.'" is not supported' );
		if( $channel === self::CHANNEL_AUTO ){
			$channel	= self::CHANNEL_HTML;
			if( getEnv( 'PROMPT' ) || getEnv( 'SHELL' ) || $channel == "console" )
				$channel	= self::CHANNEL_TEXT;
		}
		$this->channel	= $channel;
		return $this;
	}

	public static function setDefaultChannel( string $channel )
	{
		if( !in_array( $channel, self::CHANNELS ) )
			throw new OutOfRangeException( 'Channel type "'.$channel.'" is not supported' );
		self::$defaultChannel	= $channel;
	}

	public function showDOM( $node, int $offset = 0 )
	{
	//	remark( $node->nodeType." [".$node->nodeName."]" );
	//	remark( $node->nodeValue );
		$o	= str_repeat( "&nbsp;", $offset * 2 );
		switch( $node->nodeType ){
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

