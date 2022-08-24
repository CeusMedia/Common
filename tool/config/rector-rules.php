<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php56\Rector\FuncCall\PowToExpRector;
use Rector\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector;
use Rector\Php72\Rector\FuncCall\GetClassOnNullRector;
use Rector\Php73\Rector\BooleanOr\IsCountableRector;
use Rector\Php73\Rector\FuncCall\RegexDashEscapeRector;
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
//  - Set 5.6
//	# inspired by level in psalm - https://github.com/vimeo/psalm/blob/82e0bcafac723fdf5007a31a7ae74af1736c9f6f/tests/FileManipulationTest.php#L1063
//	$rectorConfig->rule(AddDefaultValueForUndefinedVariableRector::class);
//  - Set 7.1
//	$rectorConfig->rule(CountOnNullRector::class);
//  - Set 7.2
//  - Set 7.3
//	$rectorConfig->rule(JsonThrowOnErrorRector::class);
//	$rectorConfig->rule(IsCountableRector::class);
//	$rectorConfig->rule(RegexDashEscapeRector::class);


//	up next
//  - Set 5.6
	$rectorConfig->rule(PowToExpRector::class);
	$rectorConfig->ruleWithConfiguration(RenameFunctionRector::class, ['mcrypt_generic_end' => 'mcrypt_generic_deinit', 'set_socket_blocking' => 'stream_set_blocking', 'ocibindbyname' => 'oci_bind_by_name', 'ocicancel' => 'oci_cancel', 'ocicolumnisnull' => 'oci_field_is_null', 'ocicolumnname' => 'oci_field_name', 'ocicolumnprecision' => 'oci_field_precision', 'ocicolumnscale' => 'oci_field_scale', 'ocicolumnsize' => 'oci_field_size', 'ocicolumntype' => 'oci_field_type', 'ocicolumntyperaw' => 'oci_field_type_raw', 'ocicommit' => 'oci_commit', 'ocidefinebyname' => 'oci_define_by_name', 'ocierror' => 'oci_error', 'ociexecute' => 'oci_execute', 'ocifetch' => 'oci_fetch', 'ocifetchstatement' => 'oci_fetch_all', 'ocifreecursor' => 'oci_free_statement', 'ocifreestatement' => 'oci_free_statement', 'ociinternaldebug' => 'oci_internal_debug', 'ocilogoff' => 'oci_close', 'ocilogon' => 'oci_connect', 'ocinewcollection' => 'oci_new_collection', 'ocinewcursor' => 'oci_new_cursor', 'ocinewdescriptor' => 'oci_new_descriptor', 'ocinlogon' => 'oci_new_connect', 'ocinumcols' => 'oci_num_fields', 'ociparse' => 'oci_parse', 'ociplogon' => 'oci_pconnect', 'ociresult' => 'oci_result', 'ocirollback' => 'oci_rollback', 'ocirowcount' => 'oci_num_rows', 'ociserverversion' => 'oci_server_version', 'ocisetprefetch' => 'oci_set_prefetch', 'ocistatementtype' => 'oci_statement_type']);

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
