<?php
class File_CSS_Converter{
	public function __construct( ADT_CSS_Sheet $sheet = NULL ){
		if( $sheet )
			$this->fromSheet( $sheet );
	}
	
	public function fromSheet( ADT_CSS_Sheet $sheet ){
		$this->sheet	= $sheet;
	}

	public function fromFile( $fileName ){
		$this->sheet		= File_CSS_Parser::parseFile( $fileName );
	}
	
	public function toArray(){
		$level0	= array();
		foreach( $this->sheet->getRules() as $rule ){
			$level1	= array();
			foreach( $rule->getProperties() as $property )
				$level1[$property->getKey()]	= $property->getValue();
			$level0[$rule->getSelector()]	= $level1;
		}
		return $level0;
	}

	public function toString(){
		$lines	= array();
		foreach( $this->sheet->getRules() as $rule ){
			array_push( $lines, $rule->getSelector().' {' );
			foreach( $rule->getProperties() as $property )
				array_push( $lines, "\t".$property->getKey().': '.$property->getValue().';' );		//  
			$lines[]	= "\t".'}';
		}
		array_push( $lines, '' );
		$css	= implode( "\n", $lines );
		return $css;
	}

	public function toSheet(){
		return $this->sheet;
	}
}
