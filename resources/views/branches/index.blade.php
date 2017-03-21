@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">{{ __('branches.title') }}</div>

            <div class="panel-body">
                <rhpharma-branches :rows="{{ $branches }}"></rhpharma-branches>
            </div>
        </div>
    </div>
</div>
@endsection