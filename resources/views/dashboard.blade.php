@extends('layouts.app', ['metaTitle' => 'Dashboard', 'parentSection' => '', 'elementName' => ''])

@section('content')
    @component('layouts.headers.breadcrumbs')
    @endcomponent
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            {{ __('Anda sudah masuk!') }}
        </div>
    </div>
@endsection
