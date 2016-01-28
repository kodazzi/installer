<?php
 /**
 * This file is part of the Kodazzi Framework.
 *
 * (c) Jorge Gaitan <info@kodazzi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kodazzi\Installer;

use Composer\IO\IOInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

class BundleInstaller extends LibraryInstaller
{
    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $prettyName = $package->getPrettyName();

        if (!preg_match('/^(kodazzi)\/[a-z0-9-]+$/i', $prettyName))
        {
            throw new \InvalidArgumentException(
                'Unable to install bundle, '
                .'should always be formatted '
                .'"kodazzi/packagename" or "kodazzi/package-name"'
            );
        }

        list($vendor, $name) = explode('/', $prettyName);

        $vendor = $this->inflectorCamelize($vendor);
        $name = $this->inflectorCamelize($name);

        return "system/bundles/{$vendor}/{$name}";
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return 'kodazzi-bundle' === $packageType;
    }

    private function inflectorCamelize($string)
    {
        $string = 'x'.strtolower(trim($string));
        $string = ucwords(preg_replace('/[\s_]+/', ' ', $string));
        return substr(str_replace(' ', '', $string), 1);
    }
} 