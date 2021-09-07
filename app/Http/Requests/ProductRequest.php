<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    public function store(Request $request){
        if (count($request->except('_token')) == 1) {
            foreach ($request->except('_token') as $key => $value) {
                $key = $key;
                $value = $value;
            }
        } else {
            return "Fail";
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
        return Product::create($data);
    }
}
