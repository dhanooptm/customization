<div class="p-3 px-xl-4 py-sm-5">
    <div class="text-center">
        <img width="100" class="mb-4" id="view-mail-icon"
             src="{{ $template->image_full_url['path'] ?? dynamicAsset(path: 'public/assets/back-end/img/email-template/customer-registration.png')}}"
             alt="">
        <h3 class="mb-3 view-mail-title text-capitalize">
            {{$title?? translate('registration_Complete')}}
        </h3>
    </div>
    <div class="view-mail-body">
        {!! $body !!}
    </div>
    <br>
    <div>
        <p>
            {{translate('meanwhile_click_here_to_visit_').$companyName.translate('_website')}}
            <br>
            <a href="{{route('home')}}" target="_blank">{{url('/')}}</a>
        </p>
    </div>
    <hr>
    @include('admin-views.business-settings.email-template.partials-design.footer')
</div>
