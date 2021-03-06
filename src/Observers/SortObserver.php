<?php

namespace TypiCMS\Modules\Pages\Observers;

use TypiCMS\Modules\Pages\Models\Page;

class SortObserver
{
    /**
     * On update, update children uris.
     */
    public function updating(Page $model)
    {
        if ($model->isDirty('parent_id')) {
            foreach (locales() as $locale) {
                $model->setTranslation('uri', $locale, '');
            }
        }
    }
}
