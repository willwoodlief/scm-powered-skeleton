<?php
/**
 * This command will list the plugins in the console
 *
 * * do `php artisan plugin:list` for a compact table
 * * do `php artisan plugin:list --info` for more data
 *
 *  Compact table example:
 *  | name                          | location                                        | active     | installed     |
 *  | ----------------------------- | ----------------------------------------------- | ---------- | ------------- |
 *  | scm/default-images            | /var/www/html/plugins/scm-default-images        | Yes        | Yes           |
 *  | scm/log-filters-actions       | /var/www/html/plugins/scm-log-filters-actions   | Yes        | Yes           |
 *  | scm/scm-plugin-sample-theming | /var/www/html/plugins/scm-plugin-sample-theming | Yes        | Not installed |
 *  | willwoodlief/scm-plugin-test  | /var/www/html/plugins/scm-plugin-test           | Not active | Not installed |
 *
 *  more info example:
 *
 *  * scm/log-filters-actions
 *  * Authors: Will Woodlief
 *  * Location: /var/www/html/plugins/scm-log-filters-actions
 *  * Description: Can listen to and log eventy actions and filters
 *
 * -------------
 *
 *  * scm/scm-plugin-sample-theming
 *  * Not Installed
 *  * Authors: Will Woodlief
 *  * Location: /var/www/html/plugins/scm-plugin-sample-theming
 *  * Description: Can listen to and log eventy actions and filters
 */
namespace App\Console\Commands;

use App\Plugins\PluginRef;
use Illuminate\Console\Command;

class ListPlugins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "plugin:list {--info : More information}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists the plugins';

    /**
     * Execute the console command by showing the plugin info
     *
     * * `php artisan plugin:list` for a compact table
     * *  do `php artisan plugin:list --info` for more data
     *
     * Compact table example:
     *   | name                          | location                                        | active     | installed     |
     *   | ----------------------------- | ----------------------------------------------- | ---------- | ------------- |
     *   | scm/default-images            | /var/www/html/plugins/scm-default-images        | Yes        | Yes           |
     *   | scm/log-filters-actions       | /var/www/html/plugins/scm-log-filters-actions   | Yes        | Yes           |
     *   | scm/scm-plugin-sample-theming | /var/www/html/plugins/scm-plugin-sample-theming | Yes        | Not installed |
     *   | willwoodlief/scm-plugin-test  | /var/www/html/plugins/scm-plugin-test           | Not active | Not installed |
     *
     *   more info example:
     *
     *   * scm/log-filters-actions
     *   * Authors: Will Woodlief
     *   * Location: /var/www/html/plugins/scm-log-filters-actions
     *   * Description: Can listen to and log eventy actions and filters
     *
     *  -------------
     *
     *   * scm/scm-plugin-sample-theming
     *   * Not Installed
     *   * Authors: Will Woodlief
     *   * Location: /var/www/html/plugins/scm-plugin-sample-theming
     *   * Description: Can listen to and log eventy actions and filters
     *
     * @uses PluginRef::findPlugins()
     */
    public function handle()
    {
        $plugins = PluginRef::findPlugins();
        if($this->option('info')) {
            $this->line("List of plugins");
            $this->newLine(1);

            foreach ($plugins as $plugin) {

                $this->line($plugin->getFullPluginName());
                if (!$plugin->isInstalled()) {
                    $this->warn('Not Installed');
                }
                if (!$plugin->isActive()) {
                    $this->warn('Not Active');
                }
                $this->info('Authors: ' . $plugin->getAuthors());

                if ($plugin->getVersion()) {
                    $this->info('Version: ' . $plugin->getVersion());
                }

                $this->info('Location: ' . $plugin->getPluginFolder());
                $this->line('Description: ' . $plugin->getDescription());
                $this->newLine(1);

            }
        } else {
            $rows = [];
            foreach ($plugins as $plugin) {
                $node = [];
                $node[] = $plugin->getFullPluginName();
                $node[] = $plugin->getPluginFolder();
                $node[] = $plugin->isActive()? 'Yes': 'Not active';
                $node[] = $plugin->isInstalled()? 'Yes': 'Not installed';
                $rows[] = $node;
            }
            $this->table(['name','location','active','installed'],$rows);
        }
    }
}
