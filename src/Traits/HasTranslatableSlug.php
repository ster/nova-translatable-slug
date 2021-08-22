<?php
namespace Ahmetbedir\NovaTranslatableSlug;

use Illuminate\Support\Str;

trait HasTranslatableSlug
{
    /**
     * @return null
     */
    public static function bootHasTranslatableSlug()
    {
        $translatableLocales = array_keys(config('nova-translatable.locales'));

        if (!$translatableLocales || !in_array(app()->getLocale(), $translatableLocales)) {
            return;
        }

        self::creating(function ($model) use ($translatableLocales) {
            foreach ($translatableLocales as $locale) {
                $model->slug = static::getUniqueSlug($model->slug, null, $locale);
            }
        });

        self::updating(function ($model) use ($translatableLocales) {
            foreach ($translatableLocales as $locale) {
                $model->slug = static::getUniqueSlug($model->slug, $model->id, $locale);
            }
        });
    }

    /**
     * @param $value
     * @param $updateId
     * @return mixed
     */
    private static function getUniqueSlug($value, $updateId = null, $locale)
    {
        $slug = Str::slug($value);

        if (static::whereRaw("JSON_EXTRACT(slug, '$." . $locale . "') = '{$slug}'")->exists()) {
            $slug = static::makeUniqueSlug($slug, $updateId, $locale);
        }

        return $slug;
    }

    /**
     * @param $slug
     * @param $updateId
     */
    private static function makeUniqueSlug($slug, $updateId = null, $locale)
    {
        $originalSlug = $slug;
        $count = 2;

        while (static::checkSlugExists($slug, $updateId, $locale)) {
            $slug = "{$originalSlug}-" . $count++;
        }

        return $slug;
    }

    /**
     * @param $slug
     * @param $updateId
     */
    private static function checkSlugExists($slug, $updateId = null, $locale)
    {
        return static::whereRaw("JSON_EXTRACT(slug, '$." . $locale . "') = '{$slug}'")
            ->when($updateId, function ($query) use ($updateId) {
                $query->where('id', '<>', $updateId);
            })
            ->exists();
    }
}
