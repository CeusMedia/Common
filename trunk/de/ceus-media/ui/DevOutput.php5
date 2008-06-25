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
class UI_DevOutput
{
	/**	@var		string		$lineBreak		Sign for Line Break */
	public $lineBreak			= "<br/>";
	/**	@var		string		$indentSign		Sign for Spaces */
	public $indentSign			= "&nbsp;";
	/**	@var		string		$noteOpen		Sign for opening Notes */
	public $noteOpen			= "<em>";
	/**	@var		string		$noteClose		Sign for closing Notes */
	public $noteClose			= "</em>";
	/**	@var		string		$highlightOpen	Sign for opening Highlights */
	public $highlightOpen		= "<b>";
	/**	@var		string		$highlightClose	Sign for closing Highlights */
	public $highlightClose		= "</b>";
	/**	@var		int			$indentFactor	Factor of Spaces for Indents */
	public $indentFactor		= 6;

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
			$this->lineBreak		= "\n";
			$this->indentSign		= " ";
			$this->noteOpen			= "'";
			$this->noteClose		= "'";
			$this->highlightOpen	= "";
			$this->highlightClose	= "";
			$this->indentFactor		= 2;
		}
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
		$sign	= $sign ? $sign : $this->indentSign;
		$factor	= $factor ? $factor : $this->indentFactor;
		return str_repeat( $sign, $offset * $factor );
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
			$key	= ( $key !== NULL ) ? $key." => " : "";
			$space	= $this->indentSign( $offset, $sign, $factor );
			echo $space."[R] ".$key.$resource.$this->lineBreak;
		}
		else
			$this->printMixed( $object, $offset, $key, $sign, $factor );
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
			$ins_key	= ( $key !== NULL ) ? $key." -> " : "";
			$space		= $this->indentSign( $offset, $sign, $factor );
			echo $space."[O] ".$ins_key."".$this->highlightOpen.get_class( $object ).$this->highlightClose.$this->lineBreak;
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
			$space = $this->indentSign( $offset, $sign, $factor );
			if( $key !== NULL )
				echo $space."[A] ".$key.$this->lineBreak;
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
	 *	Prints out a variable by getting Type and using a suitable Method.
	 *	@access		public
	 *	@param		mixed		$mixed		variable of every kind to print out
	 *	@param		int			$offset		Intent Offset Level
	 *	@param		string		$key		Element Key Name
	 *	@param		string		$sign		Space Sign
	 *	@param		int			$factor		Space Factor
	 *	@return		void
	 */
	public function printMixed( $mixed, $offset = 0, $key = NULL, $sign = NULL, $factor = NULL )
	{
		if( is_object( $mixed ) || gettype( $mixed ) == "object" )
			$this->printObject( $mixed, $offset, $key, $sign, $factor );
		else if( is_array( $mixed ) )
			$this->printArray( $mixed, $offset, $key, $sign, $factor );
		else if( is_string( $mixed ) )
			$this->printString( $mixed, $offset, $key, $sign, $factor );
		else if( is_int($mixed ) )
			$this->printInteger( $mixed, $offset, $key, $sign, $factor );
		else if( is_double( $mixed ) )
			$this->printDouble( $mixed, $offset, $key, $sign, $factor );
		else if( is_float($mixed ) )
			$this->printFloat( $mixed, $offset, $key, $sign, $factor );
		else if( is_resource( $mixed ) )
			$this->printResource( $mixed, $offset, $key, $sign, $factor );
		else if( is_bool($mixed ) )
			$this->printBoolean( $mixed, $offset, $key, $sign, $factor );
		else if( $mixed === NULL )
			$this->printNull( $mixed, $offset, $key, $sign, $factor );
		else
			echo "No implementation in UI_DevOutput to put out a var of type ".$this->noteOpen.gettype( $mixed ).$this->noteClose.$this->lineBreak;
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
			$key = ( $key !== NULL ) ? $key." => " : "";
			$space = $this->indentSign( $offset, $sign, $factor );
			echo $space."[B] ".$key.$this->noteOpen.( $bool ? "TRUE" : "FALSE" ).$this->noteClose.$this->lineBreak;
		}
		else
			$this->printMixed( $bool, $offset, $key, $sign, $factor );
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
			$key = ( $key !== NULL ) ? $key." => " : "";
			$space = $this->indentSign( $offset, $sign, $factor );
			echo $space."[F] ".$key.$float.$this->lineBreak;
		}
		else
			$this->printMixed( $float, $offset, $key, $sign, $factor );
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
		if( is_double( $double ) )
		{
			$key = ( $key !== NULL ) ? $key." => " : "";
			$space = $this->indentSign( $offset, $sign, $factor );
			echo $space."[D] ".$key.$double.$this->lineBreak;
		}
		else
			$this->printMixed( $double, $offset, $key, $sign, $factor );
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
			$key = ( $key !== NULL ) ? $key." => " : "";
			$space = $this->indentSign( $offset, $sign, $factor );
			echo $space."[I] ".$key.$integer.$this->lineBreak;
		}
		else
			$this->printMixed( $integer, $offset, $key, $sign, $factor );
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
			$key = ( $key !== NULL ) ? $key." => " : "";
			$space = $this->indentSign( $offset, $sign, $factor );
			echo $space."[ ] ".$key.$this->noteOpen."NULL".$this->noteClose.$this->lineBreak;
		}
		else
			$this->printMixed( $null, $offset, $key, $sign, $factor );
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
			$key = ( $key !== NULL ) ? $key." => " : "";
			$space = $this->indentSign( $offset, $sign, $factor );
			echo $space."[S] ".$key.$string.$this->lineBreak;
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
 *	Prints out any variable with print_r in xmp
 *	@access		public
 *	@param		mixed		$mixed		variable to print out
 *	@return		void
 */
function dump( $variable )
{
	ob_start();
	print_r( $variable );
	xmp( ob_get_clean() );
}

/**
 *	Global Call Method for UI_DevOutput::print_m
 *	@access		public
 *	@param		mixed		$mixed		variable to print out
 *	@param		string		$sign		Space Sign
 *	@param		int			$factor		Space Factor
 *	@return		void
 */
function print_m( $mixed, $sign = NULL, $factor = NULL )
{
	$o = new UI_DevOutput();
	echo $o->lineBreak;
	$o->printMixed( $mixed, 0, NULL, $sign, $factor );
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
function remark( $text = "", $parameters = array(), $break = true )
{
	$o = new UI_DevOutput();
	if( $break )
		echo $o->lineBreak;
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
 *	@return		void
 */
function xmp( $string )
{
	echo "<xmp>".$string."</xmp>";
}
?>