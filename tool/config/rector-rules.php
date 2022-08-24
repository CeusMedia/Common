<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Php53\Rector\Ternary\TernaryToElvisRector;
use Rector\Php70\Rector\Ternary\TernaryToNullCoalescingRector;
use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
use Rector\Php72\Rector\FuncCall\GetClassOnNullRector;
use Rector\Php73\Rector\BooleanOr\IsCountableRector;
use Rector\Php73\Rector\FuncCall\RegexDashEscapeRector;

return static function (RectorConfig $rectorConfig): void {
	$rectorConfig->paths([
		__DIR__ . '/../../src'
	]);

//    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
	$rectorConfig->rule(TernaryToElvisRector::class);
	$rectorConfig->rule(TernaryToNullCoalescingRector::class);
	$rectorConfig->rule(RemoveExtraParametersRector::class);

	$rectorConfig->skip([
		__DIR__ . '/../../src/Net/XMPP/XMPPHP',
		__DIR__ . '/../../src/FS/File/YAML/Spyc.php',
		__DIR__ . '/../../src/UI',
		IsCountableRector::class,
		RegexDashEscapeRector::class,
		GetClassOnNullRector::class
	]);
};
