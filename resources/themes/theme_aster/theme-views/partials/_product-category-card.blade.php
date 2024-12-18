@php
    use App\Utils\Helpers;
    use App\Utils\ProductManager;
@endphp
<div class="swiper-slide">
    <a href="javascript:"
       class="store-product d-flex flex-column gap-2 align-items-center ov-hidden">
        <div class="store-product__top border rounded mb-2 aspect-1 overflow-hidden">
            @if(isset($product->flash_deal_status) && $product->flash_deal_status == 1)
                <div class="product__power-badge">
                    <img src="{{theme_asset('assets/img/svg/power.svg')}}" alt="" class="svg text-white">
                </div>
            @endif
            @if($product->discount > 0)
                <span class="product__discount-badge">
                    <span>
                         @if ($product->discount_type == 'percent')
                            {{'-'.' '.round($product->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0)).'%'}}
                        @elseif($product->discount_type =='flat')
                            {{'-'.' '.Helpers::currency_converter($product->discount)}}
                        @endif
                    </span>
                </span>
            @else
            @endif
            <span class="store-product__action preventDefault get-quick-view"
                  data-action="{{route('quick-view')}}"
                  data-product-id="{{$product['id']}}">
                <i class="bi bi-eye fs-12"></i>
            </span>
            <img alt="" loading="lazy" class="dark-support rounded aspect-1 img-fit"
                 src="{{ getStorageImages(path: $product?->thumbnail_full_url, type: 'product') }}">
        </div>
        <a class="fs-16 text-truncate text-muted text-capitalize width--9rem"  href="{{route('product',$product->slug)}}">
            {{ Str::limit($product['name'], 18) }}
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

        </a>
    </a>
    @if ($product->price_type == 'priceless')
    {{-- <button id="requestPriceButtonProductCategory-{{ $product->id }}" class="btn btn--primary btn-sm">
        Price Request
    </button> --}}
    <span class="text-center flex-wrap">
        {{ translate('Price Request') }}
    </span>
    <br>
        <span class="text-center flex-wrap">
            {{ translate('Min. order:') }} {{ $product->minimum_order_qty . ' ' . $product->unit }}
        </span>
    @endif
</div>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttonId = "requestPriceButtonProductCategory-" + "{{ $product->id }}";
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
