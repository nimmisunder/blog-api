<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Tag;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Http\JsonResponse;

class TagController extends BaseController
{
    /**
     * Store a newly created tag in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Create the tag
        $tag = Tag::create([
            'name' => $request->name,
        ]);

        // Return success response
        return $this->sendResponse($tag, 'Tag created successfully.');
    }
}
