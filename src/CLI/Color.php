<?php
namespace CeusMedia\Common\CLI;

class Color{

	private static $foregroundColors	= array(
		'black'			=> '0;30',
		'dark_gray'		=> '1;30',
		'red'			=> '0;31',
		'light_red'		=> '1;31',
		'green'			=> '0;32',
		'light_green'	=> '1;32',
		'brown'			=> '0;33',
		'yellow'		=> '1;33',
		'blue'			=> '0;34',
		'light_blue'	=> '1;34',
		'purple'		=> '0;35',
		'light_purple'	=> '1;35',
		'cyan'			=> '0;36',
		'light_cyan'	=> '1;36',
		'light_gray'	=> '0;37',
		'white'			=> '1;37',
	);

	private static $backgroundColors	= array(
		'black'			=> '40',
		'red'			=> '41',
		'green'			=> '42',
		'yellow'		=> '43',
		'blue'			=> '44',
		'magenta'		=> '45',
		'cyan'			=> '46',
		'light_gray'	=> '47',
	);

	public static $classes				= array(
		'error'			=> array( 'white', 'red' ),
		'warning'		=> array( 'white', 'yellow' ),
		'info'			=> array( 'white', 'blue' ),
		'success'		=> array( 'white', 'green' ),
	);

	public function applyClass( $string, $class ){
		if( isset( static::$classes[$class] ) ){
			$colors	= static::$classes[$class];
			$string	= $this->colorize( $string, $colors[0], $colors[1] );
		}
		return $string;
	}

	public function asError( $string ){
		return $this->applyClass( $string, 'error' );
	}

	public function asWarning( $string ){
		return $this->applyClass( $string, 'warning' );
	}

	public function asInfo( $string ){
		return $this->applyClass( $string, 'info' );
	}

	public function asSuccess( $string ){
		return $this->applyClass( $string, 'success' );
	}

	public function bold( $string ){
		return "\033[1m".$string."\033[0m";
	}

	public function light( $string ){
		return "\033[2m".$string."\033[0m";
	}

	public function italic( $string ){
		return "\033[3m".$string."\033[0m";
	}

	public function underscore( $string ){
		return "\033[4m".$string."\033[0m";
	}

	// Returns colored string
	public function colorize( $string, $foregroundColor = NULL, $backgroundColor = NULL ){
		$reset			= "\033[0m";
		$fgColor		= '';
		$bgColor		= '';
		//  check if given foreground color is valid
		if( isset( static::$foregroundColors[$foregroundColor] ) )
			//  set foreground color code
			$fgColor	= "\033[".static::$foregroundColors[$foregroundColor]."m";
		//  check if given background color is valid
		if( isset( static::$backgroundColors[$backgroundColor] ) )
			//  set background color code
			$bgColor	= "\033[".static::$backgroundColors[$backgroundColor]."m";
		//  continue colors after resets in string
		$string			= str_replace( $reset, $reset.$fgColor.$bgColor, $string );
		//  add string and end coloring
		return $fgColor.$bgColor.$string.$reset;
	}

	public function colorize256( $string, $foregroundColor = NULL, $backgroundColor = NULL ){
		$reset			= "\033[0m";
		$fgColor		= '';
		$bgColor		= '';
		if( !is_null( $foregroundColor ) )
			//  set foreground color code
			$fgColor	= "\033[38;5;".$foregroundColor."m";
		if( !is_null( $backgroundColor ) )
			//  set background color code
			$bgColor	= "\033[48;5;".$backgroundColor."m";
		//  continue colors after resets in string
		$string			= str_replace( $reset, $reset.$fgColor.$bgColor, $string );
		//  add string and end coloring
		return $fgColor.$bgColor.$string.$reset;
	}

	// Returns all foreground color names
	public function getForegroundColors(){
		return array_keys( static::$foregroundColors );
	}

	// Returns all background color names
	public function getBackgroundColors(){
		return array_keys( static::$backgroundColors );
	}
}
