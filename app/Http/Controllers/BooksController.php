<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\PostBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BooksController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        // @TODO implement
        // filter by tittle
        if ($request->has('title')) {
            $data = Book::where('title', 'LIKE', '%' . $request->title . '%');
        }
        // filter by author
        if ($request->has('authors')) {
            $author_ids = explode(',', $request->authors);
            $data = Book::whereHas('authors', function (Builder $query) use ($author_ids) {
                $query->whereIn('id', $author_ids);
            });
        }

        // sorting data
        if ($request->has('sortColumn')) {
            // sort by title
            if ($request->sortColumn == 'title') {
                $data = Book::orderBy('title', $request->input('sortDirection', 'asc'));
            }
            // sort by published_year
            if ($request->sortColumn == 'published_year') {
                $data = Book::orderBy('published_year', $request->input('sortDirection', 'asc'));
            }
            // sort by avg_review
            if ($request->sortColumn == 'avg_review') {
                $data = Book::withCount(['reviews as avg_review' => function($query) {
                    $query->select(DB::raw('coalesce(avg(review),0)'));
                }])->orderBy('avg_review', $request->input('sortDirection', 'asc'));
            }
        }
        // set default data
        if (isset($data)) $data = $data->paginate();
        else $data = Book::paginate();
        
        return BookResource::collection($data);
    }

    public function store(PostBookRequest $request)
    {
        // @TODO implement
        $data = $request->except('authors');
        $data = Book::create($data);
        $data->authors()->sync($request->authors);

        $res = new BookResource($data);
        return $res->response()->setStatusCode(201);
    }
}
