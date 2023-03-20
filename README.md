# Updated version for Laravel Nova 4
Original code from: https://github.com/ahmetbedir/nova-translatable-slug

All credits to https://github.com/ahmetbedir

# Install

add the following lines to repositores section of your composer.json file:

```json
{
  "type": "git",
  "url": "https://github.com/ster/nova-translatable-slug"
}
```

```sh
composer require ster/nova-translatable-slug:dev-main
```

# Usage
```php
TranslatableSlug::make('Slug')
    ->from('title')
    ->translatable()
```
</br>

</br>

**Note:** It is compatible with the [**optimistdigital/nova-translatable**](https://github.com/optimistdigital/nova-translatable) package.
