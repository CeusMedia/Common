<?php
/**
 *	Google PageRank.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@package		net.http
 *	@uses			Net_cURL
 *	@author			Emir Plicanic
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.01.2007
 *	@version		0.6
 */
import( 'de.ceus-media.net.cURL' );
define( 'GOOGLE_MAGIC', 0xE6359A60 );
/**
 *	Google PageRank.
 *	@package		net.http
 *	@uses			Net_cURL
 *	@author			Emir Plicanic
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.01.2007
 *	@version		0.6
 */
class Net_HTTP_PageRank
{
	/**
	 *	Calculates Checksum of URL for Google Request
	 *	@access		private
	 *	@param		array		$url		URL as numeric Array
	 *	@return		string
	 */
	private static function calculateChecksum( $url )
	{ 
		$length	= sizeof( $url );
		$a		= $b = 0x9E3779B9;
		$c		= GOOGLE_MAGIC;
		$k		= 0;
		$len	= $length;
		while( $len >= 12 )
		{ 
			$a		+= ( $url[$k+0] + ( $url[$k+1] <<8 ) + ( $url[$k+2] << 16 ) + ( $url[$k+3] << 24 ) );
			$b		+= ( $url[$k+4] + ( $url[$k+5] <<8 ) + ( $url[$k+6] << 16 ) + ( $url[$k+7] << 24 ) );
			$c		+= ( $url[$k+8] + ( $url[$k+9] <<8 ) + ( $url[$k+10] << 16 )+ ( $url[$k+11] << 24 ) );
			$mix	= self::mix( $a, $b, $c);
			$a		= $mix[0];
			$b		= $mix[1];
			$c		= $mix[2];
			$k		+= 12;
			$len	-= 12;
		}
		$c += $length;
		switch( $len )
		{ 
			case 11: $c	+=( $url[$k+10] << 24 );
			case 10: $c	+=( $url[$k+9] << 16 );
			case 9 : $c	+=( $url[$k+8] << 8 );
			/* the first byte of c is reserved for the length */
			case 8 : $b	+=( $url[$k+7] << 24 );
			case 7 : $b	+=( $url[$k+6] << 16 );
			case 6 : $b	+=( $url[$k+5] << 8 );
			case 5 : $b	+=( $url[$k+4]);
			case 4 : $a	+=( $url[$k+3] << 24 );
			case 3 : $a	+=( $url[$k+2] << 16 );
			case 2 : $a	+=( $url[$k+1] << 8 );
			case 1 : $a	+=( $url[$k+0] );
		} 
		$mix	= self::mix( $a, $b, $c );
		return	$mix[2];
	} 

	/**
	 *	Returns Google PageRank.
	 *	@access		public
	 *	@param		string		$url		URL to check (e.G. www.domain.com)
	 *	@return		int
	 */
	public static function get( $url )
	{
		$checksum	= "6".self::calculateChecksum( self::strord( "info:".$url ) );
		$googleUrl	= "www.google.com/search?client=navclient-auto&ch=".$checksum."&features=Rank&q=info:".$url;
		$curl		= new Net_cURL( $googleUrl );
		$response	= $curl->exec();
		$position	= strpos( $response, "Rank_" );
		if( $position === FALSE )
			throw new Exception( 'Response does not contain Rank.' );
		$pagerank = substr( $response, $position + 9 );
		return (int) $pagerank;
	}

	private static function mix( $a, $b, $c)
	{ 
		$a -= $b; $a -= $c; $a ^= ( self::zeroFill( $c, 13 ) );
		$b -= $c; $b -= $a; $b ^= ( $a << 8 );
		$c -= $a; $c -= $b; $c ^= ( self::zeroFill( $b, 13 ) );
		$a -= $b; $a -= $c; $a ^= ( self::zeroFill( $c, 12 ) );
		$b -= $c; $b -= $a; $b ^= ( $a << 16 );
		$c -= $a; $c -= $b; $c ^= ( self::zeroFill( $b, 5 ) );
		$a -= $b; $a -= $c; $a ^= ( self::zeroFill( $c, 3 ) );
		$b -= $c; $b -= $a; $b ^= ( $a << 10 );
		$c -= $a; $c -= $b; $c ^= ( self::zeroFill( $b, 15 ) );
		return array( $a, $b, $c);
	}

	/**
	 *	Converts a String into an Array of Integers containing the numeric Values of the Characters.
	 *	@access		private
	 *	@param		string		$string		String to convert
	 *	@return		array
	 */
	private static function strord( $string )
	{ 
		for( $i=0; $i<strlen( $string ); $i++ )
		{ 
			$result[$i] = ord( $string{$i} );
		}
		return $result;
	} 
	
	private static function zeroFill( $a, $b )
	{
		$z	= hexdec( 80000000 );
		if( $z & $a )
		{ 
			$a	= ( $a >> 1 );
			$a	&= ( ~$z );
			$a	|= 0x40000000;
			$a	= ( $a >> ( $b - 1 ) );
		}
		else
		{
			$a	= ( $a >> $b );
		}
		return $a;
	} 
}
?>
