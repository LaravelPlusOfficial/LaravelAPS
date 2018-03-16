<?php

namespace App\Services\Media\Tools;


use Spatie\ImageOptimizer\OptimizerChainFactory;

class OptimizeImage
{

    /**
     * @var
     */
    protected $imagePath;

    /**
     * OptimizeImage constructor.
     * @param $imagePath
     */
    public function __construct($imagePath)
    {
        $this->imagePath = $imagePath;
    }

    /**
     * Optimize Image
     * https://github.com/spatie/image-optimizer
     *
     */
    public function optimize()
    {
        $optimizer = $this->getOptimizer();

        $optimizer->optimize($this->imagePath);
    }

    /**
     * @return \Spatie\ImageOptimizer\OptimizerChain
     */
    protected function getOptimizer()
    {
        return OptimizerChainFactory::create();
    }

}