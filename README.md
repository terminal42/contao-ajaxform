# ajaxform

> 🚨 This extension will become unsupported once Contao 4.13 LTS is EOL. This is because [we have contributed the
> feature to Contao Core](https://github.com/contao/contao/pull/5307). You can use it as of Contao version 5.1 and you will thus no longer need this extension 🎉

Allows you to submit your forms that were generated with the built-in form generator via Ajax.
After the installation you will have a new content element called "Form with Ajax" available.
Simply choose that and enjoy the magic.

It also works with redirects so you can use it in combination with e.g. ["mp_forms"](https://github.com/terminal42/contao-mp_forms).

This extension does not require jQuery or MooTools and thus only works in modern browsers.

## Migration from contao-ajaxform to the Contao Core feature as of 5.1

A manual migration is very easy:

First, search for all the content elements of type `ajaxform`. If you want to do it on database level, you can do it by running `SELECT * FROM tl_content WHERE type='ajaxform'`. Then, do the following steps for every single one of them:

1. Copy the confirmation `text` to your clipboard.
2. Go to the respective form, enable the new Ajax confirmation message feature and paste your confirmation text.
3. Replace the `ajaxform` content element with the regular `form` content element.
4. Uninstall the extension.

You can also automate it by using the Contao Migration framework in your app. The migration needed looks like this:

```php
namespace App\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;

class AjaxFormMigration extends AbstractMigration
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['tl_content', 'tl_form'])) {
            return false;
        }

        $columns = $schemaManager->listTableColumns('tl_form');

        if (!isset($columns['ajax'], $columns['confirmation'])) {
            return false;
        }

        $total = $this->connection->fetchOne('SELECT COUNT(*) FROM tl_content WHERE type=?', ['ajaxform']);

        return $total > 0;
    }

    public function run(): MigrationResult
    {
        $records = $this->connection->fetchAllAssociative('SELECT id, form, text FROM tl_content WHERE type=?', ['ajaxform']);

        foreach ($records as $record) {
            $this->connection->update('tl_content', ['type' => 'form'], ['id' => $record['id']]);
            $this->connection->update('tl_form', ['confirmation' => $record['text'], 'ajax' => 1], ['id' => $record['form']]);
        }

        return $this->createResult(true);
    }
}
```
