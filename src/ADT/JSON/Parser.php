<?php
/**
 *	JSON Parser.
 *
 *	Copyright (c) 2010-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */
namespace CeusMedia\Common\ADT\JSON;

/**
 *	JSON Parser.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */
class Parser
{
	const STATUS_EMPTY			= 0;
	const STATUS_PARSED			= 1;
	const STATUS_ERROR			= 2;

	protected $status			= 0;

	/**
	 *	Returns constant value or key of last parse error.
	 *	@access		public
	 *	@param		boolean		$asConstantKey	Flag: return constant name as string instead of its integer value
	 *	@return		integer|string
	 */
	public function getError( $asConstantKey = FALSE )
	{
		$code	= json_last_error();
		if( $asConstantKey )
			return $this->getConstantFromCode( $code );
		return $code;
	}

	/**
	 *	Get new instance of JSON reader by static call.
	 *	This method is useful for chaining method calls.
	 *	@access		public
	 *	@static
	 *	@return		self
	 */
	public static function getNew(): self
	{
		return new self();
	}

	/**
	 *	Returns all information of last parse error.
	 *	@access		public
	 *	@return		object
	 */
	public function getInfo()
	{
		return (object) array(
			'status'	=> $this->status,
			'code'		=> $this->getError(),
			'constant'	=> $this->getError( TRUE ),
			'message'	=> $this->getMessage(),
		);
	}

	/**
	 *	Returns message of last parse error.
	 *	@access		public
	 *	@return		string
	 */
	public function getMessage()
	{
		return json_last_error_msg();
	}

	/**
	 *	Returns data of parsed JSON string.
	 *	@access		public
	 *	@param		string		$json			JSOn sting to parse
	 *	@param		boolean		$asArray		Flag: read into an array
	 *	@return		object|array
	 *	@throws		\RuntimeException			if parsing failed
	 */
	public function parse( string $json, bool $asArray = NULL )
	{
		$this->status	= static::STATUS_EMPTY;
		$data			= json_decode( $json, $asArray );
		if( json_last_error() !== JSON_ERROR_NONE ){
			$this->status	= static::STATUS_ERROR;
			$message	= 'Decoding JSON failed (%s): %s';
			$message	= vsprintf( $message, array(
				$this->getConstantFromCode( json_last_error() ),
				json_last_error_msg()
			) );
			throw new \RuntimeException( $message, json_last_error() );
		}
		$this->status	= static::STATUS_PARSED;
		return $data;
	}

	protected function getConstantFromCode( $code )
	{
		return \ADT_Constant::getKeyByValue( 'JSON_ERROR_', $code );
	}
}
