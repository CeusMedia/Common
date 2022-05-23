<?php

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
 *	Prints out any variable with print_r in xmp.
 *  Old function name "dump" has been rename in order to use Rector.
 *	@access		public
 *	@param		mixed		$variable	Variable to print dump of
 *	@param		boolean		$return		Flag: Return output instead of printing it
 *	@return		void
 */
function print_rx( $variable, $return = FALSE )
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
