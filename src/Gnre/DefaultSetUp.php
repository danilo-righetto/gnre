<?php

namespace PhxCargo\Gnre;

use Sped\Gnre\Configuration\CertificatePfx;
use Sped\Gnre\Configuration\Setup;
use Sped\Gnre\Configuration\CertificatePfxFileOperation;
use Sped\Gnre\Exception\UnreachableFile;

/**
 * Class DefaultSetUp
 * @package PhxCargo\Gnre
 */
class DefaultSetUp extends Setup
{
    public function getBaseUrl()
    {
        return config('gnre.sefaz_base_url');
    }

    public function getCertificateCnpj()
    {
        return config('gnre.sefaz_certificate_cnpj');
    }

    public function getCertificateDirectory()
    {
        return config('gnre.sefaz_certificate_directory');
    }

    public function getCertificateName()
    {
        return config('gnre.sefaz_certificate_name');
    }

    public function getCertificatePassword()
    {
        return config('gnre.sefaz_certificate_password');
    }

    public function getCertificatePemFile()
    {
        $gnre = $this->getCertificateGeneric();
        return $gnre->getCertificatePem();
    }

    public function getCertificationChain()
    {
        return config('gnre.sefaz_certification_chain_file');
    }

    public function getEnvironment()
    {
        return config('gnre.sefaz_environment');
    }

    public function getPrivateKey()
    {
        $gnre = $this->getCertificateGeneric();
        return $gnre->getPrivateKey();
    }

    public function getProxyIp()
    {
        return config('gnre.sefaz_proxy_ip');
    }

    public function getProxyPass()
    {
        return config('gnre.sefaz_proxy_pass');
    }

    public function getProxyPort()
    {
        return config('gnre.sefaz_proxy_port');
    }

    public function getProxyUser()
    {
        return config('gnre.sefaz_proxy_user');
    }

    public function getDebug()
    {
        return config('gnre.sefaz_debug');
    }

    /**
     * @return CertificatePfx|null
     */
    public function getCertificateGeneric()
    {
        try {
            $file = new CertificatePfxFileOperation(
                config('gnre.sefaz_certificate_name')
            );
        } catch (UnreachableFile $e) {
            return null;
        }

        return new CertificatePfx($file, config('gnre.sefaz_certificate_password'));
    }
}