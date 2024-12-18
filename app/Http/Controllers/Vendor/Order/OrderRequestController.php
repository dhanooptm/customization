<?php

namespace App\Http\Controllers\Vendor\Order;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\ReviewReplyRepositoryInterface;
use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Enums\ViewPaths\Admin\Review;
use App\Exports\CustomerReviewListExport;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\OrderRequest;
use App\Models\PriceRange;
use App\Models\Product;
use App\Models\ProductInquiry;
use App\Models\ProductPriceInquiry;
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

class OrderRequestController extends Controller
{
    public function index(Request $request)
    {
        $key = explode(' ', $request['searchValue']);
        $status = ($request->status == "1" || $request->status == "0") ? $request->status : 'all';
        $orders = OrderRequest::with(['product','customer','seller'])->where('seller_id',auth('seller')->id())->latest()
        ->when($status == "1",function($query) use($status){
            $query->where('status',1);
        })
        ->when($status == "0",function($query) use($status){
            $query->where('status',0);
        })
         ->when(isset($key), function ($query) use ($key) {
            return $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhereHas('customer',function($query) use($value){
                            $query->where('name','like',"%{$value}%");
                        })
                        ->orWhere('order_status', 'like', "%{$value}%");
                }
            });
        })
        ->paginate(Helpers::pagination_limit());
        return view('vendor-views.order.order-request.list',compact('orders','status'));

    }
    public function details(Request $request, $id)
    {
        $order = OrderRequest::with('customer','seller','product')->where(['id' => $id])->first();
        if (isset($order)) {
            return view('vendor-views.order.order-request.order-details', compact('order'));
        } else {
            Toastr::error(translate('Order_not_found'));
            return back();
        }
    }
    public function updateReadStatus(Request $request): JsonResponse
    {
        $order = OrderRequest::where('id',$request['id'])->first();

        if ($order['is_guest'] == '0' && !isset($order['customer'])) {
            return response()->json(['customer_status' => 0], 200);
        }
        $order->order_status = $request['read_status'];
        $order->save();
        return response()->json($request['read_status']);
    }
    public function view(Request $request,$id)
    {
        $inquiry = ProductInquiry::find($request->id);
        $ranges = $inquiry?->product?->ranges;
        return view('vendor-views.product.inquiry.view',compact('inquiry','ranges'));

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
        return view('vendor-views.product.price.edit',compact('product'));
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
        $range = ProductInquiry::find($id);
        if($range){
             $range->delete();
            Toastr::success(translate('Product inquiry deleted successfully'));
            return back();
        }
    }
    public function request_list(Request $request){
        $key = explode(' ', $request['searchValue']);
        $status = ($request->status == "1" || $request->status == "0") ? $request->status : 'all';
        $products = ProductPriceInquiry::with(['product','user','seller'])->latest()
        ->when($status == "1",function($query) use($status){
            $query->where('status',1);
        })
        ->when($status == "0",function($query) use($status){
            $query->where('status',0);
        })
         ->when(isset($key), function ($query) use ($key) {
            return $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%");
                }
            });
        })
        ->paginate(Helpers::pagination_limit());
        return view('vendor-views.product.price-inquiry.list',compact('products','status'));
    }
    public function price_status_update(Request $request)
    {
        $inquiry = ProductPriceInquiry::find($request->id);
        if($inquiry){
            $inquiry->status = $request->status ? 1 : 0;
            $inquiry->save();
        }
        return response()->json([
            'status' => 1,
            'message' => translate('delete_successful')
        ]);

    }
    public function price_destroy(Request $request,$id){
        $range = ProductPriceInquiry::find($id);
        if($range){
             $range->delete();
            Toastr::success(translate('Product inquiry deleted successfully'));
            return back();
        }
    }
}
