<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Trang chủ redirect đến /dat-lich
        $response = $this->get('/');
        $response->assertRedirect('/dat-lich');
    }
    
    public function test_dat_lich_page_returns_successful_response(): void
    {
        $response = $this->get('/dat-lich');
        $response->assertStatus(200);
    }
    
    public function test_tim_bac_si_page_returns_successful_response(): void
    {
        $response = $this->get('/tim-bac-si');
        $response->assertStatus(200);
    }
    
    public function test_dang_nhap_page_returns_successful_response(): void
    {
        $response = $this->get('/dang-nhap');
        $response->assertStatus(200);
    }
}
