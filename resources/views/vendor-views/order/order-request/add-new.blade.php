@extends('layouts.back-end.app')

@section('title', translate('price_range_add'))

@section('content')
    <div class="content container-fluid">

        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/brand.png') }}" alt="">
                {{ translate('Product_Price_Setup') }}
            </h2>
        </div>
        <div class="row g-3">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-body text-start">
                        <form action="{{ route('admin.products.range.store') }}" method="post" class="brand-setup-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div class="form-group">
                                        <label for="name" class="title-color">{{ translate('product') }}</label>
                                        <select class="js-select2-custom form-control" name="product_id" onchange="productName(this.value)"
                                                required>
                                            <option value="{{ old('product_id') }}" selected
                                                    disabled>{{ translate('select_product') }}</option>
                                            @foreach ($products as $prod)
                                                <option value="{{ $prod['id'] }}" {{ (isset($product_id) && $prod['id'] == $product_id ) ? 'selected' : '' }}>
                                                    {{ $prod['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="title-color text-capitalize">
                                            {{ translate('start_point') }}
                                        </label>
                                        <input type="number" name="start_point" min="1" max="10000" class="form-control"
                                               placeholder="1" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="title-color text-capitalize">
                                            {{ translate('end_point') }}
                                        </label>
                                        <input type="number" name="end_point" min="0" max="10000" class="form-control"
                                               placeholder="4" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="title-color text-capitalize">
                                            {{ translate('price') }}
                                        </label>
                                        <input type="number" name="price" min="0" max="100000000" step="0.01" class="form-control"
                                               placeholder="1000" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                        class="d-flex mb-1 justify-content-between switch toggle-switch-sm text-dark text-capitalize"
                                        for="is_endless">
                                        {{ translate('endless') }}
                                    </label>
                                        <input type="checkbox" id="is_endless" name="is_endless" class="">
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex gap-3 justify-content-end">
                                <button type="reset" id="reset"
                                        class="btn btn-secondary px-4">{{ translate('reset') }}</button>
                                <button type="submit" class="btn btn--primary px-4">{{ translate('submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th class="text-center">{{ translate('start_point') }}</th>
                            <th class="text-center">{{ translate('end_point') }}</th>
                            <th class="text-center">{{ translate('price') }}</th>
                            <th class="text-center">{{ translate('is_endless') }}</th>
                            <th class="text-center"> {{ translate('action') }}</th>
                        </tr>
                        </thead>
                    @if (isset($product) && $product->ranges)
                    <tbody>
                        @foreach($product->ranges as $key => $brand)
                        <tr>
                            <td class="text-center">
                              {{  $brand->start_point}}
                            </td>
                            <td class="text-center">
                                {{  $brand->end_point}}
                              </td>
                              <td class="text-center">
                                {{  $brand->price}}
                              </td>
                              <td class="text-center">
                                {{  $brand->is_endless}}
                              </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-outline-info btn-sm square-btn" title="{{ translate('edit') }}"
                                        href="{{ route('admin.products.range.edit', [$brand['id']]) }}">
                                        <i class="tio-edit"></i>
                                    </a>
                                    <a class="btn btn-outline-danger btn-sm  square-btn" href="javascript:"
                                            onclick="form_alert('range-{{$brand['id']}}','{{ translate('Want to delete this range ?') }}')" title="{{translate('delete')}} {{translate('price_range')}}"><i class="tio-delete-outlined"></i>
                                        </a>
                                        <form action="{{route('admin.products.range.delete',[$brand['id']])}}"
                                                    method="post" id="range-{{$brand['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    @endif
                    </table>
                </div>
            </div>


        </div>
    </div>
@endsection

@push('script')
    <script>
      function productName(id){
    location.href = "{{ route('admin.products.range.range-add')}}" +'?product_id=' + id;
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
