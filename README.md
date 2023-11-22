<p style="text-align: center"><a href="https://lunarphp.io/" target="_blank">
<picture><source media="(prefers-color-scheme: dark)"  srcset="">
<img alt="Lunar" width="200" src="https://raw.githubusercontent.com/lunarphp/art/main/lunar-logo.svg"></picture></a>
</p>


<p style="text-align: center">This addon enables Translation on your Lunar Hub.</p>

## Minimum Requirements
- Lunar >= `0.6`

## Optional Requirements

- Laravel Livewire (if using frontend components)
- Alpinejs (if using frontend components)
- Javascript framework

## Installation

### Require the composer package

```sh
composer require lunar-translation-manager/addon
```
### Run artisan

```sh
php artisan lunar:addons:discover
```

```sh
php artisan migrate
```

## Publish the config files
```sh
php artisan vendor:publish --tag="translation-config" -force
```

## Publish the modify admin header file
```sh
php artisan vendor:publish --tag="translation-views" -force
```

## Contributing

Contributions are welcome, if you are thinking of adding a feature, please submit an issue first. So we can determine whether it should be included.

## Commands 

Add all the missing translation keys for all languages or a single language

```sh
php artisan lunar:sync-missing-translation-keys {language?}
```

Synchronize all application translations
```sh
php artisan lunar:translations-synchronize
```

