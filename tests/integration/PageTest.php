<?php

class PageTest extends TestCase
{
    public function testCanViewRules()
    {
        $this->visit('/spelregels')
            ->see('Spelregels voor Bolknoms');
    }

    public function testCanViewDisclaimer()
    {
        $this->visit('/disclaimer')
            ->see('Disclaimer voor Bolknoms');
    }

    public function testCanViewPrivacyData()
    {
        $this->visit('/privacy')
            ->see('Privacyverklaring');
    }
}
