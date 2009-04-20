<?php
/**
 *	Trace Output for Debugging.
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			29.12.2005
 *	@version		0.1
 */
/**
 *	Trace Output for Debugging.
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			29.12.2005
 *	@version		0.1
 *	@deprecated		to be deleted in 0.6.7
 */
class DebugTrace
{
	/**	@var		mixed		$_old			Previously set Error Handler */
	var $_old;
	/**	@var		array		$_types			Registered Error Types */
	var $_types;
	/**	@var		string		$_message		Template for Error Messages */
	var $_message	= "<b>#error#</b> #msg# in #file# at line #line#";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$constants	= get_defined_constants();
		foreach( $constants as $constant => $value )
			if( substr( $constant, 0, 2 ) == "E_" )
				$this->_types[substr( $constant, 2 )]	= $value;
		$this->_old = set_error_handler( array( $this, "handleError" ) );
	}

	/**
	 *	Builds Message.
	 *	@access		protected
	 *	@param		int			$errNo			Error Number
	 *	@param		string		$errStr			Error Message
	 *	@param		string		$errFile		Error File
	 *	@param		int			$errLine		Error Line
	 *	@return		string
	 */
	protected function buildMessage( $errNo, $errStr, $errFile, $errLine )
	{
		$message	= str_replace(
			array( "#error#", "#msg#", "#file#", "#line#" ),
			array( $errNo, $errStr, $errFile, $errLine ),
			$this->_message );
		return $message;
	}

	/**
	 *	Builds and returns a single Trace.
	 *	@access		protected
	 *	@param		array		$data			Trace Data
	 *	@return		string
	 */
	protected function buildTrace( $data )
	{
		$args	= "";
		if( isset( $data['args'] ) && is_array( $data['args'] ) && count( $data['args'] ) )
			$args	= "(".implode( ", ", $data['args'] ).")";
		$function	= $data['function'].$args;
		if( isset( $data['class'] ) )
			$function	= $data['class'].$data['type'].$function;
		$string = "[".$data['file']." at line ".$data['line']."] ".$function;
		return $string;
	}
	
	/**
	 *	Checks Level of Error Message.
	 *	@access		private
	 *	@param		int			$level			Error Reporting Level
	 *	@param		int			$flag			Error Level
	 *	@return		void
	 */
	private function checkBinaryFlag( $level, $flag )
	{
		if( $flag == 0 )
			return false;
		$c = 0;
		while( $flag != 0 )
		{
			$c++;
			$flag = $flag >> 1;
		}
		$c--;
		$bin = $this->str_reverse( DecBin( $level ) );
		return (bool)$bin[$c];
	}
	
	/**
	 *	Error Handler to be set in by Constructor.
	 *	@access		protected
	 *	@param		int			$errNo			Error Number
	 *	@param		string		$errStr			Error Message
	 *	@param		string		$errFile		Error File
	 *	@param		int			$errLine		Error Line
	 *	@return		void
	 */
	protected function handleError( $errNo, $errStr, $errFile, $errLine )
	{
		if( $this->checkBinaryFlag( error_reporting(), $errNo ) )
		{
			$errors	= array_flip( $this->_types );
			$errors	= explode( "_", $errors[$errNo] );
			$_errors	= array();
			foreach( $errors as $error )
				$_errors[]	= ucfirst( strtolower( $error ) );
			$error	= implode( " ", $_errors );
			echo $this->buildMessage( $error, $errStr, $errFile, $errLine );
			$trace	= debug_backtrace();
			array_shift( $trace );
			krsort( $trace );
			echo $this->traceToHTML( $trace );
			if( $errNo == 1 || $errNo == 16 || $errNo == 256 )
				die;
		}
	}

	/**
	 *	Returns reverted String.
	 *	@access		private
	 *	@param		string		$string		String to be reverted
	 *	@return		string
	 */
	private function str_reverse( $string )
	{
		$new = "";
		for( $i = 0; $i < strlen( $string ); $i++ )
			$new .= $string[strlen( $string ) - 1 - $i];
		return $new;
	}

	/**
	 *	Builds HTML of Trace.
	 *	@access		public
	 *	@param		array		$trace		Array with Traces
	 *	@param		int			$deepth		Deepth Level
	 *	@return		string
	 */
	public function traceToHTML( $trace, $depth = 0 )
	{
		$track	= array_shift( $trace );
		$entry	= "<li>".$this->buildTrace( $track )."</li>";
		if( count( $trace ) )
			$entry	.= $this->traceToHTML( $trace, ++$depth );
		if( !$depth )
			$entry	= "<ul>".$entry."</ul>";
		return $entry;	
	}
}
?>