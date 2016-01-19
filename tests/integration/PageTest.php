<?php

class PageTest extends TestCase
{
    /** @test */
    public function can_view_the_rules()
    {
        $this->visit('/spelregels')
            ->see('Spelregels voor Bolknoms');
    }

    /** @test */
    public function can_view_the_disclaimer()
    {
        $this->visit('/disclaimer')
            ->see('Disclaimer voor Bolknoms');
    }

    /** @test */
    public function can_view_the_privacy_statement()
    {
        $this->visit('/privacy')
            ->see('Privacyverklaring');
    }
}
