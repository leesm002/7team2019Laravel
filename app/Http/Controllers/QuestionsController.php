<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
// use DataTables;

class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(){
      $this->middleware('auth', ['except'=>['index', 'show']]);
    }

    public function index()
    {
        // $questions = \App\Question::with('user')->latest()->paginate(10);
        $questions = Question::latest()->paginate(10);

        return view('qna.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request){
      $rules = array(
        'title'=>'required',
        'content'=>'required',
      );

      $validator = \Validator::make($request->all(), $rules);

      if($validator->fails()){
        return response()->json(['error'=> $validator->errors()->all()]);
      }

      $questionArray = array(
        'title' => $request->title,
        'content' => $request->content,
        'user_id' => $request->hidden_id,
      );

      $question = Question::create($questionArray);

      // Question::create($questionArray);
      flash()->success('질문을 성공적으로 저장했습니다.');


      return response()->json($question);

    }
  
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $search = \App\Question::where('id', '=', $id)->get();
        
      return response([
          'qid' => $search[0]['id'],
          'content' => $search[0]['content'],
      ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::find($id);


        return response()->json($question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $question = \App\Question::where('id', '=', $id)->update([
        'title'=>$request->title,
        'content'=>$request->content
      ]);

      return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      \App\Question::find($id)->delete();


      return response($id);
    }

}