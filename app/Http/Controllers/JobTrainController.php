<?php

namespace App\Http\Controllers;

use App\Models\JobTrain;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobTrainController extends Controller
{
    public function index(Request $request)
    {
        $title = "Data JobTrain";

        $query = JobTrain::orderBy('kode', 'asc');
        if ($request->cari_jobtrain) {
            $query->where('nama', 'like', '%'.$request->cari_jobtrain.'%');
            $query->orWhere('kode', 'like', '%'.$request->cari_jobtrain.'%');
        }
        $jobtrain = $query->paginate(10);

        return view('admin.jobtrain.index', compact('title', 'jobtrain'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => 'required|unique:jobtrain,kode',
            'nama' => 'required|string|max:255',
        ]);

        $create = JobTrain::create($data);

        if ($create) {
            return to_route('admin.jobtrain')->with('success', 'Data JobTrain berhasil disimpan');
        } else {
            return to_route('admin.jobtrain')->with('error', 'Data JobTrain gagal disimpan');
        }
    }

    public function edit(Request $request)
    {
        $data = JobTrain::where('id', $request->id)->first();
        return $data;
    }

    public function update(Request $request)
    {
        $jobtrain = JobTrain::where('id', $request->id)->first();
        $data = $request->validate([
            'kode' => ['required', Rule::unique('jobtrain')->ignore($jobtrain)],
            'nama' => 'required|string|max:255',
        ]);

        $update = JobTrain::where('id', $request->id)->update($data);

        if ($update) {
            return to_route('admin.jobtrain')->with('success', 'Data JobTrain berhasil diperbarui');
        } else {
            return to_route('admin.jobtrain')->with('error', 'Data JobTrain gagal diperbarui');
        }
    }

    public function delete(Request $request)
    {
        $delete = JobTrain::where('id', $request->id)->delete();

        if ($delete) {
            return response()->json(['success' => true, 'message' => 'Data JobTrain Berhasil dihapus']);
        } else {
            return response()->json(['success' => false, 'message' => 'Data JobTrain Gagal dihapus']);
        }
    }
}
