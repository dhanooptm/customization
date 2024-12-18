<?php

namespace App\Http\Controllers\RestAPI\v3\seller;

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

class ProductInquiryController extends Controller
{
    // public function inquiry_list(Request $request)
    // {
    //     $seller = $request->seller;
    //     $orders =  ProductInquiry::with(['product','user'])
    //         ->where('seller_id',$seller['id'])
    //         ->latest()
    //         ->paginate($request['limit'], ['*'], 'page', $request['offset']);

    //     return response()->json([
    //         'total_size' => $orders->total(),
    //         'limit' => (int)$request['limit'],
    //         'offset' => (int)$request['offset'],
    //         'orders' => $orders->items()
    //     ], 200);
    // }
    // public function price_request_list(Request $request)
    // {
    //     $seller = $request->seller;
    //     dd($seller);
    //     $orders =  ProductPriceInquiry::with(['product','user'])
    //         ->where('seller_id',$seller['id'])
    //         ->latest()
    //         ->paginate($request['limit'], ['*'], 'page', $request['offset']);

    //     return response()->json([
    //         'total_size' => $orders->total(),
    //         'limit' => (int)$request['limit'],
    //         'offset' => (int)$request['offset'],
    //         'orders' => $orders->items()
    //     ], 200);
    // }

}
