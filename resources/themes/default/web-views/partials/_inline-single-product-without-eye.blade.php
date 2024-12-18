@php($overallRating = getOverallRating($product->reviews))

<div class="product-single-hover style--card">
    <div class="overflow-hidden position-relative">
        <div class=" inline_product clickable d-flex justify-content-center">
            @if($product->discount > 0)
                <div class="d-flex">
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
            @else
                <div class="d-flex justify-content-end">
                    <span class="for-discount-value-null"></span>
                </div>
            @endif
            <div class="p-10px pb-0">
                <a href="{{route('product',$product->slug)}}" class="w-100">
                    <img alt=""
                         src="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'product') }}">
                </a>
            </div>

            @if($product->product_type == 'physical' && $product->current_stock <= 0)
                <span class="out_fo_stock">{{translate('out_of_stock')}}</span>
            @endif
        </div>
        <div class="single-product-details">
            @if($overallRating[0] != 0 )
                <div class="rating-show justify-content-between text-center">
                    <span class="d-inline-block font-size-sm text-body">
                        @for($inc=1;$inc<=5;$inc++)
                            @if ($inc <= (int)$overallRating[0])
                                <i class="tio-star text-warning"></i>
                            @elseif ($overallRating[0] != 0 && $inc <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
                                <i class="tio-star-half text-warning"></i>
                            @else
                                <i class="tio-star-outlined text-warning"></i>
                            @endif
                        @endfor
                        <label class="badge-style">( {{ count($product->reviews) }} )</label>
                    </span>
                </div>
            @endif
            <div class="text-center">
                <a href="{{route('product',$product->slug)}}">
                    {{ Str::limit($product['name'], 23) }}
                </a>
            </div>
            <div class="justify-content-between text-center">
                <div class="text-center d-flex flex-wrap justify-content-center align-items-center gap-8">
                    @if ($product->price_type == "single_price")
                    <div class="justify-content-between text-center">
                        <div class="product-price text-center d-flex flex-wrap justify-content-center align-items-center gap-8">
                            @if($product->discount > 0)
                                <del class="category-single-product-price">
                                    {{ webCurrencyConverter(amount: $product->unit_price) }}
                                </del>
                                <br>
                            @endif
                            <span class="text-accent text-dark flex-wrap">
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
                            <span class="text-accent text-dark flex-wrap">
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


