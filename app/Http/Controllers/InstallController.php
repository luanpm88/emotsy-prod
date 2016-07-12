<?php

namespace Mailixa\Http\Controllers;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;

class InstallController extends Controller
{
    // check for current step
    public function step($request)
    {
        $step = 0;
        
        $data = $request->session()->get('requirements');
        if (isset($data)) {
            $step = 1;
        } else {
            return $step;
        }
        
        $data = $request->session()->get('permissions');
        if (isset($data)) {
            $step = 2;
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
        
        return $step;
    }
    
    // Starting installation
    public function starting(Request $request)
    {
        exec('php '.base_path().'/artisan config:cache');
        return redirect()->action('InstallController@requirements');
    }
    
    public function requirements(Request $request)
    {
        // Begin check
        $request->session()->forget('requirements');
        
        $requirements = $this->checkRequirements();
        $result = true;        
        foreach ($requirements as $requirement) {
            if (!$requirement["check"]) {
                $result = false;
            }
        }
        
        if ($result) {
            $request->session()->set('requirements', $requirements);
        }
        
        return view('install.requirements', [
            'requirements' => $requirements,
            'result' => $result,
            'step' => $this->step($request),
            'current' => 1,
        ]);
    }
    
    public function permissions(Request $request)
    {
        if ($this->step($request) < 1) {
            return redirect()->action('InstallController@requirements');
        }
        
        // Begin check
        $request->session()->forget('permissions');
        
        $permissions = $this->checkPermissions();
        $result = true;        
        foreach ($permissions as $permission) {
            if (!$permission["check"]) {
                $result = false;
            }
        }
        
        if ($result != 0) {
            $request->session()->set('permissions', $permissions);
        }
       
        return view('install.permissions', [
            'permissions' => $permissions,
            'result' => $result,
            'step' => $this->step($request),
            'current' => 2,
        ]);
    }
    
    public function siteInfo(Request $request)
    {
        if ($this->step($request) < 2) {
            return redirect()->action('InstallController@permissions');
        }
        
        // make sure session is working
        $rules = [
            'site_name' => 'required',
            'site_keyword' => 'required',
            'site_description' => 'required',
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required|email",
            "password" => "required",
            "timezone" => "required",
        ];
        // validate and save posted data
        if ($request->isMethod('post')) {
            $request->session()->forget('site_info');
             
            $this->validate($request, $rules);
            
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
            'step' => $this->step($request),
            'current' => 3,
        ]);
    }
    
    // Database configuration
    public function database(Request $request)
    {
        if ($this->step($request) < 3) {
            return redirect()->action('InstallController@siteInfo');
        }
        
        // Check required fields
        $rules = array(
            "hostname" => "required",
            "port" => "required",
            "username" => "required",
            "password" => "required",
            "database_name" => "required",
            "tables_prefix" => "required",
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
                $rules["mysql_connection"] = "required";
            }
            
            $this->validate($request, $rules);
            
            // Save database session
            $database = $request->all();
            $request->session()->set('database', $database);
            
            // IMPORT DATABASE
            // write config file
            $this->write_env($request);
            
            return redirect()->action('InstallController@databaseImport', ["action" => "import"]);
        }
        
        $database = $request->session()->get('database');
        if (!empty($request->old())) {
            $database = $request->old();
        }
        
        return view('install.database', [
            'database' => $database,
            'rules' => $rules,
            'step' => $this->step($request),
            'current' => 4,
        ]);
    }
    
    // Import Database
    public function databaseImport(Request $request)
    {
        if ($this->step($request) < 4) {
            return redirect()->action('InstallController@database');
        }
        
        $database = $request->session()->get('database');
        
        // 
        if ($request->action == 'import') {
            $request->session()->forget('database_imported');
            
            // connect mysql
            $mysqli = new \mysqli($database["hostname"], $database["username"], $database["password"], $database["database_name"], $database["port"]);
            
            // Drop all old table
            $done = true;
            while ($done) {
                try {
                    $delete_all_tables_query = "SELECT CONCAT( 'DROP TABLE ', GROUP_CONCAT(table_name) , ';' ) 
                                                AS statement FROM information_schema.tables 
                                                WHERE table_schema = '".$database["database_name"]."' AND table_name LIKE '".$database["tables_prefix"]."%' limit 1;";
                    $result = $mysqli->query($delete_all_tables_query);
                    $result = $result->fetch_object();
                    
                    if (isset($result->statement)) {
                        $mysqli->query($result->statement);
                        continue;
                    }
                } catch (\Exception $e) {
                    $rules["mysql_connection"] = "required";
                }
                
                $done = false;
            }
            
            // Check if database is not empty
            $rules = [];
            $result = $mysqli->query("SELECT COUNT(DISTINCT `table_name`) as count FROM `information_schema`.`columns` WHERE `table_schema` = '".$database["database_name"]."' AND table_name LIKE '".$database["tables_prefix"]."%'");
            $result = $result->fetch_object();
            if ($result->count > 0) {
                $rules["database_not_empty"] = "required";
            }
            $this->validate($request, $rules);
            
            // import database with prefix
            $this->importDatabase($database);
            
            $request->session()->set('database_imported', true);
            
            return redirect()->action('InstallController@adminAccount');
        }
        
        return view('install.database_import', [
            'database' => $database,
            'step' => $this->check_step($request),
        ]);
    }
    
    
    // Admin account
    public function adminAccount(Request $request)
    {
        if ($this->step($request) < 5) {
            return redirect()->action('InstallController@database');
        }
        
        // Check required fields
        $rules = array(
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required|email",
            "password" => "required",
            "timezone" => "required",
        );
        
        // validate and save posted data
        if ($request->isMethod('post')) {
            $request->session()->forget('admin_account');
            
            $this->validate($request, $rules);
            
            // get database connection
            $database = $request->session()->get('database');
            $mysqli = new \mysqli($database["hostname"], $database["username"], $database["password"], $database["database_name"], $database["port"]);
            
            // default date
            $date = \Carbon\Carbon::now();
            
            // Insert default superuser
            $mysqli->query("DELETE FROM `".$database["tables_prefix"]."users` WHERE 1");
            $mysqli->query("INSERT INTO `".$database["tables_prefix"]."users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `group_id`, `language_id`, `first_name`, `last_name`, `timezone`, `avatar`, `removable`, `status`, `hourly_quota`, `confirmation_key`, `oauth_uid`, `oauth_provider`, `backend_access`) VALUES
(1, '".$request->first_name." ".$request->last_name."', '".$request->email."', '".bcrypt($request->password)."', '', '".$date."', '".$date."', 1, 1, '".$request->first_name."', '".$request->last_name."', '".$request->timezone."', NULL, 'no', 'active', NULL, NULL, NULL, NULL, 'yes');");

            
            // Insert default customer
            $mysqli->query("DELETE FROM `".$database["tables_prefix"]."customer` WHERE 1");
            $mysqli->query("INSERT INTO `".$database["tables_prefix"]."customer` (`customer_id`, `email`, `password`, `created_at`, `updated_at`, `group_id`, `language_id`, `first_name`, `last_name`, `timezone`, `avatar`, `removable`, `status`, `confirmation_key`, `oauth_uid`, `oauth_provider`) VALUES
(1, '".$request->email."', '".bcrypt($request->password)."', '".$date."', '".$date."', 1, 1, '".$request->first_name."', '".$request->last_name."', '".$request->timezone."', NULL, 'no', 'active', NULL, NULL, NULL);");
            
            // Insert system urls
            $mysqli->query("DELETE FROM `".$database["tables_prefix"]."option` WHERE category='system.urls' AND key='unsubscribe_url'");
            $mysqli->query("INSERT INTO `".$database["tables_prefix"]."option` (`category`, `key`, `value`, `is_serialized`, `created_at`) VALUES
                            ('system.urls', 'unsubscribe_url', '".action('CampaignController@unsubscribe', ['campaign_uid' => 'CAMPAIGN_UID', 'subscriber_uid' => 'SUBSCRIBER_UID'])."', '".$date."', '".$date."');");
            $mysqli->query("DELETE FROM `".$database["tables_prefix"]."option` WHERE category='system.urls' AND key='open_track_url'");
            $mysqli->query("INSERT INTO `".$database["tables_prefix"]."option` (`category`, `key`, `value`, `is_serialized`, `created_at`) VALUES
                            ('system.urls', 'open_track_url', '".action('CampaignController@open_track', ['campaign_uid' => 'CAMPAIGN_UID', 'subscriber_uid' => 'SUBSCRIBER_UID'])."', '".$date."', '".$date."');");
            $mysqli->query("DELETE FROM `".$database["tables_prefix"]."option` WHERE category='system.urls' AND key='campaign_notification_url'");
            $mysqli->query("INSERT INTO `".$database["tables_prefix"]."option` (`category`, `key`, `value`, `is_serialized`, `created_at`) VALUES
                            ('system.urls', 'campaign_notification_url', '".action('CampaignController@notification')."', '".$date."', '".$date."');");
            
            
            // Insert defaut tags
            $mysqli->query("DELETE FROM `".$database["tables_prefix"]."tag_registry` WHERE 1");
            $mysqli->query("INSERT INTO `".$database["tables_prefix"]."tag_registry` (`tag`, `description`, `created_at`, `updated_at`) VALUES
                            ('SUBSCRIBER_EMAIL', '', '".$date."', '".$date."'),
                            ('CAMPAIGN_NAME', '', '".$date."', '".$date."'),
                            ('CAMPAIGN_UID', '', '".$date."', '".$date."'),
                            ('CAMPAIGN_SUBJECT', '', '".$date."', '".$date."'),
                            ('CAMPAIGN_FROM_EMAIL', '', '".$date."', '".$date."'),
                            ('CAMPAIGN_FROM_NAME', '', '".$date."', '".$date."'),
                            ('CAMPAIGN_REPLY_TO', '', '".$date."', '".$date."'),
                            ('SUBSCRIBER_UID', '', '".$date."', '".$date."'),
                            ('CURRENT_YEAR', '', '".$date."', '".$date."'),
                            ('CURRENT_MONTH', '', '".$date."', '".$date."'),
                            ('CURRENT_DAY', '', '".$date."', '".$date."'),
                            ('UNSUBSCRIBE_URL', '', '".$date."', '".$date."'),
                            ('COMPANY_NAME', '', '".$date."', '".$date."'),
                            ('COMPANY_COUNTRY', '', '".$date."', '".$date."'),
                            ('COMPANY_ZONE', '', '".$date."', '".$date."'),
                            ('COMPANY_CITY', '', '".$date."', '".$date."'),
                            ('COMPANY_ADDRESS_1', '', '".$date."', '".$date."'),
                            ('COMPANY_ADDRESS_2', '', '".$date."', '".$date."'),
                            ('COMPANY_PHONE', '', '".$date."', '".$date."'),
                            ('LIST_NAME', '', '".$date."', '".$date."'),
                            ('LIST_DESCRIPTION', '', '".$date."', '".$date."'),
                            ('LIST_SUBJECT', '', '".$date."', '".$date."'),
                            ('LIST_FROM_NAME', '', '".$date."', '".$date."'),
                            ('LIST_FROM_EMAIL', '', '".$date."', '".$date."');
                    ");
            
            $admin_account = $request->all();
            $request->session()->set('admin_account', $admin_account);
            
            return redirect()->action('InstallController@smtpAccount');
        }
        
        $admin_account = $request->session()->get('admin_account');
        if (!empty($request->old())) {
            $admin_account = $request->old();
        }
        
        return view('install.admin_account', [
            'admin_account' => $admin_account,
            'rules' => $rules,
            'step' => $this->step($request),
            'current' => 5,
        ]);
    }
    
    // Admin account
    public function smtpAccount(Request $request)
    {
        if ($this->check_step($request) < 7) {
            return redirect()->action('InstallController@adminAccount');
        }
        
        // validate and save posted data
        if ($request->isMethod('post')) {
            $request->session()->forget('smtp_account');
            
            // Check required fields
            $rules = array(
                        "hostname" => "required",
                        "port" => "required",
                        "username" => "required",
                        "password" => "required",
                        "from_email" => "required|email",
                        "from_name" => "required",
                        "encryption" => "required",
                    );
            $this->validate($request, $rules);
            
            // Check SMTP connection
            $rules = [];
            try {
                $transport = \Swift_SmtpTransport::newInstance($request->hostname, $request->port, $request->encryption);
                $transport->setUsername($request->username);
                $transport->setPassword($request->password);
                $mailer = \Swift_Mailer::newInstance($transport);
                $mailer->getTransport()->start();
            } catch (\Swift_TransportException $e) {
                $rules['smtp_valid'] = 'required';
            } catch (Exception $e) {
                $rules['smtp_valid'] = 'required';
            }
            $this->validate($request, $rules);
            
            // save smtp config to session
            $smtp_account = $request->all();
            $request->session()->set('smtp_account', $smtp_account);
            
            // Write env
            $this->write_env($request);
            
            return redirect()->action('InstallController@cronJobs');
        }
        
        $smtp_account = $request->session()->get('smtp_account');
        if (!empty($request->old())) {
            $smtp_account = $request->old();
        }
        
        return view('install.smtp_account', [
            'smtp_account' => $smtp_account,
            'step' => $this->check_step($request),
        ]);
    }
    
    public function cronJobs(Request $request)
    {
        if ($this->check_step($request) < 8) {
            return redirect()->action('InstallController@adminAccount');
        }
        
        $request->session()->set('cron_jobs', true);
        
        return view('install.cron_jobs', [
            'step' => $this->check_step($request),
        ]);
    }
    
    public function finish(Request $request)
    {
        if ($this->check_step($request) < 9) {
            return redirect()->action('InstallController@cronJobs');
        }
        
        $request->session()->set('install_finish', true);
        
        return view('install.finish', [
            'step' => $this->check_step($request),
        ]);
    }
    
    // Check for requirement when install app
    public function checkRequirements()
    {
        return [
            [
                "name" => "PHP version",
                "check" => version_compare(PHP_VERSION, "5.5.9", ">="),
                "note" => 'PHP 5.5 or higher is required.'
            ],            
        ];
    }
    
    public function checkServerVar()
    {
        $vars=array('HTTP_HOST','SERVER_NAME','SERVER_PORT','SCRIPT_NAME','SCRIPT_FILENAME','PHP_SELF','HTTP_ACCEPT','HTTP_USER_AGENT');
        $missing=array();
        foreach ($vars as $var) {
            if (!isset($_SERVER[$var])) {
                $missing[]=$var;
            }
        }
        
        if (!empty($missing)) {
            return '$_SERVER does not have: '. implode(', ', $missing) ;
        }
    
        if (!isset($_SERVER["REQUEST_URI"]) && isset($_SERVER["QUERY_STRING"])) {
            return 'Either $_SERVER["REQUEST_URI"] or $_SERVER["QUERY_STRING"] must exist.';
        }
    
        if (!isset($_SERVER["PATH_INFO"]) && strpos($_SERVER["PHP_SELF"], $_SERVER["SCRIPT_NAME"]) !== 0) {
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
            $imagick=new Imagick();
            $imagickFormats=$imagick->queryFormats('PNG');
        }
        
        if (extension_loaded('gd')) {
            $gdInfo=gd_info();
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
    
    // Check filesystem permissions
    public function checkPermissions()
    {
        return [
            [
                "name" => 'Storage/app/',
                "check" => file_exists(base_path('/storage/app')) && is_dir(base_path('/storage/app')) && (is_writable(base_path('/storage/app'))),
                "note" => 'The directory must be writable by the web server (chmod 0775).'
            ],            
        ];
    }
    
    public function write_env($request)
    {
        // read app key from file
        $file_path = base_path(".env");
        $file = fopen($file_path, "r") or die("Unable to open file!");
        $app_config = fread($file, filesize($file_path));
        preg_match('/APP_KEY=(.*)\n/', $app_config, $match);
        $app_key = trim($match[1]);
        fclose($file);
        
        // get database config
        $database = $request->session()->get('database');
        $database = !empty($database) ? $database : [];

        // get smtp config
        $smtp = $request->session()->get('smtp_account');
        $smtp = !empty($smtp) ? $smtp : [];
        
        $env_file = fopen(base_path(".env"), "w") or die("Unable to open file!");
        $config_string = 'APP_ENV=local
APP_DEBUG=true
APP_KEY='.$app_key.'

DB_HOST='.(isset($database["hostname"]) ? $database["hostname"] : "").'
DB_DATABASE='.(isset($database["database_name"]) ? $database["database_name"] : "").'
DB_USERNAME='.(isset($database["username"]) ? $database["username"] : "").'
DB_PASSWORD='.(isset($database["password"]) ? $database["password"] : "").'
DB_PORT='.(isset($database["port"]) ? $database["port"] : "").'
DB_PREFIX='.(isset($database["tables_prefix"]) ? $database["tables_prefix"] : "").'

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=async

REDIS_HOST=localhost
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST='.(isset($smtp["hostname"]) ? $smtp["hostname"] : "").'
MAIL_PORT='.(isset($smtp["port"]) ? $smtp["port"] : "").'
MAIL_USERNAME='.(isset($smtp["username"]) ? $smtp["username"] : "").'
MAIL_PASSWORD='.(isset($smtp["password"]) ? $smtp["password"] : "").'
MAIL_ENCRYPTION='.(isset($smtp["encryption"]) ? $smtp["encryption"] : "").'
MAIL_FROM='.(isset($smtp["from_email"]) ? $smtp["from_email"] : "").'
MAIL_NAME="'.(isset($smtp["from_name"]) ? $smtp["from_name"] : "").'"
';
        $config_string;
        fwrite($env_file, $config_string);
        fclose($env_file);
        
        // Reload .env
        exec('php '.base_path().'/artisan config:cache');
    }
    
    public function importDatabase($database)
    {
        // read and replace prefix
        $file_path = storage_path("import/database_init.sql");
        $file = fopen($file_path, "r") or die("Unable to open file!");
        $sql = fread($file, filesize($file_path));
        $sql = str_replace("<<prefix>>", $database["tables_prefix"], $sql);
        fclose($file);
        
        // write file
        $file_path = storage_path("import/database_import.sql");
        $file = fopen($file_path, "w") or die("Unable to open file!");
        fwrite($file, $sql);
        fclose($file);
        
        // import sql
        $file_path = storage_path("import/database_import.sql");
        $command = "mysql --host=".$database["hostname"]." --user=".$database["username"]." --password=".$database["password"]." --port=".$database["port"]." --database=".$database["database_name"]." < ".$file_path;
        
        return $this->importSql($database, $file_path);
    }
    
    public function importSql($database, $file_path)
    {
        // Name of the file
        $filename = $file_path;
        // MySQL host
        $mysql_host = $database["hostname"];
        // MySQL username
        $mysql_username = $database["username"];
        // MySQL password
        $mysql_password = $database["password"];
        // Database name
        $mysql_database = $database["database_name"];
        
        // Connect to MySQL server
        $mysqli = new \mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database, $database["port"]);

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
            $mysqli->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . $mysqli->connect_error . '<br /><br />');
            // Reset temp variable to empty
            $templine = '';
        }
        }
        
        return true;
    }
    
    public function write_config($name, $data)
    {
        $objData = serialize($data);
        $filePath = storage_path()."/".$name.".tmp";
        $fp = fopen($filePath, "w") or die("Unable to open file!");
        fwrite($fp, $objData);
        fclose($fp);
        
        return true;
    }
    
    public function read_config($name)
    {
        $filePath = storage_path()."/".$name.".tmp";
        if (file_exists($filePath)) {
            $objData = file_get_contents($filePath);
            $obj = unserialize($objData);
            return $obj;
        } else {
            return false;
        }
    }
    
    public function remove_config($name)
    {
        $filePath = storage_path()."/".$name.".tmp";
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
