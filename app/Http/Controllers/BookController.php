<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Book;

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
}
