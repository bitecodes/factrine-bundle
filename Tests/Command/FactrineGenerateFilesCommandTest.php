<?php

namespace BiteCodes\FactrineBundle\Tests\Command;

use FilesystemIterator;
use BiteCodes\FactrineBundle\Command\FactrineGenerateFilesCommand;
use BiteCodes\FactrineBundle\Tests\Dummy\app\AppKernel;
use BiteCodes\FactrineBundle\Tests\Dummy\TestCase;
use BiteCodes\FactrineBundle\Tests\Dummy\TestEntity\Address;
use BiteCodes\FactrineBundle\Tests\Dummy\TestEntity\User;
use org\bovigo\vfs\vfsStream;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Tests\Bundle\BundleTest;
use Symfony\Component\Yaml\Yaml;

class FactrineGenerateFilesCommandTest extends TestCase
{
    /** @test */
    public function it_generates_config_files()
    {
        $kernel = new AppKernel('test', true);
        $kernel->boot();
        $kernel->getContainer()->set('doctrine.orm.default_entity_manager', $this->em);

        $configGenerator = $kernel->getContainer()->get('factrine.config_provider.config_generator');

        $cmd = $this->getMockBuilder(FactrineGenerateFilesCommand::class)
            ->setConstructorArgs([
                $configGenerator,
                $kernel
            ])
            ->setMethods(['getDirectory'])
            ->getMock();

        $root = vfsStream::setup();

        $cmd
            ->method('getDirectory')
            ->willReturn(vfsStream::url('root') . '/factrine/');

        $application = new Application($kernel);
        $application->add($cmd);

        $command = $application->find('factrine:generate:files');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $fi = new FilesystemIterator($root->url() . '/factrine', FilesystemIterator::SKIP_DOTS);

        $this->assertEquals(11, iterator_count($fi));
        $this->assertTrue(file_exists($root->url() . '/factrine/Address.yml'));

        $addressConfig = Yaml::parse(file_get_contents($root->url() . '/factrine/Address.yml'));

        $this->assertTrue(key($addressConfig) === Address::class);
        $this->assertTrue(isset($addressConfig[Address::class]['street']));

        $userConfig = Yaml::parse(file_get_contents($root->url() . '/factrine/User.yml'));

        $this->assertTrue(isset($userConfig[User::class]['address']));
        $this->assertEquals(Address::class, $userConfig[User::class]['address']);
    }

    /** @test */
    public function it_returns_a_config_directory_for_a_bundle()
    {
        $bundle = $this->getMockForAbstractClass(Bundle::class, [], '', true, true, true, ['getPath']);
        $bundle
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/some/path');

        $command = $this->getMockBuilder(FactrineGenerateFilesCommand::class)
            ->disableOriginalConstructor()
            ->getMock();

        $refl = new \ReflectionClass($command);
        $method = $refl->getMethod('getDirectory');
        $method->setAccessible(true);
        $dir = $method->invokeArgs($command, [$bundle]);

        $this->assertEquals('/some/path/Resources/config/factrine/', $dir);
    }
}
