<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Acelle\Model\MailList;
use Acelle\Model\EmailVerificationServer;
use Acelle\Events\MailListSubscription;
use Acelle\Model\Setting;
use Acelle\Model\Customer;
use Acelle\Model\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('products.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $products = Product::search($request)->paginate($request->per_page);

        return view('products._list', [
            'products' => $products,
        ]);
    }

    public function image(Request $request, $id)
    {
        $product = Product::find($id);

        if ($product->getImagePath()) {
            return response()->file($product->getImagePath());
        } else {
            return response()->file(public_path('image/no-product-image.png'));
        }
    }
}
