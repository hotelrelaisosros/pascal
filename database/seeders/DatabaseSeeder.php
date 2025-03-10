<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Chat;
use App\Models\Comment;
use App\Models\Subjects;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use App\Models\Talk;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'Matt Stutfer',
            'email' => 'bhola@gmail.com',
            'password' => Hash::make('admin123'),
        ]);

        // for ($i = 0; $i < 5; $i++) {
        Subjects::create(['name' => 'Maths']);
        Subjects::create(['name' => 'Physics']);
        Subjects::create(['name' => 'Chemistry']);
        Subjects::create(['name' => 'Biology']);
        // }



        // Create Category and associate Blog with it under the same user
        // $categories = Category::factory(3)->create();
        // $tags = Tag::factory()->count(2)->create();

        // // Create 5 blogs for the author and associate tags
        // $blogs = Blog::factory()->for($user)->count(5)->create();

        // foreach ($blogs as $index => $blog) {
        //     // Attach tags to each blog
        //     $blog->tags()->attach($tags->pluck('id'));
        //     $blog->category()->associate($categories[$index % $categories->count()])->save();
        //     Comment::factory()->for($user)->for($blog)->count(5)->create();
        // }

        // Tag::factory()->count(1)->create();

        // $user_id = $user["id"];
        // // dd($user_id);
        // User::factory()->count(5)->create();

        // Chat::factory()->count(5)->create([
        //     'sender_id' => $user_id,
        //     'receiver_id' =>  User::where('id', '!=', $user_id)->inRandomOrder()->first()->id,
        // ]);
        // Chat::factory()->count(5)->create([
        //     'sender_id' =>  User::where('id', '!=', $user_id)->inRandomOrder()->first()->id,
        //     'receiver_id' => $user_id,
        // ]);
    }
}
