<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::all();
        return view('admin.car', compact('cars'));
    }

    public function api(Request $request)
    {
        if($request->ketersediaan){
            $cars = Car::where('ketersediaan', $request->ketersediaan);
        } else {
            $cars = Car::query();
        }
        $datatables = datatables()->of($cars)
        ->addColumn('status', function($row){
            if($row->ketersediaan == 2){
                return 'Tersedia';
            } else {
                return 'Tidak Tersedia';
            }
        })
        ->addColumn('harga', function($row){
            return rupiah($row->sewa);
        })
        ->addIndexColumn();

        return $datatables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'merk' => 'required',
            'model' => 'required',
            'plat' => 'required',
            'sewa' => 'required',
        ]);

        $cars = new Car;
        $cars->merk = $request->merk;
        $cars->model = $request->model;
        $cars->plat = $request->plat;
        $cars->sewa = $request->sewa;
        $cars->ketersediaan = 2;
        $cars->save();

        return redirect('cars');
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        $this->validate($request, [
            'merk' => 'required',
            'model' => 'required',
            'plat' => 'required',
            'sewa' => 'required',
        ]);

        $car->update($request->all());

        return redirect('cars');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        $car->delete();
    }
}
