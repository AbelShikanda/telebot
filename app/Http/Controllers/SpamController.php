<?php

namespace App\Http\Controllers;

use App\Models\Spam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $replies = Spam::all();
        return view('spam.index', with([
            'replies' => $replies,
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('spam.create', with([
            // 'replies' => Spam::all()
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $replies = $request->validate([
            'keywords' => 'required',
        ]);
        // dd($replies);
        
        try {
            DB::beginTransaction();
            // Logic For Save User Data
            
            $replies = Spam::create([
                'keyword' => $request->keywords,
            ]);


            if(!$replies){
                DB::rollBack();

                return back()->with('error', 'Something went wrong while saving user data');
            }

            DB::commit();
            return redirect()->route('spam.index')->with('success', 'Spam Stored Successfully.');


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Spam  $spam
     * @return \Illuminate\Http\Response
     */
    public function show(Spam $spam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Spam  $spam
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $replies = Spam::findOrFail($id);
        return view('spam.edit', with([
            'replies' => $replies,
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Spam  $spam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $replies = Spam::findOrFail($id);
        $request->validate([
            'keywords' => '',
        ]);
        // dd($replies);
        
        try {
            DB::beginTransaction();
            // Logic For Save User Data

            if ($replies) {
                if ($request->keywords) {
                    $words = $request->keywords;
                    $replies->keyword = $words;
                }
                $replies->save();
            }


            if(!$replies){
                DB::rollBack();

                return back()->with('error', 'Something went wrong while saving user data');
            }

            DB::commit();
            return redirect()->route('spam.index')->with('success', 'Spam Updated Successfully.');


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Spam  $spam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spam $spam)
    {
        //
    }
}
