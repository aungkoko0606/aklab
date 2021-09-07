<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function aktest($key, Request $request)
    {
        /* $requestContent = $request->all();
        $parameter = $requestContent['timestamp']; */

        return response()->json(['message' => 'Test Environment'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product = $request->store($request);
        
        if ($product == "Fail") {
            return response()->json(['message' => 'Fail'], Response::HTTP_BAD_REQUEST);
        } else {
            $createDateTime = $product->created_at;
            $fromTime =  $createDateTime->format('g.i a');
            return response()->json(['message' => 'Success', 'product' => $product, 'Time' => $fromTime], Response::HTTP_CREATED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($key)
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = $_SERVER['REQUEST_URI'];
            $url_components = parse_url($url);
        }
        /* with timestamp */
        if (isset($url_components['query'])) {
            parse_str($url_components['query'], $params);
            $timeValue = $params['timestamp'];
            $createDate = date('Y-m-d H:i:s', $timeValue);
            $checkProduct = Product::where(['key' => $key, 'created_at' => $createDate])->get();
            $confirmProduct = $checkProduct->toArray();

            if (isset($confirmProduct[0])) {
                return response()->json(['message' => 'Success', 'data' => $confirmProduct[0]['value']], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'No record found.'], Response::HTTP_NOT_FOUND);
            }
        } else {
            /* without timestamp */
            $checkProduct = Product::where(['key' => $key, 'latest' => 1])->get();
            $confirmProduct = $checkProduct->toArray();
            if (isset($confirmProduct[0])) {
                return response()->json(['message' => 'Success', 'data' => $confirmProduct[0]['value']], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'No record found.'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function allRecords()
    {
        $products = Product::get();
        $confirmProduct = $products->toArray();
        if (isset($confirmProduct) && $confirmProduct != []) {
            return response()->json(['message' => 'Success', 'product' => json_decode($products)], Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'No record found.'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
