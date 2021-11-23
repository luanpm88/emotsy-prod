<?php

namespace Acelle\Http\Controllers\Site;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return view('site.products.index');
    }
    
    public function listing(Request $request)
    {
        $wordpress = new \Acelle\Library\WordpressManager();

        // view
        $view = $request->view ? $request->view : 'grid';

        // wordpress themes
        $products = \Acelle\Model\WpPost::products()
            ->paginate($request->per_page ? $request->per_page : 2);

        return view('site.products._list_' . $view, [
            'products' => $products,
        ]);
    }
}