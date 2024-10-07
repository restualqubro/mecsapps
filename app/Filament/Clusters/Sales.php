<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Sales extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Transactions';
}
