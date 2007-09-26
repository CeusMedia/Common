<?php
/**
 *	Tidies up and formats XML Strings for output.
 *	@package		xml
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
/**
 *	Tidies up and formats XML Strings for output.
 *	@package		xml
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
class XML_Format
{
	/**
	 *	Proves whether a XML string is tidy.
	 *	@param		string		xml			XML string or array
	 *	@return		bool
	 */
	function isTidy( $xml )
	{
		if( is_array( $xml ) )
			$xml	= implode( "\m", $xml );
		$tidy = !substr_count( $xml, "><" ) && !substr_count( $xml, "  <" );
		return $tidy;
	}
	
	/**
	 *	Tidies up a XML string.
	 *	@param		string		xml			XML string or array
	 *	@return		string
	 */
	function tidy( $xml, $fast = false )
	{
		if( is_array( $xml ) )
			$xml	= implode( "", $xml );
		if( !$this->isTidy( $xml ) )
		{
			$xml		= str_replace( array( "\r", "\n", "\t"), '', $xml );
			$xml		= ereg_replace( ">[ ]*<", "><", $xml );
			$xml		= trim( $xml );
			$xml		= substr( $xml, 1, - 1 );
			$xml		= explode ( "><", $xml );
			$first	= "<".array_shift ($xml).">";
			$stack	= array();
			foreach( $xml as $node )
			{
				if( trim( $node ) )
				{
					$tag		= $node;
					$pos1	= strpos( $node, " " );
					$pos2	= strpos( $node, ">" );
					if( $pos1 )
						$tag	= substr( $node, 0, $pos1 );
					if( $pos2 )
						$tag	= substr( $node, 0, $pos2 );
					array_push( $stack, $tag );
					if( substr( $node, 0, 1 ) == "/" )
						array_pop( $stack );
					$node	= "<".$node.">";
					$lines[]	= str_repeat("\t", count( $stack )-1 ).$node;
					if( substr_count( $node, "</" ) )
						array_pop( $stack );
				}
			}
			$xml		= array_merge (array( $first ), $lines );
			$xml		= implode( "\n", $lines );
		}
		return $xml;
	}

	/**
	 *	Returns formated XML string.
	 *	@param		string		xml			XML string or array
	 *	@return		string
	 */
	function format( $xml )
	{
		$code = $this->getStyle().$this->toHTML( $this->tidy( $xml ) );
		return $code;
	}
	
	/**
	 *	Returns style defintions for formated XML string.
	 *	@return		string
	 */
	function getStyle()
	{
		$style = "
  <style>
span.comment	{ color: gray;	}
span.content	{ color: green;	}
span.value	{ color: blue;	}
span.key		{ color: teal;	}
span.node	{ color: maroon; 	}
  </style>";
		return $style;
	}

	/**
	 *	Returns HTML formated XML string.
	 *	@param		string		xml			XML string or array
	 *	@param		string		prefix		Prefix for substitution
	 *	@param		string		suffix		Suffix for substitution
	 *	@param		string		linebreak		Substitution for linebreaks
	 *	@param		string		tab			Substitution for tabs
	 *	@return		string
	 */
	function toHTML ( $xml, $prefix = "{[#", $suffix = "#]}", $linebreak = "{[~|~]}", $tab = "{[~_~]}" )
	{
		$last_value_opener = "";
		$string = new PointerString( $xml );
		$open	= array(
			'tag'			=> 0,
			'key'			=> 0,
			'value'		=> 0,
			'content'		=> 0,
			'comment'	=> 0,
		);
		$string->_setString( str_replace( array( "\n", "\t" ), array( $linebreak, $tab ), $string->getString() ) );
		while( false !== ( $char = $string->getNext() ) )
		{
			if( $open['tag'] )
			{
				if( $open['comment'] )
				{
					if( $char == ">")
					{
						$open['comment']--;
						$open['tag']--;
						$string->replaceChar( $prefix."/span".$suffix.$char );
					}
				}
				else if( $open['value'] )
				{
					if( $char == $last_value_opener )
					{
						$open['value']--;
						$string->replaceChar( $prefix."/span".$suffix.$prefix."span class='key'".$suffix.$char );
					}
				}
				else if ( $open['key'] )
				{
					if( $char == '"' || $char == "'" )
					{
						$open['value']++;
						$open['key']--;
						$string->replaceChar( $char.$prefix."/span".$suffix.$prefix."span class='value'".$suffix );
						$last_value_opener = $char;
					}
				}
				else
				{
					if( $char == " " )
					{
						$open['key']++;
						$string->replaceChar( $prefix."/span".$suffix.$char.$prefix."span class='key'".$suffix );
					}
					else if( $char == ">" )
					{
						$open['tag']--;
						$open['key'] = 0;
						$string->replaceChar( $prefix."/span".$suffix.$char);
					}
					else if( $char == "!" )
					{
						$open['comment']++;
						$string->replaceChar( $prefix."/span".$suffix.$prefix."span class='comment'".$suffix.$char );
					}
				}
			}
			else
			{
				if( $open['content'])
				{
					if( $char == "<" )
					{
						$open['tag']++;
						$open['content'] = 0;
						$string->replaceChar( $prefix."/span".$suffix.$char.$prefix."span class='node'".$suffix );
					}
				}
				else
				{
					if( $char == "<" )
					{
						$open['tag']++;
						$string->replaceChar( $char.$prefix."span class='node'".$suffix );
					}
					else if( $char != $linebreak && $char != $tab )
					{
						$open['content']++;
						$string->replaceChar( $prefix."span class='content'".$suffix.$char) ;
					}
				}
			}
		}
		$needles	= array( "<", ">", $prefix, $suffix, $linebreak, $tab );
		$substs	= array( "&lt;", "&gt;", "<", ">", "<br/>", str_repeat( "&nbsp;", 4 ) );
		$string->_setString( str_replace( $needles, $substs, $string->getString() ) );
		return $string->getString();
	}
}
?>