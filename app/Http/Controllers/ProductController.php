<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use function GuzzleHttp\Psr7\str;

class ProductController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        try{
            DB::beginTransaction();
            $inputs     = $request->input('product');;
//            $product    = Product::create([
//                "name"=> $inputs->name,
//                "price"=> $inputs->price,
//                "description"=> $inputs->description,
//                "category_id"=> $inputs->category_id,
//                "branch_id"=> $inputs->branch_id,
//                "inventory_number"=> $inputs->inventory_number,
//                "total_number"=> $inputs->total_number,
//                "sales_number"=> $inputs->sales_number,
//                "rate"=> $inputs->rate,
//                "vote"=> $inputs->vote
//            ]);
            $product = Product::create($inputs);
            DB::commit();
            return response()->json([
                "status"  => "success",
                "message" => "success",
                "product" => $product],
            200);

        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(["status" => "failed", "message" => $e], 500);
        }


    }

    public function productProperties(Request $request){
        try{
            DB::beginTransaction();
            ProductProperty::query()->create([
                'product_id'=> $request->product_id,
                'size' => $request->size?? null,
                'material'=> $request->material?? null,
                'color'=> $request->color?? null,
                'design'=> $request->design?? null,
                'sleeve'=> $request->sleeve?? null,
                'piece'=> $request->piece?? null,
                'set_type'=> $request->set_type?? null,
                'description'=> $request->description?? null,
                'maintenance'=> $request->maintenance?? null,
                'made_in'=> $request->made_in?? null,
                'origin'=> $request->origin?? null,
                'type'=> $request->type?? null,
                'for_use'=> $request->for_use?? null,
                'collar'=> $request->collar?? null,
                'height'=> $request->height?? null,
                'physical_feature'=> $request->physical_feature?? null,
                'production_time'=> $request->production_time?? null,
                'demension'=> $request->demension?? null,
                'crotch'=> $request->crotch?? null,
                'close'=> $request->close?? null,
                'drop'=> $request->drop?? null,
                'cumin'=> $request->cumin?? null,
                'close_shoes'=> $request->close_shoes?? null,
                'typeـofـclothing'=> $request->typeـofـclothing?? null,
                'specialized_features'=> $request->specialized_features?? null
            ]);
            DB::commit();
            return response()->json([
                "status" => "success",
                "message" => "success",
            ],200);
        }catch(\Exception $exception){
            DB::rollBack();
            return response()->json(["status" => "failed", "message" => $exception],500);
        }
    }

    public function update(Request $request, $id){
//        try{
            DB::beginTransaction();
            $inputs = $request->input('product');;
            $product   =   Product::find($id);
            if($inputs['name']) $product->name = $inputs['name'];
            if($inputs['price']) $product->price = $inputs['price'];
            if($inputs['description']) $product->description = $inputs['description'];
            if($inputs['inventory_number']) $product->inventory_number = $inputs['inventory_number'];
            if($inputs['total_number']) $product->total_number = $inputs['total_number'];
            if($inputs['sales_number']) $product->sales_number = $inputs['sales_number'];
            if($inputs['brand_id']) $product->brand_id = $inputs['brand_id'];
            $product->save();

//            $inputs = $request->properties[0];
//            ProductProperty::where('product_id', $product->id)->delete();
//            foreach($inputs as $input){
//                ProductProperty::create([
//                    'product_id'=>$product->id,
//                    'property_id'=>$input[0]['property_id'],
//                    'value'=>$input[0]['value'],
//                ]);
//            }
//
//            $properties = ProductProperty::where('product_id', $product->id)
//                ->get();
            DB::commit();
            return response()->json(["status" => "success", "message" => "Success! registration completed", "product" => $product]
            );

//        }catch (\Exception $e){
//            DB::rollBack();
//            return response()->json(["status" => "failed", "message" => $e]);
//        }
    }

    public function search(Request $request){

        $name = $request->input('name');
        $min_price = $request->input('$min_price');
        $max_price = $request->input('$max_price');
        $brand_id = $request->input('$brand_id');
        $id = $request->input('$id');
        $available = $request->input('$available');
        try{
            $list = Product::query()
                ->when($name, function ($q, $name) {
                    return $q->where('name', $name);
                })
                ->when($min_price, function ($q, $min_price) {
                    return $q->where('price', '>=', $min_price);
                })
                ->when($max_price, function ($q, $max_price) {
                    return $q->where('price', '<=', $max_price);
                })
                ->when($brand_id, function ($q, $brand_id) {
                    return $q->where('brand_id', $brand_id);
                })
                ->when($id, function ($q, $id) {
                    return $q->where('id', $id);
                })
                ->orderBy('created_at')->get();
//            if ($available == 1)
//                $list->where('inventory_number', '>', 0);
//            else if ($available === 0)
//                $list->where('inventory_number', '=', 0);

//            $data = array();
//            $i = 0;

//            foreach ($list->get() as $item){
//                $properties = ProductProperty::query()
//                    ->where('product_id', $item->id)
//                    ->when($properties_filter, function ($q, $properties_filter) {
//                        return $q->whereIn('product_properties.property_id', $properties_filter);
//                    })
//                    ->join('category_metas', 'category_metas.id', 'product_properties.property_id')
//                    ->select([
//                        'category_metas.value as key',
//                        'product_properties.value as value',
//                    ])
//                    ->get();
//
//                $images = Media::query()->where('product_id', $item->id)
//                    ->select('id','url')->get();
//
//                $data[$i++] = array([
//                    'product' => $item,
//                    'properties' => $properties,
//                    'images' => $images
//                ]);
//            }

            $response = [
                'status' => true,
                'msg' => 'list successfully get.',
                'data' => $list
            ];

            return response()->json($response);
        }catch(Exception $e){
            return response($e, 500);
        }
    }

    public function delete($id){
        try{
            $product = Product::find($id);
            $product->delete();
            return response()->json(['data'=>'ok'], 200);
        }catch (\Exception $exception){
            return response()->json(['data'=>'error'], 500);
        }
    }
}
