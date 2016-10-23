<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

class InstallController extends Controller
{
    // check for current step
    public function step($request)
    {
        $step = 0;

        $data = $request->session()->get('compatibilities');
        if (isset($data)) {
            $step = 1;
        } else {
            return $step;
        }

        $data = $request->session()->get('site_info');
        if (isset($data)) {
            $step = 3;
        } else {
            return $step;
        }

        $data = $request->session()->get('database');
        if (isset($data)) {
            $step = 4;
        } else {
            return $step;
        }

        $data = $request->session()->get('database_imported');
        if (isset($data)) {
            $step = 5;
        } else {
            return $step;
        }

        $data = $request->session()->get('cron_jobs');
        if (isset($data)) {
            $step = 6;
        } else {
            return $step;
        }

        return $step;
    }

    // Starting installation
    public function starting(Request $request)
    {
        $next = action('InstallController@systemCompatibility');
        \Artisan::call('config:cache');
        sleep(5);
        return redirect()->away($next);
    }

    public function systemCompatibility(Request $request)
    {
        // Begin check
        $request->session()->forget('compatibilities');

        $compatibilities = $this->checkSystemCompatibility();
        $result = true;
        foreach ($compatibilities as $compatibility) {
            if (!$compatibility['check']) {
                $result = false;
            }
        }
        
        // retry if something not work yet
        try {
            if ($result) {
                $request->session()->set('compatibilities', $compatibilities);
            }        
        
            return view('install.compatibilities', [
                'compatibilities' => $compatibilities,
                'result' => $result,
                'step' => $this->step($request),
                'current' => 1,
            ]);
        } catch (\Exception $e) {
            $next_page = action('InstallController@systemCompatibility');
            \Artisan::call('config:cache');
            sleep(5);
            return redirect()->away($next_page);
        }
    }

    public function siteInfo(Request $request)
    {
        if ($this->step($request) < 1) {
            return redirect()->action('InstallController@systemCompatibility');
        }

        // make sure session is working
        $rules = [
            'site_name' => 'required',
            'site_keyword' => 'required',
            'site_description' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'timezone' => 'required',
        ];
        $smtp_rules = [
            'smtp_hostname' => 'required',
            'smtp_port' => 'required',
            'smtp_username' => 'required',
            'smtp_password' => 'required',
            'smtp_from_email' => 'required|email',
            'smtp_from_name' => 'required',
            'smtp_encryption' => 'required',
        ];
        // validate and save posted data
        if ($request->isMethod('post')) {
            $request->session()->forget('site_info');

            if ($request->mail_driver == 'smtp') {
                $rules = array_merge($rules, $smtp_rules);
            }
            $this->validate($request, $rules);

            // Check SMTP connection
            if ($request->mail_driver == 'smtp') {
                $rules = [];
                try {
                    $transport = \Swift_SmtpTransport::newInstance($request->smtp_hostname, $request->smtp_port, $request->smtp_encryption);
                    $transport->setUsername($request->smtp_username);
                    $transport->setPassword($request->smtp_password);
                    $mailer = \Swift_Mailer::newInstance($transport);
                    $mailer->getTransport()->start();
                } catch (\Swift_TransportException $e) {
                    $rules['smtp_valid'] = 'required';
                } catch (Exception $e) {
                    $rules['smtp_valid'] = 'required';
                }
                $this->validate($request, $rules);
            }

            $site_info = $request->all();

            $request->session()->set('site_info', $site_info);

            return redirect()->action('InstallController@database');
        }

        $site_info = $request->session()->get('site_info');
        if (!empty($request->old())) {
            $site_info = $request->old();
        }

        return view('install.site_info', [
            'site_info' => $site_info,
            'rules' => $rules,
            'smtp_rules' => $smtp_rules,
            'step' => $this->step($request),
            'current' => 2,
        ]);
    }

    // Database configuration
    public function database(Request $request)
    {
        if ($this->step($request) < 2) {
            return redirect()->action('InstallController@siteInfo');
        }

        // Check required fields
        $rules = array(
            'hostname' => 'required',
            'port' => 'required',
            'username' => 'required',
            'password' => 'required',
            'database_name' => 'required',
        );

        // validate and save posted data
        if ($request->isMethod('post')) {
            $request->session()->forget('database');

            $this->validate($request, $rules);

            // Check mysql connection
            try {
                $port = $request->port;
                $port = (int) $port;
                $conn = new \mysqli($request->hostname, $request->username, $request->password, $request->database_name, $port);
            } catch (\Exception $e) {
                $rules['mysql_connection'] = 'required';
            }

            $this->validate($request, $rules);

            // Save database session
            $database = $request->all();
            $request->session()->set('database', $database);
            
            $next_page = action('InstallController@databaseImport');
            
            // write config file
            $this->writeEnv($request);
            
            // wait for config:cache
            sleep(5);
            
            return redirect()->away($next_page);
        }

        $database = $request->session()->get('database');
        if (!empty($request->old())) {
            $database = $request->old();
        }

        return view('install.database', [
            'database' => $database,
            'rules' => $rules,
            'step' => $this->step($request),
            'current' => 3,
        ]);
    }

    // Import Database
    public function databaseImport(Request $request)
    {
        if ($this->step($request) < 3) {
            return redirect()->action('InstallController@database');
        }

        $database = $request->session()->get('database');
        $site_info = $request->session()->get('site_info');

        if ($request->action == 'import') {
            $request->session()->forget('database_imported');

            // connect mysql
            $mysqli = new \mysqli($database['hostname'], $database['username'], $database['password'], $database['database_name'], $database['port']);

            //// Drop all old table
            //$done = true;
            //while ($done) {
            //    try {
            //        $delete_all_tables_query = "SELECT CONCAT( 'DROP TABLE ', GROUP_CONCAT(table_name) , ';' )
            //                                    AS statement FROM information_schema.tables
            //                                    WHERE table_schema = '".$database["database_name"]."' AND table_name LIKE '".$database["tables_prefix"]."%' limit 1;";
            //        $result = $mysqli->query($delete_all_tables_query);
            //        $result = $result->fetch_object();

            //        if (isset($result->statement)) {
            //            $mysqli->query($result->statement);
            //            continue;
            //        }
            //    } catch (\Exception $e) {
            //        $rules["mysql_connection"] = "required";
            //    }

            //    $done = false;
            //}

            // Check if database is not empty
            $rules = [];
            $prefix_check = empty($database['tables_prefix']) ? '' : "  AND table_name LIKE '".$database['tables_prefix']."%'";
            $result = $mysqli->query("SELECT COUNT(DISTINCT `table_name`) as count FROM `information_schema`.`columns` WHERE `table_schema` = '".$database['database_name']."'".$prefix_check);
            $result = $result->fetch_object();
            if ($result->count > 0) {
                $rules['database_not_empty'] = 'required';
            }
            $this->validate($request, $rules);
            
            $next_page = action('InstallController@cronJobs');

            // Run migrate
            \Artisan::call('migrate', ["--force"=> true]);

            // import database with prefix
            $this->importDatabase($database);
            
            // get database connection
            $database = $request->session()->get('database');
            $mysqli = new \mysqli($database['hostname'], $database['username'], $database['password'], $database['database_name'], $database['port']);

            // default date
            $date = \Carbon\Carbon::now();

            // Insert default superuser
            $mysqli->query('DELETE FROM `'.$database['tables_prefix'].'users` WHERE 1');

            $aaa = $mysqli->query('INSERT INTO `'.$database['tables_prefix']."users`
                (`id`, `uid`, `api_token`, `contact_id`, `user_group_id`, `language_id`, `name`, `email`, `password`, `remember_token`, `image`, `timezone`, `status`, `created_at`, `updated_at`, `first_name`, `last_name`, `frontend_scheme`, `backend_scheme`) VALUES
(1, '" .uniqid()."', '".str_random(60)."', NULL, 1, 1, '".$site_info['first_name'].' '.$site_info['last_name']."', '".$site_info['email']."', '".bcrypt($site_info['password'])."', '', '', '".$site_info['timezone']."', 'active', '".$date."', '".$date."', '".$site_info['first_name']."', '".$site_info['last_name']."', '', '');");

            // Insert system urls
            $mysqli->query('DELETE FROM `'.$database['tables_prefix']."settings` WHERE name='url_delivery_handler'");
            $mysqli->query('INSERT INTO `'.$database['tables_prefix']."settings` (`name`, `value`, `created_at`, `updated_at`) VALUES
                            ('url_delivery_handler', '".action('DeliveryController@notify', ['stype' => ''])."', '".$date."', '".$date."');");
            $mysqli->query('DELETE FROM `'.$database['tables_prefix']."settings` WHERE name='url_unsubscribe'");
            $mysqli->query('INSERT INTO `'.$database['tables_prefix']."settings` (`name`, `value`, `created_at`, `updated_at`) VALUES
                            ('url_unsubscribe', '".action('CampaignController@unsubscribe', ['message_id' => trans('messages.MESSAGE_ID')])."', '".$date."', '".$date."');");
            $mysqli->query('DELETE FROM `'.$database['tables_prefix']."settings` WHERE name='url_open_track'");
            $mysqli->query('INSERT INTO `'.$database['tables_prefix']."settings` (`name`, `value`, `created_at`, `updated_at`) VALUES
                            ('url_open_track', '".action('CampaignController@open', ['message_id' => trans('messages.MESSAGE_ID')])."', '".$date."', '".$date."');");
            $mysqli->query('DELETE FROM `'.$database['tables_prefix']."settings` WHERE name='url_click_track'");
            $mysqli->query('INSERT INTO `'.$database['tables_prefix']."settings` (`name`, `value`, `created_at`, `updated_at`) VALUES
                            ('url_click_track', '".action('CampaignController@click', ['message_id' => trans('messages.MESSAGE_ID'), 'url' => trans('messages.URL')])."', '".$date."', '".$date."');");
            //$mysqli->query("DELETE FROM `".$database["tables_prefix"]."settings` WHERE name='url_campaign_notification'");
            //$mysqli->query("INSERT INTO `".$database["tables_prefix"]."settings` (`name`, `value`, `created_at`, `updated_at`) VALUES
            //                ('url_campaign_notification', '".action('HomeController@index')."', '".$date."', '".$date."');");
            $mysqli->query('DELETE FROM `'.$database['tables_prefix']."settings` WHERE name='site_name'");
            $mysqli->query('INSERT INTO `'.$database['tables_prefix']."settings` (`name`, `value`, `created_at`, `updated_at`) VALUES
                            ('site_name', '".$site_info['site_name']."', '".$date."', '".$date."');");
            $mysqli->query('DELETE FROM `'.$database['tables_prefix']."settings` WHERE name='site_keyword'");
            $mysqli->query('INSERT INTO `'.$database['tables_prefix']."settings` (`name`, `value`, `created_at`, `updated_at`) VALUES
                            ('site_keyword', '".$site_info['site_keyword']."', '".$date."', '".$date."');");
            $mysqli->query('DELETE FROM `'.$database['tables_prefix']."settings` WHERE name='site_description'");
            $mysqli->query('INSERT INTO `'.$database['tables_prefix']."settings` (`name`, `value`, `created_at`, `updated_at`) VALUES
                            ('site_description', '".$site_info['site_description']."', '".$date."', '".$date."');");

            $request->session()->set('database_imported', true);

            $request->session()->flash('alert-success', trans('messages.install.database_import.success'));

            return redirect()->away($next_page);
        }

        return view('install.database_import', [
            'database' => $database,
            'step' => $this->step($request),
            'current' => 3,
        ]);
    }

    public function cronJobs(Request $request)
    {
        if ($this->step($request) < 5) {
            return redirect()->action('InstallController@database');
        }

        $request->session()->set('cron_jobs', true);

        return view('install.cron_jobs', [
            'step' => $this->step($request),
            'current' => 5,
        ]);
    }

    public function finish(Request $request)
    {
        if ($this->step($request) < 6) {
            return redirect()->action('InstallController@database');
        }

        $request->session()->set('install_finish', true);
        
        $file_path = storage_path('installed');
        $file = fopen($file_path, 'w') or die('Unable to open file!');
        fwrite($file, '');
        fclose($file);

        return view('install.finish', [
            'step' => $this->step($request),
            'current' => 6,
        ]);
    }

    // Check for requirement when install app
    public function checkSystemCompatibility()
    {
        return [
            [
                'type' => 'requirement',
                'name' => 'PHP version',
                'check' => version_compare(PHP_VERSION, '5.5.9', '>='),
                'note' => 'PHP 5.5.9 or higher is required.',
            ],
            [
                'type' => 'requirement',
                'name' => 'OpenSSL Extension',
                'check' => extension_loaded('openssl'),
                'note' => 'OpenSSL PHP Extension is required.',
            ],
            [
                'type' => 'requirement',
                'name' => 'Mbstring PHP Extension',
                'check' => extension_loaded('mbstring'),
                'note' => 'Mbstring PHP Extension is required.',
            ],
            [
                'type' => 'requirement',
                'name' => 'PDO PHP extension',
                'check' => extension_loaded('pdo'),
                'note' => 'PDO PHP extension is required.',
            ],
            [
                'type' => 'requirement',
                'name' => 'Tokenizer PHP Extension',
                'check' => extension_loaded('tokenizer'),
                'note' => 'Tokenizer PHP Extension is required.',
            ],
            [
                'type' => 'requirement',
                'name' => 'PHP Zip Archive',
                'check' => class_exists('ZipArchive', false),
                'note' => 'PHP Zip Archive is required.',
            ],
            [
                'type' => 'requirement',
                'name' => 'IMAP Extension',
                'check' => extension_loaded('imap'),
                'note' => 'PHP IMAP Extension is required.',
            ],
            [
                'type' => 'requirement',
                'name' => 'PHP GD Library',
                'check' => (extension_loaded('gd') && function_exists('gd_info')),
                'note' => 'PHP GD Library is required.',
            ],
            [
                'type' => 'requirement',
                'name' => 'PHP Fileinfo extension',
                'check' => extension_loaded('fileinfo'),
                'note' => 'PHP Fileinfo extension is required.',
            ],    
            [
                'type' => 'permission',
                'name' => 'Storage/app/',
                'check' => file_exists(base_path('/storage/app')) &&
                    is_dir(base_path('/storage/app')) &&
                    (is_writable(base_path('/storage/app'))) &&
                    \Acelle\Library\Tool::getPerms(base_path('/storage/app')) >= 775,
                'note' => 'The directory must be writable by the web server (0775).',
            ],
            [
                'type' => 'permission',
                'name' => 'Storage/framework/',
                'check' => file_exists(base_path('/storage/framework')) && is_dir(base_path('/storage/framework')) && (is_writable(base_path('/storage/framework'))) &&
                    \Acelle\Library\Tool::getPerms(base_path('/storage/framework')) >= 775,
                'note' => 'The directory must be writable by the web server (0775).',
            ],
            [
                'type' => 'permission',
                'name' => 'Storage/logs/',
                'check' => file_exists(base_path('/storage/logs')) && is_dir(base_path('/storage/logs')) && (is_writable(base_path('/storage/logs'))) &&
                    \Acelle\Library\Tool::getPerms(base_path('/storage/logs')) >= 775,
                'note' => 'The directory must be writable by the web server (0775).',
            ],
            [
                'type' => 'permission',
                'name' => 'bootstrap/cache/',
                'check' => file_exists(base_path('/bootstrap/cache')) && is_dir(base_path('/bootstrap/cache')) && (is_writable(base_path('/bootstrap/cache'))) &&
                    \Acelle\Library\Tool::getPerms(base_path('/bootstrap/cache')) >= 775,
                'note' => 'The directory must be writable by the web server (0775).',
            ],
        ];
    }

    public function checkServerVar()
    {
        $vars = array('HTTP_HOST', 'SERVER_NAME', 'SERVER_PORT', 'SCRIPT_NAME', 'SCRIPT_FILENAME', 'PHP_SELF', 'HTTP_ACCEPT', 'HTTP_USER_AGENT');
        $missing = array();
        foreach ($vars as $var) {
            if (!isset($_SERVER[$var])) {
                $missing[] = $var;
            }
        }

        if (!empty($missing)) {
            return '$_SERVER does not have: '.implode(', ', $missing);
        }

        if (!isset($_SERVER['REQUEST_URI']) && isset($_SERVER['QUERY_STRING'])) {
            return 'Either $_SERVER["REQUEST_URI"] or $_SERVER["QUERY_STRING"] must exist.';
        }

        if (!isset($_SERVER['PATH_INFO']) && strpos($_SERVER['PHP_SELF'], $_SERVER['SCRIPT_NAME']) !== 0) {
            return 'Unable to determine URL path info. Please make sure $_SERVER["PATH_INFO"] (or $_SERVER["PHP_SELF"] and $_SERVER["SCRIPT_NAME"]) contains proper value.';
        }

        return '';
    }

    public function checkCaptchaSupport()
    {
        if (function_exists('getimagesize')) {
            return '';
        }

        if (extension_loaded('imagick')) {
            $imagick = new Imagick();
            $imagickFormats = $imagick->queryFormats('PNG');
        }

        if (extension_loaded('gd')) {
            $gdInfo = gd_info();
        }

        if (isset($imagickFormats) && in_array('PNG', $imagickFormats)) {
            return '';
        } elseif (isset($gdInfo)) {
            if ($gdInfo['FreeType Support']) {
                return '';
            }

            return 'GD installed,<br />FreeType support not installed';
        }

        return 'GD or ImageMagick not installed';
    }

    // Write configuration values to file
    public function writeEnv($request)
    {
        // read app key from file
        $file_path = base_path('.env');
        $file = fopen($file_path, 'r') or die('Unable to open file!');
        $app_config = fread($file, filesize($file_path));
        preg_match('/APP_KEY=(.*)\n/', $app_config, $match);
        $app_key = trim($match[1]);
        fclose($file);

        // get database config
        $database = $request->session()->get('database');
        $database = !empty($database) ? $database : [];

        // get smtp config
        $smtp = $request->session()->get('site_info');
        $smtp = !empty($smtp) ? $smtp : [];

        $env_file = fopen(base_path('.env'), 'w') or die('Unable to open file!');
        $config_string = 'APP_ENV=production
APP_DEBUG=false
APP_KEY='.$app_key.'

DB_HOST='.(isset($database['hostname']) ? $database['hostname'] : '').'
DB_DATABASE='.(isset($database['database_name']) ? $database['database_name'] : '').'
DB_USERNAME='.(isset($database['username']) ? $database['username'] : '').'
DB_PASSWORD='.(isset($database['password']) ? $database['password'] : '').'
DB_PORT='.(isset($database['port']) ? $database['port'] : '').'
DB_TABLES_PREFIX='.(isset($database['tables_prefix']) ? $database['tables_prefix'] : '').'

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=async

REDIS_HOST=localhost
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER='.(isset($smtp['mail_driver']) ? $smtp['mail_driver'] : 'mail').'
MAIL_HOST='.(isset($smtp['smtp_hostname']) ? $smtp['smtp_hostname'] : '').'
MAIL_PORT='.(isset($smtp['smtp_port']) ? $smtp['smtp_port'] : '').'
MAIL_USERNAME='.(isset($smtp['smtp_username']) ? $smtp['smtp_username'] : '').'
MAIL_PASSWORD='.(isset($smtp['smtp_password']) ? $smtp['smtp_password'] : '').'
MAIL_ENCRYPTION='.(isset($smtp['smtp_encryption']) ? $smtp['smtp_encryption'] : '').'
MAIL_FROM_EMAIL='.(isset($smtp['smtp_from_email']) ? $smtp['smtp_from_email'] : '').'
MAIL_FROM_NAME="'.(isset($smtp['smtp_from_name']) ? $smtp['smtp_from_name'] : '').'"
';
        $config_string;
        fwrite($env_file, $config_string);
        fclose($env_file);

        // Reload .env
        \Artisan::call('config:cache');
    }

    public function importDatabase($database)
    {
        // read and replace prefix
        $file_path = storage_path('install/database_init.sql');
        $file = fopen($file_path, 'r') or die('Unable to open file!');
        $sql = fread($file, filesize($file_path));
        $sql = str_replace('<<prefix>>', $database['tables_prefix'], $sql);
        fclose($file);

        // write file
        $file_path = storage_path('app/database_import.sql');
        $file = fopen($file_path, 'w') or die('Unable to open file!');
        fwrite($file, $sql);
        fclose($file);

        // import sql
        $file_path = storage_path('app/database_import.sql');
        $command = 'mysql --host='.$database['hostname'].' --user='.$database['username'].' --password='.$database['password'].' --port='.$database['port'].' --database='.$database['database_name'].' < '.$file_path;

        return $this->importSql($database, $file_path);
    }

    public function importSql($database, $file_path)
    {
        // Name of the file
        $filename = $file_path;
        // MySQL host
        $mysql_host = $database['hostname'];
        // MySQL username
        $mysql_username = $database['username'];
        // MySQL password
        $mysql_password = $database['password'];
        // Database name
        $mysql_database = $database['database_name'];

        // Connect to MySQL server
        $mysqli = new \mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database, $database['port']);

        /* check connection */
        if ($mysqli->connect_errno) {
            printf("Connect failed: %s\n", $mysqli->connect_error);
            exit();
        }

        // Temporary variable, used to store current query
        $templine = '';
        // Read in entire file
        $lines = file($filename);
        // Loop through each line
        foreach ($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '') {
                continue;
            }

            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';') {
                // Perform the query
                $mysqli->query($templine) or print 'Error performing query \'<strong>'.$templine.'\': '.$mysqli->connect_error.'<br /><br />';
                // Reset temp variable to empty
                $templine = '';
            }
        }
        
        unlink($file_path);
        
        return true;
    }
}
