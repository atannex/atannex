<?php

namespace Atannex\Interactions\Components;

trait CanShare
{
    public int $sharesCount = 0;

    public function mountCanShare(): void
    {
        $this->syncShareState();
    }

    protected function syncShareState(): void
    {
        if (isset($this->post) && method_exists($this->post, 'sharesCount')) {
            $this->sharesCount = $this->post->sharesCount();
        }
    }
}
