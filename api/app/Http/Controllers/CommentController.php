<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function create($id, Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'body' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
	        return response()->json([
				"message" => $validator->errors()->first(),
	        ], 400);
        }

        $video = Video::where('id', $id)->first();

        if (!isset($video)) {
            return response()->json([
            	'message' => "Not found"
            ], 404);
        }

		$user = Auth::user();

		$comment = Comment::create([
			'body' => $request->get('body'),
			'user_id' => $user->id,
			'video_id' => $id
		]);

        return response()->json([
            "message" => "Ok",
            "data" => $comment
        ], 201);
    }

    public function getComments($id, Request $request) {
        $perPage = $request->get('perPage') == null ? 5 : $request->get('perPage');
        $page = $request->get('page') == null ? 1 : $request->get('page');

        $video = Video::where('id', $id)->first();

        if (!isset($video)) {
            return response()->json([
                'message' => "Not found"
            ], 404);
        }

        $commentList = Comment::where('video_id', $id)->paginate($perPage, ['*'], 'page', $page);

        if (empty($commentList->items())) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $pager[] = [
            'current' => $commentList->currentPage(),
            'total' => $commentList->lastPage()
        ];

        if ($page > $commentList->lastPage()) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json([
        	'message' => 'Ok', 
        	'data' => $commentList->items(), 
        	'pager' => $pager
        ], 201);    
    }
}
