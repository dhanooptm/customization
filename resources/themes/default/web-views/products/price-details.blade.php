@extends('layouts.front-end.app')

@section('title', $product['name'])

@push('css_or_js')
<style>
    .modal-body.priceless-modal {
        padding: 20px 50px 20px 50px;
    }
    </style>
    @include(VIEW_FILE_NAMES['product_seo_meta_content_partials'], ['metaContentData' => $product?->seoInfo, 'product' => $product])
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/product-details.css') }}"/>
@endpush

@section('content')
    <div class="__inline-23">
        <div class="container mt-4 rtl text-align-direction">
            <div class="row {{Session::get('direction') === "rtl" ? '__dir-rtl' : ''}}">
                <div class="col-lg-12 col-12">

                    <?php $guestCheckout = getWebConfig(name: 'guest_checkout'); ?>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="cz-product-gallery">
                                <div class="cz-preview">
                                    <div id="sync1" class="owl-carousel owl-theme product-thumbnail-slider">
                                        @if($product->images!=null && json_decode($product->images)>0)
                                            @if(json_decode($product->colors) && count($product->color_images_full_url)>0)
                                                @foreach ($product->color_images_full_url as $key => $photo)
                                                    @if($photo['color'] != null)
                                                        <div
                                                            class="product-preview-item d-flex align-items-center justify-content-center {{$key==0?'active':''}}"
                                                            id="image{{$photo['color']}}">
                                                            <img class="cz-image-zoom img-responsive w-100"
                                                                 src="{{ getStorageImages(path: $photo['image_name'], type: 'product') }}"
                                                                 data-zoom="{{ getStorageImages(path: $photo['image_name'], type: 'product')  }}"
                                                                 alt="{{ translate('product') }}" width="">
                                                            <div class="cz-image-zoom-pane"></div>
                                                        </div>
                                                    @else
                                                        <div
                                                            class="product-preview-item d-flex align-items-center justify-content-center {{$key==0?'active':''}}"
                                                            id="image{{$key}}">
                                                            <img class="cz-image-zoom img-responsive w-100"
                                                                 src="{{ getStorageImages(path: $photo['image_name'], type: 'product') }}"
                                                                 data-zoom="{{ getStorageImages(path: $photo['image_name'], type: 'product') }}"
                                                                 alt="{{ translate('product') }}" width="">
                                                            <div class="cz-image-zoom-pane"></div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                @foreach ($product->images_full_url as $key => $photo)
                                                    <div
                                                        class="product-preview-item d-flex align-items-center justify-content-center {{$key==0?'active':''}}"
                                                        id="image{{$key}}">
                                                        <img class="cz-image-zoom img-responsive w-100"
                                                             src="{{ getStorageImages($photo, type: 'product') }}"
                                                             data-zoom="{{ getStorageImages(path: $photo, type: 'product') }}"
                                                             alt="{{ translate('product') }}" width="">
                                                        <div class="cz-image-zoom-pane"></div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-8 col-12 mt-md-0 mt-sm-3 web-direction">
                            <div class="ml-4 details __h-100">
                                <span class="mb-2 __inline-24">{{$product->name}}</span>
                                <h6>Select variations and quantity</h6>
                                <form id="add-to-cart-form" class="mb-2">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $product->id }}">
                                    <div
                                        class="position-relative {{Session::get('direction') === "rtl" ? 'ml-n4' : 'mr-n4'}} mb-2">
                                        @if (count(json_decode($product->colors)) > 0)
                                            <div class="flex-start align-items-center mb-2">
                                                <div
                                                    class="product-description-label m-0 text-dark font-bold">{{translate('color')}}
                                                    :
                                                </div>
                                                <div>
                                                    <ul class="list-inline checkbox-color mb-0 flex-start ms-2 ps-0">
                                                        @foreach (json_decode($product->colors) as $key => $color)
                                                            <li>
                                                                <input type="radio"
                                                                       id="{{ str_replace(' ', '', ($product->id. '-color-'. str_replace('#','',$color))) }}"
                                                                       name="color" value="{{ $color }}"
                                                                       disabled>
                                                                <label style="background: {{ $color }};"
                                                                    class="focus-preview-image-by-color shadow-border"
                                                                    for="{{ str_replace(' ', '', ($product->id. '-color-'. str_replace('#','',$color))) }}"
                                                                    data-toggle="tooltip"
                                                                    data-key="{{ str_replace('#','',$color) }}"
                                                                   data-colorid="preview-box-{{ str_replace('#','',$color) }}" data-title="{{ \App\Utils\get_color_name($color) }}">
                                                                    <span class="outline"></span></label>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                        @php
                                            $qty = 0;
                                            if(!empty($product->variation)){
                                            foreach (json_decode($product->variation) as $key => $variation) {
                                                    $qty += $variation->qty;
                                                }
                                            }
                                        @endphp
                                    </div>

                                    @php($extensionIndex=0)
                                    @if($product['product_type'] == 'digital' && $product['digital_product_file_types'] && count($product['digital_product_file_types']) > 0 && $product['digital_product_extensions'])
                                        @foreach($product['digital_product_extensions'] as $extensionKey => $extensionGroup)
                                        <div class="row flex-start mx-0 align-items-center mb-1">
                                            <div class="product-description-label text-dark font-bold {{Session::get('direction') === "rtl" ? 'pl-2' : 'pr-2'}} text-capitalize mb-2">
                                                {{ translate($extensionKey) }} :
                                            </div>
                                            <div>
                                                @if(count($extensionGroup) > 0)
                                                <div class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-0 mx-1 flex-start row ps-0">
                                                    @foreach($extensionGroup as $index => $extension)
                                                    <div>
                                                        <div class="for-mobile-capacity">
                                                            <input type="radio" hidden
                                                                   id="extension_{{ str_replace(' ', '-', $extension) }}"
                                                                   name="variant_key"
                                                                   value="{{ $extensionKey.'-'.preg_replace('/\s+/', '-', $extension) }}"
                                                                {{ $extensionIndex == 0 ? 'checked' : ''}}>
                                                            <label for="extension_{{ str_replace(' ', '-', $extension) }}"
                                                                   class="__text-12px">
                                                                {{ $extension }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @php($extensionIndex++)
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif

                                    @foreach (json_decode($product->choice_options) as $key => $choice)
                                        <div class="row flex-start mx-0 align-items-center">
                                            <div
                                                class="product-description-label text-dark font-bold {{Session::get('direction') === "rtl" ? 'pl-2' : 'pr-2'}} text-capitalize mb-2">{{ $choice->title }}
                                                :
                                            </div>
                                            <div>
                                                <div class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-0 mx-1 flex-start row ps-0">
                                                    @foreach ($choice->options as $index => $option)
                                                        <div>
                                                            <div class="for-mobile-capacity">
                                                                <input type="radio"
                                                                       id="{{ str_replace(' ', '', ($choice->name. '-'. $option)) }}"
                                                                       name="{{ $choice->name }}" value="{{ $option }}"
                                                                       {{-- @if($index == 0) checked @endif  --}}
                                                                       disabled>
                                                                <label class="__text-12px"
                                                                       for="{{ str_replace(' ', '', ($choice->name. '-'. $option)) }}"">{{ $option }}</label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if ($product->price_type == 'multiple_price' && json_decode($product->product_multi_price,true))
                                    @php($product_multi_price = json_decode($product->product_multi_price,true))
                                    <h6 class="mt-2 font-weight-bold">{{ translate('price_before_shipping') }}</h6>
                                    <div class="row" style="display: flex">
                                    @foreach ($product_multi_price as $key => $price)
                                                <div class="m-3" style="display: flex; flex-direction: column;">

                                                            <span class="key text-capitalize d-block mb-1">
                                                                @if ($loop->last)
                                                                >= {{ $price['start_point'] }} {{ translate('pieces') }}
                                                            @else
                                                                {{ $price['start_point'] }} - {{ $price['end_point'] }} {{ translate('pieces') }}
                                                            @endif
                                                            </span>
                                                            @if($product->discount > 0)
                                                            <del class="category-single-product-price">
                                                                {{ webCurrencyConverter(amount: $price['price']) }}
                                                            </del>
                                                            @endif
                                                           <strong> <span class="value">
                                                            {{ webCurrencyConverter(amount:
                                                                $price['price']-(getProductDiscount(product: $product, price: $price['price']))
                                                            ) }}
                                                        </span></strong>
                                                    </div>
                                @endforeach
                                    </div>
                                @endif
                                    @if($product->price_type != "multiple_price")
                                    <div class="mt-3">
                                        <div class="product-quantity d-flex flex-column __gap-15">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="product-description-label text-dark font-bold mt-0">
                                                    {{translate('quantity')}} :
                                                </div>
                                                <div class="d-flex justify-content-center align-items-center quantity-box border rounded border-base web-text-primary">
                                                    <span class="input-group-btn">
                                                        <button class="btn quantity-update __p-10 web-text-primary" type="button"
                                                                data-type="minus" data-field="quantity"
                                                                disabled="disabled">
                                                            -
                                                        </button>
                                                    </span>
                                                    <input type="text" name="quantity"
                                                           class="form-control input-number text-center __inline-29 border-0 "
                                                           placeholder="{{ translate('1') }}"
                                                           value="{{ $product->minimum_order_qty ?? 1 }}"
                                                           data-producttype="{{ $product->product_type }}"
                                                           min="{{ $product->minimum_order_qty ?? 1 }}"
                                                           max="{{$product['product_type'] == 'physical' ? $product->current_stock : 100}}">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-number __p-10 web-text-primary" type="button"
                                                                data-producttype="{{ $product->product_type }}"
                                                                data-type="plus" data-field="quantity" {{ $product->price_type =='priceless' ? 'disabled' : '' }}>
                                                                +
                                                        </button>
                                                    </span>
                                                </div>
                                                <input type="hidden" class="product-generated-variation-code" name="product_variation_code" data-product-id="{{ $product['id'] }}">
                                                <input type="hidden" value="" class="in_cart_key form-control w-50" name="key">
                                            </div>
                                        @if ($product->price_type == 'priceless')
                                        <div id="chosen_price_div" class="d-none">
                                            <div
                                                class="d-none d-sm-flex justify-content-start align-items-center me-2">
                                                <div
                                                    class="product-description-label text-dark font-bold text-capitalize {{ $product->price_type == 'priceless' ? 'd-none' : '' }}">
                                                    <strong>{{translate('total_price')}}</strong> :
                                                </div>
                                                &nbsp; <strong id="chosen_price" class="text-base"></strong>
                                                <small
                                                    class="ms-2 font-regular">
                                                    (<small>{{translate('tax')}} : </small>
                                                    <small id="set-tax-amount"></small>)
                                                </small>
                                            </div>
                                        </div>
                                        @endif
                                        </div>
                                    </div>
                                    @endif
                                    @php($variation_check = json_decode($product->variation,true))
                                    @php($variation_check_count = count($variation_check))
                                    @if (count($variation_check) == 0)
                                     <div class="mt-3">
                                        <div class="product-quantity d-flex flex-column __gap-15">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="product-description-label text-dark font-bold mt-0">
                                                    {{translate('quantity')}} :
                                                </div>
                                                <div class="d-flex justify-content-center align-items-center quantity-box border rounded border-base web-text-primary">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-number __p-10 web-text-primary quantity-update" type="button"
                                                                data-type="minus" data-field="quantity_single"
                                                                >
                                                            -
                                                        </button>
                                                    </span>
                                                    <input type="text" name="quantity_single"
                                                           class="form-control input-number text-center __inline-29 border-0 "
                                                           placeholder="{{ translate('1') }}"
                                                           value="{{ $product->minimum_order_qty ?? 1 }}"
                                                           data-producttype="{{ $product->product_type }}"
                                                           min="{{ $product->minimum_order_qty ?? 1 }}"
                                                           max="{{$product['product_type'] == 'physical' ? $product->current_stock : 1000}}">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-number __p-10 web-text-primary quantity-update" type="button"
                                                                data-producttype="{{ $product->product_type }}"
                                                                data-type="plus" data-field="quantity_single">
                                                                +
                                                        </button>
                                                    </span>

                                            </div>
                                        </div>
                                    </div>

                                    @endif


                                     @foreach (json_decode($product->variation,true) as $key => $variant )

                                    <div class="card __card cart_information __cart-table mb-3 mt-2">
                                    <table
                                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table __cart-table">
                                        <tbody>
                                            @php($checkProductStatus = 1)
                                            <tr>
                                                <td class="__w-45">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        {{-- <input type="checkbox" class="shop-item-check shop-item-check-desktop" value="{{ $variant['type'] }}"> --}}

                                                        <div class="d-flex gap-3">
                                                            <div class="">
                                                                <a href="{{ $checkProductStatus == 1 ? route('product', $product['slug']) : 'javascript:'}}"
                                                                   class="position-relative overflow-hidden">
                                                                    <img class="rounded __img-62 {{ $checkProductStatus == 0?'custom-cart-opacity-50':'' }}"
                                                                         src="{{ getStorageImages(path: $product?->thumbnail_full_url, type: 'product') }}"
                                                                        alt="{{ translate('product') }}">
                                                                    @if ($checkProductStatus == 0)
                                                                        <span class="temporary-closed position-absolute text-center p-2">
                                                                            <span class="fs-12 font-weight-bolder">{{ translate('N/A') }}</span>
                                                                        </span>
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="d-flex flex-column gap-1">
                                                                <div
                                                                    class="text-break __line-2 __w-18rem {{ $checkProductStatus == 0?'custom-cart-opacity-50':'' }}">
                                                                    <a href="{{ $checkProductStatus == 1 ? route('product', $product['slug']) : 'javascript:'}}">
                                                                        {{$variant['type']}}
                                                                    </a>
                                                                </div>

                                                                @php($getProductCurrentStock = 1000)

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="{{ $checkProductStatus == 0?'custom-cart-opacity-50':'' }} __w-15p">
                                                    <div class="text-center">
                                                        <div class="fw-semibold">
                                                            {{ webCurrencyConverter(amount: $variant['price']) }}
                                                        </div>
                                                        {{-- <span class="text-nowrap fs-10">
                                                                @if ($product->tax_model == "exclude")
                                                                ({{ translate('tax')}}
                                                                : {{ webCurrencyConverter(amount: 500)}}
                                                                )
                                                            @else
                                                                ({{ translate('tax_included')}})
                                                            @endif
                                                             </span> --}}
                                                    </div>
                                                </td>
                                                <td class="__w-15p text-center">
                                                    <input type="hidden" class="" value="{{ $variant['type'] }}">
                                                    <div class="qty d-flex justify-content-center align-items-center gap-3">
                                                        <span class="qty_minus action-update-multiple-price-quantity-list"
                                                              data-minimum-order="{{ $product->minimum_order_qty }}"
                                                               data-variant-available="1"
                                                              data-cart-id="{{ $product['id'] }}"
                                                              data-increment="-1">
                                                            <i class="tio-remove"></i>
                                                        </span>

                                                        <input type="text" class="qty_input cartQuantity{{ $variant['type'] }}"
                                                               value="0"
                                                               name="quantity[{{ $product['id'] }}]"
                                                               id="cart_quantity_web{{ $product['id'] }}_{{ $variant['type'] }}"
                                                               data-variant-type="{{ $variant['type'] }}"
                                                               data-variant-stock="{{ $variant['qty'] }}"
                                                               data-variant-available="1"
                                                               data-variant-price="{{ $variant['price'] }}"
                                                               data-current-stock="{{ $variant['qty'] }}"
                                                               data-minimum-order="{{ $product->minimum_order_qty }}"
                                                               data-cart-id="{{ $product['id'] }}"
                                                               oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                                                        <span class="qty_plus action-update-multiple-price-quantity-list"
                                                              data-minimum-order="{{ $product->minimum_order_qty }}"
                                                              data-cart-id="{{ $product['id'] }}"
                                                               data-variant-available="1"
                                                              data-increment="1">
                                                            <i class="tio-add"></i>
                                                        </span>
                                                    </div>
                                                </td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                                    @endforeach
                                    <div class="container mt-1">
                                        <div class="row">
                                            <div class="col-6 text-start">
                                                <span class="subtotalLabel">Discount:</span>
                                            </div>
                                            <div class="col-6 text-end">
                                                <span class="totalDiscount">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container mt-1">
                                        <div class="row">
                                            <div class="col-6 text-start">
                                                <span class="subtotalLabel">Tax:</span>
                                            </div>
                                            <div class="col-6 text-end">
                                                <span class="totalTax">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container mt-1">
                                        <div class="row">
                                            <div class="col-6 text-start">
                                                <span class="subtotalLabel">Subtotal:</span>
                                            </div>
                                            <div class="col-6 text-end">
                                                <span class="subtotalPrice">0</span>
                                            </div>
                                        </div>
                                    </div>
                            </div>


                                    <div class="mr-3 d-flex justify-content-end"   id="custom_design_multiple_price">

                                        @if ($product->price_type == 'multiple_price' && count($variation_check) > 0)
                                        <button
                                        id="complete_order_request"
                                        class="btn btn-secondary p-2 btn-sm btn-gap-{{Session::get('direction') === "rtl" ? 'left' : 'right'}} mt-1" disabled
                                        type="button"
                                    >
                                        <strong class="string-limit">{{translate('complete_order_request')}}</strong>
                                    </button>
                                        @endif
                                        @if ($product->price_type == 'multiple_price' && count($variation_check) == 0)
                                    <div id="complete_order_request_single_button">

                                    </div>
                                <div class="mt-3" id="complete_order_request_single_text">

                                </div>
                                        @endif
                                    </div>

                                    <div class="row no-gutters d-none flex-start d-flex">
                                        <div class="col-12">
                                            @if(($product['product_type'] == 'physical'))
                                                <h5 class="text-danger out-of-stock-element d--none">{{translate('out_of_stock')}}</h5>
                                            @endif
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    @include('layouts.front-end.partials.modal._chatting',['seller'=>$product->seller, 'user_type'=>$product->added_by])


    <span id="route-review-list-product" data-url="{{ route('review-list-product') }}"></span>
    <span id="products-details-page-data" data-id="{{ $product['id'] }}"></span>
@endsection

@push('script')
    {{-- <script src="{{ theme_asset(path: 'public/assets/front-end/js/product-details.js') }}"></script> --}}
    <script type="text/javascript" async="async"
            src="https://platform-api.sharethis.com/js/sharethis.js#property=5f55f75bde227f0012147049&product=sticky-share-buttons"></script>
            <script>
                function multiplePrice(slug){
                    location.href = "{{ route('multiple-price') }}" + '?product_id='+slug;
                }
            </script>
            @push('script')
            {{-- <script>
                $(document).on('click', '.action-update-multiple-price-quantity-list', function() {
                    let cartId = $(this).data('cart-id');
                    let increment = parseInt($(this).data('increment'));
                    console.log(increment);

                    let quantityInput = $(`#cart_quantity_web${cartId}`);
                    let currentQuantity = parseInt(quantityInput.val()) || 0;
                    console.log(currentQuantity)
                    let newQuantity = currentQuantity + increment;
                    let variantType = quantityInput.data('variant-type');
                    let minimumOrder = $(this).data('minimum-order');

                if (newQuantity < $(this).data('minimum-order')) {
                    newQuantity = $(this).data('minimum-order');
                }

                quantityInput.val(newQuantity);

                // Perform the Ajax request
                $.ajax({
                    url: '{{ route("multi-price-update") }}', // Your route to update the cart
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        cart_id: cartId,
                        quantity: newQuantity,
                        variant_type: $(`.cartQuantity${cartId}`).data('variant-type')
                    },
                    success: function(response) {
                        // Update the UI with the new totals
                        $(`#total_price${cartId}`).text(response.new_total_price);
                        // You can also update other parts of the UI as needed
                    },
                    error: function(response) {
                        // Handle errors if needed
                    }
                });
            });
            </script> --}}
            <script>
// Handling the plus and minus button click for quantity update
$(document).on('click', '.action-update-multiple-price-quantity-list', function() {
    let cartId = $(this).data('cart-id');
    let variationExist = $(this).data('variant-available');
    let increment = parseInt($(this).data('increment'));
    let quantityInput = $(this).siblings('.qty_input');
    let currentQuantity = parseInt(quantityInput.val()) || 0;
    let variantStock = quantityInput.data('variant-stock');
    let variantPrice = quantityInput.data('variant-price');
    let variantType = quantityInput.data('variant-type');
    let newQuantity = currentQuantity + increment;
    let minimumOrder = quantityInput.data('minimum-order');


    if (increment < 0 && (newQuantity < 0 || newQuantity == 0)) {
        newQuantity = 0;
    }
    else if (newQuantity < 0) {
        newQuantity = 0;
    }
    // else if (newQuantity < minimumOrder) {
    //     newQuantity = minimumOrder;
    // }

    if(variantStock < newQuantity ){
        newQuantity = variantStock;

    }

    quantityInput.val(newQuantity);


    let variants = [];
    $('.qty_input').each(function() {
        let variant = {
            cart_id: $(this).data('cart-id'),
            variant_type: $(this).data('variant-type') || null,
            variant_stock: $(this).data('variant-stock'),
            variant_price: $(this).data('variant-price') || 0,
            quantity: parseInt($(this).val()) || 0
        };
        variants.push(variant);
    });

    updateCartQuantities(variants);
});


$(document).on('input', '.qty_input', function() {
    let cartId = $(this).data('cart-id');
    let variantType = $(this).data('variant-type') || null;
    let variantStock = $(this).data('variant-stock');
    let variantPrice = $(this).data('variant-price') || 0;
    let newQuantity = parseInt($(this).val()) || 0;
    let minimumOrder = $(this).data('minimum-order');
// console.log(newQuantity);

    // if (newQuantity < minimumOrder) {
    //     newQuantity = minimumOrder;
    //     $(this).val(newQuantity);
    // }
      if(variantStock < newQuantity ){
        newQuantity = variantStock;
        $(this).val(newQuantity);
    }


    let variants = [];
    $('.qty_input').each(function() {
        let variant = {
            cart_id: $(this).data('cart-id'),
            variant_type: $(this).data('variant-type') || null,
            variant_stock: $(this).data('variant-stock'),
            variant_price: $(this).data('variant-price') || 0,
            quantity: parseInt($(this).val()) || 0
        };
        variants.push(variant);
    });
    updateCartQuantities(variants);
});

function updateCartQuantities(variants) {
    $.ajax({
        url: '{{ route("multi-price-update") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            variants: variants
        },
        success: function(response) {
            var completeOrderButton = $('#complete_order_request');
           if(response.errors.length == 0){
            $('.totalDiscount').text(response.totalDiscount);
            $('.totalTax').text(response.totalTax);
            $('.subtotalPrice').text(response.totalAmount);

            completeOrderButton.prop('disabled', false);
           }else{
            response.errors.forEach(function(error) {
                toastr.error(error);
            });
            completeOrderButton.prop('disabled', true);
           }
        },
        error: function() {
        toastr.error('An unexpected error occurred.');
    }
    });
}
$(document).on('click', '#complete_order_request', function() {
    let variants = [];
    $('.qty_input').each(function() {
        let variant = {
            cart_id: $(this).data('cart-id'),
            variant_type: $(this).data('variant-type') || null,
            variant_stock: $(this).data('variant-stock'),
            variant_price: $(this).data('variant-price') || 0,
            quantity: parseInt($(this).val()) || 0
        };
        variants.push(variant);
    });

    saveOrder(variants);
});

function saveOrder(variants) {
    console.log('hj');

    $.ajax({
        url: '{{ route("order-placed-request") }}', // Replace with your save order route
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            variants: variants
        },
            success: function(response) {
                console.log(response);
            var completeOrderButton = $('#complete_order_request');
           if(response.errors.length == 0){
            console.log(response);

            window.location.href = "{{ route('order-placed-complete') }}?order_id=" + response.order_id;
           }else{
            response.errors.forEach(function(error) {
                toastr.error(error);
            });
            $('.totalDiscount').text(response.totalDiscount);
            $('.totalTax').text(response.totalTax);
            $('.subtotalPrice').text(response.totalAmount);
            completeOrderButton.prop('disabled', true);
           }
        },
        error: function(xhr) {
            console.log(xhr);

            if (xhr.status === 401) {  // Handle unauthenticated case
                window.location.href = "{{ route('customer.auth.login') }}";  // Redirect to login page
            }
            else{
                toastr.error('An unexpected error occurred.');
            }
    }
    });
}

</script>
<script>
    $(document).ready(function () {
    $('.quantity-update').click(function (e) {
        e.preventDefault();

        var fieldName = $(this).attr('data-field');
        var type = $(this).attr('data-type');
        var input = $("input[name='" + fieldName + "']");
        var currentVal = parseInt(input.val());
        var minValue = parseInt(input.attr('min'));
        var maxValue = parseInt(input.attr('max'));
        var productType = $(this).attr('data-producttype');

        if (!isNaN(currentVal)) {
            if (type === 'minus') {
                if (currentVal > minValue) {
                    input.val(currentVal - 1).change();
                }
            } else if (type === 'plus') {
                if (currentVal < maxValue) {
                    input.val(currentVal + 1).change();
                }
            }
        } else {
            input.val(minValue);
        }

        updateQuantity(input.val(), productType);
    });
    $(document).on('input', "input[name='quantity_single']", function() {
    let productId = '{{ $product->id }}'; // Assuming product ID is available as in your previous code
    let productType = $(this).data('producttype');
    let newQuantity = parseInt($(this).val()) || 0;
    let minQuantity = $(this).attr('min');
    let maxQuantity = $(this).attr('max');

    // If the new quantity is less than the minimum order, set it to the minimum order
    if (newQuantity < minQuantity) {
        newQuantity = minQuantity;
        $(this).val(newQuantity);
    }
    if(maxQuantity < newQuantity ){
        newQuantity = maxQuantity;
        $(this).val(newQuantity);
    }

    // Call function to update quantity with product details
    updateQuantity(newQuantity,productType);
});
    function updateQuantity(quantity, productType) {
        $.ajax({
            url: '{{ route("multi-price-update") }}', // Your route to handle the request
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // Include CSRF token for Laravel
                quantity: quantity,
                product_type: productType,
                product_id: '{{ $product->id }}'
            },
            success: function(response) {
            // var completeOrderButton = $('#complete_order_request_single');
           if(response.errors.length == 0){
            $('.totalDiscount').text(response.totalDiscount);
            $('.totalTax').text(response.totalTax);
            $('.subtotalPrice').text(response.totalAmount);
            $("#complete_order_request_single_button").empty();
            $("#complete_order_request_single_text").empty();
            $("#complete_order_request_single_button").append(`<button
                                        id="complete_order_request_single"
                                        class="btn btn-secondary p-2 btn-sm float-right btn-gap-{{Session::get('direction') === "rtl" ? 'left' : 'right'}} mt-1"
                                        type="button"
                                    >
                                        <strong class="string-limit">{{translate('complete_order_request')}}</strong>
                                    </button>`);
            // completeOrderButton.prop('disabled', false);
           }else{
            response.errors.forEach(function(error) {
                toastr.error(error);
            });
            $("#complete_order_request_single_button").empty();
            $("#complete_order_request_single_text").empty();
            $("#complete_order_request_single_text").append(`<p style="color: red;">
                                        {{ translate('No price range available. Please increase or decrease the quantity to fit within an available range') }}
                                    </p>`);
            // completeOrderButton.prop('disabled', true);
           }
        },
        error: function() {
            $("#complete_order_request_single_button").empty();
            $("#complete_order_request_single_text").empty();
            $("#complete_order_request_single_text").append(`<p style="color: red;">
                                        {{ translate('No price range available. Please increase or decrease the quantity to fit within an available range') }}
                                    </p>`);
        toastr.error('An unexpected error occurred.');
    }
        });
    }
});

$(document).on('click', '#complete_order_request_single', function() {
    let quantity = parseInt($('input[name="quantity_single"]').val()) || 0;
    let product_id = "{{ $product->id }}";

    saveSingleOrder(quantity, product_id);
});

function saveSingleOrder(quantity, product_id) {
    $.ajax({
        url: '{{ route("order-placed-request") }}', // Replace with your save order route
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            quantity: quantity,
            product_id: product_id,
            product_type :  "{{ $product->product_type }}"
        },
        success: function(response) {
            var completeOrderButton = $('#complete_order_request_single');
            if(response.errors.length == 0){
                window.location.href = "{{ route('order-placed-complete') }}?order_id=" + response.order_id;
            } else {
                response.errors.forEach(function(error) {
                    toastr.error(error);
                });
                $('.totalDiscount').text(response.totalDiscount);
                $('.totalTax').text(response.totalTax);
                $('.subtotalPrice').text(response.totalAmount);
                completeOrderButton.prop('disabled', true);
            }
        },
        error: function() {
            toastr.error('An unexpected error occurred.');
        }
    });
}
var multiple_price_data = @json(json_decode($product->product_multi_price)); // Encodes PHP array as a JavaScript object
var single_start_point = multiple_price_data[0]['start_point']; // Get the start point
var min_order_qty = parseInt("{{ $product->minimum_order_qty }}");
    if ({{ $variation_check_count }} === 0) {
        if (min_order_qty >= single_start_point) {
            $.ajax({
            url: '{{ route("multi-price-update") }}', // Your route to handle the request
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // Include CSRF token for Laravel
                quantity: min_order_qty,
                product_type: '{{ $product->product_type }}',
                product_id: '{{ $product->id }}'
            },
            success: function(response) {
            var completeOrderButton = $('#complete_order_request_single');
           if(response.errors.length == 0){
            $('.totalDiscount').text(response.totalDiscount);
            $('.totalTax').text(response.totalTax);
            $('.subtotalPrice').text(response.totalAmount);
            $("#complete_order_request_single_button").empty();
            $("#complete_order_request_single_text").empty();
            $("#complete_order_request_single_button").append(`<button
                                        id="complete_order_request_single"
                                        class="btn btn-secondary p-2 btn-sm float-right btn-gap-{{Session::get('direction') === "rtl" ? 'left' : 'right'}} mt-1"
                                        type="button"
                                    >
                                        <strong class="string-limit">{{translate('complete_order_request')}}</strong>
                                    </button>`);
            // completeOrderButton.prop('disabled', false);
           }else{
            response.errors.forEach(function(error) {
                toastr.error(error);
            });
            $("#complete_order_request_single_button").empty();
            $("#complete_order_request_single_text").empty();
            $("#complete_order_request_single_text").append(`<p style="color: red;">
                                        {{ translate('No price range available. Please increase or decrease the quantity to fit within an available range') }}
                                    </p>`);
            // completeOrderButton.prop('disabled', true);
           }
        },
        error: function() {
            $("#complete_order_request_single_button").empty();
            $("#complete_order_request_single_text").empty();
            $("#complete_order_request_single_text").append(`<p style="color: red;">
                                        {{ translate('No price range available. Please increase or decrease the quantity to fit within an available range') }}
                                    </p>`);
        toastr.error('An unexpected error occurred.');
    }
        });
            // $('#complete_order_request_single').prop('disabled', false);
        }else{
            $("#complete_order_request_single_text").append(`<p style="color: red;">
                                        {{ translate('No price range available. Please increase or decrease the quantity to fit within an available range') }}
                                    </p>`);
        }
    }

</script>
        @endpush
@endpush
