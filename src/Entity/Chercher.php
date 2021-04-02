<?php

namespace App\Entity;

class Chercher
{
    private $keyWord;

    /**
     * @return string
     */
    public function getKeyWord(): ?string
    {
        return $this->keyWord;
    }

    /**
     * @param string $keyWord
     */
    public function setKeyWord(string $keyWord): void
    {
        $this->keyWord = $keyWord;
    }
}
