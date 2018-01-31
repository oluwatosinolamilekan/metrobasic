<?php

namespace App\Http\Controllers;

use Image;
use App\Usedpin;
use App\Post;
use App\Pin;
// use App\Image;
use Validator;
use Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
// use Storage;


class PostsController extends Controller
{
    //

     public function __construct()
    {
        $this->middleware('auth')->except(['index']);

    }

    public function adminindex()
    {
        return view('adminpages.index');
    }

    public function index()
    {
        return  view ('posts.index');
    }

    public function listcourses()
    {
        return view ('pages.listcourses');
    }
    public function show()
    {   
        $posts  = Post::latest()->get();
        $posts=Post::paginate(10);
        return  view ('posts.show',compact('posts',$posts));
    }

    public function list(Post $post)
    {
        return view ('posts.list');
    }

    public function enter()
    {

        return view ('posts.enter');
    }


    public function enterstore(Request $request)
    {

            //validate the form request and save
            // $validatedData = $request->validate([
            // 'random' => 'required',
               
            // ]);
            $validator = Validator::make($request->all(), [
                'random' => 'required',
    
                ]);
            
            $pin = Pin::where('numbers', $request->random)->first();
            if($pin)
            {
                // dd('true');
                return view('posts.create')->with('pin',$pin);
            }else{

                return view('partials.Epin');
            }


    }

    
    public function create()
 
    {
        return view('posts.create');
    }

        public function store(Request $request)
    {

            $pin = Pin::where('numbers', $request->random)->first();
            $usedPin = Usedpin::where('pin_id', $pin->id)->first();
            if(!$usedPin || $usedPin->status <= 3){
                
            //     $validatedData = $request->validate([
            //     'title' => 'required|max:255',
            //     'body' => 'required',
            // ]);
                    
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:255',
                'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'body' => 'required',
                ]);

                if( $request->hasFile('image') ) {
                        $image = $request->file('image');
                        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
    
                        $img = Image::make($image->getRealPath(),array(
                                    'width' => 100,
                                    'height' => 100,
                                    'grayscale' => false
                                ));
                            
                        $destinationPath = public_path();
                        // dd($destinationPath.'/images/'.$input['imagename']);
                        $img->save($destinationPath.'/images/'.$input['imagename']);
                            $destinationPath = public_path('images');
                            $image->move($destinationPath, $input['imagename']);
                        
                        }
        
                
                        $post = new Post;
                        $post->user_id=Auth::user()->id;
                        $post->post_id=Auth::user()->id;
                        $post->title = $request->title;
                        $post->body = $request->body;

                        // save images
                
                        // if($request->hasFile('file'))
                        // {
    
                        //     $path = Storage::putFile('avatars', $request->file('avatar'));

                        //     $image = $request->file('image');
                        //     $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
                        // $destinationPath = public_path('images');
                        //     $img = Image::make($image->getRealPath(),array(
                        //         'width' => 100,
                        //         'height' => 100,
                        //         'grayscale' => false
                        //     ));
                        //     $img->save($destinationPath.'/'.$input['imagename']);
                        //     $destinationPath = public_path('images');
                        //     $image->move($destinationPath, $input['imagename']);
                        
                        // }
    
                        
                             $post->save();

            // dd($request->all());       
            if(!$usedPin){
                $pin = Usedpin::create([
                    'user_id' => Auth::user()->id,
                    'post_id' => $post->id,
                    'pin_id' => $pin->id,
                    'status' => 1,
                ]);    
            }else{
                    // dd($usedPin);
                     $usedPin->status += 1;
                     $usedPin->save();
            }
          
            
            // dd('success');
            return view('partials.source');

        
            }else{
                // dd('error msg');
            return view ('partials.404');
                
            }
                
        
    }

    
    public function home()
    {
        return view('users.home');
    }
    public function reply(Post $post)
    {
        return view('posts.list', compact('post'));
    }

    // public function inbox(Request $request, $id)
    // {
    //     $posts = Post::find($id);
    //     $reply = Session::has('post') ? Session::get('post') : null;
    //     $newinbox = new Post($inbox);
    //     $newinbox->add($posts, $posts->$id);
    //     dd($request->all());
    //     $request->session()->put('post',$post);
    //     return redirect()->route('home');
 
    // }

    public function comments()
    {
        if(Auth::check()){
            $comment = Comment::create([
                'body' => $request->input('body'),
                'user_id' => Auth::user()->id
            ]);

            if($comment){
                return back()->with('success' , 'Comment created successfully');
            }

        }
        
            return back()->withInput()->with('errors', 'Error creating new comment');

    }

    




}
