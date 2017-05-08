<!-- Modal Reservation -->
<div class="modal fade" tabindex="-1" role="dialog" id="reservation-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Réservation</h4>
          </div>
          <div class="modal-body">
              <div id="modal-resume" class="notice notice-info">
                      <p>Réservation du court .. le 'date' </p>
              </div>
              @if (Auth::check())
                <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#member-invite" aria-controls="member-invite" role="tab" data-toggle="tab">Réservation avec un membre</a></li>
                  <li role="presentation" {{((!Auth::user()->invitRight) ? 'class=disabled' : '')}}><a {{((Auth::user()->invitRight) ? 'href=#nonmember-invite' : '')}}
                     @if (!Auth::user()->invitRight) class="btn disabled" @endif aria-controls="nonmember-invite" role="tab" data-toggle="tab">Réservation avec un invité</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="member-invite">
                        <form method="post" role="form" method="POST" action="{{ url('/booking')}}" name="reservation-form" >
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <input type="hidden" class="reservation-date" name="dateTimeStart">
                            <input type="hidden" class="fkCourt" id="fkCourt" name="fkCourt" value=1>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Choissiez
                                    adversaire:</label>
                                 <select name="fkWithWho" class="form-control">
                                    @foreach($membersList as $member)
                                      @if($member->reservations_count != null)
                                        <option class="strong" value="{{$member->id}}">{{$member->firstname}} {{$member->lastname}}</option>
                                      @else
                                        <option value="{{$member->id}}">{{$member->firstname}} {{$member->lastname}}</option>
                                      @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" id="booking" class="btn btn-success btn-block push-to-bottom" name="btn-reserver">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                    Réserver
                                </button>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="nonmember-invite">
                        <form method="post" role="form" method="POST" action="{{ url('/booking')}}" name="reservation-member-invite-form" >
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <input type="hidden" class="reservation-date" name="dateTimeStart">
                            <input type="hidden" class="fkCourt" name="fkCourt" value=1>

                            <div class="form-group @if($errors->has('invitFirstname')) {{'has-error'}} @endif" >
                                <label for="recipient-name" class="control-label">Prénom de l'invité <span class="mandatory">*</span></label>
                                <input class="form-control" type="text" value="{{old('invitFirstname')}}" name="invitFirstname" data-verif="required|text|min_l:2|max_l:45" />
                                @if ($errors->has('invitFirstname'))
                                    <span class="help-block">
                                          <strong>{{ $errors->first('invitFirstname') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group @if($errors->has('invitLastname')) {{'has-error'}} @endif">
                                <label for="recipient-name" class="control-label">Nom de l'invité <span class="mandatory">*</span></label>
                                <input class="form-control" type="text" name="invitLastname" value="{{old('invitLastname')}}" data-verif="required|text|min_l:2|max_l:45"/>
                                @if ($errors->has('invitLastname'))
                                    <span class="help-block">
                                          <strong>{{ $errors->first('invitLastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <span class="mandatory">*</span> obligatoire
                            <div class="form-group push-to-bottom ">
                                <button type="button" id="btn-reserver-member-invite" class="btn btn-success btn-block" name="btn-reserver">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                    Réserver
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
              @else
                  <form method="post" role="form" method="POST" action="{{ url('/booking')}}" name="reservation-member-invite-form" >
                      {{ csrf_field() }}
                      {{ method_field('POST') }}
                      <input type="hidden" class="reservation-date" name="dateTimeStart">
                      <input type="hidden" class="fkCourt" id="fkCourt" name="fkCourt" value=1>

                      <div class="form-group @if($errors->has('firstname')) {{'has-error'}} @endif" >
                          <label for="recipient-name" class="control-label">Prénom <span class="mandatory">*</span></label>
                          <input class="form-control" type="text" value="{{old('firstname')}}" name="firstname" data-verif="required|text|min_l:2|max_l:45" />
                          @if ($errors->has('firstname'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('firstname') }}</strong>
                              </span>
                          @endif
                      </div>
                      <div class="form-group @if($errors->has('lastname')) {{'has-error'}} @endif">
                          <label for="recipient-name" class="control-label">Nom <span class="mandatory">*</span></label>
                          <input class="form-control" type="text" name="lastname" value="{{old('lastname')}}" data-verif="required|text|min_l:2|max_l:45"/>
                          @if ($errors->has('lastname'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('lastname') }}</strong>
                              </span>
                          @endif
                      </div>
                      <div class="form-group @if($errors->has('email')) {{'has-error'}} @endif">
                          <label for="recipient-name" class="control-label">Email <span class="mandatory">*</span></label>
                          <input class="form-control" type="text" name="email" value="{{old('email')}}" data-verif="required|email"/>
                          @if ($errors->has('email'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('email') }}</strong>
                              </span>
                          @endif
                      </div>
                      <div class="form-group @if($errors->has('lastname')) {{'has-error'}} @endif">
                          <label for="recipient-name" class="control-label">Téléphone <span class="mandatory">*</span></label>
                          <input class="form-control" type="text" name="phone" value="{{old('phone')}}" data-verif="required|phone"/>
                          @if ($errors->has('phone'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('phone') }}</strong>
                              </span>
                          @endif
                      </div>
                      <div class="form-group @if($errors->has('street')) {{'has-error'}} @endif">
                          <label for="recipient-name" class="control-label">Rue <span class="mandatory">*</span></label>
                          <input class="form-control" type="text" name="street" value="{{old('street')}}" data-verif="text|min_l:2|max_l:45"/>
                          @if ($errors->has('street'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('street') }}</strong>
                              </span>
                          @endif
                      </div>
                      <div class="form-group @if($errors->has('streetNbr')) {{'has-error'}} @endif">
                          <label for="recipient-name" class="control-label">Numéro de rue <span class="mandatory">*</span></label>
                          <input class="form-control" type="text" name="streetNbr" value="{{old('streetNbr')}}" data-verif="min_l:1|max_l:45"/>
                          @if ($errors->has('streetNbr'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('streetNbr') }}</strong>
                              </span>
                          @endif
                      </div>
                      <div class="form-group @if($errors->has('locality')) {{'has-error'}} @endif">
                          <label for="recipient-name" class="control-label">Ville <span class="mandatory">*</span></label>
                          <select class="form-control" id="locality" name="locality">
                              <option id="locality" value="-1" selected>Choisissez une localité</option>
                              @foreach($localities as $locality)
                                  <!-- we select the value in the city of the member. If the form as been return with error the old value is selected -->
                                      <option id="locality" value="{{$locality->id}}" @if ($locality->id == old('locality')) selected @endif> {{$locality->npa.' - '.$locality->name}} </option>
                              @endforeach
                          </select>
                          @if ($errors->has('locality'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('locality') }}</strong>
                              </span>
                          @endif

                      </div>
                      <span class="mandatory">*</span> obligatoire
                      <div class="form-group push-to-bottom ">
                          <button type="button" id="btn-reserver-member-invite" class="btn btn-success btn-block" name="btn-reserver">
                              <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                              Réserver
                          </button>
                      </div>
                  </form>

              @endif

              <div id="modal-panel"></div>
              <div id="modal-content"></div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
          </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    hasErros = false;
    @if($errors->count()) {{"hasErrors= true;"}} @else {{"hasErrors=false;"}} @endif
    if(hasErrors)
    {
        chooseDate = new Date("{{old('dateTimeStart')}}");
        $("#modal-resume").html('Réservation du court N° '+$("#fkCourt").val()+' le ' +chooseDate.getUTCDate().toStringN(2)+ "-" + (chooseDate.getMonth() + 1).toStringN(2) + "-" + chooseDate.getFullYear()+' à '+chooseDate.getHours().toStringN(2) + ":" + chooseDate.getMinutes().toStringN(2) );

        /*can use chooseDate because of the format*/
        $(".reservation-date").val("{{old('dateTimeStart')}}");
        $('[href="#nonmember-invite"]').tab('show');
        $("#reservation-modal").modal('show');

    }
    document.querySelector('#btn-reserver-member-invite').addEventListener('click', function(){
        VERIF.verifForm('reservation-member-invite-form',function(isOk){
            if(isOk) document.forms["reservation-member-invite-form"].submit();
        });
    });
</script>
