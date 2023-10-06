<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DataTableController extends Controller
{
    
    public function client(Request $request){
        $data = new User;

        if($request->get('search')){
            $data = $data->where('name','LIKE','%'.$request->get('search').'%')
            ->orWhere('email','LIKE','%'.$request->get('search').'%');
        }

        $data =$data->get();

        return view('datatable.client', compact('data', 'request'));
    }

    public function server(Request $request){
        if($request->ajax()){

            $data = new User;
            $data = $data->latest();
            
            return DataTables::of($data)
            ->addColumn('no',function($data){
                return 'ini nomor';
            })
            ->addColumn('file',function($data){
                return '<img src="'.asset('storage/file-user/'.$data->image).'" alt="" width="100">
                ';
            })
            ->addColumn('name',function($data){
                return $data->name;
            })
            ->addColumn('email',function($data){
                return $data->email;
            })
            ->addColumn('action',function($data){
                return '<a href="'.route('admin.user.edit', ['id'=> $data->id]).'"
                class="btn btn-primary"><i class="fas fa-pen"> Edit</i></a>
            <a data-toggle="modal" data-target="#modal-hapus'.$data->id.'"
                class="btn btn-danger"><i class="fas fa-trash-alt">
                    Delete</i></a>';
            })
            ->rawColumns(['file', 'action'])
            ->make(true);
        }

        return view('datatable.server', compact('request'));
    }

}