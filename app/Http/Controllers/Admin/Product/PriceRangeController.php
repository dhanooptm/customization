<?php

namespace App\Http\Controllers\Admin\Product;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\ReviewReplyRepositoryInterface;
use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Enums\ViewPaths\Admin\Review;
use App\Exports\CustomerReviewListExport;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\PriceRange;
use App\Models\Product;
use App\Utils\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PriceRangeController extends Controller
{
    public function index(Request $request)
    {
        $product_id = $request->product_id;
        $products = Product::Active()->get();
        $product = Product::with('ranges')->where('id',$request->product_id)->first();
        return view('admin-views.product.price.add-new',compact('products','product','product_id'));

    }
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product_id' => 'required',
            'start_point' => 'required',
            'end_point' => 'required|gt:start_point',
            'price' => 'required',
        ], [
            'product_id.required' => translate('Product is required!'),
        ]);
        $is_endless_delivery_range_mapping=PriceRange::where('product_id',$request->product_id)->where('is_endless',1)->first();

        if ($is_endless_delivery_range_mapping) {
            Toastr::warning(translate('endless already checked'));
            return back();
        }
        $start_point = $request->start_point;
        $end_point = $request->end_point;
        $temp = PriceRange::where('product_id',$request->product_id)->where(function ($query) use ($start_point) {
            $query->where('start_point', '<=', $start_point)->where('end_point', '>=', $start_point);
        })->orWhere(function ($query) use ($end_point,$request) {
            $query->where('product_id',$request->product_id)->where('start_point', '<=', $end_point)->where('end_point', '>=', $end_point);
        })->orWhere(function ($query) use ($start_point,$end_point,$request) {
            $query->where('product_id',$request->product_id)->where('start_point', '>=', $start_point)->where('end_point', '<=', $end_point);
        })
        ->first();
        if($temp){
            Toastr::warning(translate('price range overlaped'));
            return back();
        }

        $product = new PriceRange();
        $product->product_id = $request->product_id;
        $product->start_point = $request->start_point;
        $product->end_point = $request->end_point;
        $product->price = $request->price;
        if($request->is_endless){
            $product->is_endless=1;
        }
        $product->save();
        Toastr::success(translate('Price range added successfully'));
        return back();

    }
    public function edit($id){

        $product = PriceRange::find($id);
        return view('admin-views.product.price.edit',compact('product'));
    }
    public function update(Request $request,$id){
        $request->validate([
            'start_point' => 'required',
            'end_point' => 'required|gt:start_point',
            'price' => 'required',
        ]);
        $is_endless_delivery_range_mapping=PriceRange::where('id' ,'!=', $id)->where('product_id', $request->product_id)->where('is_endless',1)->first();
        if ($is_endless_delivery_range_mapping) {
            Toastr::warning(translate('endless already checked'));
            return back();
        }
        $start_point = $request->start_point;
        $end_point = $request->end_point;
        $temp = PriceRange::where('id' ,'!=', $id) ->where('product_id', $request->product_id)
        ->where(function ($q) use ($start_point,$end_point ){
            $q->where(function ($query) use ($start_point) {
                $query->where('start_point', '<=', $start_point)->where('end_point', '>=', $start_point);
            })->orWhere(function ($query) use ($end_point) {
                $query->where('start_point', '<=', $end_point)->where('end_point', '>=', $end_point);
            })->orWhere(function ($query) use ($start_point, $end_point) {
                $query->where('start_point', '>=', $start_point)->where('end_point', '<=', $end_point);
            });
        })
        ->first();
        if($temp){
            Toastr::warning(translate('price range overlaped'));
            return back();
        }

        $product = PriceRange::find($id);
        $product->product_id = $request->product_id;
        $product->start_point = $request->start_point;
        $product->end_point = $request->end_point;
        $product->price = $request->price;
        $product->is_endless= $request->is_endless ? 1 : 0;

        $product->save();
        Toastr::success(translate('Price range added successfully'));
        return back();

    }
    public function destroy(Request $request,$id){
        $range = PriceRange::find($id);
        if($range){
            $range->delete();
            Toastr::success(translate('Price range deleted successfully'));
            return back();
        }
    }
}
