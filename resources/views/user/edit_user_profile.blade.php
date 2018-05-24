@extends('layouts.app')
@php
    $selected_lang = app()->getLocale();
@endphp
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ _t('Update Profile') }}</div>

                <div class="panel-body">
                    {!! Form::open(['url' => $selected_lang.'/update/user/profile','method'=>"POST",'class'=>'form-horizontal']) !!}
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
                        <input type="hidden" name="user_id" value="{{ $user_details['id'] }}">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">{{ _t('Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $user_details['name'] }}" autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ _t($errors->first('name')) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">{{ _t('E-Mail Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{{ $user_details['email'] }}" >
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ _t($errors->first('email')) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">{{ _t('User Role') }}</label>
                            <div class="col-md-6">
                                <select name="user_type" class="form-control">
                                    @if(isset($user_details['user_type']) && $user_details['user_type']) != '')
                                        <option value="{{ $user_details['user_type'] }}" selected="selected">{{ $user_details['user_type'] }}</option>
                                    @else
                                        <option value="Admin">Admin</option>
                                        <option value="User">User</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-4 control-label">{{ _t('Address') }}</label>
                            <div class="col-md-6">
                                <input id="address" type="address" class="form-control" name="address" value="{{ $user_details['address'] }}">
                                @if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ _t($errors->first('address')) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" name="latitude" id="latitude" value="">
                        <input type="hidden" name="longitude" id="longitude" value="">
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ _t('Update') }}
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXCv7nP1wBBmBOdtSgBqmtMYWlk1PEOuc&libraries=places"></script> --}}
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        google.maps.event.addDomListener(window, 'load', function () {
        var places = new google.maps.places.Autocomplete(document.getElementById('address'));
        google.maps.event.addListener(places, 'place_changed', function () {
            var place = places.getPlace();
            var address = place.formatted_address;
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();

            $("#latitude").val(latitude);
            $("#longitude").val(longitude);
            $("#address").val(address);
            var mesg = "Address: " + address;
            mesg += "\nLatitude: " + latitude;
            mesg += "\nLongitude: " + longitude;
        });
    });
    });
</script>