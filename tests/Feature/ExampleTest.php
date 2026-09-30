<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_shows_the_preview_shell_with_the_portal_as_web(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('data-mode="web"', false);
        $response->assertSee('data-mode="app"', false);
        $response->assertSee(route('pharmacy-portal.dashboard'), false);
        $response->assertSee(route('app.home'), false);
    }
}
