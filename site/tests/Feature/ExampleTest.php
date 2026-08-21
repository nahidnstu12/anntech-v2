<?php

namespace Tests\Feature;

use App\Mail\ContactEnquiry;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Enovak')
            ->assertSee('Foyjul Alam')
            ->assertSee('Modular Clean Room Panel')
            ->assertSee('Solid production (OSD)')
            ->assertSee('74/B, 11th Floor, R H Home Center')
            ->assertSee('<meta name="description"', false)
            ->assertSee('name="keywords"', false)
            ->assertSee('rel="canonical"', false);
    }

    public function test_product_catalog_and_detail(): void
    {
        $this->get('/products')
            ->assertOk()
            ->assertSee('Products')
            ->assertSee('Tablet Compression Press');

        $this->get('/products?category=packaging-equipment')
            ->assertOk()
            ->assertSee('Blister Packaging Line')
            ->assertDontSee('Tablet Compression Press');

        $this->get('/products/tablet-press')
            ->assertOk()
            ->assertSee('Tablet Compression Press')
            ->assertSee('OSD / effervescent');

        $this->get('/products/does-not-exist')->assertNotFound();

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(url('/products/tablet-press'), false);
    }

    public function test_contact_queues_mail(): void
    {
        Mail::fake();

        $this->from('/')
            ->post('/contact', [
                'name' => 'Amina Rahman',
                'email' => 'amina@example.com',
                'phone' => '+880 1700 000000',
                'message' => 'Need an AHU package quote.',
            ])
            ->assertRedirect('/#contact')
            ->assertSessionHas('status');

        Mail::assertQueued(ContactEnquiry::class, function (ContactEnquiry $mail) {
            return $mail->hasTo('support@enovak.com')
                && $mail->senderName === 'Amina Rahman'
                && $mail->hasReplyTo('amina@example.com');
        });
    }

    public function test_contact_validates(): void
    {
        Mail::fake();

        $this->from('/#contact')
            ->post('/contact', [])
            ->assertRedirect('/#contact')
            ->assertSessionHasErrors(['name', 'email', 'phone', 'message']);

        Mail::assertNothingOutgoing();
    }
}
