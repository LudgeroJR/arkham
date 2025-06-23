<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MembroController extends Controller
{
    public function index()
    {
        $membros = DB::table('membros')->get();
        return view('membros.index', ['membros' => $membros]);
    }
}
