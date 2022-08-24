<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Php52\Rector\Switch_\ContinueToBreakInSwitchRector;
use Rector\Php53\Rector\Ternary\TernaryToElvisRector;
use Rector\Php70\Rector\Assign\ListSplitStringRector;
use Rector\Php70\Rector\Assign\ListSwapArrayOrderRector;
use Rector\Php70\Rector\Break_\BreakNotInLoopOrSwitchToReturnRector;
use Rector\Php70\Rector\ClassMethod\Php4ConstructorRector;
use Rector\Php70\Rector\FuncCall\CallUserMethodRector;
use Rector\Php70\Rector\FuncCall\EregToPregMatchRector;
use Rector\Php70\Rector\FuncCall\MultiDirnameRector;
use Rector\Php70\Rector\FuncCall\NonVariableToVariableOnFunctionCallRector;
use Rector\Php70\Rector\FuncCall\RandomFunctionRector;
use Rector\Php70\Rector\FuncCall\RenameMktimeWithoutArgsToTimeRector;
use Rector\Php70\Rector\FunctionLike\ExceptionHandlerTypehintRector;
use Rector\Php70\Rector\If_\IfToSpaceshipRector;
use Rector\Php70\Rector\List_\EmptyListRector;
use Rector\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector;
use Rector\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector;
use Rector\Php70\Rector\Switch_\ReduceMultipleDefaultSwitchRector;
use Rector\Php70\Rector\Ternary\TernaryToNullCoalescingRector;
use Rector\Php70\Rector\Ternary\TernaryToSpaceshipRector;
use Rector\Php70\Rector\Variable\WrapVariableVariableNameInCurlyBracesRector;
use Rector\Php71\Rector\Assign\AssignArrayToStringRector;
use Rector\Php71\Rector\BinaryOp\BinaryOpBetweenNumberAndStringRector;
use Rector\Php71\Rector\BooleanOr\IsIterableRector;
use Rector\Php71\Rector\ClassConst\PublicConstantVisibilityRector;
use Rector\Php71\Rector\FuncCall\CountOnNullRector;
use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
use Rector\Php71\Rector\List_\ListToArrayDestructRector;
use Rector\Php71\Rector\TryCatch\MultiExceptionCatchRector;
use Rector\Php72\Rector\Assign\ListEachRector;
use Rector\Php72\Rector\Assign\ReplaceEachAssignmentWithKeyCurrentRector;
use Rector\Php72\Rector\FuncCall\CreateFunctionToAnonymousFunctionRector;
use Rector\Php72\Rector\FuncCall\GetClassOnNullRector;
use Rector\Php72\Rector\FuncCall\IsObjectOnIncompleteClassRector;
use Rector\Php72\Rector\FuncCall\ParseStrWithResultArgumentRector;
use Rector\Php72\Rector\FuncCall\StringifyDefineRector;
use Rector\Php72\Rector\FuncCall\StringsAssertNakedRector;
use Rector\Php72\Rector\Unset_\UnsetCastRector;
use Rector\Php72\Rector\While_\WhileEachToForeachRector;
use Rector\Php73\Rector\BooleanOr\IsCountableRector;
use Rector\Php73\Rector\ConstFetch\SensitiveConstantNameRector;
use Rector\Php73\Rector\FuncCall\ArrayKeyFirstLastRector;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\Php73\Rector\FuncCall\RegexDashEscapeRector;
use Rector\Php73\Rector\FuncCall\SensitiveDefineRector;
use Rector\Php73\Rector\FuncCall\SetCookieRector;
use Rector\Php73\Rector\FuncCall\StringifyStrNeedlesRector;
use Rector\Php73\Rector\String_\SensitiveHereNowDocRector;
use Rector\Php74\Rector\ArrayDimFetch\CurlyToSquareBracketArrayStringRector;
use Rector\Php74\Rector\Assign\NullCoalescingOperatorRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php74\Rector\Double\RealToFloatTypeCastRector;
use Rector\Php74\Rector\FuncCall\ArrayKeyExistsOnPropertyRector;
use Rector\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector;
use Rector\Php74\Rector\FuncCall\FilterVarToAddSlashesRector;
use Rector\Php74\Rector\FuncCall\MbStrrposEncodingArgumentPositionRector;
use Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector;
use Rector\Php74\Rector\MethodCall\ChangeReflectionTypeToStringToGetNameRector;
use Rector\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Php74\Rector\StaticCall\ExportToReflectionFunctionRector;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;

return static function (RectorConfig $rectorConfig): void {
	$rectorConfig->paths([
		__DIR__ . '/../../src'
	]);

//    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);


//	sets completed
//  - Set 7.0
//  - Set 7.1
//  - Set 7.2
//  - Set 7.3


//	applied, lately
//	$rectorConfig->rule(PublicConstantVisibilityRector::class);

//	surrendered at  JsonThrowOnErrorRector



//	skipped / declined
//  - Set 7.1
//	$rectorConfig->rule(CountOnNullRector::class);
//  - Set 7.2
//  - Set 7.3
//	$rectorConfig->rule(JsonThrowOnErrorRector::class);
//	$rectorConfig->rule(IsCountableRector::class);
//	$rectorConfig->rule(RegexDashEscapeRector::class);



//	up next
//  - Set 7.4
//	$rectorConfig->rule(TypedPropertyRector::class);
//	$rectorConfig->ruleWithConfiguration(RenameFunctionRector::class, [
//		#the_real_type
//		# https://wiki.php.net/rfc/deprecations_php_7_4
//		'is_real' => 'is_float',
//		#apache_request_headers_function
//		# https://wiki.php.net/rfc/deprecations_php_7_4
//		'apache_request_headers' => 'getallheaders',
//	]);
//	$rectorConfig->rule(ArrayKeyExistsOnPropertyRector::class);
//	$rectorConfig->rule(FilterVarToAddSlashesRector::class);
//	$rectorConfig->rule(ExportToReflectionFunctionRector::class);
//	$rectorConfig->rule(MbStrrposEncodingArgumentPositionRector::class);
//	$rectorConfig->rule(RealToFloatTypeCastRector::class);
//	$rectorConfig->rule(NullCoalescingOperatorRector::class);
//	$rectorConfig->rule(ClosureToArrowFunctionRector::class);
//	$rectorConfig->rule(ArraySpreadInsteadOfArrayMergeRector::class);
//	$rectorConfig->rule(AddLiteralSeparatorToNumberRector::class);
//	$rectorConfig->rule(ChangeReflectionTypeToStringToGetNameRector::class);
//	$rectorConfig->rule(RestoreDefaultNullToNullableTypePropertyRector::class);
//	$rectorConfig->rule(CurlyToSquareBracketArrayStringRector::class);




	$rectorConfig->skip([
		__DIR__ . '/../../src/Net/XMPP/XMPPHP',
		__DIR__ . '/../../src/FS/File/YAML/Spyc.php',
		__DIR__ . '/../../src/UI',
		IsCountableRector::class,
		RegexDashEscapeRector::class,
		GetClassOnNullRector::class
	]);
};
