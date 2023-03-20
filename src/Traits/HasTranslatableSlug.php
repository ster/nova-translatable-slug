<?php

namespace Ster\NovaTranslatableSlug\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

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
                $model->slug = static::getUniqueSlug($model->slug, $locale);
            }
        });

        self::updating(function ($model) use ($translatableLocales) {
            foreach ($translatableLocales as $locale) {
                $model->slug = static::getUniqueSlug($model->slug, $locale, $model->id);
            }
        });
    }

    /**
     * @param $value
     * @param $updateId
     * @return mixed
     */
    private static function getUniqueSlug($value, $locale, $updateId = null)
    {
        $slug = Str::slug($value);

        if (static::whereRaw("JSON_EXTRACT(slug, '$." . $locale . "') = '{$slug}'")->exists()) {
            $slug = static::makeUniqueSlug($slug, $locale, $updateId);
        }

        return $slug;
    }

    /**
     * @param $slug
     * @param $updateId
     */
    private static function makeUniqueSlug($slug, $locale, $updateId = null)
    {
        $originalSlug = $slug;
        $count = 2;

        while (static::checkSlugExists($slug, $locale, $updateId)) {
            $slug = "{$originalSlug}-" . $count++;
        }

        return $slug;
    }

    /**
     * @param $slug
     * @param $updateId
     */
    private static function checkSlugExists($slug, $locale, $updateId = null)
    {
        return static::whereRaw("JSON_EXTRACT(slug, '$." . $locale . "') = '{$slug}'")
            ->when($updateId, function ($query) use ($updateId) {
                $query->where('id', '<>', $updateId);
            })
            ->exists();
    }

    /**
     * @param $query
     * @param $slug
     * @param $locale
     */
    public function scopeWhereTranslatableSlug(Builder $query, string $slug, ?string $locale = null): Builder
    {
        $locale = $locale ?: app()->getLocale();

        return $query->whereRaw("JSON_EXTRACT(slug, '$." . $locale . "') = '{$slug}'");
    }

    /**
     * @param string $slug
     * @param array $columns
     */
    public static function findBySlug(string $slug, ?string $locale = null, array $columns = ['*'])
    {
        return static::whereTranslatableSlug($slug, $locale)->first($columns);
    }

    /**
     * @param string $slug
     * @param array $columns
     */
    public static function findBySlugOrFail(string $slug, ?string $locale = null, array $columns = ['*'])
    {
        return static::whereTranslatableSlug($slug, $locale)->firstOrFail($columns);
    }
}
