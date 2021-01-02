<?php

namespace App\Http\Controllers;

use App\Catatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatatanController extends Controller
{
    public function __construct()
    {
        $this->page = [
            'active' => 'catatan'
        ];
    }

    public function index()
    {
        $catatans = Catatan::paginate(10);

        return view('catatan.index', [
            'page' => $this->page,
            'catatans' => $catatans
        ]);
    }

    public function destroy($id)
    {
        $catatan = Catatan::findOrFail($id);

        try {
            $catatan->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors($th->getMessage());
        }

        return redirect()->back()->with('message', 'Berhasil Dihapus');
    }

    public function total()
    {
        $total = DB::select(DB::raw("SELECT jenis_sampahs.jenis, SUM(berat) AS berat FROM catatans LEFT JOIN jenis_sampahs ON catatans.jenis_sampah_id = jenis_sampahs.id GROUP BY jenis_sampahs.jenis"));

        // dd($total);
        $peringkat = DB::select(DB::raw("SELECT users.name, SUM(berat) AS total FROM users LEFT JOIN catatans ON users.id = catatans.user_id WHERE users.role = 3 GROUP BY users.name ORDER BY total DESC LIMIT 5"));

        // dd($peringkat);
    }
}
