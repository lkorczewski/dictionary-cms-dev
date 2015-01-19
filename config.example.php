<?php

// include location
$config['include_path'] = '../../library';

// enables displaying errors if disabled by php configuration
$config['debug'] = true;

// base URL path relative to the hostname for styles, scripts, etc.
$config['base_url'] = '/';

//----------------------------------------------------------------------------
// session
//----------------------------------------------------------------------------
// if two instances of dictionary are hosted under the same domain, they have
// to have different values in session_name or session_path, otherwise they
// would share session data, what is usually an unwanted behaviour
//----------------------------------------------------------------------------
$config['session_name']    = 'session_id';
//$config['session_domain']  = '';
//$config['session_path']    = '/'

//----------------------------------------------------------------------------
// dictionary database specification
//----------------------------------------------------------------------------
//$config['db_host']      = 'localhost';
//$config['db_port']      = 3306;
$config['db_user']      = 'dictionary';
$config['db_password']  = 'PASSWORD';
$config['db_database']  = 'dictionary';

//----------------------------------------------------------------------------
// localization settings
//----------------------------------------------------------------------------
// locale_path -- path to translation files
// locale -- selected locale, must be one of locales available at locale_path
//----------------------------------------------------------------------------
$config['locale_path']  = 'languages';
$config['locale']       = 'en';

//----------------------------------------------------------------------------
// interface configuration
//----------------------------------------------------------------------------
$config['title']                 = 'Demo dictionary';
$config['search_results_limit']  = 25;

//----------------------------------------------------------------------------
return $config;

