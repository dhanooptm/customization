@extends('layouts.back-end.app')

@section('title', translate('product_Add'))

@push('css_or_js')
    <link href="{{ dynamicAsset(path: 'public/assets/back-end/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ dynamicAsset(path: 'public/assets/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/summernote/summernote.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0 d-flex gap-2">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/inhouse-product-list.png') }}" alt="">
                {{ translate('add_New_Product') }}
            </h2>
        </div>

        <form class="product-form text-start" action="{{ route('admin.products.store') }}" method="POST"
              enctype="multipart/form-data" id="product_form">
            @csrf
            <div class="card">
                <div class="px-4 pt-3 d-flex justify-content-between">
                    <ul class="nav nav-tabs w-fit-content mb-4">
                        @foreach ($languages as $lang)
                            <li class="nav-item">
                                <span class="nav-link text-capitalize form-system-language-tab {{ $lang == $defaultLanguage ? 'active' : '' }} cursor-pointer"
                                      id="{{ $lang }}-link">{{ getLanguageName($lang) . '(' . strtoupper($lang) . ')' }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <a class="btn btn--primary btn-sm text-capitalize h-100" href="{{route('admin.products.product-gallery') }}">
                        {{translate('add_info_from_gallery')}}
                    </a>
                </div>

                <div class="card-body">
                    @foreach ($languages as $lang)
                        <div class="{{ $lang != $defaultLanguage ? 'd-none' : '' }} form-system-language-form"
                             id="{{ $lang }}-form">
                            <div class="form-group">
                                <label class="title-color"
                                       for="{{ $lang }}_name">{{ translate('product_name') }}
                                    ({{ strtoupper($lang) }})
                                </label>
                                <input type="text" {{ $lang == $defaultLanguage ? 'required' : '' }} name="name[]"
                                       id="{{ $lang }}_name" class="form-control {{ $lang == $defaultLanguage ? 'product-title-default-language' : '' }}" placeholder="{{ translate('new_Product') }}">
                            </div>
                            <input type="hidden" name="lang[]" value="{{ $lang }}">
                            <div class="form-group pt-2">
                                <label class="title-color" for="{{ $lang }}_description">
                                    {{ translate('description') }} ({{ strtoupper($lang) }})
                                </label>
                                <textarea class="summernote {{ $lang == $defaultLanguage ? 'product-description-default-language' : '' }}" name="description[]">{{ old('details') }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


            <div class="card mt-3 rest-part">
                <div class="card-header">
                    <div class="d-flex gap-2">
                        <i class="tio-user-big"></i>
                        <h4 class="mb-0">{{ translate('general_setup') }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="title-color">{{ translate('category') }}</label>
                                <select class="js-select2-custom form-control action-get-request-onchange" name="category_id"
                                        data-url-prefix="{{ url('/admin/products/get-categories?parent_id=') }}"
                                        data-element-id="sub-category-select"
                                        data-element-type="select"
                                        required>
                                    <option value="{{ old('category_id') }}" selected
                                            disabled>{{ translate('select_category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category['id'] }}"
                                            {{ old('name') == $category['id'] ? 'selected' : '' }}>
                                            {{ $category['defaultName'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="title-color">{{ translate('sub_Category') }}</label>
                                <select class="js-select2-custom form-control action-get-request-onchange" name="sub_category_id"
                                        id="sub-category-select"
                                        data-url-prefix="{{ url('/admin/products/get-categories?parent_id=') }}"
                                        data-element-id="sub-sub-category-select"
                                        data-element-type="select">
                                    <option value="{{ null }}" selected
                                            disabled>{{ translate('select_Sub_Category') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="title-color">{{ translate('sub_Sub_Category') }}</label>
                                <select class="js-select2-custom form-control" name="sub_sub_category_id"
                                        id="sub-sub-category-select">
                                    <option value="{{ null }}" selected disabled>
                                        {{ translate('select_Sub_Sub_Category') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        @if($brandSetting)
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label class="title-color">{{ translate('brand') }}</label>
                                    <select class="js-select2-custom form-control" name="brand_id" required>
                                        <option value="{{ null }}" selected
                                                disabled>{{ translate('select_Brand') }}</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand['id'] }}">{{ $brand['defaultName'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label class="title-color">{{ translate('product_type') }}</label>
                                <select name="product_type" id="product_type" class="form-control" required>
                                    <option value="physical" selected>{{ translate('physical') }}</option>
                                    @if($digitalProductSetting)
                                        <option value="digital">{{ translate('digital') }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label class="title-color">{{ translate('price_type') }}</label>
                                <select name="price_type" id="price_type" class="form-control" required>
                                    <option value="single_price" selected>{{ translate('single_price') }}</option>
                                    <option value="multiple_price">{{ translate('multiple_price') }}</option>
                                    <option value="priceless">{{ translate('nonvisible') }}</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3" id="digital_product_type_show">
                            <div class="form-group">
                                <label for="digital_product_type"
                                       class="title-color">{{ translate("delivery_type") }}</label>
                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                      title="{{
                                      translate('for_Ready_Product_deliveries,_customers_can_pay_&_instantly_download_pre-uploaded_digital_products').' '.
                                      translate('For_Ready_After_Sale_deliveries,_customers_pay_first_then_admin_uploads_the_digital_products_that_become_available_to_customers_for_download') }}">
                                    <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}" alt="">
                                </span>
                                <select name="digital_product_type" id="digital_product_type" class="form-control"
                                        required>
                                    <option value="{{ old('category_id') }}" selected disabled>
                                        ---{{ translate('select') }}---
                                    </option>
                                    <option value="ready_after_sell">{{ translate("ready_After_Sell") }}</option>
                                    <option value="ready_product">{{ translate("ready_Product") }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label class="title-color d-flex justify-content-between gap-2">
                                    <span class="d-flex align-items-center gap-2">
                                        {{ translate('product_SKU') }}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              title="{{ translate('create_a_unique_product_code_by_clicking_on_the_Generate_Code_button') }}">
                                            <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}"
                                                 alt="">
                                        </span>
                                    </span>
                                    <span class="style-one-pro cursor-pointer user-select-none text--primary action-onclick-generate-number" data-input="#generate_number">
                                        {{ translate('generate_code') }}
                                    </span>
                                </label>
                                <input type="text" minlength="6" id="generate_number" name="code"
                                       class="form-control" value="{{ old('code') }}"
                                       placeholder="{{ translate('ex').': 161183'}}" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 physical_product_show">
                            <div class="form-group">
                                <label class="title-color">{{ translate('unit') }}</label>
                                <select class="js-example-basic-multiple form-control" name="unit">
                                    @foreach (units() as $unit)
                                        <option value="{{ $unit }}" {{ old('unit') == $unit ? 'selected' : '' }}>
                                            {{ $unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="title-color d-flex align-items-center gap-2">
                                    {{ translate('search_tags') }}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          title="{{ translate('add_the_product_search_tag_for_this_product_that_customers_can_use_to_search_quickly') }}">
                                        <img width="16" src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}"
                                             alt="">
                                    </span>
                                </label>
                                <input type="text" class="form-control" placeholder="{{ translate('enter_tag') }}"
                                       name="tags" data-role="tagsinput">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="card-header-icon"><i class="tio-money"></i></span>
                        <span class="p-md-1">{{translate('price_range')}}</span>
                    </h3>
                </div>
                <div id="price-points-container" style="display: none;">
                    <!-- Dynamic rows will be appended here -->
                </div>
            </div>
            <div class="card mt-3 rest-part">
                <div class="card-header">
                    <div class="d-flex gap-2">
                        <i class="tio-user-big"></i>
                        <h4 class="mb-0">{{ translate('pricing_&_others') }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-6 col-lg-4 col-xl-3 d-none">
                            <div class="form-group">
                                <div class="d-flex gap-2 mb-2">
                                    <label class="title-color mb-0">{{ translate('purchase_price') }}
                                        ({{ getCurrencySymbol(currencyCode: getCurrencyCode()) }}
                                        )</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          title="{{ translate('add_the_purchase_price_for_this_product') }}.">
                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}" alt="">
                                    </span>
                                </div>
                                <input type="number" min="0" step="0.01"
                                       placeholder="{{ translate('purchase_price') }}"
                                       value="{{ old('purchase_price') }}" name="purchase_price"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <div class="d-flex gap-2 mb-2">
                                    <label class="title-color mb-0">{{ translate('unit_price') }}
                                        ({{ getCurrencySymbol(currencyCode: getCurrencyCode()) }})</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          title="{{ translate('set_the_selling_price_for_each_unit_of_this_product._This_Unit_Price_section_won’t_be_applied_if_you_set_a_variation_wise_price') }}.">
                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}" alt="">
                                    </span>
                                </div>
                                <input type="number" min="0" step="0.01"
                                       placeholder="{{ translate('unit_price') }}" name="unit_price"
                                       value="{{ old('unit_price') }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3" id="minimum_order_qty">
                            <div class="form-group">
                                <div class="d-flex gap-2 mb-2">
                                    <label class="title-color mb-0"
                                           for="minimum_order_qty">{{ translate('minimum_order_qty') }}</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          title="{{ translate('set_the_minimum_order_quantity_that_customers_must_choose._Otherwise,_the_checkout_process_won’t_start') }}.">
                                        <img src="{{ asset('public/assets/back-end/img/info-circle.svg') }}" alt="">
                                    </span>
                                </div>

                                <input type="number" min="1" value="1" step="1"
                                       placeholder="{{ translate('minimum_order_quantity') }}" name="minimum_order_qty"
                                       id="minimum_order_qty" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 physical_product_show" id="quantity">
                            <div class="form-group">
                                <div class="d-flex gap-2 mb-2">
                                    <label class="title-color mb-0"
                                           for="current_stock">{{ translate('current_stock_qty') }}</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          title="{{ translate('add_the_Stock_Quantity_of_this_product_that_will_be_visible_to_customers') }}.">
                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}" alt="">
                                    </span>
                                </div>

                                <input type="number" min="0" value="0" step="1"
                                       placeholder="{{ translate('quantity') }}" name="current_stock" id="current_stock"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <div class="d-flex gap-2 mb-2">
                                    <label class="title-color mb-0"
                                           for="discount_Type">{{ translate('discount_Type') }}</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          title="{{ translate('if_Flat,_discount_amount_will_be_set_as_fixed_amount._If_Percentage,_discount_amount_will_be_set_as_percentage.') }}">
                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}" alt="">
                                    </span>
                                </div>

                                <select class="form-control" name="discount_type" id="discount_type">
                                    <option value="flat">{{ translate('flat') }}</option>
                                    <option value="percent">{{ translate('percent') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <label class="title-color" for="discount">{{ translate('discount_amount') }} <span
                                            class="discount_amount_symbol">({{getCurrencySymbol(currencyCode: getCurrencyCode()) }})</span></label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          title="{{ translate('add_the_discount_amount_in_percentage_or_a_fixed_value_here') }}.">
                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}" alt="">
                                    </span>
                                </div>
                                <input type="number" min="0" value="0" step="0.01"
                                       placeholder="{{ translate('ex: 5') }}"
                                       name="discount" id="discount" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <label class="title-color" for="tax">{{ translate('tax_amount') }}(%)</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          title="{{ translate('set_the_Tax_Amount_in_percentage_here') }}">
                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}" alt="">
                                    </span>
                                </div>

                                <input type="number" min="0" step="0.01"
                                       placeholder="{{ translate('ex: 5') }}" name="tax" id="tax"
                                       value="{{ old('tax') ?? 0 }}" class="form-control">
                                <input name="tax_type" value="percent" class="d-none">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <label class="title-color"
                                           for="tax_model">{{ translate('tax_calculation') }}</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          title="{{ translate('set_the_tax_calculation_method_from_here.').' '.translate('select_Include_with_product_to_combine_product_price_and_tax_on_the_checkout.').' '.translate('pick_Exclude_from_product_to_display_product_price_and_tax_amount_separately.') }}">
                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}" alt="">
                                    </span>
                                </div>
                                <select name="tax_model" id="tax_model" class="form-control" required>
                                    <option value="include">{{ translate("include_with_product") }}</option>
                                    <option value="exclude">{{ translate("exclude_with_product") }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 physical_product_show" id="shipping_cost">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <label class="title-color">{{ translate('shipping_cost') }}
                                        ({{getCurrencySymbol(currencyCode: getCurrencyCode()) }})</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          title="{{ translate('set_the_shipping_cost_for_this_product_here._Shipping_cost_will_only_be_applicable_if_product-wise_shipping_is_enabled.') }}">
                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}" alt="">
                                    </span>
                                </div>

                                <input type="number" min="0" value="0" step="1"
                                       placeholder="{{ translate('shipping_cost') }}" name="shipping_cost"
                                       class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6 physical_product_show" id="shipping_cost_multy">
                            <div class="form-group">
                                <div
                                    class="form-control h-auto min-form-control-height d-flex align-items-center flex-wrap justify-content-between gap-2">
                                    <div class="d-flex gap-2">
                                        <label class="title-color text-capitalize"
                                               for="shipping_cost">{{ translate('shipping_cost_multiply_with_quantity') }}</label>

                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              title="{{ translate('if_enabled,_the_shipping_charge_will_increase_with_the_product_quantity') }}">
                                            <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}"
                                                 alt="">
                                        </span>
                                    </div>

                                    <div>
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input" name="multiply_qty">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3 rest-part physical_product_show">
                <div class="card-header">
                    <div class="d-flex gap-2">
                        <i class="tio-user-big"></i>
                        <h4 class="mb-0">{{ translate('product_variation_setup') }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <div class="mb-3 d-flex align-items-center gap-2">
                                <label for="colors" class="title-color mb-0">
                                    {{ translate('select_colors') }} :
                                </label>
                                <label class="switcher">
                                    <input type="checkbox" class="switcher_input" id="product-color-switcher"
                                           value="{{ old('colors_active') }}"
                                           name="colors_active">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                            <select
                                class="js-example-basic-multiple js-states js-example-responsive form-control color-var-select"
                                name="colors[]" multiple="multiple" id="colors-selector" disabled>
                                @foreach ($colors as $key => $color)
                                    <option value="{{ $color->code }}">
                                        {{ $color['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="choice_attributes" class="title-color">
                                {{ translate('select_attributes') }} :
                            </label>
                            <select class="js-example-basic-multiple js-states js-example-responsive form-control"
                                    name="choice_attributes[]" id="choice_attributes" multiple="multiple">
                                @foreach ($attributes as $key => $a)
                                    <option value="{{ $a['id'] }}">
                                        {{ $a['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 mt-2 mb-2">
                            <div class="row customer_choice_options mt-2" id="customer_choice_options"></div>
                            <div class="form-group sku_combination" id="sku_combination"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3 rest-part digitalProductVariationSetupSection">
                <div class="card-header">
                    <div class="d-flex gap-2">
                        <i class="tio-user-big"></i>
                        <h4 class="mb-0">{{ translate('product_variation_setup') }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-2" id="digital-product-type-choice-section">
                        <div class="col-sm-6 col-md-4 col-xxl-3">
                            <div class="multi--select">
                                <label class="title-color">{{ translate('File_Type') }}</label>
                                <select class="js-example-basic-multiple js-select2-custom form-control" name="file-type" multiple id="digital-product-type-select">
                                    @foreach($digitalProductFileTypes as $FileType)
                                        <option value="{{ $FileType }}">{{ translate($FileType) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3 rest-part" id="digital-product-variation-section"></div>

            <div class="mt-3 rest-part">
                <div class="row g-2">
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                        <div>
                                            <label for="name"
                                                   class="title-color text-capitalize font-weight-bold mb-0">{{ translate('product_thumbnail') }}</label>
                                            <span
                                                class="badge badge-soft-info">{{ THEME_RATIO[theme_root_path()]['Product Image'] }}</span>
                                            <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                                  title="{{ translate('add_your_product’s_thumbnail_in') }} JPG, PNG or JPEG {{ translate('format_within') }} 2MB">
                                                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}"
                                                     alt="">
                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="custom_upload_input">
                                            <input type="file" name="image" class="custom-upload-input-file action-upload-color-image" id=""
                                                   data-imgpreview="pre_img_viewer"
                                                   accept=".jpg, .webp, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                            <span class="delete_file_input btn btn-outline-danger btn-sm square-btn d--none">
                                                <i class="tio-delete"></i>
                                            </span>

                                            <div class="img_area_with_preview position-absolute z-index-2">
                                                <img id="pre_img_viewer" class="h-auto aspect-1 bg-white d-none"
                                                     src="dummy" alt="">
                                            </div>
                                            <div
                                                class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                <div
                                                    class="d-flex flex-column justify-content-center align-items-center">
                                                    <img alt="" class="w-75"
                                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/product-upload-icon.svg') }}">
                                                    <h3 class="text-muted">{{ translate('Upload_Image') }}</h3>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="text-muted mt-2">
                                            {{ translate('image_format') }} : {{ "Jpg, png, jpeg, webp," }}
                                            <br>
                                            {{ translate('image_size') }} : {{ translate('max') }} {{ "2 MB" }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="color_image_column col-md-9 d-none">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                        <div>
                                            <label for="name"
                                                   class="title-color text-capitalize font-weight-bold mb-0">{{ translate('colour_wise_product_image') }}</label>
                                            <span
                                                class="badge badge-soft-info">{{ THEME_RATIO[theme_root_path()]['Product Image'] }}</span>
                                            <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                                  title="{{ translate('add_color-wise_product_images_here') }}.">
                                                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}"
                                                     alt="">
                                            </span>
                                        </div>

                                    </div>
                                    <p class="text-muted">{{ translate('must_upload_colour_wise_images_first._Colour_is_shown_in_the_image_section_top_right') }}
                                        . </p>

                                    <div id="color-wise-image-section" class="row g-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="additional_image_column col-md-9">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                    <div>
                                        <label for="name"
                                               class="title-color text-capitalize font-weight-bold mb-0">{{ translate('upload_additional_image') }}</label>
                                        <span
                                            class="badge badge-soft-info">{{ THEME_RATIO[theme_root_path()]['Product Image'] }}</span>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              title="{{ translate('upload_any_additional_images_for_this_product_from_here') }}.">
                                            <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}" alt="">
                                        </span>
                                    </div>

                                </div>
                                <p class="text-muted">{{ translate('upload_additional_product_images') }}</p>

                                <div class="row g-2" id="additional_Image_Section">
                                    <div class="col-sm-12 col-md-4">
                                        <div class="custom_upload_input position-relative border-dashed-2">
                                            <input type="file" name="images[]" class="custom-upload-input-file action-add-more-image"
                                                   data-index="1" data-imgpreview="additional_Image_1"
                                                   accept=".jpg, .png, .webp, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                   data-target-section="#additional_Image_Section"
                                            >

                                            <span class="delete_file_input delete_file_input_section btn btn-outline-danger btn-sm square-btn d-none">
                                                <i class="tio-delete"></i>
                                            </span>

                                            <div class="img_area_with_preview position-absolute z-index-2 border-0">
                                                <img id="additional_Image_1" class="h-auto aspect-1 bg-white d-none "
                                                     src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/product-upload-icon.svg-dummy') }}" alt="">
                                            </div>
                                            <div
                                                class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                <div
                                                    class="d-flex flex-column justify-content-center align-items-center">
                                                    <img alt=""
                                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/product-upload-icon.svg') }}"
                                                         class="w-75">
                                                    <h3 class="text-muted">{{ translate('Upload_Image') }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3 rest-part">
                <div class="card-header">
                    <div class="d-flex gap-2">
                        <i class="tio-user-big"></i>
                        <h4 class="mb-0">{{ translate('product_video') }}</h4>
                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                              title="{{ translate('add_the_YouTube_video_link_here._Only_the_YouTube-embedded_link_is_supported') }}.">
                            <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}" alt="">
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="title-color mb-0">{{ translate('youtube_video_link') }}</label>
                        <span class="text-info"> ({{ translate('optional_please_provide_embed_link_not_direct_link') }}.)</span>
                    </div>
                    <input type="text" name="video_url"
                           placeholder="{{ translate('ex').': https://www.youtube.com/embed/5R06LRdUCSE' }}"
                           class="form-control" required>
                </div>
            </div>

            <div class="card mt-3 rest-part">
                <div class="card-header">
                    <div class="d-flex gap-2">
                        <i class="tio-user-big"></i>
                        <h4 class="mb-0">
                            {{ translate('seo_section') }}
                            <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                  data-placement="top"
                                  title="{{ translate('add_meta_titles_descriptions_and_images_for_products').', '.translate('this_will_help_more_people_to_find_them_on_search_engines_and_see_the_right_details_while_sharing_on_other_social_platforms') }}">
                                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}" alt="">
                            </span>
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="title-color">
                                    {{ translate('meta_Title') }}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          data-placement="top"
                                          title="{{ translate('add_the_products_title_name_taglines_etc_here').' '.translate('this_title_will_be_seen_on_Search_Engine_Results_Pages_and_while_sharing_the_products_link_on_social_platforms') .' [ '. translate('character_Limit') }} : 100 ]">
                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}" alt="">
                                    </span>
                                </label>
                                <input type="text" name="meta_title" placeholder="{{ translate('meta_Title') }}"
                                       class="form-control" id="meta_title">
                            </div>
                            <div class="form-group">
                                <label class="title-color">
                                    {{ translate('meta_Description') }}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          data-placement="top"
                                          title="{{ translate('write_a_short_description_of_the_InHouse_shops_product').' '.translate('this_description_will_be_seen_on_Search_Engine_Results_Pages_and_while_sharing_the_products_link_on_social_platforms') .' [ '. translate('character_Limit') }} : 160 ]">
                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}" alt="">
                                    </span>
                                </label>
                                <textarea rows="4" type="text" name="meta_description" id="meta_description" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="d-flex justify-content-center">
                                <div class="form-group w-100">
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div>
                                            <label class="title-color" for="meta_Image">
                                                {{ translate('meta_Image') }}
                                            </label>
                                            <span
                                                class="badge badge-soft-info">{{ THEME_RATIO[theme_root_path()]['Meta Thumbnail'] }}</span>
                                            <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                                  title="{{ translate('add_Meta_Image_in') }} JPG, PNG or JPEG {{ translate('format_within') }} 2MB, {{ translate('which_will_be_shown_in_search_engine_results') }}.">
                                                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}"
                                                     alt="">
                                            </span>
                                        </div>

                                    </div>

                                    <div>
                                        <div class="custom_upload_input">
                                            <input type="file" name="meta_image"
                                                   class="custom-upload-input-file meta-img action-upload-color-image"
                                                   data-imgpreview="pre_meta_image_viewer"
                                                   id="meta_image_input"
                                                   accept=".jpg, .webp, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                            <span class="delete_file_input btn btn-outline-danger btn-sm square-btn d--none">
                                                <i class="tio-delete"></i>
                                            </span>

                                            <div class="img_area_with_preview position-absolute z-index-2 d-flex align-items-center justify-content-center">
                                                <img id="pre_meta_image_viewer" class="h-auto bg-white onerror-add-class-d-none pre-meta-image-viewer" alt=""
                                                     src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/product-upload-icon.svg-dummy') }}">
                                            </div>
                                            <div
                                                class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center overflow-hidden">
                                                <div
                                                    class="d-flex flex-column justify-content-center align-items-center">
                                                    <img alt="" class="w-75"
                                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/product-upload-icon.svg') }}">
                                                    <h3 class="text-muted">{{ translate('Upload_Image') }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    @include('admin-views.product.partials._seo-section')
                </div>
            </div>

            <div class="row justify-content-end gap-3 mt-3 mx-1">
                <button type="reset" class="btn btn-secondary px-5">{{ translate('reset') }}</button>
                <button type="button" class="btn btn--primary px-5 product-add-requirements-check">
                    {{ translate('submit') }}
                </button>
            </div>
        </form>
    </div>

    <span id="route-admin-products-sku-combination" data-url="{{ route('admin.products.sku-combination') }}"></span>
    <span id="route-admin-products-digital-variation-combination" data-url="{{ route('admin.products.digital-variation-combination') }}"></span>
    <span id="image-path-of-product-upload-icon" data-path="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/product-upload-icon.svg') }}"></span>
    <span id="image-path-of-product-upload-icon-two" data-path="{{ dynamicAsset(path: 'public/assets/back-end/img/400x400/img2.jpg') }}"></span>
    <span id="message-enter-choice-values" data-text="{{ translate('enter_choice_values') }}"></span>
    <span id="message-upload-image" data-text="{{ translate('upload_Image') }}"></span>
    <span id="message-file-size-too-big" data-text="{{ translate('file_size_too_big') }}"></span>
    <span id="message-are-you-sure" data-text="{{ translate('are_you_sure') }}"></span>
    <span id="message-yes-word" data-text="{{ translate('yes') }}"></span>
    <span id="message-no-word" data-text="{{ translate('no') }}"></span>
    <span id="message-want-to-add-or-update-this-product" data-text="{{ translate('want_to_add_this_product') }}"></span>
    <span id="message-please-only-input-png-or-jpg" data-text="{{ translate('please_only_input_png_or_jpg_type_file') }}"></span>
    <span id="message-product-added-successfully" data-text="{{ translate('product_added_successfully') }}"></span>
    <span id="message-discount-will-not-larger-then-variant-price" data-text="{{ translate('the_discount_price_will_not_larger_then_Variant_Price') }}"></span>
    <span id="system-currency-code" data-value="{{ getCurrencySymbol(currencyCode: getCurrencyCode()) }}"></span>
    <span id="system-session-direction" data-value="{{ Session::get('direction') }}"></span>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/tags-input.min.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/spartan-multi-image-picker.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/plugins/summernote/summernote.min.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/product-add-update.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/product-add-colors-img.js') }}"></script>

    <script>
        document.getElementById('price_type').addEventListener('change', function () {
    var priceType = this.value;
    var pricePointsContainer = document.getElementById('price-points-container');

    if (priceType === 'multiple_price') {
        pricePointsContainer.style.display = 'block';
        if (pricePointsContainer.children.length === 0) {
            addPricePointRow(); // Add the first row immediately when multiple_price is selected
        }
    } else {
        pricePointsContainer.style.display = 'none';
        pricePointsContainer.innerHTML = ''; // Clear all dynamic rows when not multiple_price
    }
});
function addPricePointRow() {
    var pricePointsContainer = document.getElementById('price-points-container');

    // Make previous row's inputs read-only
    var lastRow = pricePointsContainer.querySelector('.price-point-row:last-child');
    if (lastRow) {
        var inputs = lastRow.querySelectorAll('input');
        console.log("Last Row Found:", lastRow);
        inputs.forEach(function(input) {
            console.log("Setting input to read-only:", input);
            input.setAttribute('readonly', 'readonly'); // Set read-only
        });

        // Remove actions from the previous row
        var actions = lastRow.querySelector('.actions');
        if (actions) {
            actions.innerHTML = ''; // Remove buttons from the previous row
        }
    }

    // Create new row
    var newRow = document.createElement('div');
    newRow.classList.add('row', 'price-point-row');

    newRow.innerHTML = `
        <div class="col-md-3">
            <div class="form-group">
                <label class="title-color text-capitalize">{{ translate('start_point') }}</label>
                <input type="number" name="start_point[]" min="1" max="10000" class="form-control" placeholder="1" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="title-color text-capitalize">{{ translate('end_point') }}</label>
                <input type="number" name="end_point[]" min="0" max="10000" class="form-control" placeholder="4" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="title-color text-capitalize">{{ translate('price') }}</label>
                <input type="number" name="price[]" min="0" max="100000000" step="0.01" class="form-control" placeholder="1000" required>
            </div>
        </div>
        <div class="col-md-3 d-flex align-items-center actions">
            <button type="button" class="btn btn-primary add-price-point mr-2" disabled>Add</button>
            <button type="button" class="btn btn-danger remove-price-point">Delete</button>
        </div>
    `;

    pricePointsContainer.appendChild(newRow);

    // Handle inputs and buttons in the new row
    var addBtn = newRow.querySelector('.add-price-point');
    var inputs = newRow.querySelectorAll('input');

    inputs.forEach(function(input) {
        input.addEventListener('input', function() {
            if (Array.from(inputs).every(input => input.value !== '')) {
                addBtn.disabled = false;
            } else {
                addBtn.disabled = true;
            }
        });
    });

    addBtn.addEventListener('click', function () {
        var startPoint = parseInt(newRow.querySelector('input[name="start_point[]"]').value);
        var endPoint = parseInt(newRow.querySelector('input[name="end_point[]"]').value);

        if (!checkOverlap(startPoint, endPoint)) {
            addPricePointRow(); // Add the new row if validation passes
        } else {
            toastr.error('This price range overlaps with an existing range.');
        }
    });

    newRow.querySelector('.remove-price-point').addEventListener('click', function () {
        if (pricePointsContainer.children.length > 1) {
            newRow.remove();
            handleLastRowButtons(); // Handle buttons for the new last row
        }
        if (pricePointsContainer.children.length === 1) {
            console.log('hjk');
            var lastRow = pricePointsContainer.querySelector('.price-point-row:last-child');
            if (lastRow) {
                var inputs = lastRow.querySelectorAll('input');
                inputs.forEach(function(input) {
                    input.removeAttribute('readonly'); // Remove read-only
                });

                var actions = lastRow.querySelector('.actions');
                if (actions) {
                    actions.innerHTML = `
                        <button type="button" class="btn btn-primary add-price-point mr-2" disabled>Add</button>
                        <button type="button" class="btn btn-danger remove-price-point">Delete</button>
                    `;
                }
            }
        }
    });

    handleLastRowButtons(); // Ensure proper buttons on the last row
}


function checkOverlap(startPoint, endPoint) {
    var pricePointsContainer = document.getElementById('price-points-container');
    var rows = pricePointsContainer.querySelectorAll('.price-point-row');

    for (var i = 0; i < rows.length - 1; i++) { // Exclude the row being added
        var rowStart = parseInt(rows[i].querySelector('input[name="start_point[]"]').value);
        var rowEnd = parseInt(rows[i].querySelector('input[name="end_point[]"]').value);

        // Condition 1: The start_point of the new row should not be within an existing range
        if (startPoint >= rowStart && startPoint <= rowEnd) {
            return true;
        }

        // Condition 2: The end_point of the new row should not be within an existing range
        if (endPoint >= rowStart && endPoint <= rowEnd) {
            return true;
        }

        // Condition 3: The new row should not completely encompass an existing range
        if (startPoint <= rowStart && endPoint >= rowEnd) {
            return true;
        }
    }

    return false;
}

function handleLastRowButtons() {
    var pricePointsContainer = document.getElementById('price-points-container');
    var lastRow = pricePointsContainer.querySelector('.price-point-row:last-child');
    if (lastRow) {
        var actions = lastRow.querySelector('.actions');
        actions.innerHTML = `
            <button type="button" class="btn btn-primary add-price-point mr-2" disabled>Add</button>
            <button type="button" class="btn btn-danger remove-price-point">Delete</button>
        `;

        var addBtn = actions.querySelector('.add-price-point');
        var inputs = lastRow.querySelectorAll('input');

        // Re-check inputs after deletion and re-enable Add button if all filled
        inputs.forEach(function(input) {
            input.addEventListener('input', function() {
                if (Array.from(inputs).every(input => input.value !== '')) {
                    addBtn.disabled = false;
                } else {
                    addBtn.disabled = true;
                }
            });
        });

        // Trigger input event check in case inputs are already filled after deletion
        if (Array.from(inputs).every(input => input.value !== '')) {
            addBtn.disabled = false;
        } else {
            addBtn.disabled = true;
        }

        addBtn.addEventListener('click', function () {
            var startPoint = parseInt(lastRow.querySelector('input[name="start_point[]"]').value);
            var endPoint = parseInt(lastRow.querySelector('input[name="end_point[]"]').value);

            if (!checkOverlap(startPoint, endPoint)) {
                addPricePointRow(); // Add the new row if validation passes
            } else {
                toastr.error('This price range overlaps with an existing range.');
            }
        });

        actions.querySelector('.remove-price-point').addEventListener('click', function () {
            console.log(pricePointsContainer.children.length)
            if (pricePointsContainer.children.length > 1) {
                lastRow.remove();
                handleLastRowButtons(); // Re-check for the new last row
            }
            if(pricePointsContainer.children.length === 1){
                var abc = document.getElementById('price-points-container');
                var lastRowActive= pricePointsContainer.querySelector('.price-point-row:last-child');
                if (lastRowActive) {
                var inputs = lastRowActive.querySelectorAll('input');
                inputs.forEach(function(input) {
                    input.removeAttribute('readonly'); // Remove read-only
                });
            }
            }
        });
    }
}



//         document.getElementById('price_type').addEventListener('change', function () {
//     var priceType = this.value;
//     var pricePointsContainer = document.getElementById('price-points-container');

//     if (priceType === 'multiple_price') {
//         pricePointsContainer.style.display = 'block';
//         if (pricePointsContainer.children.length === 0) {
//             addPricePointRow(); // Add the first row immediately when multiple_price is selected
//         }
//     } else {
//         pricePointsContainer.style.display = 'none';
//         pricePointsContainer.innerHTML = ''; // Clear all dynamic rows when not multiple_price
//     }
// });

// function addPricePointRow() {
//     var pricePointsContainer = document.getElementById('price-points-container');

//     // Remove Add/Delete buttons from the previous row
//     var lastRow = pricePointsContainer.querySelector('.price-point-row:last-child');
//     if (lastRow) {
//         lastRow.querySelector('.actions').innerHTML = '';
//     }

//     var newRow = document.createElement('div');
//     newRow.classList.add('row', 'price-point-row');

//     newRow.innerHTML = `
//         <div class="col-md-3">
//             <div class="form-group">
//                 <label class="title-color text-capitalize">{{ translate('start_point') }}</label>
//                 <input type="number" name="start_point[]" min="1" max="10000" class="form-control" placeholder="1" required>
//             </div>
//         </div>
//         <div class="col-md-3">
//             <div class="form-group">
//                 <label class="title-color text-capitalize">{{ translate('end_point') }}</label>
//                 <input type="number" name="end_point[]" min="0" max="10000" class="form-control" placeholder="4" required>
//             </div>
//         </div>
//         <div class="col-md-3">
//             <div class="form-group">
//                 <label class="title-color text-capitalize">{{ translate('price') }}</label>
//                 <input type="number" name="price[]" min="0" max="100000000" step="0.01" class="form-control" placeholder="1000" required>
//             </div>
//         </div>
//         <div class="col-md-3 d-flex align-items-center actions">
//             <button type="button" class="btn btn-primary add-price-point mr-2" disabled>Add</button>
//             <button type="button" class="btn btn-danger remove-price-point">Delete</button>
//         </div>
//     `;

//     pricePointsContainer.appendChild(newRow);

//     // Add event listeners for the new buttons
//     var addBtn = newRow.querySelector('.add-price-point');
//     var inputs = newRow.querySelectorAll('input');

//     inputs.forEach(function(input) {
//         input.addEventListener('input', function() {
//             // Enable Add button only if all inputs are filled
//             if (Array.from(inputs).every(input => input.value !== '')) {
//                 addBtn.disabled = false;
//             } else {
//                 addBtn.disabled = true;
//             }
//         });
//     });

//     addBtn.addEventListener('click', function () {
//         var startPoint = parseInt(newRow.querySelector('input[name="start_point[]"]').value);
//         var endPoint = parseInt(newRow.querySelector('input[name="end_point[]"]').value);

//         // Validate the range before adding it
//         if (!checkOverlap(startPoint, endPoint)) {
//             addPricePointRow(); // Add the new row if validation passes
//         } else {
//             toastr.error('This price range overlaps with an existing range.');
//         }
//     });

//     newRow.querySelector('.remove-price-point').addEventListener('click', function () {
//         if (pricePointsContainer.children.length > 1) {
//             newRow.remove();
//             handleLastRowButtons(); // Handle buttons for the new last row
//         }
//     });

//     handleLastRowButtons(); // Ensure proper buttons on the last row
// }

// function checkOverlap(startPoint, endPoint) {
//     var pricePointsContainer = document.getElementById('price-points-container');
//     var rows = pricePointsContainer.querySelectorAll('.price-point-row');

//     for (var i = 0; i < rows.length - 1; i++) { // Exclude the row being added
//         var rowStart = parseInt(rows[i].querySelector('input[name="start_point[]"]').value);
//         var rowEnd = parseInt(rows[i].querySelector('input[name="end_point[]"]').value);

//         // Condition 1: The start_point of the new row should not be within an existing range
//         if (startPoint >= rowStart && startPoint <= rowEnd) {
//             return true;
//         }

//         // Condition 2: The end_point of the new row should not be within an existing range
//         if (endPoint >= rowStart && endPoint <= rowEnd) {
//             return true;
//         }

//         // Condition 3: The new row should not completely encompass an existing range
//         if (startPoint <= rowStart && endPoint >= rowEnd) {
//             return true;
//         }
//     }

//     return false;
// }

// function handleLastRowButtons() {
//     var pricePointsContainer = document.getElementById('price-points-container');
//     var lastRow = pricePointsContainer.querySelector('.price-point-row:last-child');
//     if (lastRow) {
//         var actions = lastRow.querySelector('.actions');
//         actions.innerHTML = `
//             <button type="button" class="btn btn-primary add-price-point mr-2" disabled>Add</button>
//             <button type="button" class="btn btn-danger remove-price-point">Delete</button>
//         `;

//         var addBtn = actions.querySelector('.add-price-point');
//         var inputs = lastRow.querySelectorAll('input');

//         inputs.forEach(function(input) {
//             input.addEventListener('input', function() {
//                 // Enable Add button only if all inputs are filled
//                 if (Array.from(inputs).every(input => input.value !== '')) {
//                     addBtn.disabled = false;
//                 } else {
//                     addBtn.disabled = true;
//                 }
//             });
//         });

//         addBtn.addEventListener('click', function () {
//             var startPoint = parseInt(lastRow.querySelector('input[name="start_point[]"]').value);
//             var endPoint = parseInt(lastRow.querySelector('input[name="end_point[]"]').value);

//             // Validate the range before adding it
//             if (!checkOverlap(startPoint, endPoint)) {
//                 addPricePointRow(); // Add the new row if validation passes
//             } else {
//                 toastr.error('This price range overlaps with an existing range.');
//             }
//         });

//         actions.querySelector('.remove-price-point').addEventListener('click', function () {
//             if (pricePointsContainer.children.length > 1) {
//                 lastRow.remove();
//                 handleLastRowButtons(); // Re-check for the new last row
//             }
//         });
//     }
// }





//         document.getElementById('price_type').addEventListener('change', function () {
//     var priceType = this.value;
//     var pricePointsContainer = document.getElementById('price-points-container');

//     if (priceType === 'multiple_price') {
//         pricePointsContainer.style.display = 'block';
//         if (pricePointsContainer.children.length === 0) {
//             addPricePointRow(); // Add the first row immediately when multiple_price is selected
//         }
//     } else {
//         pricePointsContainer.style.display = 'none';
//         pricePointsContainer.innerHTML = ''; // Clear all dynamic rows when not multiple_price
//     }
// });

// function addPricePointRow() {
//     var pricePointsContainer = document.getElementById('price-points-container');

//     // Remove Add/Delete buttons from the previous row
//     var lastRow = pricePointsContainer.querySelector('.price-point-row:last-child');
//     if (lastRow) {
//         lastRow.querySelector('.actions').innerHTML = '';
//     }

//     var newRow = document.createElement('div');
//     newRow.classList.add('row', 'price-point-row');

//     newRow.innerHTML = `
//         <div class="col-md-3">
//             <div class="form-group">
//                 <label class="title-color text-capitalize">{{ translate('start_point') }}</label>
//                 <input type="number" name="start_point[]" min="1" max="10000" class="form-control" placeholder="1" required>
//             </div>
//         </div>
//         <div class="col-md-3">
//             <div class="form-group">
//                 <label class="title-color text-capitalize">{{ translate('end_point') }}</label>
//                 <input type="number" name="end_point[]" min="0" max="10000" class="form-control" placeholder="4" required>
//             </div>
//         </div>
//         <div class="col-md-3">
//             <div class="form-group">
//                 <label class="title-color text-capitalize">{{ translate('price') }}</label>
//                 <input type="number" name="price[]" min="0" max="100000000" step="0.01" class="form-control" placeholder="1000" required>
//             </div>
//         </div>
//         <div class="col-md-3 d-flex align-items-center actions">
//             <button type="button" class="btn btn-primary add-price-point mr-2">Add</button>
//             <button type="button" class="btn btn-danger remove-price-point">Delete</button>
//         </div>
//     `;

//     pricePointsContainer.appendChild(newRow);

//     // Add event listeners for the new buttons
//     newRow.querySelector('.add-price-point').addEventListener('click', function () {
//         var startPoint = parseInt(newRow.querySelector('input[name="start_point[]"]').value);
//         var endPoint = parseInt(newRow.querySelector('input[name="end_point[]"]').value);

//         // Validate the range before adding it
//         if (!checkOverlap(startPoint, endPoint)) {
//             addPricePointRow(); // Add the new row if validation passes
//         } else {
//             alert('This price range overlaps with an existing range.');
//         }
//     });

//     newRow.querySelector('.remove-price-point').addEventListener('click', function () {
//         if (pricePointsContainer.children.length > 1) {
//             newRow.remove();
//             handleLastRowButtons(); // Handle buttons for the new last row
//         }
//     });

//     handleLastRowButtons(); // Ensure proper buttons on the last row
// }

// function checkOverlap(startPoint, endPoint) {
//     var pricePointsContainer = document.getElementById('price-points-container');
//     var rows = pricePointsContainer.querySelectorAll('.price-point-row');

//     for (var i = 0; i < rows.length - 1; i++) { // Exclude the row being added
//         var rowStart = parseInt(rows[i].querySelector('input[name="start_point[]"]').value);
//         var rowEnd = parseInt(rows[i].querySelector('input[name="end_point[]"]').value);

//         // Check for overlap
//         if ((startPoint <= rowEnd && endPoint >= rowStart)) {
//             return true;
//         }
//     }

//     return false;
// }

// function handleLastRowButtons() {
//     var pricePointsContainer = document.getElementById('price-points-container');
//     var lastRow = pricePointsContainer.querySelector('.price-point-row:last-child');
//     if (lastRow) {
//         var actions = lastRow.querySelector('.actions');
//         actions.innerHTML = `
//             <button type="button" class="btn btn-primary add-price-point mr-2">Add</button>
//             <button type="button" class="btn btn-danger remove-price-point">Delete</button>
//         `;

//         actions.querySelector('.add-price-point').addEventListener('click', function () {
//             var startPoint = parseInt(lastRow.querySelector('input[name="start_point[]"]').value);
//             var endPoint = parseInt(lastRow.querySelector('input[name="end_point[]"]').value);

//             // Validate the range before adding it
//             if (!checkOverlap(startPoint, endPoint)) {
//                 addPricePointRow(); // Add the new row if validation passes
//             } else {
//                 alert('This price range overlaps with an existing range.');
//             }
//         });

//         actions.querySelector('.remove-price-point').addEventListener('click', function () {
//             if (pricePointsContainer.children.length > 1) {
//                 lastRow.remove();
//                 handleLastRowButtons(); // Re-check for the new last row
//             }
//         });
//     }
// }






//     document.getElementById('price_type').addEventListener('change', function () {
//     var priceType = this.value;
//     var pricePointsContainer = document.getElementById('price-points-container');

//     if (priceType === 'multiple_price') {
//         pricePointsContainer.style.display = 'block';
//         if (pricePointsContainer.children.length === 0) {
//             addPricePointRow(); // Add the first row immediately when multiple_price is selected
//         }
//     } else {
//         pricePointsContainer.style.display = 'none';
//         pricePointsContainer.innerHTML = ''; // Clear all dynamic rows when not multiple_price
//     }
// });

// function addPricePointRow() {
//     var pricePointsContainer = document.getElementById('price-points-container');

//     // Remove Add/Delete buttons from the previous row
//     var lastRow = pricePointsContainer.querySelector('.price-point-row:last-child');
//     if (lastRow) {
//         lastRow.querySelector('.actions').innerHTML = '';
//     }

//     var newRow = document.createElement('div');
//     newRow.classList.add('row', 'price-point-row');

//     newRow.innerHTML = `
//         <div class="col-md-3">
//             <div class="form-group">
//                 <label class="title-color text-capitalize">{{ translate('start_point') }}</label>
//                 <input type="number" name="start_point[]" min="1" max="10000" class="form-control" placeholder="1" required>
//             </div>
//         </div>
//         <div class="col-md-3">
//             <div class="form-group">
//                 <label class="title-color text-capitalize">{{ translate('end_point') }}</label>
//                 <input type="number" name="end_point[]" min="0" max="10000" class="form-control" placeholder="4" required>
//             </div>
//         </div>
//         <div class="col-md-3">
//             <div class="form-group">
//                 <label class="title-color text-capitalize">{{ translate('price') }}</label>
//                 <input type="number" name="price[]" min="0" max="100000000" step="0.01" class="form-control" placeholder="1000" required>
//             </div>
//         </div>
//         <div class="col-md-3 d-flex align-items-center actions">
//             <button type="button" class="btn btn-primary add-price-point mr-2">Add</button>
//             <button type="button" class="btn btn-danger remove-price-point">Delete</button>
//         </div>
//     `;

//     pricePointsContainer.appendChild(newRow);

//     // Add event listeners for the new buttons
//     newRow.querySelector('.add-price-point').addEventListener('click', function () {
//         var startPoint = newRow.querySelector('input[name="start_point[]"]').value;
//         var endPoint = newRow.querySelector('input[name="end_point[]"]').value;

//         // Validate the range before adding it
//         if (!checkOverlap(startPoint, endPoint)) {
//             addPricePointRow(); // Add the new row if validation passes
//         } else {
//             alert('This price range overlaps with an existing range.');
//         }
//     });

//     newRow.querySelector('.remove-price-point').addEventListener('click', function () {
//         if (pricePointsContainer.children.length > 1) {
//             newRow.remove();
//             handleLastRowButtons(); // Handle buttons for the new last row
//         }
//     });

//     handleLastRowButtons(); // Ensure proper buttons on the last row
// }

// function checkOverlap(startPoint, endPoint) {
//     var pricePointsContainer = document.getElementById('price-points-container');
//     var rows = pricePointsContainer.querySelectorAll('.price-point-row');

//     for (var i = 0; i < rows.length; i++) {
//         var rowStart = parseInt(rows[i].querySelector('input[name="start_point[]"]').value);
//         var rowEnd = parseInt(rows[i].querySelector('input[name="end_point[]"]').value);

//         // Check for overlap
//         if ((startPoint < rowEnd && endPoint > rowStart) ||
//             (rowStart < endPoint && rowEnd > startPoint)) {
//             return true;
//         }
//     }

//     return false;
// }

// function handleLastRowButtons() {
//     var pricePointsContainer = document.getElementById('price-points-container');
//     var lastRow = pricePointsContainer.querySelector('.price-point-row:last-child');
//     if (lastRow) {
//         var actions = lastRow.querySelector('.actions');
//         actions.innerHTML = `
//             <button type="button" class="btn btn-primary add-price-point mr-2">Add</button>
//             <button type="button" class="btn btn-danger remove-price-point">Delete</button>
//         `;

//         actions.querySelector('.add-price-point').addEventListener('click', function () {
//             var startPoint = lastRow.querySelector('input[name="start_point[]"]').value;
//             var endPoint = lastRow.querySelector('input[name="end_point[]"]').value;

//             // Validate the range before adding it
//             if (!checkOverlap(startPoint, endPoint)) {
//                 addPricePointRow(); // Add the new row if validation passes
//             } else {
//                 alert('This price range overlaps with an existing range.');
//             }
//         });

//         actions.querySelector('.remove-price-point').addEventListener('click', function () {
//             if (pricePointsContainer.children.length > 1) {
//                 lastRow.remove();
//                 handleLastRowButtons(); // Re-check for the new last row
//             }
//         });
//     }
// }

    //   document.getElementById('price_type').addEventListener('change', function () {
//     var priceType = this.value;
//     var pricePointsContainer = document.getElementById('price-points-container');

//     if (priceType === 'multiple_price') {
//         pricePointsContainer.style.display = 'block';
//         if (pricePointsContainer.children.length === 0) {
//             addPricePointRow(); // Add the first row immediately when multiple_price is selected
//         }
//     } else {
//         pricePointsContainer.style.display = 'none';
//         pricePointsContainer.innerHTML = ''; // Clear all dynamic rows when not multiple_price
//     }
// });

// function addPricePointRow() {
//     var pricePointsContainer = document.getElementById('price-points-container');

//     // Remove Add/Delete buttons from the previous row
//     var lastRow = pricePointsContainer.querySelector('.price-point-row:last-child');
//     if (lastRow) {
//         lastRow.querySelector('.actions').innerHTML = '';
//     }

//     var newRow = document.createElement('div');
//     newRow.classList.add('row', 'price-point-row');

//     newRow.innerHTML = `
//         <div class="col-md-3">
//             <div class="form-group">
//                 <label class="title-color text-capitalize">{{ translate('start_point') }}</label>
//                 <input type="number" name="start_point[]" min="1" max="10000" class="form-control" placeholder="1" required>
//             </div>
//         </div>
//         <div class="col-md-3">
//             <div class="form-group">
//                 <label class="title-color text-capitalize">{{ translate('end_point') }}</label>
//                 <input type="number" name="end_point[]" min="0" max="10000" class="form-control" placeholder="4" required>
//             </div>
//         </div>
//         <div class="col-md-3">
//             <div class="form-group">
//                 <label class="title-color text-capitalize">{{ translate('price') }}</label>
//                 <input type="number" name="price[]" min="0" max="100000000" step="0.01" class="form-control" placeholder="1000" required>
//             </div>
//         </div>
//         <div class="col-md-3 d-flex align-items-center actions">
//             <button type="button" class="btn btn-primary add-price-point mr-2">Add</button>
//             <button type="button" class="btn btn-danger remove-price-point">Delete</button>
//         </div>
//     `;

//     pricePointsContainer.appendChild(newRow);

//     // Add event listeners for the new buttons
//     newRow.querySelector('.add-price-point').addEventListener('click', addPricePointRow);
//     newRow.querySelector('.remove-price-point').addEventListener('click', function () {
//         if (pricePointsContainer.children.length > 1) {
//             newRow.remove();
//             handleLastRowButtons(); // Handle buttons for the new last row
//         }
//     });

//     handleLastRowButtons(); // Ensure proper buttons on the last row
// }

// function handleLastRowButtons() {
//     var pricePointsContainer = document.getElementById('price-points-container');
//     var lastRow = pricePointsContainer.querySelector('.price-point-row:last-child');
//     if (lastRow) {
//         var actions = lastRow.querySelector('.actions');
//         actions.innerHTML = `
//             <button type="button" class="btn btn-primary add-price-point mr-2">Add</button>
//             <button type="button" class="btn btn-danger remove-price-point">Delete</button>
//         `;

//         actions.querySelector('.add-price-point').addEventListener('click', addPricePointRow);
//         actions.querySelector('.remove-price-point').addEventListener('click', function () {
//             if (pricePointsContainer.children.length > 1) {
//                 lastRow.remove();
//                 handleLastRowButtons(); // Re-check for the new last row
//             }
//         });
//     }
// }
//

    </script>
@endpush
