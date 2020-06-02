<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Measure;
use Illuminate\Support\Facades\Response;
class MeasureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = explode(" ",$request->filter);
        //$sort=json_decode($request->sort,true);
        //$fieldOrder=$sort->order;
        //$order=$sort[1];
        //return $fieldOrder;
        return Measure::select('name','status','created_at')
        ->where(function($query) use($search){
            foreach($search as $item){
                $query->where('measures.name','like','%'.$item.'%');
            }
        })->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $measure=new Measure;
            $measure->name=$request->name;
            $measure->save();
            return Response::json(['message'=>'Medida guardada exitosamente'], 200);
        } catch (Exception $e) {
            return Response::json(['message'=>$e->getMessage()],400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
