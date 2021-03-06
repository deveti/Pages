<?php

namespace TypiCMS\Modules\Pages\Observers;

use TypiCMS\Modules\Pages\Models\Page;

class UriObserver
{
    /**
     * On create, update uri.
     */
    public function creating(Page $model)
    {
        $slugs = $model->getTranslations('slug');
        foreach ($slugs as $locale => $slug) {
            $uri = $this->incrementWhileExists($model, $slug, $locale);
            $model->setTranslation('uri', $locale, $uri);
        }
    }

    /**
     * On update, change uri.
     */
    public function updating(Page $model)
    {
        $slugs = $model->getTranslations('slug');
        $parentUris = $this->getParentUris($model);
        $uris = [];

        foreach ($slugs as $locale => $slug) {
            $parentUri = $parentUris[$locale] ?? '';
            if ($parentUri !== '') {
                $uri = $parentUri;
                if ($slug) {
                    $uri .= '/'.$slug;
                }
            } else {
                $uri = $slug;
            }
            $uri = $this->incrementWhileExists($model, $uri, $locale, $model->id);
            $uris[$locale] = $uri;
        }
        $model->uri = $uris;
    }

    /**
     * Get the URIs of the parent page.
     *
     * @return array|null
     */
    private function getParentUris(Page $model)
    {
        if ($model->parent !== null) {
            return $model->parent->getTranslations('uri');
        }
    }

    /**
     * Check if the uri exists.
     *
     * @param string $uri
     * @param int    $id
     *
     * @return bool
     */
    private function uriExists(Page $model, $uri, $locale, $id)
    {
        $query = $model->where('uri->'.$locale, $uri);
        if ($id) {
            $query->where('id', '!=', $id);
        }

        if ($query->first()) {
            return true;
        }

        return false;
    }

    /**
     * Add '-x' on uri if it exists in page_translations table.
     *
     * @param string $uri
     * @param int    $id
     *
     * @return string
     */
    private function incrementWhileExists(Page $model, $uri, $locale, $id = null)
    {
        if (!$uri) {
            return '';
        }

        $originalUri = $uri;

        $i = 0;
        // Check if uri is unique
        while ($this->uriExists($model, $uri, $locale, $id)) {
            ++$i;
            // increment uri if it exists
            $uri = $originalUri.'-'.$i;
        }

        return $uri;
    }
}
