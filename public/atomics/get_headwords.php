<?php

require_once __DIR__ . '/_public_header.php';

//----------------------------------------------------
// parameters
//----------------------------------------------------

$headword_mask = Script::get_parameter('h');
$_SESSION['search_mask'] = $headword_mask;

//----------------------------------------------------
// data acquisition
//----------------------------------------------------

$headwords = $data->get_headwords($headword_mask, $config['search_results_limit']);
$_SESSION['search_results'] = $headwords;

//----------------------------------------------------
// returning result
//----------------------------------------------------

echo JSON_encode($headwords, JSON_UNESCAPED_UNICODE);

