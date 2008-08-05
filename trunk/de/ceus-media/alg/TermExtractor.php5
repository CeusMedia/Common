<?php
/**
 *	Extracts Terms from a Text Document.
 *	@package		alg
 */
import( 'de.ceus-media.alg.StringUnicoder' );
import( 'de.ceus-media.file.Editor' );
/**
 *	Extracts Terms from a Text Document.
 *	@package		alg
 *	@todo			Code Doc
 */
class Alg_TermExtractor
{
	protected $list				= array();
	public static $blacklist	= array();

	public function getTerms( $text )
	{
		$list	= array();
		$lines	= explode( "\n", $text );
		foreach( $lines as $line )
		{
			$words	= explode( " ", trim( $line ) );
			foreach( $words as $word )
			{
				$word	= trim( $word );
				$word	= preg_replace( "@^\(@i", "", $word );
				$word	= preg_replace( "@\)$@i", "", $word );
				$word	= preg_replace( "@,|;$@i", "", $word );
				$word	= trim( $word );
				if( strlen( $word ) < 2 )
					continue;
				
				if( !Alg_StringUnicoder::isUnicode( $word ) )
					$word	= Alg_StringUnicoder::convertToUnicode( $word );
				
				if( in_array( $word, self::$blacklist ) )
					continue;
				
				if( $word )
				{
					if( !isset( $list[$word] ) )
						$list[$word]	= 0;
					$list[$word]++;
				}
			}
		}
		arsort( $list );
		return $list;
	}
	
	public function loadBlacklist( $fileName )
	{
		$string	= File_Editor::load( $fileName );
		if( !Alg_StringUnicoder::isUnicode( $string ) )
		{
			$string	= Alg_StringUnicoder::convertToUnicode( $string );
			File_Editor::save( $fileName, $string );
		}
		$list	= File_Editor::loadArray( $fileName );
		$this->setBlacklist( $list );
	}
	
	public function setBlacklist( $list )
	{
		self::$blacklist	= $list;
	}
}
?>