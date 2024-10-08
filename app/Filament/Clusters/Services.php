<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Services extends Cluster
{
    protected static ?string $navigationGroup = 'Services';

    protected static ?string $navigationLabel = 'Categories & Catalog';

    protected static ?int $navigationSort = 99;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
}
