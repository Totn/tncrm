<?php

namespace App\Models\Dealer;

use App\Models\Dealer;
use Dcat\Admin\Models\Administrator as DcatAdministrator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 模型：Dealer的用户
 */
class Administrator extends DcatAdministrator
{

    public function setTable($table)
    {
        $this->table = 'dealer_users';
    }

    /**
     * A user has and belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'dealer_role_users', 'user_id', 'role_id')->withTimestamps();
    }

    /**
     * 关联经销商
     *
     * @return BelongsTo
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class, 'dealer_id', 'id');
    }

    public function isAdministrator(): bool
    {
        return $this->isRole(Role::ADMINISTRATOR);
    }
}
