<?php

namespace App\Http\Requests\BookTransaction;

use Illuminate\Foundation\Http\FormRequest;

class BookStockInRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'semester_id' => [
                'required',
                'integer',
                'exists:semesters,id'
            ],
            'books' => [
                'required',
                'array',
                'min:1',
            ],
            'books.*.book_id' => [
                'required',
                'integer',
                'exists:books,id',
                'distinct'
            ],
            'books.*.quantity' => [
                'required',
                'integer',
            ],
            'books.*.unit_price' => [
                'required',
                'integer',
            ],
            'books.*.mutation_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],
            'books.*.return_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ]
        ];
    }

    public function attributes(): array
    {
        return [
            'semester_id' => 'semester',
            'books.*.book_id' => 'buku',
            'books.*.quantity' => 'jumlah',
            'books.*.unit_price' => 'harga beli',
            'books.*.mutation_percentage' => 'persentase mutasi',
            'books.*.return_percentage' => 'persentase retur',
        ];
    }
}
