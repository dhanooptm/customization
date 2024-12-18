<div class="p-3 px-xl-4 py-sm-5">
    <h3 class="mb-4 view-mail-title">
        {{$title}}
    </h3>
    <div class="view-mail-body">
        {!! $body !!}
    </div>

    <div class="{{$template['button_content_status'] == 1 ? '' : 'd-none'}}" id="button-content">
        <div class="d-flex justify-content-center mb-4" >
            <a href="{{$template['button_url'] ?? route('account-request-order')}}" target="_blank"
               class="btn btn-primary view-button-content view-button-link m-auto">{{$buttonName ??translate('See_Order_Request')}}</a>
        </div>
    </div>
    <div class="main-table-inner mb-4">
        <div class="d-flex justify-content-center pt-3">
            <img width="76" class="mb-4" id="view-mail-logo" src="{{$template->logo_full_url['path'] ?? getStorageImages(path: $companyLogo, type:'backend-logo')}}" alt="">
        </div>
        <h3 class="mb-3 text-center">{{translate('order_Info')}}</h3>
        <div class="main-table-inner bg-white">
            <div class="d-flex mb-3 p-2">
                <div class="flex-1 w-49">
                    <h3 class="mb-2">{{translate('order_Summary')}}</h3>
                    <div class="mb-2">{{translate('Order').' # '.($data['order']->id ?? '432121')}} </div>
                    <div class="mb-2">{{translate('product').' # '.($data['order']->product->name ?? '432121')}} </div>
                    <div>{{date('d M, Y : h:i:A' ,strtotime($data['order']->created_at ?? now()))}}</div>
                </div>
            </div>
            <div class="{{$template['order_information_status'] == 1 ? '' : 'd-none'}}" id="order-information">
                <table class="email-table">
                    <thead>
                        <tr>
                            <th class="text-left">{{translate('product')}}</th>
                            <th class="text-right">{{translate('price')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                  @if(isset($data['order']))
                  @php($order = $data['order'])
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
                            <td>
                                <div class="d-flex gap-2">
                                    <span class="text-nowrap">{{ ++$row.'.' }} </span>
                                    <span class="text-nowrap">{{$variant['variant_type'].' x '.$variant['quantity']}}</span>
                                    <br>
                                </div>
                                <div class="d-flex gap-2 pl-3">
                                    <strong>{{translate('variation_price')}} :</strong>
                                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $variant['variant_price'])) }}

                                </div>
                            </td>
                            <td class="text-right fw-bold">{{webCurrencyConverter(amount: $price)}}</td>
                        </tr>
                    @endforeach
                    @endif
                    @if (!json_decode($order->variation,true))
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
                              <td>
                                  <div class="d-flex gap-2">
                                      <span class="text-nowrap">{{ ++$row.'.' }} </span>
                                      <span> {{substr($order->product->name, 0, 30).' x '.$order['quantity']}}</span>
                                  </div>
                              </td>
                              <td class="text-right fw-bold">{{webCurrencyConverter(amount: $price)}}</td>
                          </tr>
                      @endif

                  @else
                      <tr>
                          <td>
                              <div class="d-flex gap-2">
                                  <span>{{translate('1').'.'}} </span>
                                  <span>{{translate('The school of life - emotional baggage tote bag - canvas tote bag (navy) x 1')}}</span>
                              </div>
                          </td>
                          <td class="text-right fw-bold">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: 500), currencyCode: getCurrencyCode())}}</td>
                      </tr>
                  @endif
                    </tbody>
                </table>
                <hr>
                <dl class="email-dl">
                    <dt class="flex-1">{{translate('item_price')}}</dt>
                    <dd class="flex-1 text-right">
                        {{isset($total_item_price) ? webCurrencyConverter(amount: $total_item_price) :setCurrencySymbol(amount: usdToDefaultCurrency(amount: 500), currencyCode: getCurrencyCode())}}
                    </dd>
                    <dt class="flex-1">{{translate('item_discount')}}</dt>
                    <dd class="flex-1 text-right">
                        - {{isset($totalDiscount) ? webCurrencyConverter(amount: $totalDiscount) :setCurrencySymbol(amount: usdToDefaultCurrency(amount: 50), currencyCode: getCurrencyCode())}}
                    </dd>
                   <dt class="flex-1">{{translate('vat/Tax')}}</dt>
                    <dd class="flex-1 text-right">
                        {{isset($totalTax) ? webCurrencyConverter(amount: $totalTax) :setCurrencySymbol(amount: usdToDefaultCurrency(amount: 25), currencyCode: getCurrencyCode())}}
                    </dd>
                    <dt class="flex-1 fw-bold">{{translate('Total')}}</dt>
                    <dd class="flex-1 text-right text-success fw-bold fs">
                        {{isset($total_price) ? webCurrencyConverter(amount: $total_price) :setCurrencySymbol(amount: usdToDefaultCurrency(amount: 475), currencyCode: getCurrencyCode())}}
                    </dd>
                </dl>
            </div>
            <hr>
            <p class="view-footer-text">
                {{$footerText}}
            </p>
            <p>{{translate('Thanks_&_Regards')}}, <br> {{getWebConfig('company_name')}}</p>
        </div>
    </div>
    @include('admin-views.business-settings.email-template.partials-design.footer-design-without-logo')
</div>
