@extends('layouts.main')

@section('extraStyling')
<link rel="stylesheet" href="{{ asset('css/arabicText.css') }}">
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

@endsection



@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <br>
        <div class="card border-dark">
            <div class="card-header bg-dark">
                <h4 class="m-b-0 text-white">@yield('queryCardHead')</h4>
            </div>
            <div class="card-body">
                @yield('queryCardBody')
            </div>
        </div>
    </div>
</div>
 <br>
 <div class="row">
    <div class="col-12">
        <div class="card border-dark">
            <div class="card-header bg-info">
                <h4 class="m-b-0 text-white">@yield('queryResultCardHead')</h4>
            </div>
            <div class="card-body">
                @yield('queryResultCardBody')
            </div>
        </div>
    </div>
</div>
@endsection

@section('extraJS')
@yield('queryJS')
@endsection