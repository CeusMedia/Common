<?php
class LibCallCounter
{
	public static $classBlacklist	= array(
		'de.ceus-media.throwException',
		'de.ceus-media.LibCallCounter',
		'de.ceus-media.ui.DevOutput'
	);
	public static $classCalls	= array();
	public static $logScheme	= "compress.zlib://";
	public static $logFileName	= "calls.json.gz";

	public static function closeCall()
	{
		$mode	= defined( 'CM_CLASSES_CALL_LOG_MODE' ) ? CM_CLASSES_CALL_LOG_MODE : 0;
		if( ( $mode & 1 ) !== 1 )
			return;
		$data	= self::readData();
		$data[0]--;
		if( ( $mode & 2 ) == 2 )
		{
			$classList	= array_keys( $GLOBALS['imported'] );
			$classList	= array_diff( $classList, self::$classBlacklist );
			$key		= $data[1]."-".time(); 
			if( $classList )
				$data[4][$key]	= $classList;
		}
		self::writeData( $data );
	}

	public static function getRawData()
	{
		$data	= self::readData();
		return $data;
	}

	public static function getCalls()
	{
		$data	= self::readData();
		return $data[1];
	}
	
	protected static function getFileName()
	{
		return dirname( __FILE__)."/".self::$logFileName;
	}

	public static function getOpenCalls()
	{
		$data	= self::readData();
		return $data[0];
	}

	public static function openCall()
	{
		$mode	= defined( 'CM_CLASSES_CALL_LOG_MODE' ) ? CM_CLASSES_CALL_LOG_MODE : 0;
		if( ( $mode & 1 ) !== 1 )
			return;
		$data	= self::readData();
		$data[0]++;																				//  increase number of paraller calls
		$data[1]++;																				//  increase number of total calls
		self::writeData( $data );																//  save new information
	}
	
	protected static function readData()
	{
		$fileName	= self::getFileName();
		$data	= array( 0, 0, time(), time(), array() );
		if( file_exists( $fileName ) )
			$data	= json_decode( file_get_contents( self::$logScheme.$fileName ), TRUE );
		return $data;
	}

	public static function setup()
	{
		self::openCall();
		register_shutdown_function(array('LibCallCounter','closeCall'));
	}
	
	protected static function writeData( $data )
	{
		$fileName	= self::getFileName();
		return file_put_contents( self::$logScheme.$fileName, json_encode( $data ) );
	}
	
	protected static function report()
	{
		
		
	}
}
?>