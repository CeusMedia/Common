<?php

/**
 *	HTML code generator for CodeMirror.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\JS;

/**
 *	HTML code generator for CodeMirror.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class CodeMirror
{
	protected array $addons		= [];

	protected array $theme		= [];

	protected array $options	= [
		'lineNumbers'				=> TRUE,
		'mode'						=> "application/x-httpd-php",
		'indentUnit'				=> 4,
		'indentWithTabs'			=> TRUE,
		'tabSize'					=> 4,
		'readOnly'					=> FALSE,
		'tabMode'					=> "shift",
		'enterMode'					=> "keep",
		'highlightSelectionMatches'	=> TRUE,
		'matchBrackets'				=> TRUE,
	];

	public function build( string $textareaSelector, array $options = [] ): string
	{
		$options	= array_merge( $this->options, $options );
		ksort( $options );
		$script		= '
var cmOptions = '.json_encode( $options ).';
$("'.$textareaSelector.'").each(function(){
	$(this).data("codemirror", CodeMirror.fromTextArea(this, cmOptions));
	$(this).data("codemirror-options", cmOptions);
})';
		return $script;
	}

	public function getOptions(): array
	{
		return $this->options;
	}

	public function setMode( string $mode ): self
	{
		$this->setOption( 'mode', $mode );
		return $this;
	}

	/**
	 *	@param		string		$key
	 *	@param		mixed		$value
	 *	@return		self
	 */
	public function setOption( string $key, mixed $value ): self
	{
		if( is_null( $value ) ){
			if( isset( $this->options[$key] ) )
				unset( $this->options[$key] );
		}
		else
			$this->options[$key]	= $value;
		return $this;
	}

	public function setReadOnly( bool $status = TRUE ): self
	{
		$this->setOption( 'readonly', $status );
		return $this;
	}

	public function setTheme( string $theme ): self
	{
		$this->setOption( 'theme', $theme );
		return $this;
	}
}
