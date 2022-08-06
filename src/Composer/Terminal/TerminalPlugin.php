<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace SpaethTech\Composer\Terminal;

use Composer\Composer;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use SpaethTech\Common\FileSystem;

class TerminalPlugin implements PluginInterface, EventSubscriberInterface
{
    protected Composer $composer;
    protected IOInterface $io;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // TODO: Determine if we should remove the ide/terminals folder?
    }

    public static function getSubscribedEvents() : array
    {
        return array(
            "post-update-cmd" => "onPostUpdateCmd"
        );
    }

    /**
     * Occurs after `composer update` or `composer install` when a `composer.lock` file is NOT present.
     *
     * @param Event $event
     *
     * @return void
     * @noinspection PhpUnusedParameterInspection
     */
    public function onPostUpdateCmd(Event $event)
    {
        $vendorDir = $this->composer->getConfig()->get("vendor-dir");
        require $vendorDir."/autoload.php";

        $status = realpath(PROJECT_DIR."/ide/terminals") ? "updated" : "installed";

        if (($ide = realpath(__DIR__."/../../../ide")) && $ide !== PROJECT_DIR)
            FileSystem::copyDir($ide, PROJECT_DIR."/ide", TRUE);

        $this->io->write("Terminal scripts have been $status");

    }

}
