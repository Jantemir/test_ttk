<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Illuminate\Routing\Controller;
class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(! Gate::allows('authors.viewAny', Auth::user())) {
            abort(403, 'This action is unauthorized');
        }
        $authors = Author::orderBy('name', 'asc')->paginate(5);
        $pages = $authors->toArray();
        unset($pages['data']);
        return Inertia::render('Author/List', [
            'items' => $authors->items(),
            'pagination' => $pages,
            'can' => [
                'create' => auth()->user()?->can('authors.create') ?? false,
                'viewHidden' => auth()->user()?->can('authors.viewHidden') ?? false,
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(! Gate::allows('authors.create')) {
            abort(403, 'This action is unauthorized');
        }
        return Inertia::render('Author/Edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuthorRequest $request)
    {
        if(! Gate::allows('authors.create')) {
            abort(403, 'This action is unauthorized');
        }
        $fields = $request->safe()->only(['name', 'mother_country', 'comment']);
        $author = Author::create($fields);
        return redirect()->route('authors.show', $author->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $query = Author::query();
        if (Auth::user()?->isAdmin()) {
            $query->withoutGlobalScope('active');
        }
        $author = $query->findOrFail($id);
        if(! Gate::allows('authors.view', $author)) {
            abort(403, 'This action is unauthorized');
        }
        return Inertia::render('Author/Detail', [
            'item' => $author,
            'can' => [
                'update' => auth()->user()?->can('authors.update', $author) ?? false,
                'delete' => auth()->user()?->can('authors.delete', $author) ?? false,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $query = Author::query();
        if (Auth::user()?->isAdmin()) {
            $query->withoutGlobalScope('active');
        }
        $author = $query->findOrFail($id);
        if(! Gate::allows('authors.update', $author)) {
            abort(403, 'This action is unauthorized');
        }
        return Inertia::render('Author/Edit', [
            'item' => $author,
            'action' => 'change',
            'can' => [
                'hide' => auth()->user()?->can('sections.hide') ?? false,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AuthorRequest $request, int $id)
    {
        $query = Author::query();
        if (Auth::user()?->isAdmin()) {
            $query->withoutGlobalScope('active');
        }
        $author = $query->findOrFail($id);
        if(! Gate::allows('authors.update', $author)) {
            abort(403, 'This action is unauthorized');
        }
        $fields = $request->safe()->only(['name', 'mother_country', 'comment', 'active']);
        $author->update($fields);
        return redirect()->route('authors.show', $author->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $query = Author::query();
        if (Auth::user()?->isAdmin()) {
            $query->withoutGlobalScope('active');
        }
        $author = $query->findOrFail($id);
        if(! Gate::allows('authors.delete', $author)) {
            abort(403, 'This action is unauthorized');
        }
        $author->delete();
        return redirect()->to(route('authors.index'));
    }

    public function hidden()
    {
        if(! Gate::allows('authors.viewHidden')) {
            abort(403, 'This action is unauthorized');
        }
        $authors = Author::withoutGlobalScope('active')
            ->hidden()
            ->orderBy('name', 'asc')
            ->paginate(5);
        $pages = $authors->toArray();
        unset($pages['data']);
        return Inertia::render('Author/List', [
            'items' => $authors->items(),
            'pagination' => $pages,
            'can' => [
                'create' => false,
            ]
        ]);
    }
}
