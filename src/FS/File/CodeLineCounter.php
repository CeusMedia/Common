<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Counter for Lines of Code of a File.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File;

/**
 *	Counter for Lines of Code of a File.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class CodeLineCounter
{
	/**
	 *	Reads File and counts Code Lines, Documentation Lines and unimportant Lines and returns a Data Array.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		Name File to analyse
	 *	@return		array
	 */
	public static function countLines( string $fileName ): array
	{
		$content	= Reader::load( $fileName );
		return self::countLinesFromSource( $content );
	}

	/**
	 *	Reads File and counts Code Lines, Documentation Lines and unimportant Lines and returns a Data Array.
	 *	@access		public
	 *	@static
	 *	@param		string		$content		Source Code of File
	 *	@return		array
	 */
	public static function countLinesFromSource( string $content ): array
	{
		$counter		= 0;
		$numberCodes	= 0;
		$numberDocs		= 0;
		$numberStrips	= 0;
		$linesCodes		= [];
		$linesDocs		= [];
		$linesStrips	= [];

		$lines		= explode( "\n", $content );
		foreach( $lines as $line ){
			if( preg_match( "@^(\t| )*/?\*@", $line ) ){
				$linesDocs[$counter] = $line;
				$numberDocs++;
			}
			else if( preg_match( "@^(<\?php|<\?|\?>|\}|\{|\t| )*$@", trim( $line ) ) ){
				$linesStrips[$counter] = $line;
				$numberStrips++;
			}
			else if( preg_match( "@^(public|protected|private|class|function|final|define|import)@", trim( $line ) ) ){
				$linesStrips[$counter] = $line;
				$numberStrips++;
			}
			else{
				$linesCodes[$counter] = $line;
				$numberCodes++;
			}
			$counter ++;
		}
		return [
			'length'		=> strlen( $content ),
			'numberCodes'	=> $numberCodes,
			'numberDocs'	=> $numberDocs,
			'numberStrips'	=> $numberStrips,
			'linesTotal'	=> $counter,
			'linesCodes'	=> $linesCodes,
			'linesDocs'		=> $linesDocs,
			'linesStrips'	=> $linesStrips,
			'ratioCodes'	=> $numberCodes / $counter * 100,
			'ratioDocs'		=> $numberDocs / $counter * 100,
			'ratioStrips'	=> $numberStrips / $counter * 100,
		];
	}
}
