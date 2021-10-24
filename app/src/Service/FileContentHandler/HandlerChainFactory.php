<?php

namespace App\Service\FileContentHandler;

class HandlerChainFactory
{

    public function buildChain(): FileContentHandler
    {
        $initial = $this->returnInitialHandler();
        $composer = $this->returnComposerHandler();
        $yarn = $this->returnYarnHandler();

        $initial->setNext($composer)->setNext($yarn);

        return $initial;
    }

    private function returnInitialHandler(): InitialHandler
    {
        return new InitialHandler();
    }

    private function returnComposerHandler(): ComposerHandler
    {
        return new ComposerHandler();
    }

    private function returnYarnHandler(): YarnHandler
    {
        return new YarnHandler();
    }

}