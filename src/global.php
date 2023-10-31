<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

use CeusMedia\Common\UI\DevOutput;

/**
 *	Prints out Code formatted with Tag CODE
 *	@access		public
 *	@param		string		$string	Code to print out
 *	@return		void
 */
function code( string $string )
{
	echo "<code>".$string."</code>";
}

/**
 *	Prints given content only if flag CM_SHOW_DEV is on (or if forced).
 *	@access		public
 *	@param		string		$content	Dev Info to show
 *	@param		bool		$force		Flag: force display
 *	@param		string		$flagKey	...
 *	@return		void
 */
function dev( string $content, bool $force = FALSE, string $flagKey = 'CM_SHOW_DEV' )
{
	if( !( !$force && !( defined( $flagKey ) && constant( $flagKey ) ) ) )
		echo $content;
}

/**
 *	Prints out any variable with print_r in xmp.
 *  Old function name "dump" has been renamed in order to use Rector.
 *	@access		public
 *	@param		mixed		$variable	Variable to print dump of
 *	@param		boolean		$return		Flag: Return output instead of printing it
 *	@return		string|NULL
 */
function print_rx( $variable, bool $return = FALSE ): ?string
{
	ob_start();
	print_r( $variable );
	if( $return )
		ob_start();
	xmp( ob_get_clean() );
	if( $return )
		return ob_get_clean();
	return NULL;
}

/**
 *	Prints out Code formatted with Tag "pre".
 *	@access		public
 *	@param		string			$string		Code to print out
 *	@param		boolean			$dump		...
 *	@return		string|NULL
 */
function pre( string $string, bool $dump = FALSE ): ?string
{
	ob_start();
	echo "<pre>".htmlentities( $string, ENT_QUOTES, 'UTF-8' )."</pre>";
	if( $dump )
		return ob_get_clean();
	print( ob_get_clean() );
	return NULL;
}

/**
 *	Global function for DevOutput::printJson.
 *	@access		public
 *	@param		mixed		$mixed		variable to print out
 *	@param		string|NULL	$sign		Space Sign
 *	@param		int|NULL	$factor		Space Factor
 *	@param		boolean		$return		Flag: Return output instead of printing it
 *	@return		string|NULL
 */
function print_j( $mixed, ?string $sign = NULL, ?int $factor = NULL, bool $return = FALSE ): ?string
{
	$o		= new DevOutput();
	$break	= DevOutput::$channelSettings[$o->channel]['lineBreak'];
	if( $return )
		return $o->printJson( $mixed, $sign, $factor, TRUE );
	echo $break;
	$o->printJson( $mixed, $sign, $factor );
	return NULL;
}

/**
 *	Global function for DevOutput::printMixed.
 *	@access		public
 *	@param		mixed			$mixed		variable to print out
 *	@param		string|NULL		$sign		Space Sign
 *	@param		int|NULL		$factor		Space Factor
 *	@param		boolean			$return		Flag: Return output instead of printing it
 *	@param		string|NULL		$channel	...
 *	@return		string|NULL
 */
function print_m( $mixed, ?string $sign = NULL, ?int $factor = NULL, bool $return = FALSE, ?string $channel = NULL ): ?string
{
	$o		= new DevOutput();
	if( $channel )
		$o->setChannel( $channel );
	$break	= DevOutput::$channelSettings[$o->channel]['lineBreak'];
	if( $return )
		return $break.$o->printMixed( $mixed, 0, NULL, $sign, $factor, $return );
	echo $break;
	$o->printMixed( $mixed, 0, NULL, $sign, $factor, $return );
	return NULL;
}

/**
 *	Prints out all global registered variables with DevOutput::print_m
 *	@access		public
 *	@param		string|NULL		$sign		Space Sign
 *	@param		int|NULL		$factor		Space Factor
 *	@return		void
 */
function print_globals( ?string $sign = NULL, ?int $factor = NULL )
{
	$globals	= $GLOBALS;
	unset( $globals['GLOBALS'] );
	print_m( $globals, $sign, $factor );
}

/**
 *	Prints out a String after Line Break.
 *	@access		public
 *	@param		string		$text			String to print out
 *	@param		array		$parameters		Associative Array of Parameters to append
 *	@param		bool		$break			Flag: break Line before Print
 *	@return		void
 */
function remark( string $text = '', array $parameters = [], bool $break = TRUE )
{
	$o = new DevOutput();
	if( $break )
		echo DevOutput::$channelSettings[$o->channel]['lineBreak'];
	$o->remark( $text, $parameters );
}

/**
 *	Prints out a variable with DevOutput::print_m
 *	@access		public
 *	@param		mixed			$mixed			variable to print out
 *	@param		string|NULL		$sign			Space Sign
 *	@param		int|NULL		$factor			Space Factor
 *	@return		void
 */
function show( $mixed, ?string $sign = NULL, ?int $factor = NULL )
{
	print_m( $mixed, $sign, $factor );
}

/**
 *	@access		public
 *	@param		DOMNode			$node			DOM node to print
 *	@return		void
 */
function showDOM( DOMNode $node )
{
	$o = new DevOutput();
	$o->showDOM( $node );
}

/**
 *	Prints out Code formatted with Tag XMP
 *	@access		public
 *	@param		string		$string		Code to print out
 *	@param		bool		$dump		Flag: return instead of print, default: no
 *	@return		string|NULL
 */
function xmp( string $string, bool $dump = FALSE ): ?string
{
	$dev	= new DevOutput();
	if( $dump )
		ob_start();
	if( $dev->channel === DevOutput::CHANNEL_TEXT )
		echo $string."\n";
	else
		echo "<xmp>".$string."</xmp>";
	if( $dump )
		return ob_get_clean();
	return NULL;
}
