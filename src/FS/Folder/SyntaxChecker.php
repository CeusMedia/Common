<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Checks Syntax of all PHP Classes and Scripts within a Folder.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\Folder;

use CeusMedia\Common\Alg\Time\Clock;
use CeusMedia\Common\FS\File\SyntaxChecker as FileSyntaxChecker;

/**
 *	Checks Syntax of all PHP Classes and Scripts within a Folder.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class SyntaxChecker
{
	/**	@var		string		$phpExtension	Extension of PHP Files, by default 'php5' */
	public static $phpExtension	= 'php5';

	/**	@var		object		$checker		Instance of SyntaxChecker */
	protected $checker;

	/**	@var		string		$lineBreak		Line break, depending on environment */
	protected $lineBreak;

	/**	@var		array		$failures		List of collected features */
	protected $failures;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->lineBreak	= getEnv( 'HTTP_HOST' ) ? "<br/>" : "\n";
		$this->checker		= new FileSyntaxChecker;
	}

	/**
	 *	Checks Syntax of all PHP Files within a given Folder and returns collected Information as Array.
	 *	For Console you can set 'verbose' to TRUE and you will see the Progress and the Results printed out.
	 *	@access		public
	 *	@param		string		$pathName		Path to Folder to check within
	 *	@param		bool		$verbose		Flag: active Output of Progress and Results
	 *	@return		array
	 */
	public function checkFolder( string $pathName, bool $verbose = FALSE ): array
	{
		$counter	= 0;
		$invalid	= [];
		$clock		= new Clock;
		$index		= new RecursiveRegexFilter( $pathName, "@\.".self::$phpExtension."$@" );
		$this->failures	= [];
		foreach( $index as $file ){
			$counter++;
			$fileName	= $file->getPathname();
			$shortName	= substr( $fileName, strlen( $pathName) );
			$valid		= $this->checker->checkFile( $file->getPathname() );
			if( !$valid ){
				$error	= $this->checker->getShortError();
				$invalid[$fileName]	= $error;
				remark( $shortName.': '.$error );
			}
			else
				remark( $shortName.': valid.' );
		}
		if( $verbose )
			$this->printResults( $invalid, $counter, $clock->stop( 0, 1 ) );
		$result	= [
			'numberFiles'	=> $counter,
			'numberErrors'	=> count( $invalid ),
			'listErrors'	=> $invalid,
		];
		return $result;
	}

	/**
	 *	Prints Results.
	 *	@access		protected
	 *	@param		array		$invalid		Array of Invalid Files and Errors
	 *	@param		int			$counter		Number of checked Files
	 *	@param		float		$time			Time needed to check Folder in Seconds
	 *	@return		void
	 */
	protected function printResults( array $invalid, int $counter, float $time )
	{
		remark( str_repeat( '-', 79 ) );
		if( count( $invalid ) ){
			remark( 'valid Files: '.( $counter - count( $invalid ) ) );
			remark( 'invalid Files: '.count( $invalid ) );
			foreach( $invalid as $fileName => $error )
				remark( '1. '.$fileName.': '.$error );
		}
		else{
			remark( 'All '.$counter.' Files are valid.' );
		}
		remark( 'Time: '.$time.' sec' );
	}
}
