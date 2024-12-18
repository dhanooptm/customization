@extends('layouts.front-end.app')

@section('title', translate('order_Complete'))

@section('content')
    <div class="container mt-5 mb-5 rtl __inline-53 text-align-direction">
        <div class="row d-flex justify-content-center">
            <div class="col-md-10 col-lg-10">
                <div class="card">
                        <div class="card-body">
                            <div class="mb-3 text-center">
                                <i class="fa fa-check-circle __text-60px __color-0f9d58"></i>
                            </div>

                            <p class="text-center fs-12">
                                {{ translate('your_order_request_has_been_successfully_processed_and_your_order') }} -
                                <span class="fw-bold text-primary">
                                        {{ $id }}

                                </span>
                                {{ translate('has_been_placed.') }}
                            </p>



                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <a href="{{route('home')}}" class="text-center">
                                        {{ translate('Continue_Shopping') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
