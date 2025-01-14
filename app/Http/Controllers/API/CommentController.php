<?php
// app/Http/Controllers/API/CommentController.php
namespace App\Http\Controllers\API;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\CommentResource;

class CommentController extends BaseController
{
    /**
     * Store a newly created comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'post_id' => 'required|exists:posts,id', // Ensure the post exists
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Create the comment
        $comment = Comment::create([
            'content' => $request->content,
            'user_id' => auth()->id(), // Assuming the user is authenticated
            'post_id' => $request->post_id,
        ]);

        return $this->sendResponse($comment, 'Comment created successfully.');
    }
    public function index($id)
    {
        // Find the post by its ID
        $post = Post::find($id);

        // If post doesn't exist, return error
        if (!$post) {
            return $this->sendError('Post not found.');
        }

        // Fetch the comments related to the post
        $comments = $post->comments; // Eloquent relationship defined in Post model

        // Return the comments as a resource collection (optional: can also use CommentResource)
        return $this->sendResponse(CommentResource::collection($comments), 'Comments fetched successfully.');
    }
}
