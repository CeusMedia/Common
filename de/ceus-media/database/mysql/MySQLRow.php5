<?php
/**
 *	MySQLRow
 *
 *	@package	database
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.4
 */
/**
 *	MySQLRow
 *
 *	@package	database
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 	0.4
 */
//class MySQLRow
class MySQLRow
{
	function getKeys ()
	{
		$keys = array();
		foreach( get_object_vars( $this ) as $key => $value )
			if( is_string( $key ) )
				$keys[] = $key;
		return $keys;
	}

	function getValue ($key)
	{
		if( isset( $this->$key ) )
			return $this->$key;
		return NULL;
	}

	function getValues ()
	{
		$values = array();
		foreach( get_object_vars( $this ) as $key => $value )
			if( is_string( $key ) )
				$values[] = $value;
		return $values;
	}
	
	function getPairs( $assoc = true )
	{
		$pairs = array();
		foreach( get_object_vars( $this ) as $key => $value )
			if( is_string( $key ) )
					$pairs[$key] = $value;
		return $pairs;
	}
	
	function getColCount ()
	{
		return count( get_object_vars( $this ) ) / 2;
	}
}
?>