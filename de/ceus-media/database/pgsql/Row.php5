<?php
/**
 *	PostgreSQLRow
 *
 *	@package	database
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.4
 */
/**
 *	PostgreSQLRow
 *
 *	@package	database
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 	0.4
 */
class Database_pgSQL_Row
{
	var $_keys	= array();
	var $_values	= array();
	var $_pairs	= array();

	function getKeys ()
	{
		if( count( $this->_keys ) == 0)
			foreach( get_object_vars( $this ) as $key => $value )
				if( is_string( $key ) && !is_array( $value ) )
					$this->_keys[] = $key;
		return $this->_keys;
	}

	function getValue ($key)
	{
		return $this->$key;
	}

	function getValues ()
	{
		if( count( $this->_pairs ) == 0)
			foreach( get_object_vars( $this ) as $key => $value )
				if( is_string( $key ) && !is_array( $value ) )
					$this->_values[] = $value;
		return $this->_values;
	}
}
?>