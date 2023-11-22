<?php

namespace Lunar\TranslationManager\Models;


use Lunar\Base\Traits\Searchable;
use Spatie\TranslationLoader\LanguageLine as LanguageLineOrigin;
 use Eloquent;
 use Illuminate\Database\Eloquent\Builder;

/**
 * Lunar\TranslationManager\Models\LanguageLine
 *
 * @property string group
 * @property string key
 * @property string text
 * @method static Builder|LanguageLine newModelQuery()
 * @method static Builder|LanguageLine newQuery()
 * @method static Builder|LanguageLine query()
 * @method static Builder|LanguageLine whereCollectionName($value)
 * @method static Builder|LanguageLine whereCreatedAt($value)
 * @method static Builder|LanguageLine whereGuestId($value)
 * @method static Builder|LanguageLine whereId($value)
 * @method static Builder|LanguageLine whereModelId($value)
 * @method static Builder|LanguageLine whereModelType($value)
 * @method static Builder|LanguageLine whereUpdatedAt($value)
 * @method static Builder|LanguageLine whereUserId($value)
 * @mixin Eloquent
 */
class LanguageLine extends LanguageLineOrigin
{
    use Searchable;

    /**
     * Create a new instance of the Model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('lunar.database.table_prefix') . $this->getTable());

        if ($connection = config('lunar.database.connection', false)) {
            $this->setConnection($connection);
        }
    }

    protected $fillable = ['group', 'key', 'text'];

}
