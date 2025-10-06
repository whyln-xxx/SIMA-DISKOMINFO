<?php

namespace App\Http\Controllers;

use App\Models\JobTrain;
use App\Models\PesertaMagang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PesertaMagangController extends Controller
{
    public function index()
    {
        $title = "Profile";
        $peserta_magang = PesertaMagang::where('npm', auth()->guard('peserta_magang')->user()->npm)->first();
        return view('dashboard.profile.index', compact('title', 'peserta_magang'));
    }

    public function update(Request $request)
    {
        $peserta_magang = PesertaMagang::where('npm', auth()->guard('peserta_magang')->user()->npm)->first();

        if ($request->hasFile('foto')) {
            $foto = $peserta_magang->npm . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $peserta_magang->foto;
        }

        if ($request->password != null) {
            $update = PesertaMagang::where('npm', auth()->guard('peserta_maganng')->user()->npm)->update([
                'nama_lengkap' => $request->nama_lengkap,
                'telepon' => $request->telepon,
                'password' => Hash::make($request->password),
                'foto' => $foto,
                'updated_at' => Carbon::now(),
            ]);

        } elseif ($request->password == null) {
            $update = PesertaMagang::where('npm', auth()->guard('peserta_maganng')->user()->npm)->update([
                'nama_lengkap' => $request->nama_lengkap,
                'telepon' => $request->telepon,
                'foto' => $foto,
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/unggah/peserta_maganng/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return redirect()->back()->with('success', 'Profile updated successfully');
        } else {
            return redirect()->back()->with('error', 'Profile updated failed');
        }
    }

    public function indexAdmin(Request $request)
    {
        $title = "Data PesertaMagang";

        $jobtrain = PesertaMagang::get();

        $query = PesertaMagang::join('jobtrain as d', 'peserta_magang.jobtrain_id', '=', 'd.id')->select('peserta_magang.*', 'd.kode')->orderBy('d.kode', 'asc')->orderBy('peserta_magang.nama_lengkap', 'asc');
        if ($request->nama_peserta_magang) {
            $query->where('peserta_magang.nama_lengkap', 'like', '%'.$request->nama_peserta_magang.'%');
        }
        if ($request->kode_jobtrain) {
            $query->where('d.kode', 'like', '%'.$request->kode_jobtrain.'%');
        }
        $peserta_magang = $query->paginate(10);

        return view('admin.peserta_magang.index', compact('title', 'peserta_magang', 'jobtrain'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'npm' => 'required|unique:peserta_magang,npm',
            'jobtrain_id' => 'required',
            'nama_lengkap' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jurusan' => 'required|string|max:255',
            'telepon' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:peserta_magang,email',
            'password' => 'required',
        ]);
        $data['password'] = Hash::make($data['password']);
        if ($request->hasFile('foto')) {
            $foto = $request->npm . "." . $request->file('foto')->getClientOriginalExtension();
        }

        $create = PesertaMagang::create($data);

        if ($create) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/unggah/peserta_magang/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return to_route('admin.peserta_magang')->with('success', 'Data Peserta Maganng berhasil disimpan');
        } else {
            return to_route('admin.peserta_magang')->with('error', 'Data Peserta Magang gagal disimpan');
        }
    }

    public function edit(Request $request)
    {
        $data = PesertaMagang::where('npm', $request->npm)->first();
        return $data;
    }

    public function updateAdmin(Request $request)
    {
        $peserta_magang = PesertaMagang::where('npm', $request->npm_lama)->first();
        $data = $request->validate([
            'npm' => ['required', Rule::unique('PesertaMagang')->ignore($peserta_magang)],
            'jobtrain_id' => 'required',
            'nama_lengkap' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jurusan' => 'required|string|max:255',
            'telepon' => 'required|string|max:15',
            'email' => ['required', 'email', Rule::unique('peserta_magang')->ignore($peserta_magang)],
        ]);
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->npm . "." . $request->file('foto')->getClientOriginalExtension();
        }

        $update = PesertaMagang::where('npm', $request->npm_lama)->update($data);

        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/unggah/peserta_magang/";
                $request->file('foto')->storeAs($folderPath, $data['foto']);
            }
            return to_route('admin.peserta_magang')->with('success', 'Data Peserta Magang berhasil diperbarui');
        } else {
            return to_route('admin.peserta_magang')->with('error', 'Data Peserta Magang gagal diperbarui');
        }
    }

    public function delete(Request $request)
    {
        $data = PesertaMagang::where('npm', $request->npm)->first();
        $delete = PesertaMagang::where('npm', $request->npm)->delete();
        if ($delete && $data->foto) {
            $folderPath = "public/unggah/peserta_magang/";
            Storage::delete($folderPath . $data->foto);
        }

        if ($delete) {
            return response()->json(['success' => true, 'message' => 'Data Peserta Magang Berhasil dihapus']);
        } else {
            return response()->json(['success' => false, 'message' => 'Data Peserta Magang Gagal dihapus']);
        }
    }
}
