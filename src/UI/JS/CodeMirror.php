<?php
namespace CeusMedia\Common\UI\JS;

class CodeMirror
{
	protected $addons	= [];

	protected $theme	= [];

	protected $options	= [
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

	public function setOption( string $key, $value ): self
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
