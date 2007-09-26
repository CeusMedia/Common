<?php
/**
 *	Output Methods for Developement.
 *	@package		ui
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Output Methods for Developement.
 *	@package		ui
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class DevOutput
{
	/**	@var		string		$_line_break	Sign for Line Break */
	var $_line_break	= "<br/>";
	/**	@var		string		$_space		Sign for Spaces */
	var $_space		= "&nbsp;";
	/**	@var		string		$_note_in	Sign for opening Notes */
	var $_note_in		= "<em>";
	/**	@var		string		$_note_out	Sign for closing Notes */
	var $_note_out	= "</em>";
	/**	@var		string		$_high_in		Sign for opening Highlights */
	var $_high_in		= "<b>";
	/**	@var		string		$_high_out	Sign for closing Highlights */
	var $_high_out	= "</b>";
	/**	@var		int			$_factor	 	Factor of Spaces for Indents */
	var $_factor		= 6;

	/**
	 *	Constructur.
	 *	@access		public
	 *	@param		string		$channel		Selector for Channel of Output
	 *	@return		void
	 */
	public function __construct( $channel = "html" )
	{
		if( getEnv( 'PROMPT' ) || getEnv( 'SHELL' ) || $channel == "console" )
		{
			$this->_line_break	= "\n";
			$this->_space		= " ";
			$this->_note_in		= "'";
			$this->_note_out	= "'";
			$this->_high_in		= "";
			$this->_high_out	= "";
			$this->_factor		= 2;
		}
	}

	/**
	 *	Returns whitespaces.
	 *	@access		public
	 *	@param		int			$offset		amount of space
	 *	@return		string
	 */
	function _space( $offset )
	{
		return str_repeat( $this->_space, $offset * $this->_factor );
	}

	function _print_r( $resource, $offset = 0, $key = false )
	{
		if( is_resource( $resource ) )
		{
			$key	= ( $key != false ) ? $key." => " : "";
			$space	= $this->_space( $offset );
			echo $space."[R] ".$key.$resource.$this->_line_break;
		}
		else
			$this->_print_m( $object, $offset, $key );
	}

	/**
	 *	Prints out a Object.
	 *	@access		public
	 *	@param		mixed		$object		Object variable to print out
	 *	@return		void
	 */
	function _print_o( $object, $offset = 0, $key = false )
	{
		if( is_object( $object ) )
		{
			$ins_key	= ( $key !== false ) ? $key." -> " : "";
			$space		= $this->_space( $offset );
			echo $space."[O] ".$ins_key."".$this->_high_in.get_class( $object ).$this->_high_out.$this->_line_break;
			$vars		= get_object_vars( $object );
			foreach( $vars as $key => $value )
			{
				if( is_object( $value ) )
					$this->_print_o( $value, $offset + 1, $key );
				else if( is_array( $value ) )
					$this->_print_a( $value, $offset + 1, $key );
				else
					$this->_print_m( $value, $offset + 1, $key );
			}
		}
		else
			$this->_print_m( $object, $offset, $key );
	}

	/**
	 *	Prints out an Array.
	 *	@access		public
	 *	@param		array		$array		Array variable to print out
	 *	@return		void
	 */
	function _print_a( $array, $offset = 0, $key = false )
	{
		if( is_array( $array ) )
		{
			$space = $this->_space( $offset );
			if( $key !== false )
				echo $space."[A] ".$key.$this->_line_break;
			foreach( $array as $key => $value )
			{
				if( is_array( $value ) && count( $value ) )
					$this->_print_a( $value, $offset + 1, $key );
				else
					$this->_print_m( $value, $offset + 1, $key );
			}
		}
		else
			$this->_print_m( $array, $offset, $key );
	}

	/**
	 *	Prints out a variable by getting Type and using a suitable Method.
	 *	@access		public
	 *	@param		mixed		$mixed		variable of every kind to print out
	 *	@return		void
	 */
	function _print_m( $mixed, $offset = 0, $key = false )
	{
		if( is_object( $mixed ) )
			$this->_print_o( $mixed, $offset, $key );
		else if( is_array( $mixed ) )
			$this->_print_a( $mixed, $offset, $key );
		else if( is_string( $mixed ) )
			$this->_print_s( $mixed, $offset, $key );
		else if( is_int($mixed ) )
			$this->_print_i( $mixed, $offset, $key );
		else if( is_double( $mixed ) )
			$this->_print_d( $mixed, $offset, $key );
		else if( is_float($mixed ) )
			$this->_print_f( $mixed, $offset, $key );
		else if( is_resource( $mixed ) )
			$this->_print_r( $mixed, $offset, $key );
		else if( is_bool($mixed ) )
			$this->_print_b( $mixed, $offset, $key );
		else if( $mixed === NULL )
			$this->_print_n( $mixed, $offset, $key );
		else
			echo "No implementation in DevOutput to put out a var of type ".$this->_note_in.gettype( $mixed ).$this->_note_out.$this->_line_break;
	}

	/**
	 *	Prints out a boolean variable.
	 *	@access		public
	 *	@param		bool			$bool		boolean variable to print out
	 *	@return		void
	 */
	function _print_b( $bool, $offset = 0, $key = false )
	{
		if( is_bool( $bool ) )
		{
			$key = ( $key !== false ) ? $key." => " : "";
			$space = $this->_space( $offset );
			echo $space."[B] ".$key.( $bool ? "true" : "false" ).$this->_line_break;
		}
		else
			$this->_print_m( $bool, $offset );
	}

	/**
	 *	Prints out an Float variable.
	 *	@access		public
	 *	@param		float			$float		float variable to print out
	 *	@return		void
	 */
	function _print_f( $float, $offset = 0, $key = false )
	{
		if( is_float( $float ) )
		{
			$key = ( $key !== false ) ? $key." => " : "";
			$space = $this->_space( $offset );
			echo $space."[F] ".$key.$float.$this->_line_break;
		}
		else
			$this->_print_m( $float, $offset );
	}
	
	/**
	 *	Prints out an Double variable.
	 *	@access		public
	 *	@param		double		$double		double variable to print out
	 *	@return		void
	 */
	function _print_d( $double, $offset = 0, $key = false )
	{
		if( is_double( $double ) )
		{
			$key = ( $key !== false ) ? $key." => " : "";
			$space = $this->_space( $offset );
			echo $space."[D] ".$key.$double.$this->_line_break;
		}
		else
			$this->_print_m( $double, $offset );
	}
	
	/**
	 *	Prints out an Integer variable.
	 *	@access		public
	 *	@param		bool			$bool		boolean variable to print out
	 *	@return		void
	 */
	function _print_i( $integer, $offset = 0, $key = false )
	{
		if( is_int( $integer ) )
		{
			$key = ( $key !== false ) ? $key." => " : "";
			$space = $this->_space( $offset );
			echo $space."[I] ".$key.$integer.$this->_line_break;
		}
		else
			$this->_print_m( $integer, $offset );
	}
	
	/**
	 *	Prints out NULL.
	 *	@access		public
	 *	@param		NULL		$null			boolean variable to print out
	 *	@return		void
	 */
	function _print_n( $null, $offset = 0, $key = false )
	{
		if( $null === NULL )
		{
			$key = ( $key !== false ) ? $key." => " : "";
			$space = $this->_space( $offset );
			echo $space."[ ] ".$key.$this->_note_in."NULL".$this->_note_out.$this->_line_break;
		}
		else
			$this->_print_m( $null, $offset );
	}

	/**
	 *	Prints out a String variable.
	 *	@access		public
	 *	@param		string		$string		String variable to print out
	 *	@return		void
	 */
	function _print_s( $string, $offset = 0, $key = false )
	{
		if( is_string( $string ) )
		{
			$key = ( $key !== false ) ? $key." => " : "";
			$space = $this->_space( $offset );
			echo $space."[S] ".$key.$string.$this->_line_break;
		}
		else
			$this->_print_m( $string, $offset );
	}
	
	/**
	 *	Prints out a String and Parameters.
	 *	@access		public
	 *	@param		string		$text		String to print out
	 *	@param		array		$parameters	Associative Array of Parameters to append
	 *	@return		void
	 */
	function _remark( $text, $parameters = array() )
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

	function showDOM( $node, $offset = 0 )
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
 *	Global Call Method for DevOutput::print_o
 *	@access		public
 *	@param		mixed		$object		Object variable to print out
 *	@return		void
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 *	@deprecated	use print_m instead
 */
/*function print_o( $object )
{
	$o = new DevOutput();
	$o->_print_o( $object );
}
*/

/**
 *	Global Call Method for DevOutput::print_a
 *	@access		public
 *	@param		array		$array		Array variable to print out
 *	@return		void
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 *	@deprecated	use print_m instead
 */
/*function print_a( $array )
{
	$o = new DevOutput();
	$o->_print_a( $array );
}
*/

/**
 *	Global Call Method for DevOutput::print_m
 *	@access		public
 *	@param		mixed		$mixed		variable to print out
 *	@return		void
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
function print_m( $mixed )
{
	$o = new DevOutput();
	echo $o->_line_break;
	$o->_print_m( $mixed );
}

/**
 *	Prints out Code formatted with Tag XMP
 *	@access		public
 *	@param		string		$string		Code to print out
 *	@return		void
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
function xmp( $string )
{
	echo "<xmp>".$string."</xmp>";
}

/**
 *	Prints out Code formatted with Tag CODE
 *	@access		public
 *	@param		string		$string	Code to print out
 *	@return		void
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
function code( $string )
{
	echo "<code>".$string."</code>";
}

/**
 *	Prints out a variable with DevOutput::print_m
 *	@access		public
 *	@param		mixed		$mixed		variable to print out
 *	@return		void
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
function show( $mixed )
{
	print_m( $mixed );
}

/**
 *	Prints out all global registered variables with DevOutput::print_m
 *	@access		public
 *	@return		void
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
function print_globals()
{
	$globals	= $GLOBALS;
	unset( $globals['GLOBALS'] );
	print_m( $globals );
}

/**
 *	Prints out any variable with print_r in xmp
 *	@access		public
 *	@param		mixed		$mixed		variable to print out
 *	@return		void
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
function dump( $variable )
{
	ob_start();
	print_r( $variable );
	xmp( ob_get_clean() );
}

/**
 *	Prints out a String after Line Break.
 *	@access		public
 *	@param		string		$text		String to print out
 *	@param		array		$parameters	Associative Array of Parameters to append
 *	@param		bool			$break		Flag: break Line before Print
 *	@return		void
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
function remark( $text = "", $parameters = array(), $break = true )
{
	$o = new DevOutput();
	if( $break )
		echo $o->_line_break;
	$o->_remark( $text, $parameters );
}

function showDOM( $node )
{
	$o = new DevOutput();
	$o->showDOM( $node );
}
?>