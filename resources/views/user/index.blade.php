@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
          <h1>Personas en la red</h1>
          <br>
          <form class="" action="{{route('user.index')}}" method="get" id="buscador">
            <div class="row">
              <div class="form-group col">
                <input type="text" id="search" class="form-control">
              </div>
              <div class="form-group btn-search col">
                <input type="submit" class="btn btn-info" value="Buscar">
              </div>

            </div>
          </form>
          <hr>
          @include('includes.message')
          @foreach($users as $user)
          <div class="profile-user">
              @if($user->image)
                <div class="container-avatar">
                  <img src="{{route('user.avatar',['filename'=>$user->image]) }}" class="avatar">
                </div>
              @endif

            <div class="user-info">
              <h2>{{'@'.$user->nick}}</h2>
              <h3>{{$user->name.' '.$user->surname}}</h3>
              <p class="nickname date">{{'Se unió '.strtolower(\FormatTime::LongTimeFilter($user->created_at))}}</p>
              <a href="{{route('profile', ['id' => $user->id])}}" class="btn btn-success">Ver perfíl</a>
            </div>
            <div class="clearfix"></div>

            <hr>
          @endforeach
          <!--Pagination-->
          <div class="clearfix"></div>
          {{$users->links()}}
        </div>
    </div>
</div>
@endsection
