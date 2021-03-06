<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Sale;
use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $clients = DB::table('clients')
                    ->where('status','A')
                    ->leftJoin('people', 'people.dpi', '=', 'clients.dpi')
                    //->leftJoin('addresses', 'addresses.id', '=', 'people.id_address')
                    ->leftJoin('departments', 'departments.id', '=', 'people.id_department')
                    ->leftJoin('townships', 'townships.id', '=', 'people.id_township')
                    ->select(
                        'clients.id as id', 
                        'clients.dpi as dpi',
                        'people.name as name', 
                        'people.last_name as last_name', 
                        'people.age as age',
                        'people.nit as nit',
                        'people.address as address', 
                        'departments.name as department', 
                        'townships.name as township',
                        //'clients.status as status'
                    )->get();

        return $clients;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'dpi' => 'required|max:13',
            'name' => 'required|string',
            'last_name' => 'required|string',
            'nit' => 'required|string|max:10',
            'age' => 'required|integer',
            'address' => 'required|string',
            'department' => 'required|int',
            'township' => 'required|int'
        ]);

        if($validator->fails()) {
            return response($validator->errors(),400);
        }
        
        try {

            $people = People::create([
                'dpi' => $request->get('dpi'),
                'name' => $request->get('name'),
                'last_name' => $request->get('last_name'),
                'nit' => $request->get('nit'),
                'age' => $request->get('age'),
                'address' => $request->get('address'),
                'id_department' => intval($request->get('department')),
                'id_township' => intval($request->get('township'))
            ]);

            $client = Client::create([
                'dpi' => $request->get('dpi'),
                'status' => 'A'
            ]);

            return response([
                'message' => 'Clientre registrado',
                'data' => $people
            ], 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'message' => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $client = Client::where('clients.id', $id)
                    ->leftJoin('people', 'people.dpi', '=', 'clients.dpi')
                    //->leftJoin('addresses', 'addresses.id', '=', 'people.id_address')
                    ->leftJoin('departments', 'departments.id', '=', 'people.id_department')
                    ->leftJoin('townships', 'townships.id', '=', 'people.id_township')
                    ->select(
                        'clients.id as id', 
                        'clients.dpi as dpi',
                        'people.name as name', 
                        'people.last_name as last_name', 
                        'people.age as age',
                        'people.nit as nit',
                        'people.address as address', 
                        'departments.name as department', 
                        'townships.name as township',
                        'departments.id as id_department',
                        'townships.id as id_township',
                        //'clients.status as status'
                    )->get();
        
        return response($client[0],200);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        
        $query = [
            'people.name' => $request->get('name'),
            'people.last_name' => $request->get('last_name'),
            'people.age' => $request->get('age'),
            'people.nit' => $request->get('nit'),
            'people.address' => $request->get('address'),
            'people.id_department' => $request->get('department'),
            'people.id_township' => $request->get('township'),
            //'clients.status' => $request->get('status')
        ];


        $people = DB::table('clients')
                    ->where('id', $id)
                    ->leftJoin('people', 'people.dpi', '=', 'clients.dpi')
                    // ->leftJoin('addresses', 'addresses.id', '=', 'people.id_address')
                    //->leftJoin('departments', 'departments.id', '=', 'people.id_department')
                    //->leftJoing('townships', 'townships.id', '=', 'people.id_township')
                    ->update($query);
        
        return response([
            'message' => 'Datos actualizados',
            'data' => $people
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $client = DB::table('clients')
                    ->where('id', $id)
                    ->update(['status' => 'I']);
        
        return response([
            'message' => 'Registro eliminado'
        ], 200);
    }
}
