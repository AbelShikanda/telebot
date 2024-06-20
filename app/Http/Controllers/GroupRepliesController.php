<?php

namespace App\Http\Controllers;

use App\Models\GroupReplies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupRepliesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $replies = GroupReplies::all();
        return view('groupReply.index', with([
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
        return view('groupReply.create', with([
            // 'replies' => GroupReplies::all()
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
            'reply' => 'required',
            'groupreply' => 'required',
        ]);
        // dd($replies);
        
        try {
            DB::beginTransaction();
            // Logic For Save User Data
            
            $replies = GroupReplies::create([
                'keyword' => $request->keywords,
                'response' => $request->reply,
                'default_response' => $request->groupreply,
            ]);


            if(!$replies){
                DB::rollBack();

                return back()->with('error', 'Something went wrong while saving user data');
            }

            DB::commit();
            return redirect()->route('groupreplies.index')->with('success', 'Values Stored Successfully.');


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GroupReplies  $groupReplies
     * @return \Illuminate\Http\Response
     */
    public function show(GroupReplies $groupReplies)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GroupReplies  $groupReplies
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $replies = GroupReplies::findOrFail($id);
        return view('groupReply.edit', with([
            'replies' => $replies,
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GroupReplies  $groupReplies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $replies = GroupReplies::findOrFail($id);
        $request->validate([
            'keywords' => '',
            'reply' => '',
            'groupreply' => '',
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
                if ($request->reply) {
                    $reply = $request->reply;
                    $replies->response = $reply;
                }
                if ($request->groupreply) {
                    $groupreply = $request->groupreply;
                    $replies->default_response = $groupreply;
                }
                $replies->save();
            }


            if(!$replies){
                DB::rollBack();

                return back()->with('error', 'Something went wrong while saving user data');
            }

            DB::commit();
            return redirect()->route('groupreplies.index')->with('success', 'Values Updated Successfully.');


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GroupReplies  $groupReplies
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupReplies $groupReplies)
    {
        //
    }
}
