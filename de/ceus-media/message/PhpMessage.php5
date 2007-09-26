<?php
import( 'de.ceus-media.adt.OptionObject' );
/**
 *	Basic Message for serveral Channels.
 *	@package		message
 *	@extends		OptionObject
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			18.07.2005
 *	@version		0.1
 */
/**
 *	Basic Message for serveral Channels.
 *	@package		message
 *	@extends		OptionObject
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			18.07.2005
 *	@version		0.1
 *	@todo			Code Documentation
 */
class PhpMessage extends OptionObject
{
	/**	@var		array		$prefix		Array of message prefixes */
	var $prefix	= array(
			'console'	=> 'MESSAGE: ',
			'html'	=> 'Message: ',
		);
	/**	@var		array		$raisers		Array of message raising methode names */
	var $raisers		= array(
		'phpmesssage', 
		'phperror', 
		'phpwarning', 
		'phpnotice',
		'__errorhandler',
		'trigger_error',
		'user_error',
	);
	/**	@var		array		$break		Array of message breaks*/
	var $break	= array(
			'console'	=> "\n",
			'html'	=> "<br/>",
		);
	/**	@var		array		$templates	Array of message templates */
	var $templates = array(
		'console'	=> "{prefix} {message}{break}File: {file}{break}Line: {line}{break}",
		'html'	=> "<div style=\"background: white; text-align: left\"><b>{prefix}</b>{message}{break}<b>File: </b>{file}{break}<b>Line: </b>{line}{break}<b>Method: </b>{function}{break}<b>Date: </b>{datetime}{break}</div>",
		'log'		=> "{datetime} | {prefix} | {message} | {break}",
	);
	
	/**	@var		array		$_backtrace	Array of backtrace information from debug_backtrace */
	var $_backtrace	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$channel		Channel for output
	 *	@return		void
	 */
	public function __construct( $channel = false )
	{
		$this->setOption( 'channel', defined( 'MESSAGE_CHANNEL' ) ? MESSAGE_CHANNEL : array_shift( array_keys( $this->templates ) ) );
		if( false != $channel )
			$this->setOption( 'channel', $channel  );
		if( count( $this->prefix ) )
			$this->setOption( 'prefix', $this->prefix);
		if( count( $this->break ) )
			$this->setOption( 'break', $this->break);
		$this->setOption( 'datetime', defined( 'MESSAGE_DATETIME' ) ? MESSAGE_DATETIME: 'y/m/d');
		$this->setOption( 'date', defined( 'MESSAGE_DATE' ) ? MESSAGE_DATE: 'y/m/d');
		$this->setOption( 'time', defined( 'MESSAGE_TIME' ) ? MESSAGE_TIME: 'G:i:s');
		$this->setOption( 'timestamp', time() );
		$this->setOption( 'last', array() );

		if( function_exists( 'debug_backtrace' ) ) 
			$this->_catchBacktrace();
	}
	
	//  --  PUBLIC METHODS  --  //
	/**
	 *	Returns Back Trace.
	 *	@access		public
	 *	@param		int			$offset		Offset
	 *	@param		bool			$tree		Indented Output
	 *	@return		string
	 */
	function getBacktrace( $offset = 0, $tree = false )
	{
		if( $tree )
			return $this->_getBacktraceTree( $offset, $tree );
		else if( defined( 'MESSAGE_TREE' ) && (bool)MESSAGE_TREE )
			return $this->_getBacktraceTree( $offset, $tree );
		else if( defined( 'MESSAGE_TABLE' ) && (bool)MESSAGE_TABLE )
			return $this->_getBacktraceTable( $offset );
		else
			return $this->_getBacktraceList( $offset );
	}
	
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Returns Back Trace.
	 *	@access		private
	 *	@return		void
	 */
	function _catchBacktrace()
	{
		$this->_backtrace	=	debug_backtrace();
		array_shift( $this->_backtrace );
		array_shift( $this->_backtrace );
		$as = array( 'file','line', 'class', 'function', 'type', 'args' );
		$j = 0;
		for( $i = count( $this->_backtrace ) - 1; $i >= 0; --$i )
		{
			if( in_array( $this->_backtrace[$i]['function'], $this->raisers ) )
			{
				++$i;
				$j++;
				$last = array();
				foreach( $as as $a )
					if( isset( $this->_backtrace[$i][$a] ) )
						$last[$a]	=	$this->_backtrace[$i][$a];
					else
						$last[$a]	= "";

				$this->setOption( 'last', $last );
				break;
			}
		}
	}

	/**
	 *	Returns HTML of Back Trace List.
	 *	@access		private
	 *	@param		int			$offset		Offset
	 *	@param		bool			$tree		Indented Output
	 *	@return		string
	 */
	function _getBacktraceList( $offset = 0, $tree = false)
	{
		$backtrace = $this->_backtrace;
		for( $i = 0; $i<$offset; $i++ )
			unset($backtrace[$i]);
			
		$lines	= array();
		foreach( $backtrace as $trace )
		{
			$_args = array();
			extract( $trace );
			if( isset( $args ) && is_array( $args ) )
				foreach( $args as $arg )
					if( !is_array( $arg ) )
						if( !(is_string( $arg ) && !trim( $arg ) ) )
							$_args[] = $arg;
			$args = "(".implode(",_ ",$_args).")";
			if( isset( $function ) && $function )
			{
				$class	= isset( $class ) ? $class : "";
				$type	= isset( $type ) ? $type : "";
				$function	= "<b>".$class.$type.$function."</b>".$args."<b>&nbsp;@&nbsp;</b>";
			}
			else
				$function = "";
			if( isset( $file ) && $file )
				$file = str_replace( basename( $file ), "<b>".basename( $file )."</b>", $file )."&nbsp;:&nbsp;<b>".$line."</b>";
			else
				$file = "";
			$lines[] = "<div style='padding: 1px'>".$function.$file."</div>";
		}
		$code = "<div style=\"border: 1px dotted #7F7F7F; background: #EFEFEF; padding: 2px\" align=\"left\">".implode("", $lines)."</div>";
		return $code;
	}

	/**
	 *	Returns HTML of Back Trace Tree (indented List).
	 *	@access		private
	 *	@param		int			$offset		Offset
	 *	@param		bool			$tree		Indented Output
	 *	@return		string
	 */
	function _getBacktraceTree( $offset = 0, $tree = false)
	{
		$c = 0;
		$files = array();
		$backtrace = $this->_backtrace;
		for( $i = 0; $i<$offset; $i++ )
			unset($backtrace[$i]);
		$backtrace = array_reverse( $backtrace );
		$lines	= array();
		foreach( $backtrace as $trace )
		{
			$_args = array();
			extract( $trace );
			if( isset( $args ) && is_array( $args ) )
				foreach( $args as $arg )
					if( !is_array( $arg ) && !(is_string( $arg ) && !trim( $arg ) ) )
						$_args[] = $arg;
			$args = count( $_args ) ? "(".implode(", ",$_args).")" : "";
			$func	= "";
			if( isset( $function ) && $function )
			{
				$class	= isset( $class ) ? $class : "";
				$type	= isset( $type ) ? $type : "";
//				$lines [] = "<div>".str_repeat("&nbsp;", $c*2)."<b>".$class.$type.$function."</b>".$args."</div>";
				$func	= "<br/><b>".$class.$type.$function."</b>".$args;
			}
			$c++;
			if( isset( $file ) )
			{
				if( in_array( $file, array_keys( $files ) ) )
					$c = $files[$file];
				else
					$files[$file] = $c;
				$file = str_replace( basename( $file ), "<b>".basename( $file )."</b>", $file )."&nbsp;:&nbsp;<b>".$line."</b>";
				if( count( $lines ) )
					$lines[] = "\n".str_repeat( "\t", $c  )."<div style='height:1px; border-top: 1px dotted #7F7F7F'></div>";
				$lines[] = "\n".str_repeat( "\t", $c  )."<div style='margin-left: ".($c*2)."0px'>".$file.$func."</div>";
			}
			extract($trace);
			$args	= (array)$args;
		}
	//	array_shift($lines);
		$code =  "<div style='border: 1px dotted #7F7F7F; background: #EFEFEF'>".implode("", $lines)."</div>";
		return $code;
	}

	/**
	 *	Returns HTML of Back Trace List.
	 *	@access		private
	 *	@param		int			$offset		Offset
	 *	@return		string
	 */
	function _getBacktraceTable( $offset = 0 )
	{
		$backtrace = $this->_backtrace;
		for( $i = 0; $i<$offset; $i++ )
			unset($backtrace[$i]);
			
		$lines	= array();
		foreach( $backtrace as $trace )
		{
			$_args = array();
			extract( $trace );
			if( isset( $args ) && is_array( $args ) )
				foreach( $args as $arg )
					if( !is_array( $arg ) )
						if( !(is_string( $arg ) && !trim( $arg ) ) )
							$_args[] = $arg;
			$args = "(".implode(",_ ",$_args).")";
			if( isset( $function ) && $function )
			{
				$class	= isset( $class ) ? $class : "";
				$type	= isset( $type ) ? $type : "";
				$function	= $class.$type.$function.$args;
			}
			else
				$function = "";
			if( isset( $file ) && $file )
				$file = str_replace( basename( $file ), "<b>".basename( $file )."</b>", $file )."&nbsp;:&nbsp;<b>".$line."</b>";
			else
				$file = "";
			$lines[] = "<tr><td>".$file."</td><td>".$function."</td></tr>";
		}
		$code = "<table border='1' cellspacing='0' cellpadding='2' width='100%' style=\"border-color: #DFDFDF; border-collapse: collapse; background: #F7F7F7\" align=\"left\"><tr><th>File</th><th>Function</th></tr>".implode("", $lines)."</table>";
		return $code;
	}

	/**
	 *	Renders a message with given channel options.
	 *	@access		private
	 *	@param		string		$message		Message to render
	 *	@return		string
	 */
	function _render( $message )
	{
		$prefix	= $this->getOption( 'prefix' );
		$prefix	= $prefix[$this->getOption( 'channel' )];
		$break	= $this->getOption( 'break' );
		$break	= $break[$this->getOption( 'channel' )];
		$template = $this->templates[$this->getOption( 'channel' )];
		$needles	= array(
				"{prefix}",
				"{file}",
				"{line}",
				"{class}",
				"{function}",
				"{message}",
				"{break}",
				"{datetime}",
				"{date}",
				"{time}",
			);
		$last = $this->getOption( 'last' );
		$substs	= array(
				$prefix,
				(string) $last['file'],
				(string) $last['line'],
				(string) $last['class'],
				(string) $last['function'],
				$message,
				$break,
				date( $this->getOption( 'datetime'), $this->getOption( 'timestamp' ) ),
				date( $this->getOption( 'date'), $this->getOption( 'timestamp' ) ),
				date( $this->getOption( 'time'), $this->getOption( 'timestamp' ) ),
			);

		$template = str_replace( $needles, $substs, $template );
		return $template;
	}
}
?>