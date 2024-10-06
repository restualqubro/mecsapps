<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Purchases extends Cluster
{
    protected static ?string $navigationGroup = 'Transactions';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';    
}
