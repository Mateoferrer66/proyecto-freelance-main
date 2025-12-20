<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// sanitize common artifacts that appear when copying URLs from quoted-printable .eml files
// fixes like: 'r=3Dsite%2Freset-password&token=MXwx...'
$rawQuery = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
if (!empty($rawQuery)) {
	// decode HTML entities like &#61; and &amp;
	$rawQuery = html_entity_decode($rawQuery, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	// remove soft line breaks (=\r\n) used by quoted-printable
	$rawQuery = preg_replace('/=\r?\n/', '', $rawQuery);
	// replace =3D and variations (case-insensitive) with =
	$rawQuery = preg_replace('/=3D/i', '=', $rawQuery);
	// replace %3D with = (url-encoded equals)
	$rawQuery = str_ireplace('%3D', '=', $rawQuery);
	// fix any stray spaces
	$rawQuery = str_replace(' ', '', $rawQuery);

	// rebuild $_SERVER['QUERY_STRING'] and $_GET from sanitized query
	$_SERVER['QUERY_STRING'] = $rawQuery;
	// parse_str will urldecode values and populate an array
	parse_str($rawQuery, $sanitized);
	if (is_array($sanitized)) {
		// merge into $_GET but keep existing entries if present
		foreach ($sanitized as $k => $v) {
			// If token or r contain suspicious leading '3D', strip it
			if (is_string($v)) {
				$v = preg_replace('/^3D/', '', $v);
				// remove soft qp breaks and =3D left-overs inside values
				$v = preg_replace('/=\r?\n/', '', $v);
				$v = str_ireplace('=3D', '=', $v);
				$v = str_ireplace('%3D', '=', $v);
			}
			$_GET[$k] = $v;
			// also populate $_REQUEST to keep compatibility
			$_REQUEST[$k] = $v;
		}
	}
}

// Fallback: si aún no hay 'r' pero REQUEST_URI contiene 'token' o un fragmento largo, asumir reset-password
if (empty($_GET['r']) && !empty($_SERVER['REQUEST_URI'])) {
	$uri_check = html_entity_decode($_SERVER['REQUEST_URI'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
	// si aparece la palabra 'token' o una cadena base64-like larga, asumimos reset-password
	if (stripos($uri_check, 'token') !== false || preg_match('/[A-Za-z0-9\-_]{30,}/', $uri_check)) {
		$_GET['r'] = 'site/reset-password';
		$_REQUEST['r'] = 'site/reset-password';
		@file_put_contents(__DIR__ . '/../runtime/debug_query.log', "Fallback set r=site/reset-password due to token-like fragment in REQUEST_URI\n", FILE_APPEND);

		// intentar extraer token por nombre 'token' o por el primer fragmento base64-like
		if (preg_match('/(?:[?&]|\b)(?:token|tok)(?:=|&#61;|=3D|%3D)?([A-Za-z0-9\-_=]{20,})/i', $uri_check, $mt)) {
			$tok = $mt[1];
		} elseif (preg_match('/([A-Za-z0-9\-_]{30,})/', $uri_check, $mt2)) {
			$tok = $mt2[1];
		} else {
			$tok = null;
		}

		if (!empty($tok)) {
			$tok = preg_replace('/=3D/i', '=', $tok);
			$tok = preg_replace('/=\r?\n/', '', $tok);
			$tok = preg_replace('/[^A-Za-z0-9\-_=]/', '', $tok);
			$_GET['token'] = $tok;
			$_REQUEST['token'] = $tok;
			$_SERVER['QUERY_STRING'] = (($_SERVER['QUERY_STRING'] ?? '') !== '' ? $_SERVER['QUERY_STRING'] . '&' : '') . 'token=' . $tok;
			@file_put_contents(__DIR__ . '/../runtime/debug_query.log', "Fallback extracted token: " . $tok . "\n", FILE_APPEND);
		}
	}
}

// Debug: volcar query y GET saneado (temporal)
@file_put_contents(__DIR__ . '/../runtime/debug_query.log', "---- " . date('c') . " ----\nQUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? '') . "\n" . var_export(
	array_merge(['_SERVER_raw' => getenv('QUERY_STRING')], ['_GET' => $_GET]), true
) . "\n", FILE_APPEND);

// If sanitization left $_GET['r'] empty but REQUEST_URI contains reset-password,
// try to extract token directly from REQUEST_URI (covers broken links from .eml)
if (empty($_GET['r']) && !empty($_SERVER['REQUEST_URI']) && stripos($_SERVER['REQUEST_URI'], 'reset-password') !== false) {
	$uri_raw = $_SERVER['REQUEST_URI'];
	// normalize common encodings in the URI too (HTML entities like &#61;, quoted-printable fragments)
	$uri_dec = html_entity_decode($uri_raw, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	$uri_dec = str_replace('&amp;', '&', $uri_dec);
	$uri_dec = preg_replace('/=\r?\n/', '', $uri_dec);
	$uri_dec = preg_replace('/=3D/i', '=', $uri_dec);
	$uri_dec = str_ireplace('%3D', '=', $uri_dec);

	// log raw and decoded URI for diagnosis
	@file_put_contents(__DIR__ . '/../runtime/debug_query.log', "REQUEST_URI_RAW: " . $uri_raw . "\nREQUEST_URI_DEC: " . $uri_dec . "\n", FILE_APPEND);

	$uri = $uri_dec;
	// If URI contains 'site%2Freset-password' or similar, set r
	if (stripos($uri, 'site%2Freset-password') !== false || stripos($uri, 'site/reset-password') !== false) {
		$_GET['r'] = 'site/reset-password';
		$_REQUEST['r'] = 'site/reset-password';
	}

	// Try to capture token or tok parameter (allow =3D and soft line breaks)
	// try to find explicit r= and token= pairs allowing HTML entities and quoted-printable
	if (preg_match('/(?:[?&]|\b)r(?:=|&#61;|=3D|%3D)([^&"\']+)/i', $uri, $mr)) {
		$rval = urldecode($mr[1]);
		$rval = preg_replace('/^3D/', '', $rval);
		$_GET['r'] = $rval;
		$_REQUEST['r'] = $rval;
		@file_put_contents(__DIR__ . '/../runtime/debug_query.log', "Extracted r from REQUEST_URI: " . $rval . "\n", FILE_APPEND);
	}

	if (preg_match('/(?:[?&]|\b)(?:token|tok)(?:=|&#61;|=3D|%3D)([^&"\']+)/i', $uri, $m)) {
		$rawTok = $m[1];
		// cleanup similar to above
		$rawTok = html_entity_decode($rawTok, ENT_QUOTES | ENT_HTML5, 'UTF-8');
		$rawTok = preg_replace('/=\r?\n/', '', $rawTok);
		$rawTok = preg_replace('/=3D/i', '=', $rawTok);
		$rawTok = str_ireplace('%3D', '=', $rawTok);
		$rawTok = str_replace(' ', '', $rawTok);
	$rawTok = trim($rawTok, "\"'\n\r");
		if (preg_match('/^3D/', $rawTok)) {
			$rawTok = preg_replace('/^3D/', '', $rawTok);
		}
	// final cleanup: remove stray non-base64 chars except -_=
	$rawTok = preg_replace('/[^A-Za-z0-9\-_=]/', '', $rawTok);

		if (!empty($rawTok)) {
			$_GET['token'] = $rawTok;
			$_REQUEST['token'] = $rawTok;
			// append to QUERY_STRING for completeness
			$_SERVER['QUERY_STRING'] = (($_SERVER['QUERY_STRING'] ?? '') !== '' ? $_SERVER['QUERY_STRING'] . '&' : '') . 'token=' . $rawTok;
			@file_put_contents(__DIR__ . '/../runtime/debug_query.log', "Extracted token from REQUEST_URI: " . $rawTok . "\n", FILE_APPEND);
		}
	}
	else {
		// if no explicit token param, try to find a long base64-like fragment in the URI
		if (preg_match('/([A-Za-z0-9\-_]{30,})/', $uri, $m2)) {
			$rawTok = $m2[1];
			$rawTok = str_ireplace('=3D', '=', $rawTok);
			$rawTok = preg_replace('/=\r?\n/', '', $rawTok);
			$rawTok = preg_replace('/[^A-Za-z0-9\-_=]/', '', $rawTok);
			if (!empty($rawTok)) {
				$_GET['token'] = $rawTok;
				$_REQUEST['token'] = $rawTok;
				$_SERVER['QUERY_STRING'] = (($_SERVER['QUERY_STRING'] ?? '') !== '' ? $_SERVER['QUERY_STRING'] . '&' : '') . 'token=' . $rawTok;
				@file_put_contents(__DIR__ . '/../runtime/debug_query.log', "Extracted token-from-fragment: " . $rawTok . "\n", FILE_APPEND);
			}
		}
	}
}

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
