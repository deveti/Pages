<?php

namespace TypiCMS\Modules\Pages\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Sidebar\SidebarGroup;
use Maatwebsite\Sidebar\SidebarItem;

class SidebarViewComposer
{
    public function compose(View $view)
    {
        $view->sidebar->group(__('Content'), function (SidebarGroup $group) {
            $group->id = 'content';
            $group->weight = 30;
            $group->addItem(__('pages::global.name'), function (SidebarItem $item) {
                $item->id = 'pages';
                $item->icon = config('typicms.pages.sidebar.icon', 'icon fa fa-fw fa-file');
                $item->weight = config('typicms.pages.sidebar.weight');
                $item->route('admin::index-pages');
                $item->append('admin::create-page');
                $item->authorize(
                    Gate::allows('index-pages')
                );
            });
        });
    }
}
