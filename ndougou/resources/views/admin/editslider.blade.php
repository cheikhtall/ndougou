@extends('layouts.appadmin')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row grid-margin">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Modifier Slider</h4>
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
                  <form class="cmxform" id="commentForm" method="post" action="/modifierslider" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <fieldset>
                        <div class="form-group">
                            <input id="cname" class="form-control" name="id" value="{{$slider->id}}" type="hidden">
                          </div>
                      <div class="form-group">
                        <label for="cname">Description One</label>
                        <input id="cname" class="form-control" name="description1" value="{{$slider->description1}}" type="text">
                      </div>
                      <div class="form-group">
                        <label for="cprice">Description Two</label>
                        <input id="cprice" class="form-control" type="text" name="description2" value="{{$slider->description2}}">
                      </div>
                      <div class="form-group">
                        <div class="custom-file">
                            <label for="cimage">Image</label>
                            <input type="file" class="form-control" id="customFile" name="slider_image">
                          </div>
                      </div>
                      <input class="btn btn-primary" type="submit" value="Modifier">
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
