<?php
/**
 *	Returns normalizes Bytes .
 *	@access		public
 *	@param		int		$bytes		Quantity of Bytes
 *	@param		int		$accuracy	Number of decimal Places
 *	@param		int		$prefix		Known Prefix of Measure(K|M|G|T)
 *	@param		int		$separator	Separator Sign between Bytes and Measure
 *	@return		string
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		06.01.2007
 *	@version	0.1
 */
function getNormalBytes( $bytes, $accuracy = 0, $prefix = "", $separator = " " )
{
	$prefix	= strtoupper( $prefix );
	$prefixes	= array( "", "K", "M", "G", "T" );
	$index	= array_search( $prefix, $prefixes );
	while( $bytes / 1024 > 1 )
	{
		if( $index + 1 < count( $prefixes ) )
		{
			$index++;
			$bytes	/= 1024;
		}
	}
	if( !$index )
		return round( $bytes, $accuracy ).$separator."B";
	return round( $bytes, $accuracy ).$separator.$prefixes[$index]."B";
}
?>