<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function ajouterproduit(){
        $categories = Category::all();
        return view('admin.ajouterproduit')->with('categories', $categories);
    }

    public function sauverproduit(Request $request){
        $this->validate($request,[
            'product_name' => 'required|unique:products',
            'product_price' => 'required',
            'product_category'=> 'required',
            'product_image' => 'image|nullable|max:1999',
        ]);

        if($request->hasFile('product_image')){
            //1 : get file name with ext
            $fileNameWithExt = $request->file('product_image')->getClientOriginalName();
            // 2: get just file name
            $filaName =pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // 3: get just file ext
            $extension = $request->file('product_image')->getClientOriginalExtension();
            // 4 : file name to store
            $fileNameToStore = $filaName.'_'.time().'.'.$extension;
            // 5: uploader image
            $path = $request->file('product_image')->storeAS('public/product_images', $fileNameToStore);
        }
        else{
            $fileNameToStore = 'noimage.jpg';
        }
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->product_price = $request->product_price;
        $product->product_category = $request->product_category;
        $product->product_image = $fileNameToStore;
        $product->status = 1;
        $product->save();
        return redirect('/ajouterproduit')->with('status', 'Produit crée avec succes');
    }

    public function produits(){
        $produits = Product::all();
        return view('admin.produits', compact('produits'));
    }

    public function edit_produit($id){
        $categories = Category::get();
        $product = Product::find($id);
        return view('admin.editproduit', compact('product', 'categories'));
    }

    public function modifierproduit(Request $request){
        $this->validate($request,[
            'product_name' => 'required',
            'product_price' => 'required',
            'product_category'=> 'required',
            'product_image' => 'image|nullable|max:1999',
        ]);

        $product = Product::find($request->id);
        $product->product_name = $request->product_name;
        $product->product_price = $request->product_price;
        $product->product_category = $request->product_category;

        if($request->hasFile('product_image')){
            //1 : get file name with ext
            $fileNameWithExt = $request->file('product_image')->getClientOriginalName();
            // 2: get just file name
            $filaName =pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // 3: get just file ext
            $extension = $request->file('product_image')->getClientOriginalExtension();
            // 4 : file name to store
            $fileNameToStore = $filaName.'_'.time().'.'.$extension;
            // 5: uploader image
            $path = $request->file('product_image')->storeAS('public/product_images', $fileNameToStore);

            if($product->product_image != 'noimage.jpg'){
                Storage::delete('public/product_images/'.$product->product_image);
            }
            $product->product_image = $fileNameToStore;
        }

        $product->update();
        return redirect('/produits')->with('status', 'Produit modifié avec succes');

    }

    public function supprimerproduit($id){
        $produit = Product::find($id);
        $produit->delete();
        return redirect('/produits')->with('status', 'suppression effectuée');
    }

    public function desactiverproduit($id){
        $product  = Product::find($id);
        $product->status = 0;
        $product->update();
        return redirect('/produits')->with('status', 'statut modiifié avec succes');
    }

    public function activerproduit($id){
        $product  = Product::find($id);
        $product->status = 1;
        $product->update();
        return redirect('/produits')->with('status', 'statut modiifié avec succes');
    }

}
