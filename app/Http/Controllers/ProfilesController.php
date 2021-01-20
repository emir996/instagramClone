<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Cache;

class ProfilesController extends Controller
{
    public function __construct(){
        //$this->middleware('auth');
    }
    public function index($user){

    $user = User::findOrFail($user);

    $postCount = Cache::remember('count.post' . $user->id, now()->addSeconds(30), function() use ($user){
        return $user->posts->count();
    });

    $followersCount = Cache::remember('count.followers' . $user->id, now()->addSeconds(30), function() use ($user){
        return $user->profile->followers->count();
    });
    
    $followingCount = Cache::remember('count.following' . $user->id, now()->addSeconds(30), function() use ($user){
        return $user->following->count();
    });

    $authUser = Auth::user();

    $follows = ($authUser) ? $authUser->following->contains($authUser->id) : false;

        return view('profiles.index', compact('user','follows', 'postCount', 'followersCount', 'followingCount'));
        
    }

    public function edit(User $user){
        $this->authorize('update', $user->profile);
        
        return view('profiles.edit', compact('user'));
    }

    /**
    * @param Request data
    * @param User model
    */
    public function update(Request $request, User $user){

        $this->authorize('update', $user->profile);
        
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => ''
        ]);

        if($request['image']){
            $imagePath = request('image')->store('profile', 'public');

            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000,1000);
            $image->save();

            $imageArr = ['image' => $imagePath];
        }

        auth()->user()->profile->update(array_merge($data, $imageArr ?? []));

        return redirect()->route('profile.index',[$user->id]);
    }
}
