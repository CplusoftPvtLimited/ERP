<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LinkageTarget;

class MakeController extends Controller
{
    public function getAllMakes()
    {
        $makes = LinkageTarget::select('mfrId','mfrName')->orderBy('mfrName','asc')->distinct()->paginate(100);
        return view('makes.get', compact('makes'));
    }
}
