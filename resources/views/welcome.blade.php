@extends('layouts.app')

@section('content')

    <div class="row calendar">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="box">
                <div class="box-icon">
                    <span class="fa fa-4x fa-html5">Court 1</span>
                </div>
                <div class="info">
                    <div id="jqxcourt1"></div>
                    <a href="" class="btn">Détails</a>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="box">
                <div class="box-icon">
                    <span class="fa fa-4x fa-css3">Court 2</span>
                </div>
                <div class="info">
                    <div id="jqxcourt2"></div>
                    <a href="" class="btn">Détails</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row" >
            <div class="col-md-12" style="margin-top:20px;">
                <p>
                    <h1>Bienvenue sur tcchavornay.ch</h1>

                    Ce site est actuellement en construction.
                    N'hésitez pas à venir le visiter prochainement !<br/><br/>

                    Mais retenez déjà quelques dates clés ci-dessous et la date de notre évènement majeur, Le Double Mixte du mois de Juin 2016 !<br/><br/>


                <ul class="timeline">
                    <li>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title">Ouverture de la saison</h4>
                                <p><small class="text-muted"><i class="glyphicon glyphicon-calendar"></i> Le 14 avril</small></p>
                            </div>
                            <div class="timeline-body">
                                <p>Dés 18h, Apéro d'ouverture de la saison avec fondue (sur inscription)</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title">Cours 2016</h4>
                                <p><small class="text-muted"><i class="glyphicon glyphicon-calendar"></i> Le 18 avril</small></p>
                            </div>
                            <div class="timeline-body">
                                <p>Début de tous les cours 2016 (juniors à adultes) et démarrage du concours de la pyramide</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title">Tournois simple dames</h4>
                                <p><small class="text-muted"><i class="glyphicon glyphicon-calendar"></i> Du 30 avril au 1er mai</small></p>
                            </div>
                            <div class="timeline-body">
                                <p>Tournoi Simple Dames R5-R9</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title">Tournoi double mixte</h4>
                                <p><small class="text-muted"><i class="glyphicon glyphicon-calendar"></i> Du 16 au 19 juin</small></p>
                            </div>
                            <div class="timeline-body">
                                <p>Tournoi Double Mixte "Les Pirates"</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title">Stage juniors</h4>
                                <p><small class="text-muted"><i class="glyphicon glyphicon-calendar"></i> Du 4 au 8 juillet</small></p>
                            </div>
                            <div class="timeline-body">
                                <p>Stage d'été Juniors</p>
                            </div>
                        </div>
                    </li>
                </ul>
                </p>
           </div>
        </div>
    </div>
@endsection
