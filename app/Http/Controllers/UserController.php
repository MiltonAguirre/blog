<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\User;


class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function config(){
      return view ('user.config');
    }

    public function update(Request $request){
      $user = \Auth::user();
      $id = $user->id;
      $validate = $this->validate($request,[
        'name' => ['required', 'string', 'max:255'],
        'surname' => ['required', 'string', 'max:255'],
        'nick' => ['required', 'string', 'max:255', 'unique:users,nick,'.$id],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
      ]);

      $name = $request->input('name');
      $surname = $request->input('surname');
      $nick = $request->input('nick');
      $email = $request->input('email');

      $user->name = $name;
      $user->surname = $surname;
      $user->nick = $nick;
      $user->email = $email;
      //Upload image
      $image_path = $request->file('image_path');
      if($image_path){
        //Unique name
        $image_path_name = time().$image_path->getClientOriginalName();
        //Save file on disk 'user'
        Storage::disk('users')->put($image_path_name, File::get($image_path));
        //Set name image in objet user
        $user->image = $image_path_name;
      }
      $user->update();
      return redirect()->route('config')
                      ->with(['message'=>'Se actualizo el usuario correctamente']);
    }

    public function getImage($filename){
      $file = Storage::disk('users')->get($filename);
      return new Response($file,200);
    }

    public function profile($id){
      $user = User::find($id);

      return view('user.profile', ['user' => $user]);
    }
}
