<?php namespace Orchestra\Tenanti\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Tenanti\TenantiManager;

class TenantiManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Tenanti\TenantiManager::driver() method.
     *
     * @test
     */
    public function testDriverMethod()
    {
        $app = new Container;
        $app['config'] = $config = m::mock('\Illuminate\Config\Repository');

        $option = array('model' => 'User');

        $config->shouldReceive('get')->once()->with('orchestra/tenanti::drivers.user')->andReturn($option)
            ->shouldReceive('get')->once()->with('orchestra/tenanti::chunk', 100)->andReturn(100);

        $stub = new TenantiManager($app);

        $resolver = $stub->driver('user');

        $this->assertInstanceOf('\Orchestra\Tenanti\Migrator\Factory', $resolver);
    }

    /**
     * Test Orchestra\Tenanti\TenantiManager::driver() method
     * when driver is not available.
     *
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedMessage Driver [user] not supported.
     */
    public function testDriverMethodGivenDriverNotAvailable()
    {
        $app = new Container;
        $app['config'] = $config = m::mock('\Illuminate\Config\Repository');

        $config->shouldReceive('get')->once()->with('orchestra/tenanti::drivers.user')->andReturnNull()
            ->shouldReceive('get')->once()->with('orchestra/tenanti::chunk', 100)->andReturn(100);

        with(new TenantiManager($app))->driver('user');
    }

    /**
     * Test Orchestra\Tenanti\TenantiManager::getDefaultDriver()
     * is not implemented.
     *
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedMessage Default driver not implemented.
     */
    public function testGetDefaultDriverIsNotImplemented()
    {
        (new TenantiManager(null))->driver();
    }
}
