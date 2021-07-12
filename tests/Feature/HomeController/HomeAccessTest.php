<?php

namespace Tests\Feature\HomeController;

use Tests\TestCase;

class HomeAccessTest extends TestCase
{
    /**
     * @test
     */
    public function can_visit_the_home_page()
    {
        $this->get(route('home'))
            ->assertViewIs('home')
            ->assertSeeText('Evershop:')
            ->assertSeeText('The best place to')
            ->assertSeeText('Buy');
    }
}
