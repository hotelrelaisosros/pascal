<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Talk;
use App\Models\User;


class ListTasksTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_see_own_talks_but_not_others()
    {
        // Create a user with 2 talks
        $user = User::factory()
            ->has(Talk::factory()->count(2))
            ->create();
    
        // Create a talk for another user
        $otherUsersTalk = Talk::factory()->create();
    
        // Act as the created user and visit the talks index
        $response = $this->actingAs($user)->get(route('talks.index'));

        $response->assertSee($user->talks->first()->title)
                 ->assertDontSee($otherUsersTalk->title);
    
    
        // Assert the other user's talk is not visible
        $response->assertDontSee($otherUsersTalk->title);
    
        // Assert the response is OK
        $response->assertOk();
    }
    

    public function otherTalks(){
        $talk = Talk::factory()->create();


        $response  = $this->actingAs($talk->author)->get(route('talks.show',$talk))->assertSee($talk->title)
        ;

        $response->assertOk();

    }
}
