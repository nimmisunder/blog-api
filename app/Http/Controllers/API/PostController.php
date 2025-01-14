<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Post;
use Validator;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\PostResource;

class PostController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Create the post
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->id(), // Assuming the user is authenticated
        ]);

        return $this->sendResponse($post, 'Post created successfully.');
    }

    public function index(): JsonResponse
    {
        $posts = Post::all(); // Retrieve all posts

        return response()->json([
            'success' => true,
            'data' => PostResource::collection($posts), // Wrap in a resource for structured output
            'message' => 'Posts retrieved successfully.',
        ]);
    }

    public function assignTags(Request $request, $id): JsonResponse
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id', // Ensure each tag ID exists in the tags table
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Find the post
        $post = Post::find($id);
        if (!$post) {
            return $this->sendError('Post not found.');
        }

        // Attach tags to the post
        $post->tags()->sync($request->tags);

        // Return success response
        return $this->sendResponse($post->tags, 'Tags assigned successfully.');
    }
    public function getTags($id): JsonResponse
    {
        // Find the post by ID
        $post = Post::find($id);

        // Check if the post exists
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found.',
            ], 404);
        }

        // Fetch the tags associated with the post
        $tags = $post->tags;

        // Return the tags
        return response()->json([
            'success' => true,
            'data' => $tags,
            'message' => 'Tags retrieved successfully.',
        ], 200);
    }
}
