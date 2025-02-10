<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Exception;

use CeusMedia\Common\ADT\JSON\Encoder as JsonEncoder;
use CeusMedia\Common\Exception\Conversion as ConversionException;
use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\Exception\Runtime as RuntimeException;
use CeusMedia\Common\Exception\SQL as SqlException;
use CeusMedia\Common\Exception\Traits\Descriptive;
use CeusMedia\Common\XML\ElementReader as XmlElementReader;
use CeusMedia\Database\SQLSTATE;
use Throwable;
use InvalidArgumentException;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class View
{
	protected ?Throwable $exception		= NULL;
	protected string $heading			= 'Exception:';

	/**
	 *	@param		Throwable|NULL		$exception
	 *	@return		self
	 */
	public static function getInstance( ?Throwable $exception = NULL ): self
	{
		return new self( $exception );
	}

	/**
	 *	@param		Throwable|NULL		$exception
	 */
	public function __construct( ?Throwable $exception = NULL )
	{
		if( !is_null( $exception ) )
			$this->setException( $exception );
	}

	/**
	 *	@return		string
	 */
	public function __toString(): string
	{
		return $this->render();
	}

	/**
	 *	@return		string
	 */
	public function render(): string
	{
		if( NULL === $this->exception )
			throw new InvalidArgumentException( 'No exception set' );

		$e	= $this->exception;
		$lines		= '' !== $this->heading ? [$this->heading] : [];
		$lines[]	= '- Message:     '.$e->getMessage();
		$lines[]	= '- Code:        '.$e->getCode();

		self::enlistAdditionalProperties( $lines, $e );

		$lines[]	= '';
		$lines[]	= $this->renderFactsBlock();

		$lines[]	= '';
		$lines[]	= $this->renderTraceBlock();

		if( NULL !== $e->getPrevious() )
			$lines[]	= PHP_EOL.self::getInstance( $e->getPrevious() )
				->setHeading( 'Previous Exception:' )
				->render();

		return join( PHP_EOL, $lines ).PHP_EOL;
	}

	/**
	 *	@param		Throwable		$exception
	 *	@return		self
	 */
	public function setException( Throwable $exception ): self
	{
		$this->exception	= $exception;
		return $this;
	}

	/**
	 *	@param		?string		$heading
	 *	@return		self
	 */
	public function setHeading( ?string $heading ): self
	{
		$this->heading	= $heading;
		return $this;
	}

	protected static function enlistAdditionalProperties( array & $list, Throwable $e ): void
	{
		$blacklist	= ['description', 'suggestion', 'traceAsString'];
		if( $e instanceof SqlException && $e->getSQLSTATE() ){
			if( class_exists( '\\CeusMedia\\Database\\SQLSTATE' ) ){
				$meaning	= SQLSTATE::getMeaning( $e->getSQLSTATE() );
				if( NULL !== $meaning ){
					$list[]			= '- SQLSTATE:    '.$e->getSQLSTATE().': '.$meaning;
					$blacklist[]	= 'SQLSTATE';
				}
			}
		}
		if( in_array( Descriptive::class, class_uses( $e ), TRUE ) ){
			/** @var RuntimeException $e */
			if( '' !== $e->getDescription() )
				$list[]	= '- Description: '.$e->getDescription();
			if( '' !== $e->getSuggestion() )
				$list[]	= '- Suggestion:  '.$e->getSuggestion();
			foreach( $e->getAdditionalProperties() as $key => $value ){
				if( in_array( $key, $blacklist, TRUE ) )
					continue;
				switch( gettype( $value ) ){
					case 'object':
					case 'array':
						$value	= JsonEncoder::create()->encode( $value );
						break;
					default:
						$value ??= '-empty-';
				}
				$key		= str_pad( ucfirst( $key ).':', 13, ' ', STR_PAD_RIGHT );
				$list[]	= '- '.$key.$value;
			}
		}
	}

	/**
	 *	@return		string
	 */
	protected function renderFactsBlock(): string
	{
		$fullClassName	= get_class( $this->exception );
		$spaceParts	= explode( '\\', preg_replace( '/^\\\\/', '', $fullClassName ) );
		$revCan		= join( '/', array_reverse( $spaceParts ) );
		$className	= array_pop( $spaceParts );
		$namespace	= join( '\\', $spaceParts );
		return join( PHP_EOL, [
			'Facts:',
			'- File:Line:   '.$this->exception->getFile().':'.$this->exception->getLine(),
//			'- Class Name:  '.$fullClassName,
			'- Class/Type:  '.$className.( '' !== $namespace ? ' (in '.$namespace.')' : '' ),
			'- rCanonical:  '.str_replace( '/Exception', 'Exception', $revCan ),
		] );
	}

	/**
	 *	@return		string
	 */
	protected function renderTraceBlock(): string
	{
		$lines	= [];
		foreach( $this->exception->getTrace() as $nr => $step ){
			$number			= str_pad( '#'.( $nr + 1 ), 4, ' ', STR_PAD_RIGHT );
			$classPrefix	= '' !== $step['class'] ? $step['class'].' '.$step['type'].' ' : '';
			$arguments		= implode( ', ', $step['args'] ?? [] );
			$lines[]		= vsprintf( '%s Call: %s%s(%s)'.PHP_EOL.'     File: %s @ %s', [
				$number,
				$classPrefix,
				$step['function'],
				$arguments,
				$step['file'],
				$step['line']
			] );
		}
		$lines[]	= 'Trace:';
		return join( PHP_EOL, array_reverse( $lines ) );
	}
}
