<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Jobs\GetExchangeRate;

class FormController extends Controller
{
    public function index()
    {
        return view('form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/')
                ->withErrors($validator);
        }

        $this->dispatch(new GetExchangeRate($request->input('token'), $request->input('date')));

        return back()->with('success_message', 'The course rate will be saved to the database.');
    }
}
