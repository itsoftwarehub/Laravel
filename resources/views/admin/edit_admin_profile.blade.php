@extends('layouts.app')
@php
    $selected_lang = app()->getLocale();
@endphp
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ _t("Update Profile") }}</div>
                <div class="panel-body">
                    {!! Form::open(['url' => $selected_lang.'/update/admin/profile','method'=>"POST",'class'=>'form-horizontal']) !!}
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
                            <label for="name" class="col-md-4 control-label">{{ _t("Name") }}</label>
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
                            <label for="email" class="col-md-4 control-label">{{ _t("E-Mail Address") }}</label>
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
                            <label for="email" class="col-md-4 control-label">{{ _t("User Role") }}</label>
                            <div class="col-md-6">
                                <select name="user_type" class="form-control">
                                    @if(isset($user_details['user_type']) && $user_details['user_type']) != '')
                                        <option value="{{ $user_details['user_type'] }}" selected="selected">{{ _t($user_details['user_type']) }}</option>
                                    @else
                                        <option value="Admin">{{ _t("Admin") }}</option>
                                        <option value="User">{{ _t("User") }}</option>
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

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ _t("Update") }}
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
            var mesg = "Address: " + address;
            mesg += "\nLatitude: " + latitude;
            mesg += "\nLongitude: " + longitude;
            $("#address").val(address);
        });
    });
    });
</script>
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBp7tn9qnKpAQkYD5GFPKGxFEhbtF96HXk&libraries=places&callback=initAutocomplete" async defer></script> --}}
{{-- <script>
    var placeSearch, autocomplete;
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
    };

    function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('address')),
        {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
            document.getElementById(component).value = '';
            document.getElementById(component).disabled = false;
        }
        console.log(place);
        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                document.getElementById(addressType).value = val;
            }
        }
    }

    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }
    </script> --}}