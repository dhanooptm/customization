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
                        <form action="{{ route('admin.products.range.update',['id' => $product['id']]) }}" method="post" class="brand-setup-form">
                            @csrf
                            <div class="row">
                                <input type="hidden" class="form-control"
                                placeholder="1000" name="product_id" value="{{ $product->product_id }}" required>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="title-color text-capitalize">
                                            {{ translate('start_point') }}
                                        </label>
                                        <input type="number" name="start_point" min="1" max="10000" class="form-control"
                                               placeholder="1" value="{{ $product->start_point }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="title-color text-capitalize">
                                            {{ translate('end_point') }}
                                        </label>
                                        <input type="number" name="end_point" min="0" max="10000" class="form-control"
                                               placeholder="4" value="{{ $product->end_point }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="title-color text-capitalize">
                                            {{ translate('price') }}
                                        </label>
                                        <input type="number" name="price" min="0" max="100000000" step="0.01" class="form-control"
                                               placeholder="1000" value="{{ $product->price }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label
                                        class="d-flex mb-1 justify-content-between switch toggle-switch-sm text-dark text-capitalize"
                                        for="is_endless">
                                        {{ translate('endless') }}
                                    </label>
                                        <input type="checkbox" id="is_endless" name="is_endless" class="" {{ $product->is_endless == 1 ? 'checked' : '' }}>
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
