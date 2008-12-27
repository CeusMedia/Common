<?php
/**
 *	@package		console.command
 */
class Console_Command_ArgumentParser
{
	const STATUS_START				= 0; 
	const STATUS_READ_OPTION_KEY	= 1; 
	const STATUS_READ_OPTION_VALUE	= 2;
	const STATUS_READ_ARGUMENT		= 3; 
	
	protected $parsed				= FALSE;

	protected $foundArguments		= array();
	protected $foundOptions			= array();

	protected $numberArguments		= 0;
	protected $possibleOptions		= array();
	protected $shortcuts			= array();


	protected function extendPossibleOptionsWithShortcuts()
	{
		foreach( $this->shortcuts as $short	=> $long )
		{
			if( !isset( $this->possibleOptions[$long] ) )
				throw new InvalidArgumentException( 'Invalid shortcut to not existing option "'.$long.'" .' );
			$this->possibleOptions[$short]	= $this->possibleOptions[$long];
		}
	}

	protected function finishOptions()
	{
		foreach( $this->shortcuts as $short	=> $long )
		{
			if( !array_key_exists( $short, $this->foundOptions ) )
				continue;
			$this->foundOptions[$long]	= $this->foundOptions[$short];
			unset( $this->foundOptions[$short] );
		}
	}

	public function getArguments()
	{
		if( !$this->parsed )
			throw new RuntimeException( 'Nothing parsed yet.' );
		return $this->foundArguments;
	}

	public function getOptions()
	{
		if( !$this->parsed )
			throw new RuntimeException( 'Nothing parsed yet.' );
		return $this->foundOptions;
	}

	public function parse( $string )
	{
		if( !is_string( $string ) )
			throw new InvalidArgumentException( 'Given argument is not a string.' );

		$position	= 0;
		$status		= 0;
		$buffer		= "";
		$option		= "";
		$options	= array();
		
		$this->extendPossibleOptionsWithShortcuts();
		
		while( 1 )
		{
			if( !isset( $string[$position] ) )
			{
				if( $status == 3 )
					$this->foundArguments[]	= $buffer;
				else if( $status == 2 )
				{
					$this->foundOptions[$option]	= $buffer;
				}
				else if( $status == 1 )
				{
					if( !array_key_exists( $option, $this->possibleOptions ) )
						throw new InvalidArgumentException( 'Invalid Option: '.$option.'.' );
					if( $this->possibleOptions[$option] )
						throw new RuntimeException( 'Missing value of option "'.$option.'".' );
					$this->foundOptions[$option]	= NULL;
				}
				if( count( $this->foundArguments ) < $this->numberArguments )
					throw new RuntimeException( 'Missing argument.' );
				$this->finishOptions();
				$this->parsed	= TRUE;
				break;
			}
			
			$sign	= $string[$position];
			$position ++;

			if( $status == 0 )
			{
				if( $sign == "-" )
				{
					$status	= 1;
					$option	= "";
				}
				else if( preg_match( "@[a-z0-9]@i", $sign ) )
				{
					$status = 3;
					$buffer	.= $sign;
				}
				continue;
			}
			if( $status == 1 )
			{
				if( $sign == "-" )
					continue;
				if( $sign == " " )
				{
					if( !array_key_exists( $option, $this->possibleOptions ) )
						throw new InvalidArgumentException( 'Invalid Option: '.$option.'.' );
					if( !$this->possibleOptions[$option] )
					{
						$this->foundOptions[$option]	= NULL;
						$status	= 0;
					}
					else
					{
						$buffer	= "";
						$status = 2;
					}
				}
				else
					$option	.= $sign;
			}
			else if( $status == 2 )
			{
				if( $sign == "-" )																	//  illegal Option following
					throw new RuntimeException( 'Missing value of option "'.$option.'".' );
				if( $sign == " " )																	//  closing value...
				{
					if( $buffer )																	//  ...only if has value
					{
						if( $this->possibleOptions[$option] !== TRUE )										//  must match regexp
							if( !preg_match( $this->possibleOptions[$option], $buffer ) )					//  not matching
								throw new InvalidArgumentException( 'Argument "'.$option.'" has invalid value.' );
						$this->foundOptions[$option]	= $buffer;
						$buffer	= "";
						$status	= 0;
					}
					continue;
				}
				$buffer	.= $sign;
			}
			else if( $status == 3 )
			{
				if( $sign == " " )
				{
					$this->foundArguments[]	= $buffer;
					$buffer		= "";
					$status		= 0;
					continue;
				}
				$buffer	.= $sign;
			}
		}
	}
	
	public function setNumberOfArguments( $number = 0 )
	{
		$this->numberArguments	= $number;	
	}


	public function setPossibleOptions( $optionArray )
	{
		$this->possibleOptions	= $optionArray;
	}
	public function setShortcuts( $shortcutsMap )
	{
		$this->shortcuts	= $shortcutsMap;
	}
}
?>