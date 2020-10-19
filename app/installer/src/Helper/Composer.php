<?php

namespace Pagekit\Installer\Helper;

use Composer\Installer;
use Composer\IO\ConsoleIO;
use Composer\Json\JsonFile;
use Composer\Package\Locker;
use Composer\Package\Package;
use Composer\Repository\CompositeRepository;
use Composer\Repository\InstalledFilesystemRepository;
use Composer\Semver\VersionParser;
use Symfony\Component\Console\Output\OutputInterface;

class Composer
{
    public array $paths;

    public array $blueprint;

    protected ?InstallerIO $io = null;

    protected ?OutputInterface $output = null;

    protected array $packages = [];

    protected string $file = 'packages.php';

    /**
     * @param array $config
     * @param null  $output
     */
    public function __construct($config, $output = null)
    {
        $this->paths = $config;
        $this->output = $output;

        $this->file = $config['path.packages'] . '/' . $this->file;
        $this->blueprint = [
            'repositories' => [
                ['type' => 'artifact', 'url' => $config['path.artifact']],
                ['type' => 'composer', 'url' => $config['system.api']]
            ]
        ];
    }

    /**
     * @param array $install [name => version, name => version, ...]
     * @param bool $packagist
     * @param bool $writeConfig
     * @param bool $preferSource
     */
    public function install(array $install, $packagist = false, $writeConfig = true, $preferSource = false): void
    {
        $this->addPackages($install);

        $refresh = [];
        $versionParser = new VersionParser();
        foreach ($install as $name => $version) {
            try {
                $normalized = $versionParser->normalize($version);
                $refresh[] = new Package($name, $normalized, $version);
            } catch (\UnexpectedValueException $e) {}
        }

        $this->composerUpdate(array_keys($install), $refresh, $packagist, $preferSource);

        if ($writeConfig) {
            $this->writeConfig();
        }
    }


    /**
     * @param array|string $uninstall [name, name, ...]
     * @param bool $writeConfig
     */
    public function uninstall($uninstall, $writeConfig = true): void
    {
        $uninstall = (array) $uninstall;

        $this->removePackages($uninstall);

        $this->composerUpdate($uninstall);

        if ($writeConfig) {
            $this->writeConfig();
        }
    }

    /**
     * Checks if a package is installed by composer.
     *
     * @param $name
     */
    public function isInstalled($name): bool
    {
        $installed = $this->paths['path.packages'] . '/composer/installed.json';
        $installed = file_exists($installed) ? json_decode(file_get_contents($installed), true) : [];

        $installed = array_map(fn($pkg) => $pkg['name'], $installed);

        return in_array($name, $installed);
    }

    /**
     * Runs Composer Update command.
     *
     * @param  array|bool $updates
     * @param array $refresh
     * @param bool $packagist
     * @param bool $preferSource
     * @throws \Exception
     */
    protected function composerUpdate($updates = false, $refresh = [], $packagist = false, $preferSource = false): void
    {
        $installed = new JsonFile($this->paths['path.vendor'] . '/composer/installed.json');
        $internal = new CompositeRepository([]);
        $internal->addRepository(new InstalledFilesystemRepository($installed));

        $composer = $this->getComposer($packagist);
        $composer->getDownloadManager()->setOutputProgress(false);

        $local = $composer->getRepositoryManager()->getLocalRepository();
        foreach ($refresh as $package) {
            $local->removePackage($package);
        }

        $installer = Installer::create($this->getIO(), $composer)
            ->setAdditionalInstalledRepository($internal)
            ->setOptimizeAutoloader(true)
            ->setUpdate(true);

        if ($preferSource) {
            $installer->setPreferSource(true);
        } else {
            $installer->setPreferDist(true);
        }

        if ($updates) {
            $installer->setUpdateWhitelist($updates)->setWhitelistDependencies();
        }

        $installer->run();
    }

    /**
     * Returns composer instance.
     *
     * @param bool $packagist
     * @return null
     */
    protected function getComposer($packagist = false): \Composer\Composer
    {
        $config = $this->blueprint;
        $config['config'] = ['vendor-dir' => $this->paths['path.packages'], 'cache-files-ttl' => 0];
        $config['require'] = $this->packages;

        if (!$packagist) {
            $config['repositories'][] = ['packagist' => false];
        }

        // set memory limit, if < 512M
        $memory = trim(ini_get('memory_limit'));
        if ($memory != -1 && $this->memoryInBytes($memory) < 512 * 1024 * 1024) {
            @ini_set('memory_limit', '512M');
        }

        Factory::bootstrap([
            'home' => $this->paths['path.temp'] . '/composer',
            'cache-dir' => $this->paths['path.temp'] . '/composer/cache'
        ]);

        $composer = Factory::create($this->getIO(), $config);
        $composer->setLocker(new Locker(
            $this->getIO(),
            new JsonFile(preg_replace('/\.php$/i', '.lock', $this->file)),
            $composer->getRepositoryManager(),
            $composer->getInstallationManager(),
            json_encode($config)
        ));

        return $composer;
    }


    protected function getIO(): InstallerIO
    {
        return $this->io ?: ($this->io = new InstallerIO(null, $this->output));
    }

    /**
     * @param $packages
     */
    protected function addPackages($packages): void
    {
        $this->packages = array_merge($this->readConfig(), $packages);
    }

    /**
     * @param $packages
     */
    protected function removePackages($packages): void
    {
        $this->packages = array_diff_key($this->readConfig(), array_flip($packages));
    }

    /**
     * Reads packages from package file.
     */
    protected function readConfig(): array
    {
        return file_exists($this->file) ? require $this->file : [];
    }

    /**
     * Writes changes to packages file.
     */
    protected function writeConfig(): void
    {
        file_put_contents($this->file, '<?php return ' . var_export($this->packages, true) . ';');
    }

    /**
     * Converts memory value from 'php.ini' into bytes.
     *
     * @param $value
     * @return int
     */
    protected function memoryInBytes($value)
    {
        $unit = strtolower(substr($value, -1, 1));
        $value = (int) $value;

        switch ($unit) {
            case 'g':
                $value *= 1024;
            // no break (cumulative multiplier)
            case 'm':
                $value *= 1024;
            // no break (cumulative multiplier)
            case 'k':
                $value *= 1024;
        }

        return $value;
    }
}
