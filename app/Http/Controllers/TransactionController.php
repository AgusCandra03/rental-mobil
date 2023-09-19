<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
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
        $transactions = Transaction::with('cars')->get();
        return view('admin.transaction', compact('cars'));
    }


    public function api(Request $request)
    {
        if($request->status){
            $transactions = Transaction::where('status',$request->status);
        }else {
            $transactions = Transaction::query();
        }
        
        $transactions->with('cars')->get();

        $datatables = datatables()->of($transactions)
        ->addColumn('status_sewa', function($row){
            if($row->status == 1){
                return 'Disewa';
            } else {
                return 'Telah Dikembalikan';
            }
        })
        ->addColumn('plat_mobil', function($row){
            return $row->cars->plat;
        })
        ->addColumn('lama_pinjam', function($row){
            $start = Carbon::parse($row->tgl_mulai);
            $finish = Carbon::parse($row->tgl_selesai);
            return $finish->diffInDays($start)." hari";
        })
        ->addColumn('biaya', function($row){
            $start = Carbon::parse($row->tgl_mulai);
            $finish = Carbon::parse($row->tgl_selesai);
            $durasi = $finish->diffInDays($start);
            $harga = $row->cars->sewa;
            
            return rupiah($harga * $durasi);
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
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
            'id_mobil' => 'required',
        ]);

        $transactions = new Transaction;
        $transactions->tgl_mulai = $request->tgl_mulai;
        $transactions->tgl_selesai = $request->tgl_selesai;
        $transactions->id_mobil = $request->id_mobil;
        $transactions->status = 1;
        $transactions->save();
        $cars = Car::find($request->id_mobil);
        $cars->ketersediaan = $cars->ketersediaan - 1;
        $cars->save();

        return redirect('returns');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->validate($request, [
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
            'id_mobil' => 'required',
            'status' => 'required'
        ]);

        $transaction->update($request->all());
        if($request->status == 2){
            $cars = Car::find($request->id_mobil);
            $cars->ketersediaan = $cars->ketersediaan + 1;
            $cars->save();
        }

        return redirect('transactions');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
    }
}
