@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ _t('Dashboard') }}</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ _t(session('status')) }}
                        </div>
                    @endif

                    @if (Session::has('success-message'))
                        <div class="alert alert-success">
                            {{ _t(Session::get('success-message')) }}
                        </div>
                    @endif
                    @if (Session::has('error-message'))
                        <div class="alert alert-danger">
                            {{ _t(Session::get('error-message')) }}
                        </div>
                    @endif

                    {{ _t('You are logged in as User :') }} {{ _t(Auth::user()->name) }}!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
