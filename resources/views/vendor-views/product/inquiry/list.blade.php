@extends('layouts.back-end.app-seller')

@section('title', translate('product_inquiry_List'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/brand.png') }}" alt="">
                {{ translate('product_inquiry_List') }}
                <span class="badge badge-soft-dark radius-50 fz-14">{{ $products->total() }}</span>
            </h2>
        </div>
        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row g-2 flex-grow-1">
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                            placeholder="{{ translate('search_by_contact') }}" aria-label="{{ translate('search_by_contact') }}" value="{{ request('searchValue') }}" required>
                                        <button type="submit" class="btn btn--primary input-group-text">{{ translate('search') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <select class="js-select2-custom form-control" name="status" onchange="statusCheck(this.value)"
                                                required>
                                            <option value="{{ old('product_id') }}" selected
                                                    disabled>{{ translate('select') }}</option>
                                                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>{{ translate('All') }}</option>
                                                    <option value="1" {{ $status == "1" ? 'selected' : '' }}>{{ translate('completed_inquiry') }}</option>
                                                    <option value="0" {{ $status == "0" ? 'selected' : '' }}>{{ translate('pending_inquiry') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                        <i class="tio-download-to"></i>
                                        {{ translate('export') }}
                                        <i class="tio-chevron-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('vendor.brand.export', ['searchValue'=>request('searchValue')]) }}">
                                                <img width="14" src="{{ dynamicAsset(path: 'public/assets/back-end/img/excel.png') }}" alt="">
                                                {{ translate('excel') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ translate('SL') }}</th>
                                    <th>{{ translate('product Name') }}</th>
                                    <th class="text-center">{{ translate('descriptions') }}</th>
                                    <th class="text-center">{{ translate('contact_Info') }}</th>
                                    <th class="text-center">{{ translate('quantity') }}</th>
                                    <th class="text-center">{{ translate('check') }}</th>
                                    <th class="text-center"> {{ translate('action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $key => $brand)
                                    <tr>
                                        <td>{{ $products->firstItem()+$key }}</td>
                                        <td>
                                            <a href="{{ route('vendor.products.view',['addedBy'=>($brand->product->added_by=='seller'?'vendor' : 'in-house'),'id'=>$brand->product['id']]) }}"
                                               class="media align-items-center gap-2">
                                                <img src="{{ getStorageImages(path: $brand->product->thumbnail_full_url, type: 'backend-product') }}"
                                                     class="avatar border" alt="">
                                                <span class="media-body title-color hover-c1">
                                                {{ Str::limit($brand->product['name'], 20) }}
                                            </span>
                                            </a>
                                        </td>
                                        <td class="text-start text-wrap">
                                            {{ Str::limit($brand['descriptions'], 50) }}
                                            @if(Str::length($brand['descriptions']) > 50)
                                                <button type="button" data-toggle="modal" data-target="#descriptionModal{{ $brand['id'] }}"
                                                class="btn btn-info element-center btn-gap-{{Session::get('direction') === "rtl" ? 'left' : 'right'}} action-contact-now-this-product">
                                                <span class="string-limit">{{ translate('view_More') }}</span>
                                            </button>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div>

                                                @foreach (json_decode($brand?->contact, true) as $index => $contact)

                                                   <span class="text-wrap d-block mb-1">
                                                    {{ $index}} : {{ $contact }}
                                                   </span>

                                            @endforeach


                                            </div>
                                        </td>
                                        <td class="text-center">{{ $brand['quantity'] }}</td>
                                        <td>
                                            <form action="{{route('vendor.products.inquiry.status-update') }}" method="post" id="inquiry-status{{$brand['id']}}-form">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$brand['id']}}">
                                                <label class="switcher mx-auto">
                                                    <input type="checkbox" class="switcher_input toggle-switch-message" name="status"
                                                           id="inquiry-status{{ $brand['id'] }}" value="1" {{ $brand['status'] == 1 ? 'checked' : '' }}
                                                           data-modal-id = "toggle-status-modal"
                                                           data-toggle-id = "inquiry-status{{ $brand['id'] }}"
                                                           {{-- data-on-image = "brand-status-on.png" --}}
                                                           {{-- data-off-image = "brand-status-off.png" --}}
                                                           data-on-title = "{{ translate('marked_as_read') }}"
                                                           data-off-title = "{{ translate('marked_as_unread')}}"
                                                           {{-- data-on-message = "<p>{{ translate('if_enabled_this_inquiry') }}</p>"
                                                           data-off-message = "<p>{{ translate('if_disabled_this_brand_will_be_hidden_from_the_website_and_customer_app') }}</p>"> --}}>
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-outline-info btn-sm square-btn" title="{{ translate('edit') }}"
                                                    href="{{ route('vendor.products.view',['addedBy'=>($brand->product->added_by=='seller'?'vendor' : 'in-house'),'id'=>$brand->product['id']]) }}">
                                                    <i class="tio-invisible"></i>
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm  square-btn" href="javascript:"
                                                onclick="form_alert('range-{{$brand['id']}}','{{ translate('Want to delete this inquiry ?') }}')" title="{{translate('delete')}} {{translate('price_range')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            {{-- <form action="{{route('vendor.products.inquiry.delete',[$brand['id']])}}"
                                                        method="post" id="range-{{$brand['id']}}">
                                                @csrf @method('delete')
                                            </form> --}}
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="descriptionModal{{ $brand['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                          <div class="modal-content">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="descriptionModalLabel{{ $brand['id'] }}">Full Description</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ $brand['descriptions'] }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>

                                          </div>
                                        </div>
                                      </div>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <div class="d-flex justify-content-lg-end">
                            {{ $products->links() }}
                        </div>
                    </div>
                    @if(count($products)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_product_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>

    <span id="get-brands" data-brands="{{ json_encode($products) }}"></span>
@endsection

@push('script')
<script>
    function statusCheck(status){
  location.href = "{{ route('vendor.products.inquiry.list')}}" +'?status=' + status;
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
