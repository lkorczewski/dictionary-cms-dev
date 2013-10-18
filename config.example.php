<?php

// include location
$config['include_path'] = '../../library';

// enables displaying errors if disabled by php configuration
$config['debug'] = true;

//----------------------------------------------------------------------------
// dictionary database specification
//----------------------------------------------------------------------------
$config['db_host'] = 'localhost';
$config['db_user'] = 'dictionary';
$config['db_password'] = 'PASSWORD';
$config['db_database'] = 'dictionary';

//----------------------------------------------------------------------------
// localization settings
//----------------------------------------------------------------------------
// locale_path -- path to translation files
// locale -- selected locale, must be one of locales available at locale_path
//----------------------------------------------------------------------------
$config['locale_path'] = 'languages';
$config['locale'] = 'en';

//----------------------------------------------------------------------------
// interface configuration
//----------------------------------------------------------------------------
$config['title'] = 'Demo dictionary';
$config['search_results_limit'] = 25;

?>
