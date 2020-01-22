<?php
class CLI_Output_Progress{

	protected $total	= 0;

	public function __construct(){
		$this->width	= CLI_Dimensions::getWidth() - 3;
		$this->output	= new CLI_Output();
		$this->output->setMaxLineLength( $this->width );
	}

	public function setTotal( $total ){
		$this->total	= $total;
	}

	public function start(){
		if( !$this->total )
			throw new RuntimeException( 'No total set' );
		$this->startTime	= microtime( TRUE );
		$line	= $this->renderLine( 0, $this->total, $this->width );
		$this->output->newLine( $line );
	}

	public function update( $count ){
		if( !$this->total )
			throw new RuntimeException( 'No total set' );

		$line	= $this->renderLine( $count, $this->total, $this->width );
		$this->output->sameLine( $line );
	}

	public function finish(){
		$this->update( $this->total );
		$this->output->newLine();
	}

	/*  --  PROTECTED  --  */

	protected function estimateTimeLeft( $count ){
		if( $count === 0 )
			return 0;
		if( $count === $this->total )
			return 0;

		$timeDiff	= microtime( TRUE ) - $this->startTime;
		if( ( $count / $this->total * 100 ) < 1 || $timeDiff < 1 )
			return 0;

		$ratio		= $this->total / $count;
		$timeLeft	= ceil( $timeDiff * $ratio - $timeDiff );
		return $timeLeft;
	}

	protected function formatTime( $seconds, $nrParts = 2 ){
		if( $seconds < 1 )
			return '';
		$days		= 0;
		$hours		= 0;
		$minutes	= 0;
		$parts		= array();
		if( $seconds > 86400 ){
			$days		= floor( $seconds / 86400 );
			$seconds	-= $days * 86400;
			$parts[]	= $days.'d';
		}
		if( $seconds > 3600 ){
			$hours		= floor( $seconds / 3600 );
			$seconds	-= $hours * 3600;
			if( $days )
				$parts[]	= str_pad( $hours, 2, 0, STR_PAD_LEFT ).'h';
			else
				$parts[]	= $hours.'h';

		}
		if( $seconds > 60 ){
			$minutes	= floor( $seconds / 60 );
			$seconds	-= $minutes * 60;
			if( $days || $hours )
				$parts[]	= str_pad( $minutes, 2, 0, STR_PAD_LEFT ).'m';
			else
				$parts[]	= $minutes.'m';
		}
		if( $days || $hours || $minutes )
			$parts[]	= str_pad( $seconds, 2, 0, STR_PAD_LEFT ).'s';
		else
			$parts[]	= $seconds.'s';

		if( $nrParts )
			$parts		= array_slice( $parts, 0, $nrParts );
		return implode( ' ', $parts );

		return Alg_Time_Duration::render( $seconds );
	}



	protected function renderLine( $count, $total, $width ){
		$count		= min( $count, $total );
		$timeLeft	= ceil( microtime( TRUE ) - $this->startTime );
		if( $count !== $this->total )
			$timeLeft	= $this->estimateTimeLeft( $count );
		$timeLeft	= str_pad( $this->formatTime( $timeLeft ), 8, ' ', STR_PAD_LEFT );
		$ratio		= str_pad( floor( $count / $total * 100 ), 4, ' ', STR_PAD_LEFT ).'%';
		$numbers	= str_pad( $count.'/'.$total, strlen( $total ) * 2 + 2, ' ', STR_PAD_LEFT );
		$barWidth	= $width - strlen( $timeLeft ) - strlen( $ratio ) - strlen( $numbers );
		$length1	= floor( $count / $total * $barWidth );
		$length2	= $barWidth - $length1;
		$line		= '['.str_repeat( '#', $length1 ).str_repeat( '.', $length2 ).']'.$numbers.$ratio.$timeLeft;
		return $line;
	}
}
