<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Quiz;
use App\Models\Surat;
use App\Models\Juz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminLencanaController extends Controller
{
    public function index()
    {
        $badges = Badge::with(['quiz', 'surat', 'juz'])->orderBy('created_at', 'desc')->get();
        return view('admin.lencana.index', compact('badges'));
    }

    public function create()
    {
        $quizzes = Quiz::where('is_active', true)->orderBy('title')->get();
        $surats = Surat::orderBy('nomor_surat')->get();
        $juzs = Juz::orderBy('nomor')->get();

        return view('admin.lencana.create', compact('quizzes', 'surats', 'juzs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'criteria_type' => 'required|in:poin,quiz_selesai,nilai_sempurna,hafalan,juz_selesai',
            'criteria_value' => 'required|integer|min:1',
            'quiz_id' => 'nullable|exists:quizzes,id',
            'surat_id' => 'nullable|exists:surats,id',
            'juz_id' => 'nullable|exists:juz,id', // <- nama tabel 'juz'
            'is_active' => 'boolean',
            'level' => 'required|in:bronze,silver,gold,platinum',
            'required_points' => 'nullable|integer|min:0',
        ]);

        $data = $request->except(['image', '_token']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'badge_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/badges', $fileName);
            $data['image'] = $fileName;
        }

        $data['is_active'] = $request->has('is_active');
        $data['required_points'] = $request->required_points ?? 0;

        Badge::create($data);

        return redirect()->route('admin.lencana.index')
            ->with('success', 'Lencana berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $badge = Badge::findOrFail($id);
        $quizzes = Quiz::where('is_active', true)->orderBy('title')->get();
        $surats = Surat::orderBy('nomor_surat')->get();
        $juzs = Juz::orderBy('nomor')->get();

        return view('admin.lencana.edit', compact('badge', 'quizzes', 'surats', 'juzs'));
    }

    public function update(Request $request, $id)
    {
        $badge = Badge::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'criteria_type' => 'required|in:poin,quiz_selesai,nilai_sempurna,hafalan,juz_selesai',
            'criteria_value' => 'required|integer|min:1',
            'quiz_id' => 'nullable|exists:quizzes,id',
            'surat_id' => 'nullable|exists:surats,id',
            'juz_id' => 'nullable|exists:juz,id', // <- nama tabel 'juz'
            'is_active' => 'boolean',
            'level' => 'required|in:bronze,silver,gold,platinum',
            'required_points' => 'nullable|integer|min:0',
        ]);

        $data = $request->except(['image', '_token', '_method']);

        if ($request->hasFile('image')) {
            if ($badge->image && Storage::exists('public/badges/' . $badge->image)) {
                Storage::delete('public/badges/' . $badge->image);
            }
            $file = $request->file('image');
            $fileName = 'badge_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/badges', $fileName);
            $data['image'] = $fileName;
        }

        $data['is_active'] = $request->has('is_active');
        $data['required_points'] = $request->required_points ?? 0;

        $badge->update($data);

        return redirect()->route('admin.lencana.index')
            ->with('success', 'Lencana berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $badge = Badge::findOrFail($id);
        if ($badge->image && Storage::exists('public/badges/' . $badge->image)) {
            Storage::delete('public/badges/' . $badge->image);
        }
        $badge->delete();

        return redirect()->route('admin.lencana.index')
            ->with('success', 'Lencana berhasil dihapus!');
    }

    public function toggleActive($id)
    {
        $badge = Badge::findOrFail($id);
        $badge->is_active = !$badge->is_active;
        $badge->save();

        return back()->with('success', 'Status lencana berhasil diubah.');
    }
}