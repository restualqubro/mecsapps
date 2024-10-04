<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Products extends Cluster
{
    protected static ?string $navigationGroup = 'Stocks';

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
}
