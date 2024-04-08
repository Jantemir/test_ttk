<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Routing\Controller;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(! Gate::allows('books.viewAny')) {
            abort(403, 'This action is unauthorized');
        }
        $books = Book::orderBy('name', 'asc')->paginate(5);
        $pages = $books->toArray();
        unset($pages['data']);
        return Inertia::render('Book/List', [
            'items' => $books->items(),
            'pagination' => $pages,
            'can' => [
                'create' => auth()->user()?->can('books.create') ?? false,
                'viewHidden' => auth()->user()?->can('books.viewHidden') ?? false,
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(! Gate::allows('books.create')) {
            abort(403, 'This action is unauthorized');
        }
        $sections = Section::select('id', 'name')->get();
        $authors = Author::select(['id', 'name'])->get();
        return Inertia::render('Book/Edit', [
            'sections' => $sections,
            'authors' => $authors,
            'can' => [
                'create' => [
                    'section' => auth()->user()?->can('sections.create') ?? false,
                    'author' => auth()->user()?->can('authors.create') ?? false,
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        if(! Gate::allows('books.create')) {
            abort(403, 'This action is unauthorized');
        }
        return $this->handleForm($request,
            function(array $fields, ?array $sections, ?array $authors): int
            {
                $fields['user_id'] = auth()->id();
                $book = Book::make($fields);
                DB::transaction(function () use ($fields, $sections, $authors, $book) {
                    $book->save();
                    $book->sections()->attach($sections);
                    $book->authors()->attach($authors);
                });
                return $book->id;
            });
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $query = Book::query();
        if (Auth::user()?->isAdmin()) {
            $query->withoutGlobalScope('active');
        }
        $book = $query->findOrFail($id);
        if(! Gate::allows('books.view', $book)) {
            abort(403, 'This action is unauthorized');
        }
        $book->load(['authors:id,name', 'sections:id,name']);
        return Inertia::render('Book/Detail', [
            'item' => $book,
            'can' => [
                'update' => auth()->user()?->can('books.update', $book) ?? false,
                'delete' => auth()->user()?->can('books.delete', $book) ?? false,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $query = Book::query();
        if (Auth::user()?->isAdmin()) {
            $query->withoutGlobalScope('active');
        }
        $book = $query->findOrFail($id);
        if(! Gate::allows('books.update', $book)) {
            abort(403, 'This action is unauthorized');
        }
        $book->load(['authors:id,name', 'sections:id,name']);
        $sections = Section::select('id', 'name')->get();
        $authors = Author::select(['id', 'name'])->get();
        return Inertia::render('Book/Edit', [
            'action' => 'change',
            'item' => $book,
            'sections' => $sections,
            'authors' => $authors,
            'can' => [
                'create' => [
                    'section' => auth()->user()?->can('sections.create') ?? false,
                    'author' => auth()->user()?->can('authors.create') ?? false,
                ],
                'hide' => auth()->user()?->can('books.hide') ?? false,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, int $id)
    {
        $query = Book::query();
        if (Auth::user()?->isAdmin()) {
            $query->withoutGlobalScope('active');
        }
        $book = $query->findOrFail($id);
        if(! Gate::allows('books.update', $book)) {
            abort(403, 'This action is unauthorized');
        }
        return $this->handleForm($request,
            function (array $fields, ?array $sections, ?array $authors) use ($book): int
            {
                DB::transaction(function () use ($fields, $sections, $authors, $book) {
                    $book->update($fields);
                    $book->authors()->sync($authors);
                    $book->sections()->sync($sections);
                });
                return $book->id;
            });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $query = Book::query();
        if (Auth::user()?->isAdmin()) {
            $query->withoutGlobalScope('active');
        }
        $book = $query->findOrFail($id);
        if(! Gate::allows('books.delete', $book)) {
            abort(403, 'This action is unauthorized');
        }
        $book->delete();
        return redirect()->to(route('books.index'));
    }

    private function handleForm(BookRequest $request, Callable $dbAction)
    {
        $fields = $request->safe()->only(['name', 'description', 'year', 'active']);
        $sections = array_column($request->validated(['sections']), 'id');
        $authors = array_column($request->validated(['authors']), 'id');

        try {
            if($request->file('cover')) {
                $imgPath = $request->file('cover')->store('public/book_covers');
                $fields['cover'] = Storage::url($imgPath);
            }
            $bookId = $dbAction($fields, $sections, $authors);
            return redirect()->route('books.show', $bookId);
        } catch (\Exception $e) {
            if (Storage::exists($imgPath)) {
                Storage::delete($imgPath);
            }
            throw $e;
        }
    }

    public function search(Request $request)
    {
        if(! Gate::allows('books.viewAny')) {
            abort(403, 'This action is unauthorized');
        }
        $search = $request->validate(['q' => 'required|string|min:3'])['q'];
        $books = Book::whereHas('authors', function ($query) use ($search) {
                return $query->where('name', 'like', "%$search%");
            })->select(['id', 'name'])
            ->orWhere('name', 'like', "%$search%")
            ->paginate(5);
        return Inertia::render('Book/List', [
            'items' => $books->items(),
            'pagination' => $books->toArray(),
        ]);
    }

    public function hidden()
    {
        if(! Gate::allows('books.viewHidden')) {
            abort(403, 'This action is unauthorized');
        }
        $books = Book::withoutGlobalScope('active')
            ->hidden()
            ->orderBy('name', 'asc')
            ->paginate(5);
        $pages = $books->toArray();
        unset($pages['data']);
        return Inertia::render('Book/List', [
            'items' => $books->items(),
            'pagination' => $pages,
            'can' => [
                'create' => false,
            ]
        ]);
    }
}
