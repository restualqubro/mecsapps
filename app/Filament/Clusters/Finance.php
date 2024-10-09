<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Finance extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Finances';

    protected static ?string $navigationLabel = 'Cash In & Out';
}
