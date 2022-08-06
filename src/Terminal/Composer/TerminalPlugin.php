<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace SpaethTech\Terminal\Composer;

use Composer\Composer;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginEvents;
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
    }

    public static function getSubscribedEvents() : array
    {
        return array(
            "post-update-cmd" => "onPostUpdateCmd"
        );
    }

    public function onPostUpdateCmd(Event $event)
    {
        $this->io->write("*** This is a test! = ".PROJECT_DIR, TRUE);


        //$composer = $event->getComposer();

        $vendorDir = $this->composer->getConfig()->get("vendor-dir");
        require $vendorDir."/autoload.php";

        //if (!defined("PROJECT_DIR"))
        //    include_once __DIR__."/../../../inc/globals.inc.php";

        //$event->getIO()->write(PROJECT_DIR, TRUE);
        if (($ide = realpath(__DIR__."/../../../ide")) && $ide !== PROJECT_DIR)
            FileSystem::copyDir($ide, PROJECT_DIR."/ide", TRUE);
        //$event->getIO()->write(PROJECT_DIR, TRUE);

    }

}
