<?php

namespace Tests\Feature;

use App\Support\ScreenRegistry;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DemoScreensRenderTest extends TestCase
{
    public static function screenProvider(): array
    {
        $cases = [];

        foreach (ScreenRegistry::all() as $id => $row) {
            $cases[$id] = [$id, $row['app']];
        }

        return $cases;
    }

    #[DataProvider('screenProvider')]
    public function test_app_screen_resolves_correctly(string $id, array $entry): void
    {
        $response = $this->get("/app/{$id}");

        if ($entry['type'] === 'view') {
            $response->assertOk();
            $response->assertDontSee('Undefined array key', false);
            $response->assertDontSee('Attempt to read property', false);
            $response->assertDontSee('htmlspecialchars', false);
        } else {
            $response->assertRedirectToRoute("app.{$entry['target']}", ['from' => $id]);
        }
    }

    #[DataProvider('screenProvider')]
    public function test_old_web_url_redirects_to_the_portal_preview(string $id): void
    {
        $this->get("/web/{$id}")->assertRedirect('/?mode=web');
    }
}
