<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.brand_name', 'TPA Baitul Jannah Management System');
        $this->migrator->add('general.brand_logo', 'sites/logo.png');
        $this->migrator->add('general.brand_logoHeight', '3rem');
        $this->migrator->add('general.site_favicon', 'sites/logo.ico');
        $this->migrator->add('general.search_engine_indexing', false);
        $this->migrator->add('general.site_theme', [
            'primary' => '#dc8a78',
            'secondary' => '#7287fd',
            'gray' => '#acb0be',
            'success' => '#40a02b',
            'danger' => '#d20f39',
            'info' => '#1e66f5',
            'warning' => '#df8e1d',
        ]);
    }
};
