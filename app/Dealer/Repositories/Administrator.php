<?php

namespace App\Dealer\Repositories;

use App\Models\Dealer\Administrator as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Administrator extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
