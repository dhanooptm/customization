<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Events\ContactSupplierEvent;
use App\Events\PriceRequestEvent;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MostDemanded;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductInquiry;
use App\Models\ProductPriceInquiry;
use App\Models\Review;
use App\Models\ShippingMethod;
use App\Models\User;
use App\Models\Wishlist;
use App\Traits\FileManagerTrait;
use App\Utils\CategoryManager;
use App\Utils\Helpers;
use App\Utils\ImageManager;
use App\Utils\ProductManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductInquiryController extends Controller
{
    public function product_inquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'quantity' => 'required',
            'descriptions' => 'required',
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];
        $product = Product::find($request->product_id);
        $inquiry = new ProductInquiry();
        $inquiry->product_id = $request->product_id;
        $inquiry->seller_id = $product->added_by == 'seller' ? $product->user_id:null;
        $inquiry->user_id = $request->user()?->id;
        $inquiry->quantity = $request->quantity;
        $inquiry->descriptions = $request->descriptions;
        $inquiry->contact = json_encode($data);
        $inquiry->save();
        try {
            $user = User::where('id',auth('api')->user()->id)->first();
            $email = $user->email;
            $data = [
                'subject' => translate('contact_supplier'),
                'title' => translate('contact_supplier'),
                'userName' => auth('customer')->name,
                'userType' => 'customer',
                'templateName' => 'contact-supplier',
                'order' => $inquiry,
                'shopName' => $inquiry?->seller?->shop?->name ?? getWebConfig('company_name'),
                'shopId' => $inquiry?->seller?->shop?->id ?? 0,
                // 'attachmentPath' =>self::storeInvoice($order->id),
            ];
            event(new ContactSupplierEvent(email: $email, data: $data));
        } catch (\Exception $exception) {
        }
        return response()->json(['message' => translate('product_Inquiry_added_successfully')], 200);

    }
    public function price_inquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'descriptions' => 'required',
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'company' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $product = Product::find($request->product_id);
        $inquiry = new ProductPriceInquiry();
        $inquiry->product_id = $request->product_id;
        $inquiry->seller_id = $product->added_by == 'seller' ? $product->user_id:null;
        $inquiry->user_id = $request->user()?->id;
        $inquiry->descriptions = $request->descriptions;
        $inquiry->company = $request->company;
        $inquiry->name = $request->name;
        $inquiry->email = $request->email;
        $inquiry->phone = $request->phone;
        $inquiry->is_dealer = $request->is_dealer ? 1 : 0;
        $inquiry->similar_info = $request->similar_info ? 1 : 0;
        $inquiry->pin = $request->pin;
        $inquiry->save();
        try {
            $user = User::where('id',auth('api')->user()->id)->first();
            $email = $user->email;
            $data = [
                'subject' => translate('price_request'),
                'title' => translate('price_request'),
                'userName' => auth('customer')->name,
                'userType' => 'customer',
                'templateName' => 'price-request',
                'order' => $inquiry,
                'shopName' => $inquiry?->seller?->shop?->name ?? getWebConfig('company_name'),
                'shopId' => $inquiry?->seller?->shop?->id ?? 0,
                // 'attachmentPath' =>self::storeInvoice($order->id),
            ];
            event(new PriceRequestEvent(email: $email, data: $data));
        } catch (\Exception $exception) {
        }

        return response()->json(['message' => translate('product_price_inquiry_added_successfully')], 200);

    }
    public function list(Request $request)
    {
        $key = explode(' ', $request['searchValue']);
        $status = $request->status ? $request->status : null;
        $products = ProductInquiry::with(['product','user','seller'])->latest()
        ->when($status,function($query) use($status){
            $query->where('status',$status);
        })
         ->when(isset($key), function ($query) use ($key) {
            return $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('contact', 'like', "%{$value}%")
                        ->orWhere('descriptions', 'like', "%{$value}%");
                }
            });
        })
        ->paginate(Helpers::pagination_limit());
        return view('admin-views.product.inquiry.list',compact('products','status'));

    }
    public function status_update(Request $request)
    {
        $inquiry = ProductInquiry::find($request->id);
        if($inquiry){
            $inquiry->status = $request->status ? 1 : 0;
            $inquiry->save();
        }
        return response()->json([
            'status' => 1,
            'message' => translate('delete_successful')
        ]);

    }
    public function view(Request $request,$id)
    {
        $inquiry = ProductInquiry::find($request->id);
        $ranges = $inquiry?->product?->ranges;
        return view('admin-views.product.inquiry.view',compact('inquiry','ranges'));

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
        $range = ProductInquiry::find($id);
        if($range){
             $range->delete();
            Toastr::success(translate('Product inquiry deleted successfully'));
            return back();
        }
    }
    public function request_list(Request $request){
        $key = explode(' ', $request['searchValue']);
        $status = $request->status ? $request->status : null;
        $products = ProductPriceInquiry::with(['product','user','seller'])->latest()
        ->when($status,function($query) use($status){
            $query->where('status',$status);
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
        return view('admin-views.product.price-inquiry.list',compact('products','status'));
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
