<?php

namespace App\OrderDocumentMigrator\Shared;

use Symfony\Component\Console\Helper\ProgressBar;

class ExecuteManager
{
    protected ProgressBar $progressBar;

    protected mixed $params;

    /**
     * @return void
     */
    public function progressBarStart(): void
    {
        if (isset($this->progressBar)) {
            $this->progressBar->start();
        }
    }

    /**
     * @param int $steps
     *
     * @return void
     */
    public function progressBarSetSteps(int $steps): void
    {
        if (isset($this->progressBar)) {
            $this->progressBar->setMaxSteps($steps);
        }
    }

    /**
     * @param int $step
     *
     * @return void
     */
    public function progressBarAdvance(int $step): void
    {
        if (isset($this->progressBar)) {
            $this->progressBar->advance($step);
        }
    }

    /**
     * @param int $step
     *
     * @return void
     */
    public function progressBarSetStep(int $step): void
    {
        if (isset($this->progressBar)) {
            $this->progressBar->setProgress($step);
        }
    }

    /**
     * @return void
     */
    public function progressBarFinish(): void
    {
        if (isset($this->progressBar)) {
            $this->progressBar->finish();
        }
    }

    /**
     * @param \Symfony\Component\Console\Helper\ProgressBar $progressBar
     *
     * @return ExecuteManager
     */
    public function setProgressBar(ProgressBar $progressBar): ExecuteManager
    {
        $this->progressBar = $progressBar;

        return $this;
    }

    /**
     * @return \Symfony\Component\Console\Helper\ProgressBar
     */
    public function getProgressBar(): ProgressBar
    {
        return $this->progressBar;
    }

    public function setParams(mixed $params): ExecuteManager
    {
        $this->params = $params;

        return $this;
    }

    public function getParams(): mixed
    {
        return $this->params;
    }
}