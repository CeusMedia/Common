<?php
namespace CeusMedia\Common\UI\JS;

class CodeMirror
{
	protected $addons	= [];

	protected $theme	= [];

	protected $options	= array(
		'lineNumbers'				=> TRUE,
		'matchBrackets'				=> TRUE,
		'mode'						=> "application/x-httpd-php",
		'indentUnit'				=> 4,
		'indentWithTabs'			=> TRUE,
		'tabSize'					=> 4,
		'readOnly'					=> FALSE,
		'tabMode'					=> "shift",
		'enterMode'					=> "keep",
		'highlightSelectionMatches'	=> TRUE,
		'matchBrackets'				=> TRUE,
	);

	public function build( $textareaSelector, $options = array() )
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

	public function getOptions()
	{
		return $this->options;
	}

	public function setMode( $mode ): self
	{
		$this->setOption( 'mode', $mode );
		return $this;
	}

	public function setOption( $key, $value ): self
	{
		if( is_null( $value ) ){
			if( isset( $this->options[$key] ) )
				unset( $this->options[$key] );
		}
		else
			$this->options[$key]	= $value;
		return $this;
	}

	public function setReadOnly( $status = TRUE ): self
	{
		$this->setOption( 'readonly', (bool) $status );
		return $this;
	}

	public function setTheme( $theme ): self
	{
		$this->setOption( 'theme', $theme );
		return $this;
	}
}
