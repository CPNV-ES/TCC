@extends('layouts.app')

@section('content')
    <div class="background"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="head-panel">
                    <div class="title-panel">Inscription</div>
                    <div class="body-panel">
                        <form class="form-horizontal" name="register_form" role="form" method="POST" action="{{ url('/register') }}">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Prénom*</label>

                                <div class="col-md-6">
                                    <input type="text" data-verif-group="register_form" class="form-control" name="first_name"
                                          data-verif="required|min_l:2|max_l:30"
                                           value="{{ old('first_name') }}">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Nom*</label>

                                <div class="col-md-6">
                                    <input type="text" data-verif-group="register_form" data-verif="required|min_l:2|max_l:30" class="form-control" name="last_name"
                                           value="{{ old('last_name') }}">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Adresse*</label>

                                <div class="col-md-6">
                                    <input type="text" data-verif-group="register_form" data-verif="required|min_l:2|max_l:60" class="form-control" name="address" value="{{ old('address') }}">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('zip_code') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">NPA*</label>

                                <div class="col-md-6">
                                    <input type="text" data-verif-group="register_form" data-verif="required|int|min_l:4|max_l:4" class="form-control" name="zip_code"
                                           value="{{ old('zip_code') }}">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Ville*</label>

                                <div class="col-md-6">
                                    <input type="text" data-verif-group="register_form" data-verif="required|min_l:2|max_l:45" class="form-control" name="city" value="{{ old('city') }}">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">E-mail*</label>

                                <div class="col-md-6">
                                    <input type="email" data-verif-group="register_form" data-verif="required|email" class="form-control" name="email" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('home_phone') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Téléphone privé*</label>

                                <div class="col-md-6">
                                    <input type="text" data-verif-group="register_form" data-verif="required" class="form-control" name="home_phone"
                                           placeholder="123 456 78 90" value="{{ old('home_phone') }}">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('mobile_phone') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Téléphone mobile*</label>

                                <div class="col-md-6">
                                    <input type="text" data-verif-group="register_form" data-verif="required" class="form-control" name="mobile_phone"
                                           placeholder="123 456 78 90" value="{{ old('mobile_phone') }}">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('birth_date') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Date de naissance*</label>

                                <div class="col-md-6">
                                    <input type="text" data-verif-group="register_form" data-verif="required|date|date_past|date_more_diff:72" class="form-control" id="birth_date" name="birth_date"
                                           value="{{ old('birth_date') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button id="btn_register"  type="button" class="btn btn-primary">
                                        <i class="fa fa-btn fa-user"></i>S'inscrire
                                    </button>
                                </div>
                            </div>
                            <script type="text/javascript">
                                document.querySelector('#btn_register').addEventListener('click', function(e) {
                                    if(VERIF.verifGroup('register_form'))
                                        document.forms["register_form"].submit();
                                });
                            </script>
                            @if (count($errors->all())>0)
                                <script>$(()=>{VERIF.verifGroup('register_form');})</script>
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
