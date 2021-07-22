<?php

namespace Acelle\Http\Controllers\Admin;

use Acelle\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Acelle\Model\Language;

class Upgrade extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateTranslationFiles(Request $request)
    {
        Language::dump();
        $pageUrl = action('Controller@runMigration');
        echo '<html>
            <head>
                <meta http-equiv="refresh" content="3;'.url('/').'" />
                <title>Upgrade</title>
            </head>
            <body>
                Translation files updated, redirecting...
            </body>
        </html>';
    }
}
