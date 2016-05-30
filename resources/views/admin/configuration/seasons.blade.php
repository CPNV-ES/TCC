@extends('layouts.admin')

@section('title')
    Gestion des saisons
@endsection

@section('content')
    <div class="row">
        <div id="jqxseasons"></div>
    </div>
    <br />
    <div class="row">

        {{--@if (!empty(Session::has('message')))--}}

            {{--<div class="alert alert-success alert-dismissible" role="alert">--}}
                {{--<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                {{--{{ Session::flash() }}--}}
            {{--</div>--}}

        {{--@endif--}}
    </div>
    <div class="row" align="center"><h3>Ajouter une saison</h3></div>
    <br />
    <div class="row">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/config/seasons') }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('begin_date') ? ' has-error' : '' }}">
                <label class="col-md-6 control-label">Date de début (format jj.mm.aaaa)*</label>

                <div class="col-md-4">
                    <input type="date" class="form-control" name="begin_date" value="{{ old('begin_date') }}">

                    @if ($errors->has('begin_date'))
                        <span class="help-block">
                        <strong>{{ $errors->first('begin_date') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
                <label class="col-md-6 control-label">Date de fin (format jj.mm.aaaa)*</label>

                <div class="col-md-4">
                    <input type="date" class="form-control" name="end_date" value="{{ old('end_date') }}">

                    @if ($errors->has('end_date'))
                        <span class="help-block">
                        <strong>{{ $errors->first('end_date') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group" align="center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-btn">Ajouter</i>
                    </button>
            </div>
        </form>
    </div>

@endsection