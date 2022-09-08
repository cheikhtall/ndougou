@extends('layouts.appadmin')

@section('content')

    <div class="main-panel">
        <div class="content-wrapper">

            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Catégories</h4>
                <div class="row">
                    <div class="col-12">
                    <div class="table-responsive">
                        @if (Session::has('status'))
                            <div class="alert alert-success">
                                {{Session::get('status')}}
                            </div>
                        @endif

                        <table id="order-listing" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom de la categorie</th>
                                <th>Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                            <tr>
                                <td>{{$category->id}}</td>
                                <td>{{$category->category_name}}</td>
                                <td>
                                <button class="btn btn-outline-primary" onclick="window.location='{{ url('/edit_categorie/'.$category->id) }}'">Edit</button>
                                {{-- <button class="btn btn-outline-danger">Delete</button> --}}
                                <a class="btn btn-outline-danger" id="delete" type="button" href="{{url('/supprimercategorie/'.$category->id)}}">Delete</a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
    <script src="backend/js/data-table.js"></script>
@endsection
