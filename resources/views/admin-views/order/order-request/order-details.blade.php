@extends('layouts.back-end.app')
@section('title', translate('order_Details'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"
          href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/css/intlTelInput.css') }}">
@endpush

@section('content')
    @php($shippingAddress = $order['shipping_address_data'] ?? null)
    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/all-orders.png')}}" alt="">
                {{translate('order_Request_Details')}}
            </h2>
        </div>

        <div class="row gy-3" id="printableArea">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap flex-md-nowrap gap-10 justify-content-between mb-4">
                            <div class="d-flex flex-column gap-10">
                                <h4 class="text-capitalize">{{translate('Order_Request_ID')}} #{{$order['id']}}</h4>
                                <div class="">
                                    {{date('d M, Y , h:i A',strtotime($order['created_at']))}}
                                </div>
                                {{-- @if ($linkedOrders->count() >0)
                                    <div class="d-flex flex-wrap gap-10">
                                        <div
                                            class="color-caribbean-green-soft font-weight-bold d-flex align-items-center rounded py-1 px-2"> {{translate('linked_orders')}}
                                            ({{$linkedOrders->count()}}) :
                                        </div>
                                        @foreach($linkedOrders as $linked)
                                            <a href="{{route('admin.orders.details',[$linked['id']])}}"
                                               class="btn color-caribbean-green text-white rounded py-1 px-2">{{$linked['id']}}</a>
                                        @endforeach
                                    </div>
                                @endif --}}
                            </div>
                            {{-- <div class="text-sm-right flex-grow-1">
                                <div class="d-flex flex-wrap gap-10 justify-content-end">
                                    @if ($order->verificationImages && count($order->verificationImages)>0 && $order->verification_status ==1)
                                        <div>
                                            <button class="btn btn--primary px-4" data-toggle="modal"
                                                    data-target="#order_verification_modal"><i
                                                    class="tio-verified"></i> {{translate('order_verification')}}
                                            </button>
                                        </div>
                                    @endif

                                    @if (getWebConfig('map_api_status') == 1 && isset($shippingAddress->latitude) && isset($shippingAddress->longitude))
                                        <div class="">
                                            <button class="btn btn--primary px-4" data-toggle="modal"
                                                    data-target="#locationModal"><i
                                                    class="tio-map"></i> {{translate('show_locations_on_map')}}
                                            </button>
                                        </div>
                                    @endif

                                    <a class="btn btn--primary px-4" target="_blank"
                                       href={{route('admin.orders.generate-invoice',[$order['id']])}}>
                                        <img
                                            src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/uil_invoice.svg') }}"
                                            alt="" class="mr-1">
                                        {{translate('print_Invoice')}}
                                    </a>
                                </div>
                                <div class="d-flex flex-column gap-2 mt-3">
                                    <div class="order-status d-flex justify-content-sm-end gap-10 text-capitalize">
                                        <span class="title-color">{{translate('status')}}: </span>
                                        @if($order['order_status']=='pending')
                                            <span
                                                class="badge color-caribbean-green-soft font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{translate(str_replace('_',' ',$order['order_status']))}}</span>
                                        @elseif($order['order_status']=='failed')
                                            <span
                                                class="badge badge-soft-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{translate(str_replace('_',' ',$order['order_status'] == 'failed' ? 'Failed to Deliver' : ''))}}
                                            </span>
                                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                            <span
                                                class="badge badge-soft-warning font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{translate(str_replace('_',' ',$order['order_status'] == 'processing' ? 'Packaging' : $order['order_status']))}}
                                            </span>
                                        @elseif($order['order_status']=='delivered' || $order['order_status']=='confirmed')
                                            <span
                                                class="badge badge-soft-success font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{translate(str_replace('_',' ',$order['order_status']))}}
                                            </span>
                                        @else
                                            <span
                                                class="badge badge-soft-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{translate(str_replace('_',' ',$order['order_status']))}}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="payment-method d-flex justify-content-sm-end gap-10 text-capitalize">
                                        <span class="title-color">{{translate('payment_Method')}} :</span>
                                        <strong>{{translate($order['payment_method'])}}</strong>
                                    </div>

                                    @if($order->payment_method != 'cash_on_delivery' && $order->payment_method != 'pay_by_wallet' && !isset($order->offlinePayments))
                                        <div
                                            class="reference-code d-flex justify-content-sm-end gap-10 text-capitalize">
                                            <span class="title-color">{{translate('reference_Code')}} :</span>
                                            <strong>{{str_replace('_',' ',$order['transaction_ref'])}} {{ $order->payment_method == 'offline_payment' ? '('.$order->payment_by.')':'' }}</strong>
                                        </div>
                                    @endif

                                    <div class="payment-status d-flex justify-content-sm-end gap-10">
                                        <span class="title-color">{{translate('payment_Status')}}:</span>
                                        @if($order['payment_status']=='paid')
                                            <span class="text-success payment-status-span font-weight-bold">
                                                {{translate('paid')}}
                                            </span>
                                        @else
                                            <span class="text-danger payment-status-span font-weight-bold">
                                                {{translate('unpaid')}}
                                            </span>
                                        @endif
                                    </div>

                                    @if(getWebConfig('order_verification'))
                                        <span class="">
                                            <b>
                                                {{translate('order_verification_code')}} : {{$order['verification_code']}}
                                            </b>
                                        </span>
                                    @endif

                                </div>
                            </div> --}}
                        </div>
                        {{-- @if ($order->order_note !=null)
                            <div class="mt-2 mb-5 w-100 d-block">
                                <div class="gap-10">
                                    <h4>{{translate('order_Note')}}:</h4>
                                    <div class="text-justify">{{$order->order_note}}</div>
                                </div>
                            </div>
                        @endif --}}
                        <div class="table-responsive datatable-custom">
                            <table
                                class="table fz-12 table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
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
                                            <td>
                                                <div class="media align-items-center gap-10">
                                                    <a href="{{ route('admin.products.view',['addedBy'=>($order->product['added_by']=='seller'?'vendor' : 'in-house'),'id'=>$order->product['id']]) }}"><img class="avatar avatar-60 rounded img-fit"
                                                        src="{{ getStorageImages(path:$order?->product?->thumbnail_full_url, type: 'backend-product') }}"
                                                        alt="{{translate('image_Description')}}"></a>
                                                    <div>
                                                        <a href="{{ route('admin.products.view',['addedBy'=>($order->product['added_by']=='seller'?'vendor' : 'in-house'),'id'=>$order->product['id']]) }}">
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

                                                {{-- @if(isset($productDetails->digital_product_type) && $productDetails->digital_product_type == 'ready_after_sell')
                                                    <button type="button" class="btn btn-sm btn--primary mt-2"
                                                            title="{{translate('file_upload')}}" data-toggle="modal"
                                                            data-target="#fileUploadModal-{{ $detail->id }}">
                                                        <i class="tio-file-outlined"></i> {{translate('file')}}
                                                    </button>
                                                @endif --}}
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
                                            <td>
                                                <div class="media align-items-center gap-10">
                                                <a href="{{ route('admin.products.view',['addedBy'=>($order->product['added_by']=='seller'?'vendor' : 'in-house'),'id'=>$order->product['id']]) }}">
                                                    <img class="avatar avatar-60 rounded img-fit"
                                                    src="{{ getStorageImages(path:$order?->product?->thumbnail_full_url, type: 'backend-product') }}"
                                                    alt="{{translate('image_Description')}}">
                                                </a>
                                                    <div>
                                                        <a href="{{ route('admin.products.view',['addedBy'=>($order->product['added_by']=='seller'?'vendor' : 'in-house'),'id'=>$order->product['id']]) }}">
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

                        <hr/>

                        <div class="row justify-content-md-end mb-3">
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
            </div>

            <div class="col-lg-4 d-flex flex-column gap-3">
                @if($order->payment_method == 'offline_payment' && isset($order->offlinePayments))
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                                <h4 class="d-flex gap-2">
                                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/product_setup.png')}}"
                                         alt=""
                                         width="20">
                                    {{translate('Payment_Information')}}
                                </h4>
                            </div>
                            <div>
                                <table>
                                    <tbody>
                                    <tr>
                                        <td>{{translate('payment_Method')}}</td>
                                        <td class="py-1 px-2">:</td>
                                        <td><strong>{{ translate($order['payment_method']) }}</strong></td>
                                    </tr>
                                    @foreach ($order->offlinePayments->payment_info as $key=>$item)
                                        @if (isset($item) && $key != 'method_id')
                                            <tr>
                                                <td>{{translate($key)}}</td>
                                                <td class="py-1 px-2">:</td>
                                                <td><strong>{{ $item }}</strong></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body text-capitalize d-flex flex-column gap-4">
                        {{-- <div class="d-flex flex-column align-items-center gap-2">
                            <h4 class="mb-0 text-center">{{translate('order_&_Shipping_Info')}}</h4>
                        </div> --}}
                        {{-- <div class="">
                            <label
                                class="font-weight-bold title-color fz-14">{{translate('change_order_status')}}</label>
                            <select name="order_status" id="order_status"
                                    class="status form-control" data-id="{{$order['id']}}">

                                <option
                                    value="pending" {{$order->order_status == 'pending'?'selected':''}} > {{translate('pending')}}</option>
                                <option
                                    value="confirmed" {{$order->order_status == 'confirmed'?'selected':''}} > {{translate('confirmed')}}</option>
                                <option
                                    value="processing" {{$order->order_status == 'processing'?'selected':''}} >{{translate('packaging')}} </option>
                                <option class="text-capitalize"
                                        value="out_for_delivery" {{$order->order_status == 'out_for_delivery'?'selected':''}} >{{translate('out_for_delivery')}} </option>
                                <option
                                    value="delivered" {{$order->order_status == 'delivered'?'selected':''}} >{{translate('delivered')}} </option>
                                <option
                                    value="returned" {{$order->order_status == 'returned'?'selected':''}} > {{translate('returned')}}</option>
                                <option
                                    value="failed" {{$order->order_status == 'failed'?'selected':''}} >{{translate('failed_to_Deliver')}} </option>
                                <option
                                    value="canceled" {{$order->order_status == 'canceled'?'selected':''}} >{{translate('canceled')}} </option>
                            </select>
                        </div> --}}
                        <div
                            class="d-flex justify-content-between align-items-center gap-10 form-control h-auto flex-wrap">
                            <span class="title-color">
                                {{translate('read_status')}}
                            </span>
                            <div class="d-flex justify-content-end min-w-100 align-items-center gap-2">
                                <span
                                    class="text--primary font-weight-bold">{{ $order->order_status=='read' ? translate('read'):translate('unread')}}</span>
                                <label
                                    class="switcher read-status-text read-status-alert">
                                    <input class="switcher_input read-status" type="checkbox" name="status"
                                           data-id="{{$order->id}}"
                                           value="{{$order->order_status}}"
                                        {{ $order->order_status == 'read' ? 'checked':''}} >
                                    <span class="switcher_control switcher_control_add
                                        {{ $order->order_status=='read' ? 'checked':'unchecked'}}"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
                @if(!$order->is_guest && $order->customer)
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                                <h4 class="d-flex gap-2">
                                    <img
                                        src="{{dynamicAsset(path: 'public/assets/back-end/img/vendor-information.png')}}"
                                        alt="">
                                    {{translate('customer_information')}}
                                </h4>
                            </div>
                            <div class="media flex-wrap gap-3">
                                <div class="">
                                    <img class="avatar rounded-circle avatar-70"
                                         src="{{ getStorageImages(path: $order->customer->image_full_url , type: 'backend-basic') }}"
                                         alt="{{translate('Image')}}">
                                </div>
                                <div class="media-body d-flex flex-column gap-1">
                                    <span
                                        class="title-color"><strong>{{$order->customer['f_name'].' '.$order->customer['l_name']}} </strong></span>

                                    <span
                                        class="title-color break-all"><strong>{{$order->customer['phone']}}</strong></span>
                                    <span class="title-color break-all">{{$order->customer['email']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- <div class="card">
                    <div class="card-body">
                        <h4 class="d-flex gap-2 mb-4">
                            <img src="{{dynamicAsset(path: 'public/assets/back-end/img/shop-information.png')}}" alt="">
                            {{translate('product_Information')}}
                        </h4>
                        <div class="media align-items-center gap-10">
                            <img class="avatar avatar-60 rounded img-fit"
                                 src="{{ getStorageImages(path:$order?->product?->thumbnail_full_url, type: 'backend-product') }}"
                                 alt="{{translate('image_Description')}}">
                            <div>
                                <a href="{{ route('admin.products.view',['addedBy'=>($order->product['added_by']=='seller'?'vendor' : 'in-house'),'id'=>$order->product['id']]) }}"><h6 class="title-color">{{substr($order->product->name, 0, 30)}}{{strlen($order->product->name)>10?'...':''}}</h6></a>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    @if ($order->verificationImages && count($order->verificationImages)>0)
        <div class="modal fade" id="order_verification_modal" tabindex="-1" aria-labelledby="order_verification_modal"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header pb-4">
                        <h3 class="mb-0">{{translate('order_verification_images')}}</h3>
                        <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i
                                class="tio-clear"></i></button>
                    </div>
                    <div class="modal-body px-4 px-sm-5 pt-0">
                        <div class="d-flex flex-column align-items-center gap-2">
                            <div class="row gx-2">
                                @foreach ($order->verificationImages as $image)
                                    <div class="col-lg-4 col-sm-6 ">
                                        <div class="mb-2 mt-2 border-1">
                                            <img
                                                src="{{ getStorageImages(path: $image->image_full_url , type: 'backend-basic') }}"
                                                class="w-100" alt="">
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="button" class="btn btn-secondary px-5"
                                                data-dismiss="modal">{{translate('close')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <span id="payment-status-message" data-title="{{translate('confirm_payments_before_change_the_status').'.'}}"
          data-message="{{translate('change_the_status_paid_only_when_you_received_the_payment_from_customer').translate('_once_you_change_the_status_to_paid').','.translate('_you_cannot_change_the_status_again').'!' }}"></span>
    <span id="read-status-message" data-title="{{translate('change_read_status').'.'}}"
          data-message="{{translate('Are_you_want_to_change_read_status?') }}"></span>

    <span id="message-status-confirm-text" data-text="{{ translate("yes_change_it") }}!"></span>
    <span id="message-status-cancel-text" data-text="{{ translate("cancel") }}"></span>
    <span id="message-status-success-text" data-text="{{ translate("status_change_successfully") }}"></span>
    <span id="message-status-warning-text"
          data-text="{{ translate("account_has_been_deleted_you_can_not_change_the_status") }}"></span>
    <span id="message-order-status-delivered-text"
          data-text="{{ translate("order_is_already_delivered_you_can_not_change_it") }}!"></span>
    <span id="message-order-status-paid-first-text"
          data-text="{{ translate("before_delivered_you_need_to_make_payment_status_paid") }}!"></span>
    <span id="order-status-url" data-url="{{route('admin.orders.status')}}"></span>
    <span id="payment-status-url" data-url="{{ route('admin.orders.payment-status') }}"></span>
    <span id="read-status-url" data-url="{{ route('admin.orders.order-request.read-status') }}"></span>

    <span id="message-deliveryman-add-success-text"
          data-text="{{ translate("delivery_man_successfully_assigned/changed") }}"></span>
    <span id="message-deliveryman-add-error-text"
          data-text="{{ translate("deliveryman_man_can_not_assign_or_change_in_that_status") }}"></span>
    <span id="message-deliveryman-add-invalid-text"
          data-text="{{ translate("deliveryman_man_can_not_assign_or_change_in_that_status") }}"></span>
    <span id="delivery-type" data-type="{{ $order->delivery_type }}"></span>
    <span id="add-delivery-man-url" data-url="{{url('/admin/orders/add-delivery-man/'.$order['id'])}}/"></span>

    <span id="message-deliveryman-charge-success-text"
          data-text="{{ translate("deliveryman_charge_add_successfully") }}"></span>
    <span id="message-deliveryman-charge-error-text"
          data-text="{{ translate("failed_to_add_deliveryman_charge") }}"></span>
    <span id="message-deliveryman-charge-invalid-text" data-text="{{ translate("add_valid_data") }}"></span>
    <span id="add-date-update-url" data-url="{{route('admin.orders.amount-date-update')}}"></span>

    <span id="customer-name" data-text="{{$order->customer['f_name']??""}} {{$order->customer['l_name']??""}}}"></span>
    <span id="is-shipping-exist" data-status="{{$shippingAddress ? 'true':'false'}}"></span>
    <span id="shipping-address" data-text="{{$shippingAddress->address??''}}"></span>
    <span id="shipping-latitude" data-latitude="{{$shippingAddress->latitude??'-33.8688'}}"></span>
    <span id="shipping-longitude" data-longitude="{{$shippingAddress->longitude??'151.2195'}}"></span>
    <span id="billing-latitude" data-latitude="{{$billing->latitude??'-33.8688'}}"></span>
    <span id="billing-longitude" data-longitude="{{$billing->longitude??'151.2195'}}"></span>
    <span id="location-icon"
          data-path="{{dynamicAsset(path: 'public/assets/front-end/img/customer_location.png')}}"></span>
    <span id="customer-image"
          data-path="{{dynamicStorage(path: 'storage/app/public/profile/')}}{{$order->customer->image??""}}"></span>
    <span id="deliveryman-charge-alert-message"
          data-message="{{translate('when_order_status_delivered_you_can`t_update_the_delivery_man_incentive').'.'}}"></span>

@endsection

@push('script_2')
    @if(getWebConfig('map_api_status') ==1 )
        <script
            src="https://maps.googleapis.com/maps/api/js?key={{getWebConfig('map_api_key')}}&callback=mapCallBackFunction&loading=async&libraries=places&v=3.56"
            defer>
        </script>
    @endif
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/country-picker-init.js') }}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/order.js')}}"></script>
@endpush
