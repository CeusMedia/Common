<?php
/**
 *	@todo		Code Documentation
 */
class Example
{
	var $_prefix	= "";
	var $_suffix	= " - Example";
	var $_title		= "";
	var $template	= "<html>
<head>
<style>
pre.listing {
	padding: 10px;
	background: white;
	border: 1px solid gray;
	background-color: #f7f9fa;
	border: 1px solid #8cacbb;
	color: black;
	overflow: auto;
	line-height: 0.8em;
}
</style>
</head>
<body>
<h1>[#heading#]</h1>
<div>[#content#]</div>
<pre class='listing'>[#listing#]</pre>
</body>
</html>";

	public function __construct( $title = "Example" )
	{
		$this->setTitle( $title );
	}
	
	function getPrefix()
	{
		return $this->_prefix;
	}

	function getSuffix()
	{
		return $this->_suffix;
	}

	function getTitle()
	{
		return $this->_title;
	}

	function setPrefix( $prefix )
	{
		$this->_prefix	= prefix;
	}

	function setSuffix( $suffix )
	{
		$this->_suffix	= suffix;
	}
	
	function setTitle( $title )
	{
		$this->_title	= $title;
	}
	
	function main()
	{
		echo "file: ".__FILE__."<br/>line: ".__LINE__;
	}
	
	function show()
	{
		ob_start();
		$this->main();
		$content	= ob_get_contents();		
		ob_end_clean();
		$listing	= $this->_getListing();
		$content	= str_replace( "[#content#]", $content, $this->template );
		$content	= str_replace( "[#listing#]", htmlspecialchars( implode( "\n", $listing ) ), $content );
		$content	= str_replace( "[#heading#]", $this->_prefix.$this->_title.$this->_suffix, $content );
		die( $content );
	}

	function _getListing()
	{
		$file	= file( getEnv( 'SCRIPT_FILENAME' ) );
		$level = 0;
		foreach( $file as $line )
		{
			$line	= preg_replace( "/^(\t\t|    )/", "", $line );
			if( $level )
			{
				$level += substr_count( $line, "{" );
				$level -= substr_count( $line, "}" );
				if( $level <= 0 )
				{
					if( trim( $mainlines[0] ) == "{" )
						array_shift( $mainlines );
					if( trim( $mainlines[count( $mainlines )-1] ) == "}" )
						array_pop( $mainlines );
					break;
				}
				$mainlines[]	= stripslashes( $line );
			}
			else
			{
				if( preg_match( "@(\t| )*function main@i", $line ) )
					$level = 1;
			}
		}
		return $mainlines;
	}
}
?>