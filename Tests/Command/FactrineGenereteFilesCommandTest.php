<?php

namespace Fludio\FactrineBundle\Tests\Command;

use FilesystemIterator;
use Fludio\FactrineBundle\Command\FactrineGenerateFilesCommand;
use Fludio\FactrineBundle\Tests\Dummy\app\AppKernel;
use Fludio\FactrineBundle\Tests\Dummy\TestCase;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\Address;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\User;
use org\bovigo\vfs\vfsStream;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Yaml\Yaml;

class FactrineGenereteFilesCommandTest extends TestCase
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
            ->willReturn(vfsStream::url('root') . '/');

        $application = new Application($kernel);
        $application->add($cmd);

        $command = $application->find('factrine:generate:files');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $fi = new FilesystemIterator($root->url(), FilesystemIterator::SKIP_DOTS);

        $this->assertEquals(10, iterator_count($fi));
        $this->assertTrue(file_exists($root->url() . '/Address.yml'));

        $addressConfig = Yaml::parse(file_get_contents($root->url() . '/Address.yml'));

        $this->assertTrue(key($addressConfig) === Address::class);
        $this->assertTrue(isset($addressConfig[Address::class]['street']));

        $userConfig = Yaml::parse(file_get_contents($root->url() . '/User.yml'));

        $this->assertTrue(isset($userConfig[User::class]['address']));
        $this->assertEquals(Address::class, $userConfig[User::class]['address']);

    }
}
