<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMarketplacesAccounts extends Model
{
    use HasFactory;

    protected $table = 'user_marketplaces_accounts';

    protected $fillable = [
        'user_id',
        'company_id',
        'marketplace',
        'name',
        'seller_id',
        'tax',
        'include_shipping',
        'token',
        'refresh_token',
        'expiration_time',
    ];
}
