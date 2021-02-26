<?php

namespace Tests\CrudRepositories;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\Api\ApiRepositories;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Controllers\CrudController;
use Ignite\Crud\Fields\Relations\MorphMany;
use Ignite\Crud\Repositories\Relations\MorphManyRepository;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery as m;
use Tests\BackendTestCase;

/**
 * @see https://laravel.com/docs/7.x/eloquent-relationships#one-to-many-polymorphic-relations
 */
class MorphManyRepositoryTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Schema::create('posts', fn ($table) => $table->id());
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commentable_id')->nullable();
            $table->string('commentable_type')->nullable();
        });

        $config = m::mock(ConfigHandler::class);
        $controller = m::mock(CrudController::class);
        $form = m::mock(BaseForm::class);
        $this->field = m::mock(MorphMany::class)->makePartial();
        $this->repository = new MorphManyRepository($config, $controller, $form, $this->field);
    }

    public function tearDown(): void
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('comments');
        parent::tearDown();
    }

    /** @test */
    public function test_create_method()
    {
        $post = MorphManyRepositoryPost::create();
        $comment = MorphManyRepositoryComment::create();

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $comment->id;

        $this->field->shouldReceive('getQuery')->andReturn($comment->query());
        $this->field->shouldReceive('getRelationQuery')->andReturn($post->comments());
        $this->repository->create($request, $post);

        $this->assertEquals(1, $post->refresh()->comments->count());
        $this->assertEquals($comment->id, $post->refresh()->comments->first()->id);
    }

    /** @test */
    public function test_destroy_method()
    {
        $post = MorphManyRepositoryPost::create();
        $comment = MorphManyRepositoryComment::create([
            'commentable_type' => MorphManyRepositoryPost::class,
            'commentable_id'   => $post->id,
        ]);

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $comment->id;
        $request->delete_unlinked = false;

        $this->field->shouldReceive('getQuery')->andReturn($comment->query());
        $this->field->shouldReceive('getRelationQuery')->andReturn($post->comments());
        $this->repository->destroy($request, $post);

        $this->assertEquals(0, $post->refresh()->comments->count());
    }

    /** @test */
    public function test_repository_is_registered()
    {
        $this->assertEquals(
            MorphManyRepository::class,
            app(ApiRepositories::class)->find('morph-many')
        );
    }
}

class MorphManyRepositoryPost extends Model
{
    public $table = 'posts';
    public $timestamps = false;

    public function comments()
    {
        return $this->morphMany(MorphManyRepositoryComment::class, 'commentable');
    }
}

class MorphManyRepositoryComment extends Model
{
    public $table = 'comments';
    public $timestamps = false;
    protected $fillable = ['commentable_id', 'commentable_type'];
}
