<?php

declare(strict_types=1);

namespace CyrildeWit\EloquentViewable\Tests;

use CyrildeWit\EloquentViewable\Tests\TestClasses\Models\Post;
use CyrildeWit\EloquentViewable\Tests\TestClasses\Models\PostSoftdeletes;
use CyrildeWit\EloquentViewable\View;

class ViewableObserverTest extends TestCase
{
    /** @var \CyrildeWit\EloquentViewable\Tests\TestClasses\Models\Post */
    protected $post;

    public function setUp(): void
    {
        parent::setUp();

        $this->post = factory(Post::class)->create();
    }

    /** @test */
    public function it_can_destroy_all_views_when_viewable_gets_deleted()
    {
        TestHelper::createView($this->post);
        TestHelper::createView($this->post);
        TestHelper::createView($this->post);

        $this->assertEquals(3, View::count());

        $this->post->delete();

        $this->assertEquals(0, View::count());
    }

    /** @test */
    public function it_does_not_destroy_all_views_when_viewable_gets_deleted_and_removeViewsOnDelete_is_set_to_false()
    {
        $this->post->removeViewsOnDelete = false;

        TestHelper::createView($this->post);
        TestHelper::createView($this->post);
        TestHelper::createView($this->post);

        $this->assertEquals(3, View::count());

        $this->post->delete();

        $this->assertEquals(3, View::count());
    }

    /** @test */
    public function somethings()
    {
        $postSoftdeletes = factory(PostSoftdeletes::class)->create();

        $postSoftdeletes->removeViewsOnDelete = true;

        TestHelper::createView($postSoftdeletes);
        TestHelper::createView($postSoftdeletes);
        TestHelper::createView($postSoftdeletes);

        $this->assertEquals(3, View::count());

        $postSoftdeletes->forceDelete();

        $this->assertEquals(0, View::count());
    }

    /** @test */
    public function somethings2()
    {
        app()->bind(
            \CyrildeWit\EloquentViewable\Contracts\View::class,
            \CyrildeWit\EloquentViewable\Tests\TestClasses\ViewSoftdeletes::class
        );

        $postSoftdeletes = factory(PostSoftdeletes::class)->create();

        TestHelper::createView($postSoftdeletes);
        TestHelper::createView($postSoftdeletes);
        TestHelper::createView($postSoftdeletes);

        $this->assertEquals(3, \CyrildeWit\EloquentViewable\Tests\TestClasses\ViewSoftdeletes::count());

        $postSoftdeletes->delete();

        $this->assertEquals(0, \CyrildeWit\EloquentViewable\Tests\TestClasses\ViewSoftdeletes::count());
        $this->assertEquals(3, $postSoftdeletes->views()->withTrashed()->count());
    }

    /** @test */
    public function somethings3()
    {
        app()->bind(
            \CyrildeWit\EloquentViewable\Contracts\View::class,
            \CyrildeWit\EloquentViewable\Tests\TestClasses\ViewSoftdeletes::class
        );

        $postSoftdeletes = factory(PostSoftdeletes::class)->create();

        TestHelper::createView($postSoftdeletes);
        TestHelper::createView($postSoftdeletes);
        TestHelper::createView($postSoftdeletes);

        $this->assertEquals(3, \CyrildeWit\EloquentViewable\Tests\TestClasses\ViewSoftdeletes::count());

        $postSoftdeletes->delete();

        $this->assertEquals(0, \CyrildeWit\EloquentViewable\Tests\TestClasses\ViewSoftdeletes::count());
        $this->assertEquals(3, $postSoftdeletes->views()->withTrashed()->count());

        //

        $postSoftdeletes->restore();

        $this->assertEquals(3, \CyrildeWit\EloquentViewable\Tests\TestClasses\ViewSoftdeletes::count());
        $this->assertEquals(3, $postSoftdeletes->views()->count());
    }
}
