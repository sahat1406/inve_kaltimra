<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Pelaporan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FeedbackReply;
use Illuminate\Support\Facades\Validator;

class CekPelaporanController extends Controller
{
    public function index()
    {
        return view('cek-pelaporan.index', [
            'pelaporans' => Pelaporan::orderBy('id', 'DESC')->get()
        ]);
    }

    public function detail($id)
    {
        $pelaporan      = Pelaporan::findOrFail($id);
        $feedback       = Feedback::where('pelaporan_id', $pelaporan->id)->first();
        $feedbackReply  = $feedback ? FeedbackReply::where('feedback_id', $feedback->id)->first() : null;

        return view('cek-pelaporan.detail', [
            'pelaporan'     => $pelaporan,
            'feedback'      => $feedback,
            'feedbackReply' => $feedbackReply
        ]);
    }

    public function store(Request $request, $id)
    {
        $feedback = $request->filled('feedback_id')
            ? Feedback::find($request->feedback_id)
            : Feedback::where('pelaporan_id', $id)->first();

        if (!$feedback) {
            return back()->withErrors([
                'feedback_replies' => 'Analisis perbaikan belum tersedia untuk pelaporan ini.',
            ])->withInput();
        }

        $validator = Validator::make($request->all(), [
            'feedback_replies'  => 'required'
        ], [
            'feedback_replies.required' => 'Form Tidak Boleh Kosong '
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        FeedbackReply::create([
            'feedback_id'       => $feedback->id,
            'feedback_replies'  => $request->feedback_replies,
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan feedback baru');
    }
}
