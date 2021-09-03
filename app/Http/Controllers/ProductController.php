<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use DateTime;


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
        print_r(base_path());exit;
        print_r(time());exit; 
        $requestContent = $request->all();
        $parameter = $requestContent['timestamp'];
        print_r($parameter);
        print_r("ak is testing".$key);exit;
        
        return response()->json(['message' => $key], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = $request->input('json');
        $convertArray = (array)json_decode($item);
        $key = key($convertArray);
        if (isset($convertArray[$key])) {
            $value = $convertArray[$key];
        } else {
            return response()->json(['message' => 'Please provide json : {key:value}'], 400);
        }


        /* update existing record with same key */
        $checkProduct = Product::where(['key' => $key, 'latest' => 1])->get();
        $confirmProduct = $checkProduct->toArray();
        if ($confirmProduct) {
            $findProduct = Product::find($confirmProduct[0]['id']);
            $findProduct->latest = false;
            $findProduct->save();
        }

        /* create new record and mark as latest */
        $data = array(
            'key' => $key,
            'value' => json_encode($value)
        );
        $product = Product::create($data);

        if ($product) {
            $createDateTime = $product->created_at;
            $fromTime =  $createDateTime->format('g.i a');
            //    return response()->json(['message' => 'Success', 'id' => $product->id, 'Time' => $fromTime], 201);
            return response()->json(['message' => 'Success', 'product' => $product, 'Time' => $fromTime], 201);
        } else {
            return response()->json(['message' => 'Fail'], 400);
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
        if(isset($_SERVER['REQUEST_URI'])){
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
                return response()->json(['message' => 'Success', 'data' => $confirmProduct[0]['value']], 200);
            } else {
                return response()->json(['message' => 'No record found.'], 404);
            }
        } else {
            /* without timestamp */
            $checkProduct = Product::where(['key' => $key, 'latest' => 1])->get();
            $confirmProduct = $checkProduct->toArray();
            if (isset($confirmProduct[0])) {
                return response()->json(['message' => 'Success', 'data' => $confirmProduct[0]['value']], 200);
            } else {
                return response()->json(['message' => 'No record found.'], 404);
            }
        }
    }

    public function allRecords()
    {
        $products = Product::get();
        $confirmProduct = $products->toArray();
        if (isset($confirmProduct) && $confirmProduct != []) {
            return response()->json(['message' => 'Success', 'product' => json_decode($products)], 200);
        } else {
            return response()->json(['message' => 'No record found.'], 404);
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
