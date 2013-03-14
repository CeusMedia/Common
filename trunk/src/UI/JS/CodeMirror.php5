<?php
class UI_JS_CodeMirror{

	protected $addons	= array(
	);
	protected $theme	= array(
	);
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

	public function build( $textareaSelector, $options = array() ){
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

	public function setMode( $mode ){
		$this->setOption( 'mode', $mode );
	}

	public function getOptions(){
		return $this->options;
	}

	public function setOption( $key, $value ){
		if( is_null( $value ) ){
			if( isset( $this->options[$key] ) )
				unset( $this->options[$key] );
		}
		else
			$this->options[$key]	= $value;
	}

	public function setReadOnly( $status = TRUE ){
		$this->setOption( 'readonly', (bool) $status );
	}

	public function setTheme( $theme ){
		$this->setOption( 'theme', $theme );
	}
}
?>
