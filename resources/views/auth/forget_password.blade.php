@extends('layouts.auth')

@section('title', 'Forget Password')

@push('plugin-styles')
    <style>
        @media (min-width:320px)  { 
            .image-1 {
                width: 150px;
                display: block;
                margin-left: auto;
                margin-right: auto;
                margin-bottom: 10px;
            }

            .title-logo {
                display: none;
            }
            
            .title-mobile {
                font-size: 16px;
                display: block;
                margin-top: auto;
                margin-bottom: auto;
                color: #000865;
                font-weight: 700;
            }
            
            .title-mobile span {
                font-size: 14px;
                color: #009ce3;
                font-weight: 300;
            }

            .auth-side-wrapper {
                display: none;
            }
            
            .auth-form-wrapper button {
                width: 100%;
            }
        }
        @media (min-width:768px)  { 
            .image-1 {
                width: 64px;
                margin-bottom: 0px;
            }

            .title-logo {
                font-size: 10px;
                display: block;
                margin-top: auto;
                margin-bottom: auto;
                margin-left: 10px;
            }

            .title-logo span {
                font-size: 12px;
            }

            .auth-side-wrapper {
                display: block;
            }
            
            .title-mobile {
                display: none;
            }
        }
        
        @media (min-width:1024px) { 
            .image-1 {
                width: 80px;
                margin-bottom: 0px;
            }

            .title-logo {
                font-size: 13px;
                display: block;
                margin-top: auto;
                margin-bottom: auto;
                margin-left: 10px;
            }

            .title-logo span {
                font-size: 12px;
            }

            .auth-side-wrapper {
                display: block;
            }
            
            .title-mobile {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-content d-flex align-items-center justify-content-center">
        <div class="row w-100 mx-0 auth-page">
            <div class="col-md-8 col-xl-6 mx-auto">
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="row">
                        <div class="col-md-4 pe-md-0">
                            <div class="auth-side-wrapper">
                                <img src="{{ asset('guest_assets/img/login/1.jpg') }}" alt="" width="100%"
                                    height="100%">
                            </div>
                        </div>
                        <div class="col-md-8 ps-md-0">
                            <div class="auth-form-wrapper px-4 py-4">
                                <a href="#" class="noble-ui-logo d-block mb-2 d-flex">
                                    <img src="{{ asset('guest_assets/img/logo-kkp.png') }}" class="image-1" alt="">
                                    <p class="title-logo">Kementerian Kelautan dan Perikanan <br> Balai Pengelolaan Sumberdaya Pesisir dan Laut Pontianak <br> <span>Sistem pendataan pendaratan ikan</span></p>
                                    <p class="subtitle-logo"></p>
                                </a>
                                <p class="title-mobile">Kementerian Kelautan dan Perikanan <br> Balai Pengelolaan Sumberdaya Pesisir dan Laut Pontianak <br> <span>Sistem pendataan pendaratan ikan</span></p>
                                <h6 class="text-muted fw-normal mb-4 mt-4">Buat Password Baru.</h6>
                                <form action="{{ route('forget-password.update', $user->id) }}" method="post" class="forms-sample">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="userPassword" class="form-label">Kata Sandi</label>
                                        <input type="password" name="password" class="form-control" id="txtNewPassword" autocomplete="current-password" placeholder="*******" autocomplete="off" onkeyup="checkPasswordMatch();" required>
                                    </div>
                                    
                                    <div class="mb-1">
                                        <label for="userPassword" class="form-label">Konfirmasi Kata Sandi</label>
                                        <input type="password" name="password" class="form-control" id="txtConfirmPassword" autocomplete="current-password" placeholder="Ketik ulang kata sandi" onkeyup="checkPasswordMatch();" autocomplete="off" required>
                                    </div>
                                    
                                    <div class="registrationFormAlert" id="divCheckPasswordMatch"></div>
                                    
                                    <div class="mt-3">
                                        <button class="btn btn-primary me-2 mb-2 mb-md-0" id="btn-submit">Ubah Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('custom-scripts')
<script>
    function checkPasswordMatch() {
        var password = $("#txtNewPassword").val();
        var confirmPassword = $("#txtConfirmPassword").val();
    
        if (password != confirmPassword){
            
            $("#divCheckPasswordMatch").html("<span class='text-danger'>Kata sandi tidak cocok!</span>");
        } else {
            $("#divCheckPasswordMatch").html("<span class='text-success'>Kata sandi cocok</span>.");
        }
    }
    
    $('#btn-submit').on('click', function(){
        var password = $("#txtNewPassword").val();
        var confirmPassword = $("#txtConfirmPassword").val();
        
        if (password != confirmPassword){
            return false;
        }
    });
</script>
@endpush