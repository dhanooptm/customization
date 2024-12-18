@if(isset($product))
@php($overallRating = getOverallRating($product->reviews))
<div class="flash_deal_product rtl cursor-pointer mb-2 get-view-by-onclick"
    data-link="{{ route('product',$product->slug) }}">
    @if($product->discount > 0)
    <div class="d-flex position-absolute z-2">
        <span class="for-discount-value p-1 pl-2 pr-2 font-bold fs-13">
            <span class="direction-ltr d-block">
                @if ($product->discount_type == 'percent')
                    -{{ round($product->discount,(!empty($decimalPointSettings) ? $decimalPointSettings: 0))}}%
                @elseif($product->discount_type =='flat')
                    -{{ webCurrencyConverter(amount: $product->discount) }}
                @endif
            </span>
        </span>
    </div>
    @endif
    <div class="d-flex">
        <div class="d-flex align-items-center justify-content-center p-3">
            <div class="flash-deals-background-image image-default-bg-color">
                <img class="__img-125px" alt="" src="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'product') }}">
            </div>
        </div>
        <div class=" flash_deal_product_details pl-3 pr-3 pr-1 d-flex align-items-center">
            <div>
                <div>
                    <span class="text-center flash-product-title">
                        {{$product['name']}}
                    </span>
                </div>
                @if($overallRating[0] != 0 )
                <div class="flash-product-review">
                    @for($inc=1;$inc<=5;$inc++) @if ($inc <=(int)$overallRating[0]) <i class="tio-star text-warning">
                        </i>
                        @elseif ($overallRating[0] != 0 && $inc <= (int)$overallRating[0] + 1.1 && $overallRating[0]>
                            ((int)$overallRating[0]))
                            <i class="tio-star-half text-warning"></i>
                            @else
                            <i class="tio-star-outlined text-warning"></i>
                            @endif
                            @endfor
                            <label class="badge-style2">
                                ( {{ count($product->reviews) }} )
                            </label>
                </div>
                @endif
                <div class="d-flex flex-wrap gap-8 align-items-center row-gap-0">
                    @if ($product->price_type == "single_price")
            <div class="justify-content-between text-center">
                <div class="text-center d-flex flex-wrap justify-content-center align-items-center gap-8">
                    @if($product->discount > 0)
                        <del class="category-single-product-price">
                            {{ webCurrencyConverter(amount: $product->unit_price) }}
                        </del>
                        <br>
                    @endif
                    <span class="text-accent text-dark flex-wrap product-price">
                        {{ webCurrencyConverter(amount:
                            $product->unit_price-(getProductDiscount(product: $product, price: $product->unit_price))
                        ) }}
                    </span>
                </div>
               <span class="text-center flex-wrap">
                {{ translate('Min. order:') }}  {{ $product->minimum_order_qty .' ' . $product->unit }}
             </span>
            </div>
            @endif
            @if ($product->price_type == "multiple_price" && json_decode($product->product_multi_price,true))
            @php($product_multi_price = json_decode($product->product_multi_price,true))
            <div class="justify-content-between text-center">
                <div class="text-center d-flex flex-wrap justify-content-center align-items-center gap-8">
                    <span class="flex-wrap" style="font-size: 15px;">{{ translate('starting_from :') }}</span>
                    @if($product->discount > 0)
                    <del class="category-single-product-price">
                        {{ webCurrencyConverter(amount: $product_multi_price[0]['price']) }}
                    </del>
                    <br>
                    @endif
                    <span class="text-accent text-dark flex-wrap product-price">
                        {{ webCurrencyConverter(amount:
                            $product_multi_price[0]['price']-(getProductDiscount(product: $product, price: $product_multi_price[0]['price']))
                        ) }}
                    </span>
                </div>
                <span class="text-center flex-wrap">
                    {{ translate('Min. order:') }}  {{ $product->minimum_order_qty .' ' . $product->unit }}
                 </span>
            </div>
            @endif
            @if ($product->price_type == 'priceless')
            <div class="justify-content-between text-center">
            {{-- <button id="requestPriceButtonFeatureProduct-{{ $product->id }}" class="text-center btn btn--primary btn-sm">
                Price Request
            </button> --}}
            <span class="text-center flex-wrap">
                {{ translate('Price Request') }}
            </span>
            <br>
                <span class="text-center flex-wrap">
                    {{ translate('Min. order:') }} {{ $product->minimum_order_qty . ' ' . $product->unit }}
                </span>
            </div>
            @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endif
