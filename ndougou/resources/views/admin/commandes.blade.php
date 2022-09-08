@extends('layouts.appadmin')

@section('content')

    <div class="main-panel">
        <div class="content-wrapper">
            @if (Session::has('error'))
                <div class="alert alert-danger">
                    {{Session::get('error')}}
                    {{Session::put('error', null)}}
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Produits</h4>
                <div class="row">
                    <div class="col-12">
                    <div class="table-responsive">
                        <table id="order-listing" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom du client</th>
                                <th>Adresse</th>
                                <th>Panier</th>
                                <th>Payment ID</th>
                                <th>Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr>
                                <td>{{$order->id}}</td>
                                <td>{{$order->nom}}</td>
                                <td>{{$order->adresse}}</td>
                                <td>
                                    @foreach ($order->panier->items as $item)
                                    {{$item['product_name'].', '}}
                                    @endforeach
                                </td>
                                <td>{{$order->payment_id}}</td>
                                <td>
                                <button class="btn btn-outline-primary"  onclick="window.location='{{ url('/voir_pdf/'.$order->id) }}'">View</button>
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
