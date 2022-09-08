<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Models\Slider;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Client;

use App\Mail\SendMail;

use App\Cart;
use Stripe\Charge;
use Stripe\Stripe;

class ClientController extends Controller
{
    public function home(){
        $sliders = Slider::where('status', 1)->get();
        $products = Product::where('status', 1)->get();
        return view('client.index', compact('sliders', 'products'));
    }

    public function shop(){
        $categories = Category::all();
        $products = Product::where('status', 1)->get();
        return view('client.shop', compact('categories', 'products'));
    }

    public function ajouter_au_panier($id){
        $product = Product::find($id);

        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);
        Session::put('cart', $cart);

        //dd(Session::get('cart'));
        return redirect('/shop');
    }

    public function retirer_produit($id) {
        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);

        if(count($cart->items) > 0){
            Session::put('cart', $cart);
        }
        else{
            Session::forget('cart');
        }

        //dd(Session::get('cart'));
        return redirect('/panier');
    }

    public function panier(){
        if(!Session::has('cart')){
            return view('client.cart');
        }

        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        return view('client.cart', ['products' => $cart->items]);
    }

    public function modifier_qty($id, Request $request){
        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->updateQty($request->id, $request->quantity);
        Session::put('cart', $cart);

        //dd(Session::get('cart'));
        return redirect('/panier');
    }

    public function paiement(){
        if(!Session::has('client')){
            return view('client.login');
        }
        if(!Session::has('cart')){
            return view('client.cart');
        }
        return view('client.checkout');
    }

    public function payer(Request $request){
        if(!Session::has('cart')){
            return view('client.cart');
        }
        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);

        Stripe::setApiKey('sk_test_51LSL5IKiqgkEEbV6xXEAcrMwopsKkLkovtKSkA5QEIVskIoWwVdx6UVR3M6YKdbnKKnFDDTF5fqIW9Q5hZYhnwN2007jtjk2Oi');

        try{

            $charge = Charge::create(array(
                "amount" => $cart->totalPrice * 100,
                "currency" => "usd",
                "source" => $request->input('stripeToken'), // obtainded with Stripe.js
                "description" => "Test Charge"
            ));

            $order = new Order();
            $order->nom = $request->input('name');
            $order->adresse = $request->adresse;
            $order->panier = serialize($cart);
            $order->payment_id = $charge->id;

            $order->save();

            $orders = Order::where('payment_id', $charge->id)->get();
            $orders->transform(function($order, $key){
                $order->panier = unserialize($order->panier);

                return $order;
            });

            $email = Session::get('client')->email;
                Mail::to($email)->send(new SendMail($orders));

        } catch(\Exception $e){
            Session::put('error', $e->getMessage());
            return redirect('/paiement');
        }

        Session::forget('cart');
        Session::put('status', 'Purchase accomplished successfully !');
        return redirect('/panier');
    }

    public function select_cat($name){
        $categories = Category::all();
        $products = Product::where('product_category', $name)->where('status', 1)->get();

        return view('client.shop', compact('categories', 'products'));
    }

    public function creer_compte(Request $request){
        $this->validate($request, [
            'email' => 'email|required|unique:clients',
            'password' => 'required|min:4',
        ]);

        $client = new Client();
        $client->email = $request->input('email');
        $client->password = bcrypt($request->input('password'));

        $client->save();
        return back()->with('status', 'compe créé avec succes');
    }

    public function acceder_compte(Request $request){
        $this->validate($request, [
            'email' => 'email|required',
            'password' => 'required',
        ]);

        $client = Client::where('email', $request->input('email'))->first();

        if($client){
            if(Hash::check($request->input('password'), $client->password)){
                Session::put('client', $client);
                return redirect('/shop');
            }
            else{
                return back()->with('status', 'email ou mot de passe incorrect');
            }
        }
        else {
            return back()->with('status', 'ce compte est inexistant');
        }
    }

    public function logout(){
        Session::forget('client');
        return back();
    }

    public function client_login(){
        return view('client.login');
    }

    public function signup(){
        return view('client.signup');
    }

}
