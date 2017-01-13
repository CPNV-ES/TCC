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
                            <div class="huge">{{ $members }}</div>
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

        @foreach ($notmembers as $member)

            <div class="col-md-3">
                <div class="box">
                    <div class="box-content">
                        <h4 class="tag-title text-center">{{ $member->first_name }} {{ $member->last_name }}</h4>
                        <hr/>
                        <p>
                            {{ $member->address }}<br/>
                            {{ $member->zip_code }}<br/>
                            {{ $member->city }}<br/>
                        </p>
                        <hr/>
                        <p>
                            {{ $member->email }}<br/>
                            {{ $member->phone }}
                        </p>
                        <hr/>
                        <p>
                        <form action="{{url('admin/members/'. $member->id)}}" method="post">

                            {!! csrf_field() !!}
                            {!! method_field('put') !!}


                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Saisissez un login"
                                       name="login{{$member->id}}" value="{{ old('login'.$member->id) }}">

                                @if ($errors->has('login'.$member->id))

                                    <span class="help-block">
                                            <strong>{{$errors->first('login'.$member->id)}}</strong>
                                        </span>

                                @endif
                            </div>

                            <input type="hidden" name="id" value="{{ $member->id }}">

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary">
                                    Activer
                                </button>
                            </div>
                        </form>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
