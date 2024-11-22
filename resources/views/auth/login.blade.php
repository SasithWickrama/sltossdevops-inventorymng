@extends('layouts.app', ['class' => 'login-page', 'page' => _('Login Page'), 'contentClass' => 'login-page'])

@section('content')

<div class="col-lg-4 col-md-6 ml-auto mr-auto">
    <form class="form" method="post" action="{{ route('login.custom') }}">
        @csrf

        <div class="card card-login card-white">
            <div class="card-header">
                <img src="{{ asset('white') }}/img/card-primary.png" alt="">
                <h1 class="card-title">{{ _('Log in') }}</h1>
            </div>
            <div class="card-body">

                @include('alerts.feedback', ['field' => 'genaral'])
                <div class="input-group{{ $errors->has('conname') ? ' has-danger' : '' }}">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="tim-icons icon-badge"></i>
                        </div>
                    </div>

                    <select name="conname" id="conname" class="form-control" required>
                        <option value=""></option>
                        <option value="SLT"  {{ old('conname') == "SLT" ? "selected" : "" }} >SLT</option>
                        @if(!$result==null)
                        @foreach($result as $data)

                       
                        <option  {{ old('conname') == $data ? "selected" : "" }} value="{{ $data }}" >{{ $data }}</option>
                        

                        
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="input-group{{ $errors->has('sno') ? ' has-danger' : '' }}" id="snogroup">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="tim-icons icon-single-02"></i>
                        </div>
                    </div>
                    <input type="text" name="sno" id="sno" class="form-control" placeholder="{{ _('Service No') }}" required value="{{ old('sno') }}">
                    @include('alerts.feedback', ['field' => 'sno'])
                </div>
                <div class="input-group{{ $errors->has('password') ? ' has-danger' : '' }}" id="pwdgroup">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="tim-icons icon-lock-circle"></i>
                        </div>
                    </div>
                    <input type="password" placeholder="{{ _('Password') }}" name="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required>

                </div>

            </div>
            <div class="card-footer">
                <button type="submit" name="submitbtn" id="submitbtn" value="login" class="btn btn-primary btn-lg btn-block mb-3">{{ _('Submit') }}</button>
                <button type="submit" name="submitbtn" id="getotpbtn" value="otp" class="btn btn-primary btn-lg btn-block mb-3" style="visibility: hidden;">{{ _('Get OTP') }}</button>

            </div>
        </div>
    </form>
</div>


@endsection



@push('js')
<script>
    $(document).ready(function() {
        $('.form-control').unbind('click').on('click', function() {
            $('.invalid-feedback').hide();
        });



        $('#conname').on('change', function() {
            if (this.value == 'SLT') {
                $('#getotpbtn').css('visibility', 'hidden');
                $('#sno').attr("placeholder", "Service Number");
                $('#password').attr("placeholder", "Password");
                $("#password").prop('required', true);
                $('#pwdgroup').show();
                $('#submitbtn').show();
            } else {
                $('#sno').attr("placeholder", "User Name");
                $('#password').attr("placeholder", "OTP");
                $("#password").prop('required', false);
                $('#pwdgroup').hide();
                $('#submitbtn').hide();
                $('#getotpbtn').css('visibility', 'visible');

            }
        });

        
    });
</script>
@endpush