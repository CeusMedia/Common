<?php
/**
 *	Parses SGML based Tags (also HTML, XHTML and XML).
 *	@package		alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.08.2008
 *	@version		0.1
 */
/**
 *	Parses SGML based Tags (also HTML, XHTML and XML).
 *	@package		alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.08.2008
 *	@version		0.1
 */
class Alg_SgmlTagReader
{
	const TRANSFORM_LOWERCASE	= 1;
	const TRANSFORM_UPPERCASE	= 2;

	/**
	 *	Returns Node Name from Tag.
	 *	@access		public
	 *	@param		string		$string			String containing exactly 1 SGML based Tag
	 *	@return		string
	 */
	public static function getNodeName( $string, $transform = 0 )
	{
		$data	= self::getTagData( $string );
		switch( $transform )
		{
			case self::TRANSFORM_LOWERCASE:	return strtolower( $data['nodename'] );
			case self::TRANSFORM_UPPERCASE:	return strtoupper( $data['nodename'] );
			default:					return $data['nodename'];
		}
	}
		
	/**
	 *	Returns Attributes from a Tag.
	 *	@access		public
	 *	@param		string		$string			String containing exactly 1 SGML based Tag
	 *	@return		array
	 */
	public static function getAttributes( $string, $transformKeys = 0 )
	{
		$data	= self::getTagData( $string, $transformKeys );
		return $data['attributes'];
	}

	/**
	 *	Returns Content from a Tag.
	 *	@access		public
	 *	@param		string		$string			String containing exactly 1 SGML based Tag
	 *	@return		string
	 */
	public static function getContent( $string )
	{
		$data	= self::getTagData( $string );
	}

	/**
	 *	Returns all Information from a Tag.
	 *	@access		public
	 *	@param		string		$string			String containing exactly 1 SGML based Tag
	 *	@return		array
	 */
	public static function getTagData( $string, $transformKeys = 0 )
	{
		$string		= trim( $string );
		$attributes	= array();
		$content	= "";
		$nodename	= "";
		
		if( preg_match( "@^<([a-z]+)@", $string, $results ) )
			$nodename	= $results[1];
		if( preg_match( "@>([^<]*)<@", $string, $results ) )
			$content	= $results[1];
		if( preg_match_all( '@ (\S+)="([^"]+)"@', $string, $results ) )
		{
			$array	= array_combine( $results[1], $results[2] );
			foreach( $array as $key => $value )
			{
				if( $transformKeys == self::TRANSFORM_LOWERCASE )
					$key	= strtolower( $key );
				else if( $transformKeys == self::TRANSFORM_UPPERCASE )
					$key	= strtoupper( $key );
				$attributes[$key]	= $value;
			}
		}
		if( preg_match_all( "@ (\S+)='([^']+)'@", $string, $results ) )
		{
			$array	= array_combine( $results[1], $results[2] );
			foreach( $array as $key => $value )
			{
				if( $transformKeys == self::TRANSFORM_LOWERCASE )
					$key	= strtolower( $key );
				else if( $transformKeys == self::TRANSFORM_UPPERCASE )
					$key	= strtoupper( $key );
				$attributes[$key]	= $value;
			}
		}
		return array(
			'nodename'		=> $nodename,
			'content'		=> $content,
			'attributes'	=> $attributes
		);
	}
	
/*	public static function transformAttributeValues( $attributes, $transform, $keys = array() )
	{
		$list	= array();
		foreach( $attributes as $key => $value )
		{
			if( !in_array( $key, $keys ) )
				continue;
			if( $transform == self::TRANSFORM_LOWERCASE )
				$value	= strtolower( $value );
			else if( $transform == self::TRANSFORM_UPPERCASE )
				$value	= strtoupper( $value );
			$list[$key]	= $value;
		}
		return $list;
	}*/
}
?>