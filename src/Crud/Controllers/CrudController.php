<?php

namespace Ignite\Crud\Controllers;

use Ignite\Crud\Fields\Media\MediaField;
use Ignite\Crud\Models\LitFormModel;
use Ignite\Crud\RelationField;
use Ignite\Crud\Requests\CrudCreateRequest;
use Ignite\Crud\Requests\CrudDeleteRequest;
use Ignite\Crud\Requests\CrudReadRequest;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Ignite\Support\IndexTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

abstract class CrudController extends CrudBaseController
{
    /**
     * The Model Class e.g. App\Models\Post.
     *
     * @var string
     */
    protected $model;

    /**
     * Modify initial query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @return void
     */
    public function query($query)
    {
        //
    }

    /**
     * Load model.
     *
     * @param  CrudReadRequest $request
     * @param  int             $id
     * @return array
     */
    public function load(CrudReadRequest $request, $id)
    {
        $model = $this->getQuery()->findOrFail($id);

        return crud($model);
    }

    /**
     * Delete by query.
     *
     * @param  Builder $query
     * @return void
     */
    public function delete(Builder $query)
    {
        $query->delete();
    }

    /**
     * Delete one.
     *
     * @param  CrudDeleteRequest $request
     * @return void
     */
    public function destroy(CrudDeleteRequest $request, $id)
    {
        $this->delete(
            $this->getQuery()->where('id', $id)
        );
    }

    /**
     * Delete action.
     *
     * @param  CrudDeleteRequest            $request
     * @param  Collection                   $models
     * @return Illuminate\Http\JsonResponse
     */
    public function deleteAction(CrudDeleteRequest $request, Collection $models)
    {
        $models->map(fn ($item) => $item->delete());

        return response()->success(
            __lit_choice('messages.deleted_items', count($models))
        );
    }

    /**
     * Show Crud index.
     *
     * @param  CrudReadRequest $request
     * @return View
     */
    public function index(CrudReadRequest $request)
    {
        $config = $this->config->get(
            'route_prefix', 'names', 'permissions'
        );

        $page = $this->config->index->bind([
            'config' => $config,
        ]);
        $page->bindToView(['config' => $this->config]);

        return $page;
    }

    /**
     * Load index table items.
     *
     * @param  CrudReadRequest $request
     * @return array           $items
     */
    public function indexTable(CrudReadRequest $request)
    {
        $table = $this->config->index->getTable();
        $query = $table->getQuery($this->getQuery());

        $index = IndexTable::query($query)
            ->request($request)
            ->search($table->getAttribute('search'))
            ->get();

        $index['items'] = crud($index['items']);

        return $index;
    }

    /**
     * Show Crud create.
     *
     * @param  CrudCreateRequest $request
     * @return void
     */
    public function create(CrudCreateRequest $request)
    {
        $config = $this->config->get(
            'show', 'names', 'permissions', 'route_prefix'
        );

        $config['form'] = $config['show'];
        unset($config['show']);

        $page = $this->config->show->bindToView([
            'model'  => new $this->model(),
            'config' => $this->config,
        ])->bindToVue([
            'crud-model' => crud(new $this->model()),
            'config'     => $config,
        ]);

        return $page;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function show(CrudReadRequest $request, ...$parameters)
    {
        $id = last($parameters);

        $this->config->show->resolveQuery(
            $query = $this->getQuery()
        );

        // Now we are loading all relations from relation or media fields.
        foreach ($this->config->show->getRegisteredFields() as $field) {
            if ($field instanceof RelationField && ! $field instanceof MediaField) {
                $query->with($field->getRelationName());
            }
        }

        // Find model.
        $model = $query->findOrFail($id);

        // Append media.
        if (! $model instanceof LitFormModel) {
            foreach ($this->config->show->getRegisteredFields() as $field) {
                if ($field instanceof MediaField) {
                    $model->append($field->id);
                }
            }
        }

        // Load config attributes.
        $config = $this->config->get(
            'show', 'route_prefix', 'names', 'permissions',
        );
        $config['form'] = $config['show'];
        unset($config['show']);

        // Set readonly if the user has no update permission for this crud.
        foreach ($config['form']->getRegisteredFields() as $field) {
            if (! $config['permissions']['update']) {
                $field->readonly();
            }
        }

        // Get preview route.
        if ($this->config->hasMethod('previewRoute')) {
            $config['preview_route'] = $this->config->previewRoute($model);
        }

        $page = $this->config->show->bindToView([
            'model'  => $model,
            'config' => $this->config,
        ])->bindToVue([
            'crud-model' => crud($model),
            'config'     => $config,
        ]);

        [$previous, $next] = $this->nearSiblings($id);

        // Show near items.
        $page->navigationLeft()->component('lit-crud-show-near-items')->bind([
            'next'         => $next,
            'previous'     => $previous,
            'route-prefix' => $this->config->routePrefix,
        ]);

        return $page;
    }

    /**
     * Sort.
     *
     * @param  CrudUpdateRequest $request
     * @return void
     */
    public function order(CrudUpdateRequest $request)
    {
        $ids = $request->ids ?? abort(404);

        $models = $this->getQuery()
            ->whereIn('id', $ids)
            ->get();

        foreach ($ids as $order => $id) {
            $model = $models->where('id', $id)->first();

            if (! $model) {
                continue;
            }
            $model->{$this->config->orderColumn} = $order;
            $model->save();
        }
    }

    /**
     * Get close siblings.
     *
     * @param  int   $id
     * @return array
     */
    protected function nearSiblings($id)
    {
        $previous = $this->getQuery()
            ->where('id', '<', $id)
            ->orderBy('id', 'desc')
            ->select('id')
            ->first()->id ?? null;

        $next = $this->getQuery()
            ->where('id', '>', $id)
            ->orderBy('id')
            ->select('id')
            ->first()->id ?? null;

        return [$previous, $next];
    }
}
