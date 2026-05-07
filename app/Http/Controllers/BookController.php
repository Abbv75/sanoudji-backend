<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Book;
use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index()
    {
        $books = Book::with(['author', 'categories'])
            ->latest()
            ->get();

        return $this->success($books, 'Liste des livres récupérée avec succès');
    }

    /**
     * Store a newly created book.
     */
    public function store(StoreBookRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('coverUrl')) {
            $path = $request->file('coverUrl')->store('images', 'public');
            $data['coverUrl'] = Storage::url($path);
        }

        $book = Book::create($data);

        if ($request->has('categories')) {
            $book->categories()->sync($request->categories);
        }

        if ($request->has('metadata')) {
            foreach ($request->metadata as $meta) {
                $book->metadata()->create($meta);
            }
        }

        return $this->success($book->load(['author', 'categories', 'metadata.attribute']), 'Livre créé avec succès', 201);
    }

    /**
     * Display the specified book.
     */
    public function show(string $id)
    {
        $book = Book::with(['author', 'categories', 'metadata.attribute', 'reviews.user'])
            ->find($id);

        if (!$book) {
            return $this->notFound('Livre non trouvé');
        }

        return $this->success($book, 'Détails du livre récupérés avec succès');
    }

    /**
     * Update the specified book.
     */
    public function update(UpdateBookRequest $request, string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return $this->notFound('Livre non trouvé');
        }

        $data = $request->validated();

        if ($request->hasFile('coverUrl')) {
            if ($book->coverUrl) {
                $oldPath = str_replace('/storage/', '', $book->coverUrl);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('coverUrl')->store('images', 'public');
            $data['coverUrl'] = Storage::url($path);
        }

        $book->update($data);

        if ($request->has('categories')) {
            $book->categories()->sync($request->categories);
        }

        if ($request->has('metadata')) {
            $book->metadata()->delete();
            foreach ($request->metadata as $meta) {
                $book->metadata()->create($meta);
            }
        }

        return $this->success($book->load(['author', 'categories', 'metadata.attribute']), 'Livre mis à jour avec succès');
    }

    /**
     * Remove the specified book.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return $this->notFound('Livre non trouvé');
        }

        if ($book->coverUrl) {
            $oldPath = str_replace('/storage/', '', $book->coverUrl);
            Storage::disk('public')->delete($oldPath);
        }

        $book->delete();

        return $this->success(null, 'Livre supprimé avec succès');
    }
}
