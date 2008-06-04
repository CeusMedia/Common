<?php
/**
 *	Formats JSON String.
 *	@package			adt_json
 *	@author				Umbrae <umbrae@gmail.com>
 *	@author				Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since				10.01.2008
 *	@version			0.2
 */
/**
 *	Formats JSON String.
 *	@package			adt_json
 *	@author				Umbrae <umbrae@gmail.com>
 *	@author				Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since				10.01.2008
 *	@version			0.2
 *	@todo				Unit Test
 */
class ADT_JSON_Formater
{
	/**
	 *	Formats JSON String.
	 *	@access		public
	 *	@param		string		$json		JSON String or Object to format
	 *	@return		string
	 */
	public static function format( $json, $validateSource = FALSE )
	{
		$tab			= "  ";
		$content		= "";
		$indentLevel	= 0;
		$inString		= FALSE;

		if( !is_string( $json ) )
			$json	= json_encode( $json );

		if( $validateSource )
			if( json_decode( $json ) === FALSE )
				throw new InvalidArgumentException( 'JSON String is not valid.' );
			
		$len	= strlen( $json );
		for( $c=0; $c<$len; $c++ )
		{
			$char	= $json[$c];
			switch( $char )
			{
				case '{':
				case '[':
					$content .= $char;
					if( !$inString )
					{
						$content .= "\n".str_repeat( $tab, $indentLevel + 1 );
						$indentLevel++;
					}
					break;
				case '}':
				case ']':
					if( !$inString )
					{
						$indentLevel--;
						$content .= "\n".str_repeat( $tab, $indentLevel );
					}
					$content .= $char;
					break;
				case ',':
					$content .= $inString ? $char : ",\n" . str_repeat( $tab, $indentLevel );
					break;
				case ':':
					$content .= $inString ? $char : ": ";
					break;
				case '"':
					if( $c > 0 && $json[$c-1] != '\\' )
						$inString = !$inString;
				default:
					$content .= $char;
					break;                   
			}
		}
		return $content;
	}
}
?>