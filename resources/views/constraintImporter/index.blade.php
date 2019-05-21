@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Importer des contraintes</div>
                <div class="panel-body">
                    @if(session('status'))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            </div>
                        </div>
                    @endif
                        @if(session('error'))
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                        <p>Contactez Raphaël (pagette 5115).</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    <rhpharma-constraint-importer></rhpharma-constraint-importer>
                </div>
            </div>
        </div>
    </div>
@endsection