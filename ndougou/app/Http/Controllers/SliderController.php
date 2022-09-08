<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function ajouterslider(){
        return view('admin.ajouterslider');
    }

    public function sauverslider(Request $request){
        $this->validate($request, [
            'description1' => 'required',
            'description2' => 'required',
            'slider_image' => 'image|nullable|max:1999',
        ]);

        if($request->hasFile('slider_image')){
            //1 : get file name with ext
            $fileNameWithExt = $request->file('slider_image')->getClientOriginalName();
            // 2: get just file name
            $fileName =pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // 3: get just file ext
            $extension = $request->file('slider_image')->getClientOriginalExtension();
            // 4 : file name to store
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            // 5: uploader image
            $path = $request->file('slider_image')->storeAS('public/slider_images', $fileNameToStore);
        }
        else{
            $fileNameToStore = 'noimage.jpg';
        }

        $slider =  new Slider();
        $slider->description1 = $request->description1;
        $slider->description2 = $request->description2;
        $slider->slider_image = $fileNameToStore;
        $slider->status = 1;

        $slider->save();
        return redirect('/ajouterslider')->with('status', 'Slider crée avec succes');
    }

    public function sliders(){
        $sliders = Slider::all();
        return view('admin.sliders', compact('sliders'));
    }

    public function edit_slider($id){
        $slider = Slider::find($id);
        return view('admin.editslider', compact('slider'));
    }

    public function modifierslider(Request $request){

        $this->validate($request, [
            'description1' => 'required',
            'description2' => 'required',
            'slider_image' => 'image|nullable|max:1999',
        ]);

        $slider = Slider::find($request->id);
        $slider->description1 = $request->description1;
        $slider->description2 = $request->description2;

        if($request->hasFile('slider_image')){
            //1 : get file name with ext
            $fileNameWithExt = $request->file('slider_image')->getClientOriginalName();
            // 2: get just file name
            $fileName =pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // 3: get just file ext
            $extension = $request->file('slider_image')->getClientOriginalExtension();
            // 4 : file name to store
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            // 5: uploader image
            $path = $request->file('slider_image')->storeAS('public/slider_images', $fileNameToStore);
            if($slider->slider_image != 'noimage.jpg'){
                Storage::delete('public/slider_images/'.$slider->product_image);
            }
            $slider->slider_image = $fileNameToStore;
        }

        $slider->update();
        return redirect('/sliders')->with('status', 'Slider modifié avec succes');
    }

    public function supprimerslider($id){
        $slider = Slider::find($id);
        $slider->delete();
        return redirect('/sliders')->with('status', 'Supppression réussie');
    }

    public function desactiverslider($id){
        $slider = Slider::find($id);
        $slider->status = 0;
        $slider->update();
        return redirect('/sliders')->with('status', 'statut changé avec succes');
    }

    public function activerslider($id){
        $slider = Slider::find($id);
        $slider->status = 1;
        $slider->update();
        return redirect('/sliders')->with('status', 'statut changé avec succes');
    }
}
