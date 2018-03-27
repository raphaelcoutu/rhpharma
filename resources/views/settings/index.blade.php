@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Paramètres généraux</div>
                <div class="panel-body">
                    <div class="col-md-12 col-lg-9">
                        <h3 id="order">Génération des secteurs</h3>
                        <p>C'est ici qu'on peut paramétrer l'ordre de génération de l'horaire par défaut. Les secteurs
                            seront générés du plus haut au plus bas.</p>
                        <p>Il est également possible de désactiver le secteur de la génération d'horaire en décochant la
                            case appropriée.</p>

                        <rhpharma-settings-order
                                :departments="{{ $departments }}"
                                :order="{{ $order }}"
                        ></rhpharma-settings-order>

                        <hr>

                        <h3 id="triplets">Triplets</h3>
                    </div>
                    <div class="col-lg-3 hidden-md">
                        <p>Table des matières</p>
                        <ul>
                            <li><a href="#order">Génération des secteurs</a></li>
                            <li><a href="#triplets">Triplets</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop