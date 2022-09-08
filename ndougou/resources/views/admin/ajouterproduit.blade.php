@extends('layouts.appadmin')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row grid-margin">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Ajouter Produit</h4>
                        @if (count($errors)> 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (Session::has('status'))
                            <div class="alert alert-success">
                                {{Session::get('status')}}
                            </div>
                        @endif
                  <form class="cmxform" id="commentForm" method="post" enctype="multipart/form-data" action="/sauverproduit">
                    {{csrf_field()}}
                    <fieldset>
                        <div class="form-group">
                            <label for="cname">Nom du produit</label>
                            <input id="cname" class="form-control" name="product_name" type="text">
                        </div>
                        <div class="form-group">
                            <label for="cprice">Prix</label>
                            <input id="cprice" class="form-control" type="number" name="product_price">
                        </div>

                        <div class="form-group align-items-center">
                            <label for="inlineFormCustomSelect">Catégorie du produit</label>
                            <select class="form-control" id="inlineFormCustomSelect" name="product_category">
                                @foreach ($categories as $categorie )
                                    <option value="{{$categorie->category_name}}">{{$categorie->category_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <label for="cimage">Image</label>
                                <input type="file" class="form-control"  name="product_image">
                            </div>
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
