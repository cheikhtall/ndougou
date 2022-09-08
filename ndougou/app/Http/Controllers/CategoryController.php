<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function ajoutercategorie(){
        return view('admin.ajoutercategorie');
    }

    public function sauvercategorie(Request $request){

        $this->validate($request, [
            'category_name' => 'required|unique:categories',
        ]);

        $categorie = new Category();
        $categorie->category_name = $request->category_name;
        $categorie->save();

        return redirect('/ajoutercategorie')->with('status', 'Catégorie ajoutée avec success');
    }

    public function categories(){
        $categories =Category::all();
        return view('admin.categories', compact('categories'));
    }

    public function edit_categorie($id){
        $categorie = Category::find($id);
        return view('admin.editcategorie', compact('categorie'));
    }

    public function modifiercategorie(Request $request){
        $this->validate($request, [
            'category_name' => 'required|unique:categories',
        ]);
        $categorie = Category::find($request->id);
        $categorie->category_name = $request->category_name;
        $categorie->update();
        return redirect('/categories')->with('status', 'Modifications enregistrées');
    }

    public function supprimercategorie($id){
        $categorie = Category::find($id);
        $categorie->delete();
        return redirect('/categories')->with('status', 'suppression effectuée');
    }

}
