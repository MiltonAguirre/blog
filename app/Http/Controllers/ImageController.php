<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Image;
use App\Comment;
use App\Like;

class ImageController extends Controller
{
  public function __construct(){
      $this->middleware('auth');
  }

  public function create(){
    return view('image.create');
  }

  public function save(Request $request){
    //Validation
    $validate = $this->validate($request, [
      'description' => 'required',
      'image_path' => 'required|image'
    ]);

    $image_path = $request->file('image_path');
    $description = $request->input('description');

    $user = \Auth::user();
    $image = new Image;
    $image->user_id = $user->id;
    $image->description = $description;
    //upload file
    if ($image_path) {
      $image_path_name = time().$image_path->getClientOriginalName();
      Storage::disk('images')->put($image_path_name, File::get($image_path));
      $image->image_path = $image_path_name;
    }
    $image->save();
    return redirect()->route('home')->with(['message'=> 'La imagen ha sido subida correctamente']);
  }

  public function getImage($filename){
    $file = Storage::disk('images')->get($filename);
    return new Response($file, 200);
  }

  public function detail($id){
    $image = Image::find($id);
    return view('image.detail',[
      'image'=>$image
    ]);
  }

  public function delete($id){
    $user = \Auth::user();
    $image = Image::find($id);
    $comments = Comment::where('image_id', $image->id)->get();
    $likes = Like::where('image_id', $image->id)->get();

    if($user && $image && $image->user->id == $user->id){
      //Delete comments
      if($comments && count($comments)>=1){
        foreach ($comments as $comment) {
          $comment->delete();
        }
      }
      //Delete likes
      if($likes && count($likes)>=1){
        foreach ($likes as $like) {
          $like->delete();
        }
      }
      //Delete image files
      Storage::disk('images')->delete($image->image_path);
      //Delete register of database
      $image->delete();

      $message = array('message' => 'Se ha eliminado la imagen correctamente.');

    }else{
      $message = array('message' => 'No se ha logrado eliminar la imagen.');
    }

    return redirect()->route('home')->with($message);
  }

  public function edit($id){
    $user = \Auth::user();
    $image = Image::find($id);
    if($user && $image && $image->user->id == $user->id){
      return view('image.edit', ['image'=>$image]);
    }else{
      return redirect(route('home'));
    }
  }

  public function update(Request $request){
    //Validation
    $validate = $this->validate($request, [
      'description' => 'required',
      'image_path' => 'image',
      'image_id' => 'required'
    ]);

    $image_id = $request->input('image_id');
    $description = $request->input('description');
    $image_path = $request->file('image_path');

    $image = Image::find($image_id);
    $image->description = $description;

    //upload file
    if ($image_path) {
      $image_path_name = time().$image_path->getClientOriginalName();
      Storage::disk('images')->put($image_path_name, File::get($image_path));
      $image->image_path = $image_path_name;
    }

    $image->update();

    return redirect()->route('image.detail', ['id' => $image_id])->with(['message' => 'Ha actualizado su foto']);
  }

}
