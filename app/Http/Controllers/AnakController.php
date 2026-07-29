<?php

namespace App\Http\Controllers;

use App\Models\Anak;
use Illuminate\Http\Request;

class AnakController extends Controller
{
    public function ProfilAnak()
    {
        $dataAnak = Anak::all();

        return view('profil_anak', ['profil' => $dataAnak]);
    }

    public function TampilForm()
    {
        return view('tambah_anak');
    }

    public function SimpanAnak(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'pendidikan' => 'required',
            'umur' => 'required|numeric',
            'alamat' => 'required',
        ]);

        $anak = new Anak;
        $anak->nama = $request->nama;
        $anak->pendidikan = $request->pendidikan;
        $anak->umur = $request->umur;
        $anak->alamat = $request->alamat;
        $anak->save();

        return redirect()->route('profil.anak')->with('success', 'Data anak berhasil ditambahkan.');
    }

    public function HapusAnak($id)
    {
        $anak = Anak::find($id);
        if ($anak) {
            $anak->delete();
        }

        return redirect()->route('profil.anak')->with('success', 'Data anak berhasil dihapus.');
    }

    public function TampilFormEdit($id)
    {
        $anak = Anak::find($id);
        if ($anak) {
            return view('edit_anak', ['anak' => $anak]);
        }

        return redirect()->route('profil.anak');
    }

    public function UpdateAnak(Request $request, $id)
    {
        $anak = Anak::find($id);

        $request->validate([
            'nama' => 'required',
            'pendidikan' => 'required',
            'umur' => 'required|numeric',
            'alamat' => 'required',
        ]);
        if ($anak) {
            $anak->nama = $request->nama;
            $anak->pendidikan = $request->pendidikan;
            $anak->umur = $request->umur;
            $anak->alamat = $request->alamat;
            $anak->save();
        }

        return redirect()->route('profil.anak')->with('success', 'Data anak berhasil diperbarui.');
    }

    public function GetProfilApi()
    {
        $dataAnak = Anak::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data anak berhasil diambil',
            'data' => $dataAnak,
        ], 200);

    }
}
