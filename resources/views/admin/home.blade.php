@extends('layouts.admin')

@section('title')
    Panel d'administration
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $nb_users }}</div>
                            <div>Nombre de membres</div>
                        </div>
                    </div>
                </div>
                <a href="{{ url('/admin/members') }}">
                    <div class="panel-footer">
                        <span class="pull-left">Plus d'informations</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        @if (!empty($message))

            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                {{ $message }}
            </div>

        @endif
    </div>

    <div class="row">
        <h2 align="center">Comptes en attente de validation</h2>

        @foreach ($notUser as $user)

            <div class="col-md-6 col-lg-3">
                <div class="box">
                    <div class="box-content">
                        <h4 class="tag-title text-center">{{ $user->personal_information->firstname }} {{ $user->personal_information->lastname }}</h4>
                        <hr/>
                        <p>
                            {{ $user->personal_information->street }} <br/>
                            {{ $user->personal_information->streetNbr }}<br/>
                            {{ ($user->personal_information->localities) ? $user->personal_information->localities->npa : '' }} {{ ($user->personal_information->localities) ? $user->personal_information->localities->name : '' }}<br/>
                        </p>
                        <hr/>
                        <p style="height: 40px;">
                            {{ $user->personal_information->email }}<br/>
                            {{ $user->personal_information->telephone }}
                        </p>
                        <hr/>
                        <form action="{{url('/admin/login/update/'. $user->id)}}" method="post">

                            {!! csrf_field() !!}
                            {!! method_field('put') !!}


                            <div class="form-group">
                                <input type="text" class="form-control" data-verif="required|alphanumerique|min_l:5|max_l:25" placeholder="Saisissez un login"
                                       name="login{{$user->id}}" value="{{ old('login'.$user->id) }}">

                                @if ($errors->has('login'.$user->id))

                                    <span class="help-block">
                                            <strong>{{$errors->first('login'.$user->id)}}</strong>
                                        </span>

                                @endif
                            </div>

                            <input type="hidden" name="id" value="{{ $user->id }}">

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary">
                                    Activer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
