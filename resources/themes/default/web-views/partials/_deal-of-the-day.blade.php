@if(isset($product))
    <div class="container rtl">
        <div class="row g-4 pt-2 mt-0 pb-2 __deal-of align-items-start">
            <div class="col-xl-3 col-md-4">
                <div class="deal_of_the_day h-100 bg--light">
                    @if(isset($deal_of_the_day->product))
                        <div class="d-flex justify-content-center align-items-center py-4">
                            <h4 class="font-bold fs-16 m-0 align-items-center text-uppercase text-center px-2 web-text-primary">
                                {{ translate('deal_of_the_day') }}
                            </h4>
                        </div>
                        <div class="recommended-product-card mt-0 min-height-auto">
                            <div class="d-flex justify-content-center align-items-center __pt-20 __m-20-r">
                                <div class="position-relative">
                                    <img class="__rounded-top aspect-1 h-auto" alt=""
                                         src="{{ getStorageImages(path: $deal_of_the_day?->product?->thumbnail_full_url, type: 'product') }}">
                                    @if($deal_of_the_day->discount > 0)
                                        <span class="for-discount-value p-1 pl-2 pr-2 font-bold fs-13">
                                            <span class="direction-ltr d-block">
                                                @if ($deal_of_the_day->discount_type == 'percent')
                                                    -{{round($deal_of_the_day->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}%
                                                @elseif($deal_of_the_day->discount_type =='flat')
                                                    -{{ webCurrencyConverter(amount: $deal_of_the_day->discount) }}
                                                @endif
                                            </span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="__i-1 bg-transparent text-center mb-0">
                                <div class="px-0">
                                    @php($overallRating = getOverallRating($deal_of_the_day->product['reviews']))
                                    @if($overallRating[0] != 0 )
                                        <div class="rating-show">
                                            <span class="d-inline-block font-size-sm text-body">
                                                @for($inc=1;$inc<=5;$inc++)
                                                    @if ($inc <= (int)$overallRating[0])
                                                        <i class="tio-star text-warning"></i>
                                                    @elseif ($overallRating[0] != 0 && $inc <= (int)$overallRating[0] + 1.1)
                                                        <i class="tio-star-half text-warning"></i>
                                                    @else
                                                        <i class="tio-star-outlined text-warning"></i>
                                                    @endif
                                                @endfor
                                                <label class="badge-style">( {{ count($deal_of_the_day->product['reviews']) }} )</label>
                                            </span>
                                        </div>
                                    @endif
                                    <h6 class="font-semibold pt-1">
                                        {{ Str::limit($deal_of_the_day->product['name'], 80) }}
                                    </h6>
                                    @if($deal_of_the_day->product->price_type == "single_price")
                                        <div class="mb-4 pt-1 d-flex flex-wrap justify-content-center align-items-center text-center gap-8">
                                            @if($deal_of_the_day->product->discount > 0)
                                                <del class="fs-14 font-semibold __color-9B9B9B">
                                                    {{ webCurrencyConverter(amount: $deal_of_the_day->product->unit_price) }}
                                                </del>
                                            @endif
                                            <span class="text-accent fs-18 font-bold text-dark">
                                            {{ webCurrencyConverter(amount:
                                                $deal_of_the_day->product->unit_price-(getProductDiscount(product: $deal_of_the_day->product, price: $deal_of_the_day->product->unit_price))
                                            ) }}
                                        </span>
                                        </div>
                                        <button class="btn btn--primary font-bold px-4 rounded-10 text-uppercase get-view-by-onclick"
                                                data-link="{{ route('product',$deal_of_the_day->product->slug) }}">
                                            {{translate('buy_now')}}
                                        </button>
                                    @endif
                                    @if($deal_of_the_day->product->price_type == "multiple_price" && json_decode($deal_of_the_day->product->product_multi_price,true))
                                        @php($product_multi_price = json_decode($deal_of_the_day->product->product_multi_price,true))
                                        <div class="justify-content-between text-center">
                                            <div class="product-price text-center d-flex flex-wrap justify-content-center align-items-center gap-8">
                                                <span class="flex-wrap" style="font-size: 15px;">{{ translate('starting_from :') }}</span>
                                                @if($deal_of_the_day->product->discount > 0)
                                                    <del class="category-single-product-price">
                                                        {{ webCurrencyConverter(amount: $product_multi_price[0]['price']) }}
                                                    </del>
                                                    <br>
                                                @endif

                                                <span class="text-accent text-dark flex-wrap">
                                                       {{ webCurrencyConverter(amount:
                                                        $product_multi_price[0]['price']-(getProductDiscount(product: $deal_of_the_day->product, price: $product_multi_price[0]['price']))
                                                    ) }}
                                                </span>
                                            </div>
                                            <span class="text-center flex-wrap">
                                                {{ translate('Min. order:') }}  {{ $deal_of_the_day->product->minimum_order_qty .' ' . $deal_of_the_day->product->unit }}
                                            </span>
                                        </div>
                                    @endif
                                    @if ($deal_of_the_day->product->price_type == 'priceless')
                                        <span class="text-accent fs-18 font-bold text-dark">
                                            {{ translate('Min. order:') }} {{ $deal_of_the_day->product->minimum_order_qty . ' ' . $deal_of_the_day->product->unit }}
                                        </span>
                                        @if(auth('customer')->check())
                                            <button class="btn btn--primary font-bold px-4 rounded-10 text-uppercase mt-3"
                                                    data-toggle="modal" data-target="#request_price">
                                                {{translate('Price_Request')}}
                                            </button>
                                        @else
                                            <button class="btn btn--primary font-bold px-4 rounded-10 text-uppercase get-view-by-onclick mt-3"
                                                    data-link="{{route('customer.auth.login')}}">
                                                {{translate('Price_Request')}}
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="modal fade" id="contact_supplier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">{{ $deal_of_the_day->product->name }}</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body priceless-modal">
                                      <form method="post" action="{{ route('product-inquiry') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" name="product_id" id="product_id" value="{{ $deal_of_the_day->product->id }}">
                                          <label for="name" class="col-form-label"><strong>{{ translate('quantity') }}</strong></label>
                                          <input type="number" class="form-control" min="1" id="quantity" name="quantity" required placeholder="{{translate('total_Quantity')}}">
                                        </div>
                                        <div class="form-group">
                                          <label for="message-text" class="col-form-label"><strong>{{ translate('descriptions') }}</strong></label>
                                          <textarea class="form-control" id="descriptions" name="descriptions" placeholder="{{ translate('Describe_your_sourcing_requirments_for_products') }}" required></textarea>
                                        </div>
                                        <div class="form-group">
                                          <label for="name" class="col-form-label"><strong>{{ translate('name') }}</strong></label>
                                          <input type="text" class="form-control" id="name" name="name" required placeholder="{{translate('your_Name')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="name" class="col-form-label"><strong>{{ translate('email') }}</strong></label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="{{translate('your_E_-_mail')}}" required>
                                          </div>
                                          <div class="form-group">
                                            <label for="name" class="col-form-label"><strong>{{ translate('phone') }}</strong></label>
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="{{translate('contact_Number')}}" required>
                                          </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn--primary element-center btn-gap-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">Send Enquiry</button>
                                          </div>
                                      </form>
                                    </div>

                                  </div>
                                </div>
                              </div>
                              <div class="modal fade" id="request_price" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">{{ $deal_of_the_day->product->name }}</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body priceless-modal">
                                      <form method="post" action="{{ route('product-price-inquiry') }}" enctype="multipart/form-data">
                                        @csrf
                                       <input type="hidden" class="form-control" name="product_id" id="product_id" value="{{ $deal_of_the_day->product->id }}">
                                        <div class="form-group">
                                          <label for="message-text" class="col-form-label"><strong>{{ translate('descriptions') }}</strong></label>
                                          <textarea class="form-control" id="descriptions" name="descriptions" placeholder="{{ translate('Describe_your_sourcing_requirments_for_products') }}" required></textarea>
                                        </div>
                                        <div class="form-group">
                                          <label for="name" class="col-form-label"><strong>{{ translate('name') }}</strong></label>
                                          <input type="text" class="form-control" id="name" name="name" required placeholder="{{translate('your_Name')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="name" class="col-form-label">{{ translate('company') }}</label>
                                            <input type="text" class="form-control" id="company" name="company" required placeholder="{{translate('your_Company')}}">
                                          </div>
                                        <div class="form-group">
                                            <label for="name" class="col-form-label"><strong>{{ translate('email') }}</strong></label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="{{translate('your_E_-_mail')}}" required>
                                          </div>
                                          <div class="form-group">
                                            <label for="name" class="col-form-label"><strong>{{ translate('pin') }}</strong></label>
                                            <input type="text" class="form-control" id="pin" name="pin" placeholder="{{translate('PIN')}}" required>
                                          </div>
                                          <div class="form-group">
                                            <label for="name" class="col-form-label"><strong>{{ translate('phone') }}</strong></label>
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="{{translate('contact_Number')}}" required>
                                          </div>
                                                <input type="checkbox" name="is_dealer" class="endless" />
                                                <label class="endless-label mt-6"><strong>{{ translate('i_am_a_dealer') }}</strong></label>
                                                <br>
                                                <input type="checkbox" name="similar_info" class="endless" />
                                                <label class="endless-label mt-6"><strong>{{ translate('receive_offers_for_similar_products') }}</strong></label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn--primary element-center btn-gap-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">Send Enquiry</button>
                                          </div>
                                      </form>
                                    </div>

                                  </div>
                                </div>
                        </div>
                    @else
                        @if(isset($recommendedProduct))
                            <div class="d-flex justify-content-center align-items-center py-4">
                                <h4 class="font-bold fs-16 m-0 align-items-center text-uppercase text-center px-2 web-text-primary">
                                    {{ translate('recommended_product') }}
                                </h4>
                            </div>
                            <div class="recommended-product-card mt-0">

                                <div class="d-flex justify-content-center align-items-center __pt-20 __m-20-r">
                                    <div class="position-relative">
                                        <img src="{{ getStorageImages(path: $recommendedProduct?->thumbnail_full_url, type: 'product') }}"
                                            alt="">
                                        @if($recommendedProduct->discount > 0)
                                            <span class="for-discount-value p-1 pl-2 pr-2 font-bold fs-13">
                                                <span class="direction-ltr d-block">
                                                    @if ($recommendedProduct->discount_type == 'percent')
                                                        -{{ round($recommendedProduct->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}%
                                                    @elseif($recommendedProduct->discount_type =='flat')
                                                        -{{ webCurrencyConverter(amount: $recommendedProduct->discount) }}
                                                    @endif
                                                </span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="__i-1 bg-transparent text-center mb-0 min-height-auto">
                                    <div class="px-0 pb-0">
                                        @php($overallRating = getOverallRating($recommendedProduct['reviews']))
                                        @if($overallRating[0] != 0 )
                                            <div class="rating-show">
                                                <span class="d-inline-block font-size-sm text-body">
                                                    @for($inc=0;$inc<5;$inc++)
                                                        @if ($inc <= (int)$overallRating[0])
                                                            <i class="tio-star text-warning"></i>
                                                        @elseif ($overallRating[0] != 0 && $inc <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
                                                            <i class="tio-star-half text-warning"></i>
                                                        @else
                                                            <i class="tio-star-outlined text-warning"></i>
                                                        @endif
                                                    @endfor
                                                    <label class="badge-style">( {{ count($recommendedProduct->reviews) }} )</label>
                                                </span>

                                            </div>
                                        @endif
                                        <h6 class="font-semibold pt-1">
                                            {{ Str::limit($recommendedProduct['name'],30) }}
                                        </h6>
                                        <div class="mb-4 pt-1 d-flex flex-wrap justify-content-center align-items-center text-center gap-8">
                                            @if($recommendedProduct->discount > 0)
                                                <del class="__text-12px __color-9B9B9B">
                                                    {{ webCurrencyConverter(amount: $recommendedProduct->unit_price) }}
                                                </del>
                                            @endif
                                            <span class="text-accent __text-22px text-dark">
                                                {{ webCurrencyConverter(amount:
                                                    $recommendedProduct->unit_price-(getProductDiscount(product: $recommendedProduct, price: $recommendedProduct->unit_price))
                                                ) }}
                                            </span>
                                        </div>
                                        <button class="btn btn--primary font-bold px-4 rounded-10 text-uppercase get-view-by-onclick"
                                                data-link="{{ route('product',$recommendedProduct->slug) }}">
                                            {{translate('buy_now')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="col-xl-9 col-md-8">
                <div class="latest-product-margin">
                    <div class="d-flex justify-content-between mb-14px">
                        <div class="text-center">
                            <span class="for-feature-title __text-22px font-bold text-center">
                                {{ translate('latest_products')}}
                            </span>
                        </div>
                        <div class="mr-1">
                            <a class="text-capitalize view-all-text web-text-primary"
                               href="{{route('products',['data_from'=>'latest'])}}">
                                {{ translate('view_all')}}
                                <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1 float-left' : 'right ml-1 mr-n1'}}"></i>
                            </a>
                        </div>
                    </div>

                    <div class="row mt-0 g-2">
                        @foreach($latest_products as $product)
                            <div class="col-xl-3 col-sm-4 col-md-6 col-lg-4 col-6">
                                <div>
                                    @include('web-views.partials._inline-single-product',['product'=>$product,'decimal_point_settings'=>$decimal_point_settings])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
