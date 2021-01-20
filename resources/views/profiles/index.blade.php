@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-3 p-5">
            <img class="rounded-circle w-100" src="{{$user->profile->profileImage()}}">
        </div>

        <div class="col-9 pt-5">

        <div class="d-flex justify-content-between align-items-baseline">
            <div class="d-flex align-items-center pb-3">
                <div class="h4">{{$user->username}}</div>

                
            <follow-button user-id="{{$user->id}}" follows="{{$follows}}"></follow-button>
            </div>
            @can('update', $user->profile)
                <a href="{{route('post.create')}}">New Post</a>
            @endcan
        </div>
            @can('update', $user->profile)
                <a href="{{route('profile.edit', ['user'=>$user->id])}}">Edit Profile</a>
            @endcan
            <div class="d-flex">
            <div class="pr-3">{{$postCount}}<strong> post </strong></div>
                <div class="pr-3">{{$followersCount}}<strong> followers </strong></div>
                <div class="pr-3">{{$followingCount}}<strong> following </strong></div>
            </div>

            <div class="pt-4 font-weight-bold">{{$user->profile->title}}</div>
            <div>{{$user->profile->description}}</div>
            <div class="font-weight-bold"><a href="#">{{$user->profile->url ?? 'N/A'}}</a></div>
        </div>

    </div>

    <div class="row pt-4">

        @foreach($user->posts as $post)
            <div class="col-4 pb-4">
                <a href="{{route('post.show',['post'=>$post->id])}}">
                    <img src="/storage/{{$post->image}}" class="w-100" >
                </a>
            </div>
        @endforeach
    </div>

</div>
@endsection