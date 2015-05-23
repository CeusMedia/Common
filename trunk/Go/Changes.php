<?php
class Go_Changes
{
	public function __construct()
	{
		$path		= dirname( dirname( __FILE__ ) )."/";
		$revisions	= 1000;
		$prefix		= "";
		$fileTmp	= "log.tmp";
		$fileTarget	= $path."docs/changes.md";

		if( !file_exists( $fileTmp ) )
			exec( "svn log -l".$revisions." ".$path." --verbose > ".$fileTmp );
		$c	= file_get_contents( $fileTmp );
		$c	= preg_replace( "/------------------------------------------------------------------------/", "----", $c );
		$c	= preg_replace( "/(\n+)----/s", "\n----", $c );
		$c	= preg_replace( "/\n\n/", "µµ", $c );
		$c	= preg_replace( "/----(.*)µµ(.*)\n/Us", "\n###\\2\n\\1\n", $c );
		$c	= preg_replace( "/µµ/", "\n\n", $c );
		$c	= preg_replace( "/   (A|D|M|R) /", "- \\1 ", $c );
		$c	= preg_replace( "/(Geänderte Pfade|Changed paths):/", "", $c );
		$c	= preg_replace( "/ \| [0-9] (Zeilen|Zeile|lines|line)/", "", $c );
		if( $prefix )
			$c	= str_replace( "###".$prefix, "###", $c );

		file_put_contents( $fileTarget, $c );
		unlink( $fileTmp );
	}
}
