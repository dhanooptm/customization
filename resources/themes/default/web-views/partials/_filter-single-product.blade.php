@php($overallRating = getOverallRating($product->reviews))

<div class="product-single-hover style--card">
    <div class="overflow-hidden position-relative">
        <div class=" inline_product clickable d-flex justify-content-center">
            @if($product->discount > 0)
                <span class="for-discount-value p-1 pl-2 pr-2 font-bold fs-13">
                    <span class="direction-ltr d-block">
                        @if ($product->discount_type == 'percent')
                            -{{ round($product->discount, (!empty($decimal_point_settings) ? $decimal_point_settings: 0)) }}%
                        @elseif($product->discount_type =='flat')
                            -{{ webCurrencyConverter(amount: $product->discount) }}
                        @endif
                    </span>
                </span>
            @else
                <div class="d-flex justify-content-end">
                    <span class="for-discount-value-null"></span>
                </div>
            @endif
            <div class="p-10px pb-0">
                <a href="{{route('product',$product->slug)}}" class="w-100">
                    <img alt="" src="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'product') }}">
                </a>
            </div>

            <div class="quick-view">
                <a class="btn-circle stopPropagation action-product-quick-view" href="javascript:" data-product-id="{{ $product->id }}">
                    <i class="czi-eye align-middle"></i>
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
                <div class="product-price text-center d-flex flex-wrap justify-content-center align-items-center gap-8">
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

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttonId = "requestPriceButtonFilterSingle-" + "{{ $product->id }}";
    const productId = "{{ $product->id }}";
    const productName = "{{ $product->name }}";
    document.getElementById(buttonId).addEventListener('click', function () {
        const productId = "{{$product->id  }}"; // You can dynamically set this value as needed
        const productName = "{{ $product->name }}"; // Replace with actual product name
        const modalHtml = `
        <div class="modal fade" id="request_price-${productId}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">${productName}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body priceless-modal">
                        <form method="post" action="/product-price-inquiry" enctype="multipart/form-data">
                            <input type="hidden" class="form-control" name="product_id" id="product_id" value="${productId}">
                            <div class="form-group">
                                <label for="descriptions" class="col-form-label"><strong>Descriptions</strong></label>
                                <textarea class="form-control" id="descriptions" name="descriptions"
                                    placeholder="Describe your sourcing requirements for products" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-form-label"><strong>Name</strong></label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="John Doe">
                            </div>
                            <div class="form-group">
                                <label for="company" class="col-form-label">Company</label>
                                <input type="text" class="form-control" id="company" name="company" required placeholder="example.com">
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-form-label"><strong>Email</strong></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="john.doe@gmail.com" required>
                            </div>
                            <div class="form-group">
                                <label for="pin" class="col-form-label"><strong>PIN</strong></label>
                                <input type="text" class="form-control" id="pin" name="pin" placeholder="123456" required>
                            </div>
                            <div class="form-group">
                                <label for="phone" class="col-form-label"><strong>Phone</strong></label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="0123456789" required>
                            </div>
                            <input type="checkbox" name="is_dealer" class="endless" />
                            <label class="endless-label mt-6"><strong>I am a dealer</strong></label>
                            <br>
                            <input type="checkbox" name="similar_info" class="endless" />
                            <label class="endless-label mt-6"><strong>Receive offers for similar products</strong></label>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary">Send Enquiry</button>
                    </div>
                </div>
            </div>
        </div>`;

        // Inject the modal into the DOM
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Show the modal using Bootstrap's JS API
        const modalInstance = new bootstrap.Modal(document.getElementById(`request_price-${productId}`));
        modalInstance.show();

        // Optional: Remove the modal from the DOM when it's hidden to avoid clutter
        document.getElementById(`request_price-${productId}`).addEventListener('hidden.bs.modal', function (e) {
            document.getElementById(`request_price-${productId}`).remove();
        });
    });
});
</script>

@endpush
