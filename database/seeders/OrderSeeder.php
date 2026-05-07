<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = User::where('id_role', 'R02')->get();
        $books = Book::all();
        $orderStatuses = ['S03', 'S04', 'S05', 'S06'];

        if ($clients->isEmpty() || $books->isEmpty()) {
            return;
        }

        foreach ($clients as $client) {
            // 30 commandes par client
            for ($i = 0; $i < 30; $i++) {
                $order = Order::create([
                    'id' => fake()->uuid(),
                    'totalAmount' => 0, // Sera mis à jour après
                    'id_status' => fake()->randomElement($orderStatuses),
                    'id_user' => $client->id,
                ]);

                $totalAmount = 0;
                $itemCount = rand(1, 20);
                
                // Sélectionner des livres au hasard pour cette commande
                $randomBooks = $books->random($itemCount);

                foreach ($randomBooks as $book) {
                    $quantity = rand(1, 5);
                    $unitPrice = $book->price;
                    $lineTotal = $unitPrice * $quantity;

                    OrderItem::create([
                        'id' => fake()->uuid(),
                        'quantity' => $quantity,
                        'unitPrice' => $unitPrice,
                        'totalPrice' => $lineTotal,
                        'id_order' => $order->id,
                        'id_book' => $book->id,
                    ]);

                    $totalAmount += $lineTotal;
                }

                // Mettre à jour le montant total de la commande
                $order->update(['totalAmount' => $totalAmount]);
            }
        }
    }
}
