<?php
import( 'de.ceus-media.adt.list.Dictionary' );
/**
 *	A Map with Level Support.
 *	It is a Dictionary where Keys can contain Dots.
 *	All Method work with complete Keys and single Values or Prefix Keys and Arrays.
 *	@package		adt.list
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			07.05.2008
 *	@version		0.6
 */
/**
 *	A Map with Level Support.
 *	It is a Dictionary where Keys can contain Dots.
 *	All Method work with complete Keys and single Values or Prefix Keys and Arrays.
 *	@package		adt.list
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			07.05.2008
 *	@version		0.6
 *	@todo			Unit Test
 */
class ADT_List_LevelMap extends ADT_List_Dictionary
{
	/**
	 *	Return a Value or Pair Map of Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		mixed
	 */
	public function get( $key )
	{
		if( empty( $key ) )																		//  no Key given
			throw new InvalidArgumentException( 'Key must not be empty.' );						//  throw Exception
		if( isset( $this->pairs[$key] ) )														//  Key is set on its own
			return $this->pairs[$key];															//  return Value
		else																					//  Key has not been found
		{
			$key		.= ".";																	//  prepare Prefix Key to seach for
			$list		= array();																//  define empty Map
			$length		= strlen( $key );														//  get Length of Prefix Key outside the Loop
			foreach( $this->pairs as $pairKey => $pairValue )									//  iterate all stores Pairs
			{
				if( $pairKey[0] !== $key[0] )													//  precheck for Performance
					continue;																	//  skip Pair
				if( strpos( $pairKey, $key ) === 0 )											//  Prefix Key is found
					$list[substr( $pairKey, $length )]	= $pairValue;							//  collect Pair
			}
			if( count( $list ) )																//  found Pairs
				return $list;																	//  return Pair Map
		}
		return NULL;																			//  nothing found
	}
	
	/**
	 *	Indicates whether a Key or Key Prefix is existing.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		bool
	 */
	public function has( $key )
	{
		if( empty( $key ) )																		//  no Key given
			throw new InvalidArgumentException( 'Key must not be empty.' );						//  throw Exception
		if( isset( $this->pairs[$key] ) )														//  Key is set on its own
			return TRUE;
		else																					//  Key has not been found
		{
			$key		.= ".";																	//  prepare Prefix Key to seach for
			foreach( $this->pairs as $pairKey => $pairValue )									//  iterate all stores Pairs
			{
				if( $pairKey[0] !== $key[0] )													//  precheck for Performance
					continue;																	//  skip Pair
				if( strpos( $pairKey, $key ) === 0 )											//  Prefix Key is found
					return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 *	Removes a Value or Pair Map from Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		void
	 */
	public function remove( $key )
	{
		if( empty( $key ) )																		//  no Key given
			throw new InvalidArgumentException( 'Key must not be empty.' );						//  throw Exception
		if( isset( $this->pairs[$key] ) )														//  Key is set on its own
			unset( $this->pairs[$key] );														//  remove Pair
		else																					//  Key has not been found
		{
			$key		.= ".";																	//  prepare Prefix Key to seach for
			foreach( $this->pairs as $pairKey => $pairValue )									//  iterate all stores Pairs
			{
				if( $pairKey[0] !== $key[0] )													//  precheck for Performance
					continue;																	//  skip Pair
				if( strpos( $pairKey, $key ) === 0 )											//  Prefix Key is found
					unset( $this->pairs[$pairKey] );											//  remove Pair
			}
		}
	}
	
	/**
	 *	Sets Value of Key in Dictionary.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@param		string		$value		Value of Key
	 *	@param		bool		$sort		Flag: sort by Keys after Insertion	
	 *	@return		void
	 */
	public function set( $key, $value, $sort = TRUE )
	{
		if( empty( $key ) )																		//  no Key given
			throw new InvalidArgumentException( 'Key must not be empty.' );						//  throw Exception
		if( is_array( $value ) )																//  Pair Map given
			foreach( $value as $pairKey => $pairValue )											//  iterate given Pair Map
				$this->pairs[$key.".".$pairKey]	= $pairValue;									//  add Pair to stores Pairs
		else																					//  single Value given
			$this->pairs[$key]	= $value;														//  set Pair
		if( $sort )																				//  sort after Insertion is active
			ksort( $this->pairs );																//  sort stored Pairs by Keys
	}
}
?>