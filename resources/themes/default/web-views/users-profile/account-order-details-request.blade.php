@extends('layouts.front-end.app')

@section('title', translate('order_Details'))

@section('content')

    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl __inline-47 text-align-direction">
        <div class="row g-3">
            @include('web-views.partials._profile-aside')

            <section class="col-lg-9">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 text-capitalize">
                            <h4 class="text-capitalize mb-0 mobile-fs-14 fs-18 font-bold">{{translate('order')}} #{{$order->id}} </h4>
                            @if($order['order_status'] == 'confirmed' || $order['order_status'] == 'delivered' || $order['order_status'] == 'processing' ? 'badge-soft-success':'')
                                <span
                                    class="fs-12 font-semibold rounded badge __badge {{$order['order_status'] == 'confirmed' || $order['order_status'] == 'delivered' || $order['order_status'] == 'processing' ? 'badge-soft-success border-soft-success':''}}">
                                    {{ $order['order_status'] == 'processing' ? translate('packaging') : $order['order_status'] }}
                                </span>
                            @elseif($order['order_status'] == 'failed' || $order['order_status'] == 'canceled' || $order['order_status'] == 'returned' ? 'badge-soft-danger':'')
                                <span
                                    class="fs-12 font-semibold rounded badge __badge {{$order['order_status'] == 'failed' || $order['order_status'] == 'canceled' || $order['order_status'] == 'returned' ? 'badge-soft-danger':''}}">
                                    {{ $order['order_status'] == 'failed' ? translate('Failed_To_Delivery') : $order['order_status'] }}
                                </span>
                            @elseif($order['order_status'] == 'pending' || $order['order_status'] == 'out_for_delivery' ? 'badge-soft-primary':'')
                                <span
                                    class="fs-12 font-semibold rounded badge __badge {{$order['order_status'] == 'pending' || $order['order_status'] == 'out_for_delivery' ? 'badge-soft-primary border-soft-primary':''}}">
                                    {{ $order['order_status'] == 'out_for_delivery' ? translate('Out_For_Delivery') : $order['order_status'] }}
                                </span>
                            @else
                                <span class="fs-12 font-semibold badge __badge-soft-primary rounded">
                                    {{ $order['order_status']}}
                                </span>
                            @endif
                        </div>
                        @if(isset($order['seller_id']) != 0)
                            @php($shopName=\App\Models\Shop::where('seller_id', $order['seller_id'])->first())
                        @endif
                        <div class="date fs-12 font-semibold text-secondary-50 text-body mb-3 mt-2">
                            {{ date('d M, Y h:i A', strtotime($order['created_at'])) }}
                        </div>
                    </div>
                </div>
                <div class="bg-white border-lg rounded mobile-full">
                    <div class="p-lg-3 p-0">
                        <div class="card border-sm">
                            <div class="p-lg-3">
                                <div class="payment mb-3 table-responsive d-none d-lg-block">
                                    <table class="table table-borderless min-width-600px">
                                        <thead class="thead-light text-capitalize">
                                        <tr class="fs-13 font-semi-bold">
                                            <th>{{translate('SL')}}</th>
                                            <th>{{translate('item_details')}}</th>
                                            <th>{{translate('price_range')}}</th>
                                            <th>{{translate('tax')}}</th>
                                            <th>{{translate('item_discount')}}</th>
                                            <th>{{translate('total_price')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @php($row=0)
                                @php($total_item_price=0)
                                @php($total_price=0)
                                @php($subtotal=0)
                                @php($total=0)
                                @php($discount=0)
                                @php($tax=0)
                                @php($row=0)
                                @php($totalDiscount = 0)
                                @php($totalTax=0)
                                @php($price=0)

                                        @if (json_decode($order->variation,true))
                                        @foreach ( json_decode($order->variation,true) as $variant)
                                        <?php
                                                   $discount = \App\Utils\Helpers::get_product_discount($order->product, $variant['price_range']);
                                                    if($order->product['tax_model'] == 'exclude'){
                                                        $tax = \App\Utils\Helpers::tax_calculation(product: $order->product, price: $variant['price_range'], tax: $order->product['tax'], tax_type: $order->product['tax_type']);
                                                    }
                                                    $price = ($variant['price_range'] * $variant['quantity']) + $variant['variant_price'] - ($discount * $variant['quantity']) + ($tax * $variant['quantity']);
                                                    $total_price += $price;
                                                    $totalDiscount +=  $discount * $variant['quantity'];
                                                    $totalTax +=  $tax * $variant['quantity'];
                                                    $total_item_price +=  ($variant['price_range'] * $variant['quantity']) + $variant['variant_price'];

                                         ?>

                                                <tr>
                                                    <td>{{ ++$row }}</td>
                                                    <td class="for-tab-img">
                                                        <div class="media gap-3 align-items-center">
                                                            <div>
                                                                <a href="">
                                                                <h6 class="title-color">{{substr($variant['variant_type'], 0, 30)}}{{strlen($variant['variant_type'])>20?'...':''}}</h6>
                                                                </a>
                                                                <div><strong>{{translate('qty')}} :</strong> {{$variant['quantity']}}
                                                                </div>
                                                                <div>
                                                                    <strong>{{translate('variation_price')}} :</strong>
                                                                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $variant['variant_price'])) }}

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $variant['price_range']), currencyCode: getCurrencyCode()) }}
                                                    </td>
                                                    <td>
                                                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $tax), currencyCode: getCurrencyCode()) }}
                                                    </td>
                                                    <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $discount), currencyCode: getCurrencyCode())}}</td>
                                                    <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $price), currencyCode: getCurrencyCode())}}</td>
                                                </tr>
                                                @endforeach

                                                @else
                                                <?php
                                                $discount = \App\Utils\Helpers::get_product_discount($order->product, $order['price_range']);
                                                 if($order->product['tax_model'] == 'exclude'){
                                                     $tax = \App\Utils\Helpers::tax_calculation(product: $order->product, price: $order['price_range'], tax: $order->product['tax'], tax_type: $order->product['tax_type']);
                                                 }
                                                 $price = ($order['price_range'] * $order['quantity'])- ($discount * $order['quantity']) + ($tax * $order['quantity']);
                                                 $total_price += $price;
                                                 $totalDiscount +=  $discount * $order['quantity'];
                                                 $totalTax +=  $tax * $order['quantity'];
                                                 $total_item_price +=  ($order['price_range'] * $order['quantity']);

                                      ?>
                                      <tr>
                                        <td>{{ ++$row }}</td>
                                        <td class="for-tab-img">
                                            <div class="media gap-3 align-items-center">
                                                <div>
                                                    <a href="">
                                                    <h6 class="title-color">{{substr($order->product->name, 0, 30)}}{{strlen($order->product->name)>10?'...':''}}</h6>
                                                    </a>
                                                    <div><strong>{{translate('qty')}} :</strong> {{$order->quantity}}
                                                    </div>
                                                    <div>
                                                        <strong>{{translate('unit_price')}} :</strong>
                                                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->price_range)) }}

                                                    </div>
                                                    {{-- @if ($detail->variant)
                                                        <div>
                                                            <strong>
                                                                {{translate('variation')}} :
                                                            </strong>
                                                            {{$detail['variant']}}
                                                        </div>
                                                    @endif --}}
                                                </div>
                                            </div>

                                            {{-- @if(isset($productDetails->digital_product_type) && $productDetails->digital_product_type == 'ready_after_sell')
                                                <button type="button" class="btn btn-sm btn--primary mt-2"
                                                        title="{{translate('file_upload')}}" data-toggle="modal"
                                                        data-target="#fileUploadModal-{{ $detail->id }}">
                                                    <i class="tio-file-outlined"></i> {{translate('file')}}
                                                </button>
                                            @endif --}}
                                        </td>
                                        <td>
                                            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order['price_range']), currencyCode: getCurrencyCode()) }}
                                        </td>
                                        <td>
                                            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $tax), currencyCode: getCurrencyCode()) }}
                                        </td>
                                        <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $discount), currencyCode: getCurrencyCode())}}</td>
                                        <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $price), currencyCode: getCurrencyCode())}}</td>
                                    </tr>

                                                @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- @php($order = \App\Utils\OrderManager::getorder(order: $order)) --}}
                        <div class="row d-flex justify-content-end mt-2">
                                <div class="col-md-9 col-lg-8">
                                    <dl class="row gy-1 text-sm-right">
                                        <dt class="col-5">{{translate('item_price')}}</dt>
                                        <dd class="col-6 title-color">
                                            <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total_item_price), currencyCode: getCurrencyCode())}}</strong>
                                        </dd>
                                        <dt class="col-5 text-capitalize">{{translate('item_discount')}}</dt>
                                        <dd class="col-6 title-color">
                                            -
                                            <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $totalDiscount), currencyCode: getCurrencyCode())}}</strong>
                                        </dd>
                                        <dt class="col-5 text-uppercase">{{translate('vat')}}/{{translate('tax')}}</dt>
                                        <dd class="col-6 title-color">
                                            <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $totalTax), currencyCode: getCurrencyCode())}}</strong>
                                        </dd>

                                        <dt class="col-5"><strong>{{translate('total')}}</strong></dt>
                                        <dd class="col-6 title-color">
                                            <strong>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total_price), currencyCode: getCurrencyCode())}}</strong>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    {{-- @foreach ($order->details as $key=>$detail)
        @php($product=json_decode($detail->product_details,true))
        @if($product)
            @include('layouts.front-end.partials.modal._review',['id'=>$detail->id,'order_details'=>$detail])
            @include('layouts.front-end.partials.modal._refund',['id'=>$detail->id,'order_details'=>$detail,'order'=>$order,'product'=>$product])
        @endif
    @endforeach --}}

    @if($order->order_status=='delivered')
        <div class="bottom-sticky_offset"></div>
        <div class="bottom-sticky_ele bg-white d-md-none p-3 ">
            <button class="btn btn--primary w-100 text_capitalize get-order-again-function" data-id="{{ $order->id }}">
                {{ translate('reorder') }}
            </button>
        </div>
    @endif

    @if($order->payment_method == 'offline_payment' && isset($order->offlinePayments))
        <div class="modal fade" id="verifyViewModal" tabindex="-1" aria-labelledby="verifyViewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content rtl">
                    <div class="modal-header d-flex justify-content-end  border-0 pb-0">
                        <button type="button" class="close pe-0" data-dismiss="modal">
                            <span aria-hidden="true" class="tio-clear"></span>
                        </button>
                    </div>

                    <div class="modal-body pt-0">
                        <h5 class="mb-3 text-center text-capitalize fs-16 font-semi-bold">
                            {{ translate('payment_verification') }}
                        </h5>

                        <div class="shadow-sm rounded p-3">
                            <h6 class="mb-3 text-capitalize fs-16 font-semi-bold">
                                {{translate('customer_information')}}
                            </h6>

                            <div class="d-flex flex-column gap-2 fs-12 mb-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class=" min-w-120">{{translate('name')}}</span>
                                    <span>:</span>
                                    <span class="text-dark">
                                        <a class="font-weight-medium fs-12 text-capitalize" href="Javascript:">
                                            {{$order->customer->f_name ?? translate('name_not_found') }}&nbsp;{{$order->customer->l_name ?? ''}}
                                        </a>
                                    </span>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <span class=" min-w-120">{{translate('phone')}}</span>
                                    <span>:</span>
                                    <span class="text-dark">
                                        <a class="font-weight-medium fs-12 text-capitalize" href="{{ $order?->customer?->phone ? 'tel:'.$order?->customer?->phone : 'javascript:' }}">
                                            {{ $order->customer->phone ?? translate('number_not_found') }}
                                        </a>
                                    </span>
                                </div>
                            </div>

                            <div class="mt-3 border-top pt-4">
                                <h6 class="mb-3 text-capitalize fs-16 font-semi-bold">
                                    {{ translate('payment_information') }}
                                </h6>

                                <div class="d-flex flex-column gap-2 fs-12">

                                    @foreach ($order->offlinePayments->payment_info as $key=>$value)
                                        @if ($key != 'method_id')
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-capitalize min-w-120">{{translate($key)}}</span>
                                                <span>:</span>
                                                <span class="font-weight-medium fs-12 ">
                                                    {{$value}}
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach

                                    @if($order->payment_note)
                                        <div class="d-flex align-items-start gap-2">
                                            <span class="text-capitalize min-w-120">{{ translate('payment_none') }}</span>
                                            <span>:</span>
                                            <span class="font-weight-medium fs-12 "> {{ $order->payment_note }}  </span>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="refund_details_modal" tabindex="-1" aria-labelledby="refundRequestModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h6 class="text-center text-capitalize m-0 flex-grow-1">{{translate('refund_details')}}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body d-flex flex-column gap-3" id="refund_details_field">
                </div>
            </div>
        </div>
    </div>

    <span id="message-ratingContent"
          data-poor="{{ translate('poor') }}"
          data-average="{{ translate('average') }}"
          data-good="{{ translate('good') }}"
          data-good-message="{{ translate('the_delivery_service_is_good') }}"
          data-good2="{{ translate('very_Good') }}"
          data-good2-message="{{ translate('this_delivery_service_is_very_good_I_am_highly_impressed') }}"
          data-excellent="{{ translate('excellent') }}"
          data-excellent-message="{{ translate('best_delivery_service_highly_recommended') }}"
    ></span>
@endsection


@push('script')
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/spartan-multi-image-picker.js') }}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/account-order-details.js') }}"></script>
@endpush
