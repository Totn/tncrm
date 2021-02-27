<?php

namespace App\Admin\Repositories;

use App\Models\Dealer as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Dealer extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
