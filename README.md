# laravel-easy-translate
A package for database translations. This package is suitable for small to medium sized projects. For big projects I am planing a high performance version of this package using mongodb.

## Installation
`composer require tanmuhittin/laravel-easy-translate`

`php artisan migrate --path=vendor/tanmuhittin/laravel-easy-translate/database/migrations `

You are ready to go.

## Usage
Add this to your translatable models;

`use TanMuhittin\LaraTranslate\Traits\Translatable;`

then define translatable fields

`public $translatable_columns = ['name','content'];`

Now your models returns 2 additional fields; translatable, translated

## Saving Translations
Edit the translatable object returned by your model and save it.
Example translatable object:

```
{
    name:'Default Name',
    content:'Default Content',
    created_at:01.09.1993 09:09:09,
    "translatable": {
              "en": {
                "name": {
                  "value": "enName"
                },
                "content": {
                  "value": "enContent"
                }
              },
              "tr": {
                "name": {
                  "value": "trName"
                },
                "content": {
                  "value": "trContent"
                }
              }
            }
        }
    .
    .
    .
```
Edited Version
```
{
    name:'Default Name',
    content:'Default Content',
    created_at:01.09.1993 09:09:09,
    "translatable": {
              "en": {
                "name": {
                  "value": "enName Edited"
                },
                "content": {
                  "value": "enContent Edited"
                }
              },
              "tr": {
                "name": {
                  "value": "trName Edited"
                },
                "content": {
                  "value": "trContent Edited"
                }
              }
            }
        }
    .
    .
    .
```

Saving Translations:
```
$model = new Posts;
$model->name = $request->get('name');
$model->content = $request->get('content');
if($request->has('translatable'))
    $model->trans = $request->get('translatable');
$model->save();
```

Thats it.