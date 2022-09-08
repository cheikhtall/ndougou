@extends('layouts.appadmin')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row grid-margin">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Ajouter Catégorie</h4>
                    @if (Session::has('status'))
                        <div class="alert alert-success">
                            {{Session::get('status')}}
                        </div>
                    @endif
                    @if (count($errors)> 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                  <form class="cmxform" id="commentForm" method="post" action="/sauvercategorie">
                    {{csrf_field()}}
                    <fieldset>
                      <div class="form-group">
                        <label for="cname">Nom de la catégorie</label>
                        <input id="cname" class="form-control" name="category_name" minlength="2" type="text">
                      </div>
                      <input class="btn btn-primary" type="submit" value="Ajouter">
                    </fieldset>
                  </form>
                </div>
              </div>
            </div>
          </div>
    </div>
</div>

@endsection

@section('scripts')
    {{-- <script src="backend/js/form-validation.js"></script>
    <script src="backend/js/bt-maxLength.js"></script> --}}
@endsection
