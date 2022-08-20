<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\FS;

/**
 *	@todo		finish implementation, seems to be not finished
 *	@todo		code doc
 */
class Link extends AbstractNode
{
	protected $pathName;

	public function __construct( string $pathName )
	{
		$this->setPathName( $pathName );
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
