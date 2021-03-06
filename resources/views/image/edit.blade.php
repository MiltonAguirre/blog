@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              Editar imagen
            </div>
            <div class="card-body">
              <form action="{{route('image.update')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                  <input type="hidden" name="image_id" value="{{$image->id}}">
                  <div class="col-md-1">
                    @if($image->user->image)
                      <div class="container-avatar">
                        <img src="{{route('image.file',['filename'=>$image->image_path]) }}" class="avatar">
                      </div>
                    @endif
                  </div>
                  <label class="col-md-3 col-form-label text-md-right" for="image_path">Cambiar imagen: </label>
                  <div class="col-md-7">
                    <input id="image_path" type="file" name="image_path" class="form-control {{$errors->has('image_path') ? 'is-invalid' : ''}}" >
                    @if($errors->has('image_path'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{$errors->first('image_path')}}</strong>
                      </span>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right" for="description">Descripción</label>
                  <div class="col-md-7">
                    <textarea id="description" name="description" class="form-control {{$errors->has('description') ? 'is-invalid' : ''}}" >{{$image->description}}</textarea>
                    @if($errors->has('description'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{$errors->first('description')}}</strong>
                      </span>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6 offset-md-4">
                    <input type="submit" class="btn btn-primary" value="Actualizar">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
