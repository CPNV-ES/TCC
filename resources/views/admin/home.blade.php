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
                            <div class="huge">26</div>
                            <div>En attente de validation</div>
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
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">12</div>
                            <div>New Tasks!</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-shopping-cart fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">124</div>
                            <div>New Orders!</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-support fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">13</div>
                            <div>Support Tickets!</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ($members as $member)
            <div class="col-md-3">
                <div class="box">
                    <div class="box-content">
                        <h4 class="tag-title text-center">{{ $member->first_name }} {{ $member->last_name }}</h4>
                        <hr />
                        <p>
                            {{ $member->address }}<br/>
                            {{ $member->zip_code }}<br/>
                            {{ $member->city }}<br/>
                        </p>
                        <hr />
                        <p>
                            {{ $member->email }}<br/>
                            {{ $member->phone }}
                        </p>
                        <hr />
                        <p>
                        <form action="{{url('admin/members/'. $member->id)}}" method="post">

                            {!! csrf_field() !!}
                            {!! method_field('put') !!}

                            <div class="form-group">
                                {!! Form::select('status'.$member->id, $status, null, ['class' => 'form-control', 'placeholder' => 'Sélectionner']) !!}
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Saisissez un login" name="login{{$member->id}}" value="{{ old('login'.$member->id) }}">
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
