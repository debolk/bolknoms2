<?php

use App\Models\User;

class PhotoTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        // Create a user and assign a profile picture
        // having the id 0 ensures that no normal users are ever written over
        $this->user = factory(User::class)->create(['id' => 0]);
        copy(base_path() . '/tests/user.png', $this->picturePath());
    }

    public function tearDown()
    {
        parent::tearDown();

        // Cleanup the profile picture
        unlink($this->picturePath());
    }

    /**
     * Utility function to encapsulate the correct, expected path of the profile picture
     * @return string full path
     */
    protected function picturePath()
    {
        return base_path() . '/uploads/profile_pictures/' . $this->user->id;
    }

    /** @test */
    public function can_view_ones_own_profile_picture()
    {
        $this->loginAs($this->user);

        $this->get('/photo');

        $this->assertResponseOk();
        $this->see(file_get_contents($this->picturePath()));
    }

    /** @test */
    public function can_view_the_profile_picture_of_another_user()
    {
        $picture = $this->get('/photo/'.$this->user->username);

        $this->assertResponseOk();
        $this->see(file_get_contents($this->picturePath()));
    }

    /** @test */
    public function having_no_picture_serves_the_chef()
    {
        // Remove the profile picture
        unlink($this->picturePath());

        $picture = $this->get('/photo/'.$this->user->username);

        $this->assertResponseOk();
        $this->see(file_get_contents(public_path() . '/images/swedishchef.jpg'));
    }
}
