<?php
import( 'de.ceus-media.alg.time.DurationPhraseRanges' );
class Alg_Time_DurationPhraser
{
	protected $patternLabel	= '@(.*){(s|m|h|D|W|M|Y)}(.*)::([0-9]+)$@';
	protected $patternData	= '@::[0-9]+$@';
	protected $ranges		= NULL;
	
	public function __construct( $ranges = array() )
	{
		$this->ranges	= new Alg_Time_DurationPhraseRanges( $ranges );
	}
	
	public function getPhraseFromSeconds( $seconds )
	{
		if( !count( $this->ranges ) )
			throw new Exception( 'No ranges defined' );
		$callback	= array( $this, 'insertDates' );
		foreach( $this->ranges->getRanges() as $range )
		{
			if( !( $range['from'] <= $seconds && $range['to'] > $seconds ) )
				continue;
			$label	= $range['label'];
			$value	= $label."::".$seconds;
			$label	= preg_replace_callback( $this->patternLabel, $callback, $value );
			$label	= preg_replace( $this->patternData, "", $label );
			return $label;
		}
		throw new OutOfBoundException( 'No range defined for '.$seconds.' seconds' );
	}
	
	public function getPhraseFromTimestamp( $timestamp )
	{
		$seconds	= time() - $timestamp;
		if( $seconds < 0 )
			throw new InvalidArgumentException( 'Timestamp must lay in past' );
		return $this->getPhraseFromSeconds( $seconds );
	}
	
	protected static function insertDates( $matches )
	{
		$value	= $matches[4];
		$format	= $matches[2];
		if( $format == "m" )
			$value	= floor( $value / 60 );
		else if( $format == "h" )
			$value	= floor( $value / 60 / 60 );
		else if( $format == "D" )
			$value	= floor( $value / 60 / 60 / 24 );
		else if( $format == "W" )
			$value	= floor( $value / 60 / 60 / 24 / 7 );
		else if( $format == "M" )
			$value	= floor( $value / 60 / 60 / 24 / 30.4375 );
		else if( $format == "Y" )
			$value	= floor( $value / 60 / 60 / 24 / 365.25 );
		else if( $format !== "s" )
			throw new Exception( 'Unknown date format "'.$format.'"' );

		$value	= $matches[1].(int) $value.$matches[3];
		return $value;
	}
}
?>