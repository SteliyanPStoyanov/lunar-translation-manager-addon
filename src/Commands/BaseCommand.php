<?php
namespace Lunar\TranslationManager\Commands;

use Illuminate\Console\Command;
use Lunar\TranslationManager\Drivers\Translation;

class BaseCommand extends Command
{
    protected $translation;

    public function __construct(Translation $translation)
    {
        parent::__construct();
        $this->translation = $translation;
    }
}
