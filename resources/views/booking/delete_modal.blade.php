<!-- Delete Modal Reservation -->
<div class="modal fade" tabindex="-1" role="dialog" id="del-resevation-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Supprimer réservation</h4>
            </div>
            <div class="modal-body">
                <div id="modal-del-resume" class="notice notice-info">
                    <p>Supression de la réservation du court .. le 'date' </p>
                </div>
                <form id="form-modal-delete" name="form-modal-delete-name" role="form" method="POST" action="{{ url('/booking/')}}"  >
                    {{ csrf_field() }}
                    {{--If the user is logged in we send the form with DELETE otherwise POST cause of the action done afterward--}}
                    @if(Auth::check()) {{ method_field('DELETE') }} @else {{ method_field('POST') }} @endif
                    <input type="hidden" name="id-del-reserv" id="id-del-reserv" value=''>
                    <input type="hidden" name="date-del-reserv" id="date-del-reserv" value=''>
                    <input type="hidden" name="court-del-reserv" id="court-del-reserv" value=''>
                    <input type="hidden" name="baseurl-del-reserv" id="baseurl-del-reserv" value="{{ url('/booking/')}}">
                    @if (!Auth::check())
                        <div class="form-group @if($errors->has('firstname')) {{'has-error'}} @endif" >
                            <label for="recipient-name" class="control-label">
                                Email*:</label>

                            <input class="form-control" type="email" placeholder="Entrez votre email" value="{{old('email')}}" name="email" data-verif="required|email" />
                            @if ($errors->has('firstname'))
                                <span class="help-block">
                                  <strong>{{ $errors->first('firstname') }}</strong>
                              </span>
                            @endif
                        </div>
                    @endif
                    <div class="form-group push-to-bottom ">
                        <button type="button" id="btn-del-res-modal" class="btn btn-danger btn-block" name="btn-delete">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            Supprimer
                        </button>
                        <script type="text/javascript">
                            document.querySelector('#btn-del-res-modal').addEventListener('click', function(){
                                @if(Auth::check())
                                    document.forms["form-modal-delete"].action = $("#baseurl-del-reserv").val()+'/'+$("#id-del-reserv").val();
                                document.forms["form-modal-delete"].submit();
                                @else
                                    VERIF.verifForm('form-modal-delete-name',function(isOk){
                                    if(isOk)
                                    {
                                        document.forms["form-modal-delete"].action = $("#baseurl-del-reserv").val()+'/askcancellation/'+$("#id-del-reserv").val();
                                        document.forms["form-modal-delete"].submit();
                                    }
                                });
                                @endif
                            });

                        </script>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>