<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
class BookController extends Controller
{
    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'pdf' => 'required|file|mimes:pdf|max:100000', // Máximo de 100MB
        ]);
        $pdfPath = $request->file('pdf')->store('pdfs', 'public');

        $book = Book::create([
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'path' => $pdfPath,
        ]);
        return response()->json(['message' => 'Livro criado com sucesso!', 'book' => $book], 201);

    }

    public function index(Request $request) : JsonResponse
    {
        $perPage = $request->input('perPage', 10);
        $books = Book::paginate($perPage);
        return response()->json($books);
    }

    public function downloadPdf($id)
    {
        $book = Book::findOrFail($id);

        if (!$book->path) {
            return response()->json(['message' => 'PDF não encontrado.'], 404);
        }

        return response()->file(storage_path('app/public/' . $book->path));
    }
}
