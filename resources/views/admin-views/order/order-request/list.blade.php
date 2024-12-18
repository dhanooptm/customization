@extends('layouts.back-end.app')

@section('title', translate('order_request_list'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/brand.png') }}" alt="">
                {{ translate('order_request_list') }}
                <span class="badge badge-soft-dark radius-50 fz-14">{{ $orders->total() }}</span>
            </h2>
        </div>
        <div class="row mt-20">
            <div class="col-md-12">
                <div class="">
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="px-3 py-4 light-bg">
                                <div class="row g-2 align-items-center flex-grow-1">
                                    <div class="col-md-4">
                                        <h5 class="text-capitalize d-flex gap-1">
                                            {{translate('order_list')}}
                                            <span class="badge badge-soft-dark radius-50 fz-12">{{$orders->total()}}</span>
                                        </h5>
                                    </div>
                                    <div class="col-md-8 d-flex gap-3 flex-wrap flex-sm-nowrap justify-content-md-end">
                                        {{-- <div class="col-md-6 col-lg-4 col-xl-3 form-group">
                                            <select class="js-select2-custom form-control" name="status" onchange="statusCheck(this.value)"
                                                    required>
                                                <option value="{{ old('product_id') }}" selected
                                                        disabled>{{ translate('select') }}</option>
                                                        <option value="all" {{ $status == "all" ? 'selected' : '' }}>{{ translate('All') }}</option>
                                                        <option value="1" {{ $status == "1" ? 'selected' : '' }}>{{ translate('completed_inquiry') }}</option>
                                                        <option value="0" {{ $status == "0" ? 'selected' : '' }}>{{ translate('pending_inquiry') }}</option>
                                            </select>
                                        </div> --}}
                                        <form action="{{ url()->current() }}" method="GET">
                                            <div class="input-group input-group-custom input-group-merge">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="tio-search"></i>
                                                    </div>
                                                </div>
                                                <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                                    placeholder="{{ translate('search_by_brand_name') }}" aria-label="{{ translate('search_by_brand_name') }}" value="{{ request('searchValue') }}" required>
                                                <button type="submit" class="btn btn--primary input-group-text">{{ translate('search') }}</button>
                                            </div>
                                        </form>
                                        {{-- <div class="dropdown">
                                            <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                                <i class="tio-download-to"></i>
                                                {{translate('export')}}
                                                <i class="tio-chevron-down"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a type="submit" class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.orders.export-excel', ['delivery_man_id' => request('delivery_man_id'), 'status' => $status, 'from' => $from, 'to' => $to, 'filter' => $filter, 'searchValue' => $searchValue,'seller_id'=>$vendorId,'customer_id'=>$customerId, 'date_type'=>$dateType]) }}">
                                                        <img width="14" src="{{asset('public/assets/back-end/img/excel.png')}}" alt="">
                                                        {{translate('excel')}}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive datatable-custom">
                                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                                    <thead class="thead-light thead-50 text-capitalize">
                                        <tr>
                                            <th>{{translate('SL')}}</th>
                                            <th>{{translate('order_Request_ID')}}</th>
                                            <th class="text-capitalize">{{translate('order_date')}}</th>
                                            <th class="text-capitalize">{{translate('customer_info')}}</th>
                                            <th class="text-capitalize">{{translate('total_amount')}}</th>
                                            <th class="text-center">{{translate('status')}} </th>
                                            <th class="text-center">{{translate('action')}}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($orders as $key=>$order)

                                        <tr class="status-{{$order['order_status']}} class-all">
                                            <td class="">
                                                {{$orders->firstItem()+$key}}
                                            </td>
                                            <td >
                                                <a class="title-color" href="{{route('admin.orders.order-request.details',['id'=>$order['id']])}}">{{$order['id']}} {!! $order->order_type == 'POS' ? '<span class="text--primary">(POS)</span>' : '' !!}</a>
                                            </td>
                                            <td>
                                                <div>{{date('d M Y',strtotime($order['created_at']))}},</div>
                                                <div>{{ date("h:i A",strtotime($order['created_at'])) }}</div>
                                            </td>
                                            <td>

                                                    @if($order->customer)
                                                        <a class="text-body text-capitalize" href="{{route('admin.orders.order-request.details',['id'=>$order['id']])}}">
                                                            <strong class="title-name">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</strong>
                                                        </a>
                                                        @if($order->customer['phone'])
                                                            <a class="d-block title-color" href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                                                        @else
                                                            <a class="d-block title-color" href="mailto:{{ $order->customer['email'] }}">{{ $order->customer['email'] }}</a>
                                                        @endif
                                                    @else
                                                        <label class="badge badge-danger fz-12">
                                                            {{ translate('customer_not_found') }}
                                                        </label>
                                                @endif
                                                    </td>
                                            <td>
                                                <div>
                                                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $order['order_amount']), currencyCode: getCurrencyCode()) }}
                                                </div>
                                            </td>
                                            <td class="text-center text-capitalize">

                                                @if($order['order_status']=='read')
                                                    <span class="badge badge-soft-success fz-12">
                                                        {{translate($order['order_status'])}}
                                                    </span>
                                                @else
                                                    <span class="badge badge-soft-danger fz-12">
                                                        {{translate($order['order_status'])}}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a class="btn btn-outline--primary square-btn btn-sm mr-1" title="{{translate('view')}}"
                                                        href="{{route('admin.orders.order-request.details',['id'=>$order['id']])}}">
                                                        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/eye.svg')}}" class="svg" alt="">
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive mt-4">
                                <div class="d-flex justify-content-lg-end">
                                    {!! $orders->links() !!}
                                </div>
                            </div>
                            @if(count($orders) == 0)
                                @include('layouts.back-end._empty-state',['text'=>'no_order_found'],['image'=>'default'])
                            @endif
                        </div>
                    </div>
                    @if(count($orders)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_product_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>
    <span id="route-admin-brand-delete" data-url="{{ route('admin.brand.delete') }}"></span>
    <span id="route-admin-inquiry-status-update" data-url="{{ route('admin.brand.status-update') }}"></span>
    <span id="get-brands" data-brands="{{ json_encode($orders) }}"></span>
    <div class="modal fade" id="select-brand-modal" tabindex="-1" aria-labelledby="toggle-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                            class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0 pb-sm-5">
                    <div class="d-flex flex-column align-items-center text-center gap-2 mb-2">
                        <div
                            class="toggle-modal-img-box d-flex flex-column justify-content-center align-items-center mb-3 position-relative">
                            <img src="{{dynamicAsset('public/assets/back-end/img/icons/info.svg')}}" alt="" width="90"/>
                        </div>
                        <h5 class="modal-title mb-2 brand-title-message"></h5>
                    </div>
                    <form action="{{ route('admin.brand.delete') }}" method="post" class="product-brand-update-form-submit">
                        @csrf
                        <input name="id" hidden="">
                        <div class="gap-2 mb-3">
                            <label class="title-color"
                                   for="exampleFormControlSelect1">{{ translate('select_Category') }}
                                <span class="text-danger">*</span>
                            </label>
                            <select name="brand_id" class="form-control js-select2-custom brand-option" required>

                            </select>
                        </div>
                        <div class="d-flex justify-content-center gap-3">
                            <button type="submit" class="btn btn--primary min-w-120">{{translate('update')}}</button>
                            <button type="button" class="btn btn-danger-light min-w-120"
                                    data-dismiss="modal">{{ translate('cancel') }}</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    function statusCheck(status){
  location.href = "{{ route('admin.products.inquiry.list')}}" +'?status=' + status;
    }
    function form_alert(id, message) {
      Swal.fire({
          title: '{{ translate('Are you sure?') }}',
          text: message,
          type: 'warning',
          showCancelButton: true,
          cancelButtonColor: 'default',
          confirmButtonColor: '#FC6A57',
          cancelButtonText: '{{ translate('no') }}',
          confirmButtonText: '{{ translate('Yes') }}',
          reverseButtons: true
      }).then((result) => {
          if (result.value) {
              $('#'+id).submit()
          }
      })
  }
  </script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/products-management.js') }}"></script>
@endpush
