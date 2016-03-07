@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <h2 class="text-center">Personnes à activer</h2>
        <div class="row">
            @foreach ($members as $member)
            <div class="col-md-4">
                <div class="box">
                    <div class="box-content">
                        <h2 class="tag-title text-center">{{ $member->first_name }} {{ $member->last_name }}</h2>
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
                            <form action="{{url('admin/'. $member->id)}}" method="post">

                                {!! csrf_field() !!}
                                {!! method_field('put') !!}

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
                                        <i class="fa fa-btn fa-user"></i>Activer
                                    </button>
                                </div>
                            </form>
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
