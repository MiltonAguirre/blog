<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
  public function save(Request $request)
  {//Validation
    $validate = $this->validate($request, [
      'image_id' => 'int|required',
      'content' => 'string|required'
    ]);
    //recoger datos
    $user = \Auth::user();

    $image_id = $request->input('image_id');
    $content = $request->input('content');

    $comment = new \App\Comment();
    $comment->user_id = $user->id;
    $comment->image_id = $image_id;
    $comment->content = $content;

    $comment->save();

    return redirect ()->route('image.detail', ['id'=>$image_id])
                      ->with(['message' => "Has publicado un comentario!"]);

  }
  public function delete($id)
  {
    $user = \Auth::user();
    $comment = \App\Comment::find($id);
    $image_id = $comment->image->id;
    if($user && ($user->id == $comment->user_id || $user->id == $comment->image->user_id)){
      $comment->delete();
      return redirect ()->route('image.detail', ['id'=>$image_id])
                        ->with(['message' => "El comentario ha sido eliminado!"]);
    }else{
      return redirect ()->route('image.detail', ['id'=>$image_id])
                        ->with(['message' => "No es posible eliminar el commentario"]);
    }
  }
}
