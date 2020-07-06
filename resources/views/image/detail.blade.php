@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
          @include('includes.message')
            <div class="card pub_image pub_image_detail">
                <div class="card-header">
                  @if($image->user->image)
                  <div class="container-avatar">
                    <img src="{{route('user.avatar',['filename'=>$image->user->image]) }}" class="avatar">
                  </div>
                  @endif
                  <div class="data-user">
                    {{$image->user->name.' '.$image->user->surname}}
                    <span class="nickname">{{' | @'.$image->user->nick}}</span>
                  </div>

                </div>

                <div class="card-body">
                  <div class="image-container image-detail">
                    <img src="{{route('image.file', ['filename' => $image->image_path])}}" >
                  </div>
                  <div class="description">
                    <span class="nickname">{{'@'.$image->user->nick}}</span>
                    <span class="nickname date">{{' | '.\FormatTime::LongTimeFilter($image->created_at)}}</span>

                    <p>{{$image->description}}</p>
                  </div>
                  <div class="likes">
                    <?php $user_like = false; ?>
                    @foreach ($image->likes as $like)
                      @if($like->user->id == Auth::user()->id)
                        <?php $user_like = true; ?>
                      @endif
                    @endforeach
                    @if($user_like)
                      <img src="{{asset('img/heart-red.png')}}" data-id="{{$image->id}}" class="btn-dislike">
                    @else
                      <img src="{{asset('img/heart-black.png')}}" data-id="{{$image->id}}" class="btn-like">
                    @endif
                    <span class="number-likes">{{count($image->likes)}}</span>

                  </div>
                  <div class="clearfix"></div>
                  <div class="comments">
                    <h4>Comentarios({{count($image->comments)}})</h2>
                    <hr>
                    <form action="{{route('comment.save')}}" method="post">
                      @csrf
                      <input type="hidden" name="image_id" value="{{$image->id}}" required>
                      <p>
                        <textarea class="form-control {{$errors->has('content') ? 'is-invalid' : ''}}" name="content" ></textarea>
                        @if($errors->has('content'))
                          <span class="invalid-feedback" role="alert">
                            <strong>{{$errors->first('content')}}</strong>
                          </span>
                        @endif
                      </p>
                      <button type="submit" class="btn btn-success">comentar</button>
                    </form>
                    <hr>
                    @foreach($image->comments as $comment)
                      <div class="comment">

                          <span class="nickname">{{'@'.$comment->user->nick}}</span>
                          <span class="nickname date">{{' | '.\FormatTime::LongTimeFilter($comment->created_at)}}</span>

                          <p>{{$comment->content}} <br>
                          @if(Auth::check() && (Auth::user()->id == $comment->user_id || Auth::user()->id == $comment->image->user_id))
                            <a href="{{ route('comment.delete', ['id' => $comment->id]) }}" class="btn btn-sm btn-danger">
                              Eliminar
                            </a>
                          @endif
                          </p>
                      </div>
                    @endforeach
                  </div>
                </div>
            </div>

    </div>
</div>
@endsection
