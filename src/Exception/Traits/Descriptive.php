<?php

namespace CeusMedia\Common\Exception\Traits;

trait Descriptive
{
	protected string $description	= '';

	protected string $suggestion	= '';

	public function getDescription(): string
	{
		return $this->description;
	}

	public function getAdditionalProperties(): array
	{
		$variables	= get_object_vars( $this );
		foreach( $variables as $key => $value )
			if( in_array( $key, ['message', 'code', 'file', 'line', 'trace'], TRUE ) )
				unset( $variables[$key] );
		return $variables;
	}

	public function getSuggestion(): string
	{
		return $this->suggestion;
	}

	public function setSuggestion( string $suggestion ): self
	{
		$this->suggestion	= $suggestion;
		return $this;
	}

	public function setDescription( string $description ): self
	{
		$this->description	= $description;
		return $this;
	}
}
