<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function index()
    {
        $rules = Rule::latest()->get();
        return view('rules.index', compact('rules'));
    }

    public function create()
    {
        return view('rules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
        ]);

        $rule = Rule::create($request->all());

        // LOG ACTIVITY — mengikuti format abang
        logActivity(
            'create_rule',
            'Admin membuat aturan baru: '.$rule->judul,
            $rule->id
        );

        return redirect()->route('rules.index')->with('success', 'Aturan berhasil dibuat!');
    }

    public function show(Rule $rule)
    {
        return view('rules.show', compact('rule'));
    }

    public function edit(Rule $rule)
    {
        return view('rules.edit', compact('rule'));
    }

    public function update(Request $request, Rule $rule)
    {
        $rule->update($request->all());

        // LOG ACTIVITY — mengikuti format abang
        logActivity(
            'update_rule',
            'Admin mengubah aturan: '.$rule->judul,
            $rule->id
        );

        return redirect()->route('rules.index')->with('success', 'Aturan berhasil diperbarui!');
    }

    public function destroy(Rule $rule)
    {
        $judul = $rule->judul;
        $id    = $rule->id;

        $rule->delete();

        // LOG ACTIVITY — mengikuti format abang
        logActivity(
            'delete_rule',
            'Admin menghapus aturan: '.$judul,
            $id
        );

        return redirect()->route('rules.index')->with('success', 'Aturan dihapus!');

    }
}
