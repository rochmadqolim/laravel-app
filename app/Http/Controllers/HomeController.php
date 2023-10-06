<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    
    public function index(Request $request){
        $data = new User;

        if($request->get('search')){
            $data = $data->where('name','LIKE','%'.$request->get('search').'%')
            ->orWhere('email','LIKE','%'.$request->get('search').'%');
        }

        $data =$data->get();

        return view('index', compact('data', 'request'));
    }

    public function dashboard(){

            return view('dashboard');
        
        return abort(403);
    }

    public function create(){
        return view('create');
    }

    public function store(Request $req){
        $validator = Validator::make($req->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'file' => 'required',

        ]);
        if($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $file = $req->file('file');
        $filename = date('Y-m-d').$file->getClientOriginalName();
        $path = 'file-user/'.$filename;

        Storage::disk('public')->put($path, file_get_contents($file));

        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password);
        $data['image'] = $filename;

        User::create($data);

        return redirect()->route('admin.index');
    }

    public function edit(Request $req, $id){
        $data = User::find($id);

        return view('edit', compact('data'));
    }

    public function update(Request $req, $id){
        $validator = Validator::make($req->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'nullable',
            'file' => 'required',

        ]);
        if($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);
        
        $data['name'] = $req->name;
        $data['email'] = $req->email;
        if($req->password){
            
            $data['password'] = Hash::make($req->password);
        }
        
        User::whereId($id)->update($data);
        
        return redirect()->route('index');
    }

    public function delete(Request $req, $id){
        $data = User::find($id);
    
        if($data){
            $data->delete();
        }
    
        return redirect()->route('admin.index');
    }
    
}