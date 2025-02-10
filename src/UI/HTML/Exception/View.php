<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Visualisation of Exception.
 *
 *	Copyright (c) 2010-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_UI_HTML_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\HTML\Exception;

use CeusMedia\Common\ADT\JSON\Encoder as JsonEncoder;
use CeusMedia\Common\Exception\Runtime;
use CeusMedia\Common\Exception\SQL as SqlException;
use CeusMedia\Common\Exception\Traits\Descriptive;
use CeusMedia\Common\UI\HTML\Tag;
use CeusMedia\Database\SQLSTATE;
use Throwable;

/**
 *	Visualisation of Exception Stack Trace.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class View
{
	/**
	 *	Prints exception view.
	 *	@access		public
	 *	@param		Throwable	$exception		Exception
	 *	@return		void
	 */
	public static function display( Throwable $exception ): void
	{
		print self::render( $exception );
	}

	/**
	 *	@param		Throwable	$e
	 *	@param		bool		$showTrace
	 *	@param		bool		$showPrevious
	 *	@return		string
	 */
	public static function render( Throwable $e, bool $showTrace = TRUE, bool $showPrevious = TRUE ): string
	{
		$list	= [];

		$msg	= htmlentities( $e->getMessage(), ENT_COMPAT, 'UTF-8' );
		$list[]	= Tag::create( 'dt', 'Message', ['class' => 'exception-message'] );
		$list[]	= Tag::create( 'dd', $msg, ['class' => 'exception-message'] );

		if( $e->getCode() !== 0 ){
			$code	= htmlentities( $e->getCode(), ENT_COMPAT, 'UTF-8' );
			$list[]	= Tag::create( 'dt', 'Code', ['class' => 'exception-code'] );
			$list[]	= Tag::create( 'dd', $code, ['class' => 'exception-code'] );
		}

		self::enlistAdditionalProperties( $list, $e );

		$list[]	= Tag::create( 'dt', 'Type', ['class' => 'exception-type'] );
		$list[]	= Tag::create( 'dd', get_class( $e ), ['class' => 'exception-type'] );

		$pathName	= self::trimRootPath(  $e->getFile() );
		$fileName	= '<span class="file">'.pathinfo( $pathName, PATHINFO_FILENAME ).'</span>';
		$extension	= pathinfo( $pathName, PATHINFO_EXTENSION );
		$extension	= '<span class="ext">'.( $extension ? '.'.$extension : '' ).'</span>';
		$path		= '<span class="path">'.dirname( $pathName ).'/</span>';
		$file		= $path.$fileName.$extension;

		$list[]	= Tag::create( 'dt', 'File', ['class' => 'exception-file'] );
		$list[]	= Tag::create( 'dd',$file, ['class' => 'exception-file'] );

		$list[]	= Tag::create( 'dt', 'Line', ['class' => 'exception-line'] );
		$list[]	= Tag::create( 'dd', (string) $e->getLine(), ['class' => 'exception-line'] );

		if( $showTrace ){
			$trace	= Trace::render( $e );
			if( $trace ){
				$list[]	= Tag::create( 'dt', 'Trace' );
				$list[]	= Tag::create( 'dd', $trace );
			}
		}
		if( $showPrevious ){
			if( method_exists( $e, 'getPrevious' ) && $e->getPrevious() ){
				$list[]	= Tag::create( 'dt', 'Previous' );
				$list[]	= Tag::create( 'dd', View::render( $e->getPrevious() ) );
			}
		}
		return Tag::create( 'dl', join( $list ), ['class' => 'exception'] );
	}

	/**
	 *	@param		array			$list		Reference to current property list
	 *	@param		Throwable		$e
	 *	@return		void
	 */
	protected static function enlistAdditionalProperties( array & $list, Throwable $e ): void
	{
		$blacklist	= ['description', 'suggestion', 'traceAsString'];
		if( $e instanceof SqlException && $e->getSQLSTATE() ){
			if( class_exists( '\\CeusMedia\\Database\\SQLSTATE' ) ){
				$meaning	= SQLSTATE::getMeaning( $e->getSQLSTATE() );
				if( NULL !== $meaning ){
					$list[]	= Tag::create( 'dt', 'SQLSTATE', ['class' => 'exception-code-sqlstate'] );
					$list[]	= Tag::create( 'dd', $e->getSQLSTATE().': '.$meaning, ['class' => 'exception-code-sqlstate'] );
					$blacklist[]	= 'SQLSTATE';
				}
			}
		}
		if( in_array( Descriptive::class, class_uses( $e ), TRUE ) ){
			/** @var Runtime $e */
			if( '' !== $e->getDescription() ){
				$list[]	= Tag::create( 'dt', 'Description', ['class' => 'exception-description'] );
				$list[]	= Tag::create( 'dd', $e->getDescription(), ['class' => 'exception-description'] );
			}

			if( '' !== $e->getSuggestion() ){
				$list[]	= Tag::create( 'dt', 'Suggestion', ['class' => 'exception-suggestion'] );
				$list[]	= Tag::create( 'dd', $e->getSuggestion(), ['class' => 'exception-suggestion'] );
			}
			foreach( $e->getAdditionalProperties() as $key => $value ){
				if( in_array( $key, $blacklist, TRUE ) )
					continue;
				switch( gettype( $value ) ){
					case 'object':
					case 'array':
						$value	= JsonEncoder::create()->encode( $value );
						break;
					default:
						$value ??= '-empty-';
				}
				$list[]	= Tag::create( 'dt', ucfirst( $key ), ['class' => 'exception-'.$key] );
				$list[]	= Tag::create( 'dd', $value, ['class' => 'exception-'.$key] );
			}
		}
	}

	/**
	 *	Removes Document Root in File Names.
	 *	@access		protected
	 *	@static
	 *	@param		string		$fileName		File Name to clear
	 *	@return		string
	 */
	protected static function trimRootPath( string $fileName ): string
	{
		$rootPath	= $_SERVER['DOCUMENT_ROOT'] ?? '';
		if( !$rootPath || !$fileName )
			return '';
		$fileName	= str_replace( '\\', "/", $fileName );
		$cut		= substr( $fileName, 0, strlen( $rootPath ) );
		if( $cut == $rootPath )
			$fileName	= substr( $fileName, strlen( $rootPath ) );
		return $fileName;
	}
}
