<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_FS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_FS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
abstract class AbstractNode
{
	protected string $pathName;

	/**
	 *	Static constructor.
	 *	@param		string		$pathName
	 *	@return		static
	 */
	public static function new( string $pathName ): static
	{
		$className	= static::class;
		return new $className( $pathName );
	}

	public function __construct( string $pathName )
	{
		$this->setPathName( $pathName );
	}

	public function getName(): string
	{
		return pathinfo( $this->pathName, PATHINFO_BASENAME );
	}

	public function getPathName(): string
	{
		return $this->pathName;
	}

	public function setPathName( string $pathName ): self
	{
		$pathName	= trim( $pathName );
		if( $pathName !== '/' )
			$pathName	= rtrim( $pathName, '/' );
		$this->pathName	= $pathName;
		return $this;
	}
}
