@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ _t("Dashboard") }}</div>

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
                    {{ _t('You are logged in as Admin  : ' .Auth::user()->name. " !") }}  
                </div>
                <div class="google-custome-search-div col-md-6" style="margin-top: 1px; margin-right: 20%;">
                    <script>
                      (function() {
                        var cx = '009921840689055997912:vocupysr1w0';
                        var gcse = document.createElement('script');
                        gcse.type = 'text/javascript';
                        gcse.async = true;
                        gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
                        var s = document.getElementsByTagName('script')[0];
                        s.parentNode.insertBefore(gcse, s);
                      })();
                    </script>
                    <gcse:search></gcse:search>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>