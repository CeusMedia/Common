<?php

namespace CeusMedia\Common\Exception\Traits;

trait Descriptive
{
	protected string $description	= '';

	protected string $suggestion	= '';

	public function getAdditionalProperties(): array
	{
		$variables	= get_object_vars( $this );
		foreach( $variables as $key => $value )
			if( in_array( $key, ['message', 'code', 'file', 'line', 'trace', 'previous'], TRUE ) )
				unset( $variables[$key] );
		return $variables;
	}

	/**
	 *	@return		string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 *	@return		string
	 */
	public function getSuggestion(): string
	{
		return $this->suggestion;
	}

	/**
	 * @param string $suggestion
	 * @return static
	 *
	 */
	public function setSuggestion( string $suggestion ): static
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
