<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookReview;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookReviewResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BooksReviewController extends Controller
{
    public function __construct()
    {

    }

    public function store(int $bookId, PostBookReviewRequest $request)
    {
        // @TODO implement
        $book = Book::find($bookId);
        if (!$book) {
            return response()->json('', 404);
        }

        $review = new BookReview();
        $review->book()->associate($bookId);
        $review->user()->associate(Auth::user()->id);
        $review->review = $request->review;
        $review->comment = $request->comment;
        $book->reviews()->save($review);

        $res = new BookReviewResource($review);
        return $res->response()->setStatusCode(201);
    }

    public function destroy(int $bookId, int $reviewId, Request $request)
    {
        // @TODO implement
        $book = Book::find($bookId);
        if (!$book) {
            return response()->json('', 404);
        }

        BookReview::find($reviewId)->delete();
        return response()->noContent();
    }
}
