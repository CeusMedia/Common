<?php
namespace CeusMedia\Common\FS;

abstract class AbstractNode
{
	protected $pathName;

	public function getName( bool $strict = TRUE ): string
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
