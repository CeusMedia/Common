<?php
class ADT_List implements Countable {

	public $list;

	public function __construct( $list = array() ){
		if( !( is_array( $list ) || is_null( $list ) ) )
			throw new InvalidArgumentException( 'List must be an array' );
		$this->list		= $list;
	}

	public function count(){
		return count( $this->list );
	}

	public function getKeys(){
		return array_keys( $this->list );
	}

	public function getValues(){
		return array_values( $this->list );
	}

	public function raise( $index, $steps = 1 ){
		$steps	= abs( (int) $steps );
		$index	= (int) $index;
		if( $steps && $index > 0 && $index < count( $this ) ){
			$swap	= $this->list[$index - 1];
			$this->list[$index - 1]	= $this->list[$index];
			$this->list[$index]		= $swap;
			$this->raise( --$index, --$steps );
		}
	}

	public function sink( $index, $steps = 1 ){
		$steps	= abs( (int) $steps );
		$index	= (int) $index;
		if( $steps && $index >= 0 && $index < count( $this ) -1 ){
			$swap	= $this->list[$index + 1];
			$this->list[$index + 1]	= $this->list[$index];
			$this->list[$index]		= $swap;
			$this->sink( ++$index, --$steps );
		}
	}
}
?>
