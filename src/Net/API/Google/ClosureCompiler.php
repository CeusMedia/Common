<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Minify Javascript using Google's Closure Compiler API.
 *
 *	Copyright (c) 2015-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_API_Google
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@link			http://code.google.com/closure/compiler/
 */

namespace CeusMedia\Common\Net\API\Google;

use CeusMedia\Common\Net\HTTP\Post;
use RuntimeException;

/**
 *	@category		Library
 *	@package		CeusMedia_Common_Net_API_Google
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@link			http://code.google.com/closure/compiler/
 */
class ClosureCompiler
{
	protected const URL = 'https://closure-compiler.appspot.com/compile';

	protected $options;

	/**
	 *
	 *	@param		array		$options
	 *
	 * fallbackFunc : default array($this, 'fallback');
	 */
	public function __construct( array $options = [] )
	{
		$this->options	= $options;
	}

	protected function compile( string $js, bool $returnErrors = FALSE ): string
	{
		$data	= [
			'js_code'			=> $js,
			'output_info'		=> $returnErrors ? 'errors' : 'compiled_code',
			'output_format'		=> 'text',
			'compilation_level'	=> 'SIMPLE_OPTIMIZATIONS'
		];
		return Post::sendData( self::URL, $data );
	}

	public function min( string $js ): string
	{
		$response	= $this->compile( $js );
		if( preg_match( '/^Error\(\d\d?\):/', $response ) )
			throw new RuntimeException( 'Received errors from Closure Compiler API: '.$response );
		if( !strlen( trim( $response ) ) )
			throw new RuntimeException( $this->compile( $js, TRUE ) );
		return $response;
	}

	/**
	 * Minify Javascript code via HTTP request to the Closure Compiler API
	 *
	 *	@param		string		$js			input code
	 *	@param		array		$options	unused at this point
	 *	@return		string
	 */
	public static function minify( string $js, array $options = [] ): string
	{
		$obj = new self( $options );
		return $obj->min( $js );
	}
}
