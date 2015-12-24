<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 15.9.12
 * Time: 21:41
 */

namespace SudokuBundle\Solver\Technique;

use SudokuBundle\Grid\Grid;

class Technique
{
    protected $logger = null;

    public function fillWhatYouCan(Grid $grid)
    {

    }

    /**
     * @param Logger $logger
     * @return $this
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return Logger
     * @throws \ErrorException
     */
    public function getLogger()
    {
        if (!$this->logger) {
            throw new \ErrorException('logger has not been set.');
        }
        return $this->logger;
    }
}